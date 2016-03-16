<?php
namespace Engine;

use Phalcon\DI;
use Phalcon\Loader as PhLoader;
use Phalcon\Mvc\Router\Annotations as PhRouterAnnotations;
use Phalcon\Mvc\Router as PhRouter;
use Phalcon\Security as PhSecurity;
use Phalcon\Session\Adapter\Files as PhSessionFiles;
use Phalcon\Http\Response\Cookies as PhCookies;
use Phalcon\Crypt as PhCrypt;
use Phalcon\Cache\Frontend\Data as CacheData;
use Phalcon\Cache\Frontend\Output as CacheOutput;
use Phalcon\Mvc\Url as PhUrl;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Logger\Adapter\File as PhLogFile;
use Phalcon\Logger\Formatter\Line as PhFormatLine;
use Phalcon\Flash\Direct as PhFlashDirect;
use Phalcon\Flash\Session as PhFlashSession;
use Phalcon\Mvc\Model\Manager as PhModelsManager;
use Phalcon\Mvc\Model\MetaData\Strategy\Annotations as PhStrategyAnnotations;
use Phalcon\Annotations\Adapter\Memory as PhAnnotationsMemory;
use Phalcon\Db\Profiler as PhDbProfiler;
use Engine\Profiler as EnProfiler;
use Engine\Db\Model\Annotations\Initializer as ModelAnnotationsInitializer;
use Engine\Injection\Injector as EnInjector;
use Engine\Cache\Dummy as EnDummy;
use Engine\Cache\System as EnSystem;
use Engine\Exception as EnException;
use Engine\Exception\PrettyExceptions as PrettyExceptions;
use League\Flysystem\Adapter\Local as FlyLocalAdapter;
use League\Flysystem\Filesystem as FlySystem;

/**
 * Application initialization trait.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
trait Init
{
    /**
     * Init loader.
     *
     * @param DI            $di            Dependency Injection.
     * @param Config        $config        Config object.
     * @param EventsManager $eventsManager Event manager.
     *
     * @return Loader
     */
    protected function _initLoader($di, $config, $eventsManager)
    {
        /**
         * Add all required namespaces and modules from registry.
         * @var [type]
         */
        $registry = $di->get('registry');

        $namespaces = [];
        $bootstraps = [];

        foreach ($registry->modules as $module) {
            $moduleName = ucfirst($module);
            $namespaces[$moduleName] = $registry->directories->modules . $moduleName;
            $bootstraps[$module] = $moduleName . '\Bootstrap';
        }

        $namespaces['Engine'] = $registry->directories->engine;

        $loader = new PhLoader();
        $loader->registerNamespaces($namespaces);
        // Register some directories
        $loader->registerDirs([
            ROOT_PATH . '/app/libraries'
        ]);
        $loader->setEventsManager($eventsManager);
        $loader->register();
        $this->registerModules($bootstraps);

        $di->set('loader', $loader);

        return $loader;
    }

    /**
     * Init engine.
     *
     * @param DI $di Dependency Injection.
     *
     * @return void
     */
    protected function _initEngine($di)
    {
        foreach ($di->get('registry')->modules as $module) {
            $di->setShared(strtolower($module), function () use ($module, $di) {
                return new EnInjector($module, $di);
            });
        }

        $di->setShared('transactions', function () {
            return new TxManager();
        });
    }

    /**
     * Init environment.
     *
     * @param DI     $di     Dependency Injection.
     * @param Config $config Config object.
     *
     * @return Url
     */
    protected function _initEnvironment($di, $config)
    {
        set_error_handler(
            function ($errorCode, $errorMessage, $errorFile, $errorLine) {
                throw new \ErrorException($errorMessage, $errorCode, 1, $errorFile, $errorLine);
            }
        );

        set_exception_handler(
            function ($e) use ($di) {
                /**
                 * Write to log when app in production mode.
                 */
                if (ENV == ENV_PRODUCTION) {
                    $errorId = EnException::logException($e);
                }

                if ($di->get('app')->isConsole()) {
                    echo 'Error <' . $errorId . '>: ' . $e->getMessage();
                    return true;
                }

                if (ENV == ENV_DEVELOPMENT) {
                    $p = new PrettyExceptions($di);
                    $p->setBaseUri('/plugins/pretty-exceptions/');
                    return $p->handleException($e);
                }

                return true;
            }
        );

        if ($config->global->profiler) {
            $profiler = new EnProfiler();
            $di->set('profiler', $profiler);
        }

        /**
         * The URL component is used to generate all kind of urls in the
         * application
         */
        $url = new PhUrl();
        if (SUBDOMAIN == 'm') {
            $config->global->baseUrl = 'm.' . $config->global->baseUrl;
        }
        $url->setBaseUri($di->get('request')->getScheme() . '://' . $config->global->baseUrl . '/');
        $url->setStaticBaseUri($di->get('request')->getScheme() . '://' . $config->global->staticUrl . '/');
        $di->set('url', $url);

        return $url;
    }

    /**
     * Init database.
     *
     * @param DI            $di            Dependency Injection.
     * @param Config        $config        Config object.
     * @param EventsManager $eventsManager Event manager.
     *
     * @return Pdo
     */
    protected function _initDb($di, $config, $eventsManager)
    {
        $adapter = '\Phalcon\Db\Adapter\Pdo\\' . $config->db->mysql->adapter;
        /** @var Pdo $connection */
        $connection = new $adapter([
            'host' => $config->db->mysql->host,
            'port' => $config->db->mysql->port,
            'username' => $config->db->mysql->username,
            'password' => $config->db->mysql->password,
            'dbname' => $config->db->mysql->dbname,
        ]);

        $isProfiler = $config->global->profiler;
        if ($isProfiler) {
            // Attach logger & profiler.
            $profiler = null;
            if ($isProfiler) {
                $profiler = new PhDbProfiler();
            }

            $eventsManager->attach(
                'db',
                function ($event, $connection) use ($profiler) {
                    if ($event->getType() == 'beforeQuery') {
                        $statement = $connection->getSQLStatement();
                        if ($profiler) {
                            $profiler->startProfile($statement);
                        }
                    }
                    if ($event->getType() == 'afterQuery') {
                        // Stop the active profile.
                        if ($profiler) {
                            $profiler->stopProfile();
                        }
                    }
                }
            );

            if ($profiler && $di->has('profiler')) {
                $di->get('profiler')->setDbProfiler($profiler);
            }
            $connection->setEventsManager($eventsManager);
        }

        $di->set('db', $connection);
        $di->set(
            'modelsManager',
            function () use ($config, $eventsManager) {
                $modelsManager = new PhModelsManager();
                $modelsManager->setEventsManager($eventsManager);

                // Attach a listener to models-manager
                $eventsManager->attach('modelsManager', new ModelAnnotationsInitializer());

                return $modelsManager;
            },
            true
        );

        /**
         * If the configuration specify the use of metadata adapter use it or use memory otherwise.
         */
        $di->set(
            'modelsMetadata',
            function () use ($config) {
                if (ENV == ENV_PRODUCTION && isset($config->global->metadata)) {
                    $metaDataConfig = $config->global->metadata;
                    $metadataAdapter = '\Phalcon\Mvc\Model\Metadata\\' . $metaDataConfig->adapter;
                    $metaData = new $metadataAdapter($config->global->metadata->toArray());
                } else {
                    $metaData = new \Phalcon\Mvc\Model\MetaData\Memory();
                }

                $metaData->setStrategy(new PhStrategyAnnotations());

                return $metaData;
            },
            true
        );

        return $connection;
    }

    /**
     * Init annotations.
     *
     * @param DI     $di     Dependency Injection.
     * @param Config $config Config object.
     *
     * @return void
     */
    protected function _initAnnotations($di, $config)
    {
        $di->set(
            'annotations',
            function () use ($config) {
                if (ENV == ENV_PRODUCTION && isset($config->global->annotations)) {
                    $annotationsAdapter = '\Phalcon\Annotations\Adapter\\' . $config->global->annotations->adapter;
                    $adapter = new $annotationsAdapter($config->global->annotations->toArray());
                } else {
                    $adapter = new PhAnnotationsMemory();
                }

                return $adapter;
            },
            true
        );
    }

    /**
     * Init Backend cache.
     *
     * @param DI     $di     Dependency Injection.
     * @param Config $config Config object.
     *
     * @return void
     */
    protected function _initCache($di, $config)
    {
        if (ENV == ENV_DEVELOPMENT) {
            // Create a dummy cache for system.
            // System will work correctly and the data will be always current for all adapters.
            $dummyCache = new EnDummy(null);
            $di->set('viewCache', $dummyCache);
            $di->set('cacheOutput', $dummyCache);
            $di->set('cacheData', $dummyCache);
            $di->set('modelsCache', $dummyCache);
        } else {
            // Get the parameters.
            $cacheAdapter = '\Phalcon\Cache\Backend\\' . $config->global->cache->adapter;
            $frontEndOptions = ['lifetime' => $config->global->cache->lifetime];
            $backEndOptions = $config->global->cache->toArray();
            $frontOutputCache = new CacheOutput($frontEndOptions);
            $frontDataCache = new CacheData($frontEndOptions);

            $cacheOutputAdapter = new $cacheAdapter($frontOutputCache, $backEndOptions);
            $di->set('viewCache', $cacheOutputAdapter, true);
            $di->set('cacheOutput', $cacheOutputAdapter, true);

            $cacheDataAdapter = new $cacheAdapter($frontDataCache, $backEndOptions);
            $di->set('cacheData', $cacheDataAdapter, true);
            $di->set('modelsCache', $cacheDataAdapter, true);
        }
    }

    /**
     * Init router.
     *
     * @param DI     $di     Dependency Injection.
     * @param Config $config Config object.
     *
     * @return Router
     */
    protected function _initRouter($di, $config)
    {
        $cacheData = $di->get('cacheData');
        $router = $cacheData->get(EnSystem::CACHE_KEY_ROUTER_DATA);

        if ($router == null) {
            $saveToCache = ($router === null);

            // Load all controllers of all modules for routing system.
            $modules = $di->get('registry')->modules;

            // Use the annotations router.
            $router = new PhRouterAnnotations(false);
            $router->setDefaultModule(Application::SYSTEM_DEFAULT_MODULE);
            $router->setDefaultNamespace(ucfirst(Application::SYSTEM_DEFAULT_MODULE) . '\Controller');
            $router->setDefaultController('Index');
            $router->setDefaultAction('index');

            /**
             * Load all route from router file
             */
            foreach ($modules as $module) {
                $moduleName = ucfirst($module);

                // Get all file names.
                $moduleDir = opendir($di->get('registry')->directories->modules . $moduleName . '/Controller');
                while ($file = readdir($moduleDir)) {
                    if ($file == "." || $file == ".." || strpos($file, 'Controller.php') === false) {
                        continue;
                    }
                    $controller = $moduleName . '\Controller\\' . str_replace('Controller.php', '', $file);
                    $router->addModuleResource(strtolower($module), $controller);
                }
                closedir($moduleDir);
            }

            if ($saveToCache) {
                $cacheData->save(EnSystem::CACHE_KEY_ROUTER_DATA, $router, 2592000); // 30 days cache
            }
        }

        $router->removeExtraSlashes(true);

        $di->set('router', $router);
        return $router;
    }

    /**
     * Init session.
     *
     * @param DI     $di     Dependency Injection.
     * @param Config $config Config object.
     *
     * @return SessionAdapter
     */
    protected function _initSession($di, $config)
    {
        $session = new PhSessionFiles();
        $session->start();
        $di->setShared('session', $session);
        return $session;
    }

    /**
     * Init security.
     *
     * @param DI     $di     Dependency Injection.
     *
     * @return void
     */
    protected function _initSecurity($di)
    {
        $di->set('security', function () {
            $security = new PhSecurity();
            $security->setWorkFactor(10);

            return $security;
        });
    }

    /**
     * Init cookie.
     *
     * @param DI     $di     Dependency Injection.
     *
     * @return void
     */
    protected function _initCookie($di)
    {
        $di->set('cookie', function() {
            $cookie = new PhCookies();
            $cookie->useEncryption(true);

            return $cookie;
        });
    }

    /**
     * Init crypt hash for cookie service.
     *
     * @param DI     $di     Dependency Injection.
     * @param Config $config Config object.
     *
     * @return void
     */
    public function _initCrypt($di, $config)
    {
        $di->set('crypt', function () use ($config) {
            $crypt = new PhCrypt();
            $crypt->setMode(MCRYPT_MODE_CFB);
            $crypt->setKey($config->global->cookieEncryptionkey);

            return $crypt;
        });
    }

    /**
     * Init logger.
     *
     * @param DI     $di     Dependency Injection.
     * @param Config $config Config object.
     *
     * @return void
     */
    protected function _initLogger($di, $config)
    {
        $di->set('logger', function ($file = 'main', $format = null) use ($config) {
            $logger = new PhLogFile($config->global->logger->path . ENV . '.' . $file . '.log');
            $formatter = new PhFormatLine(($format ? $format : $config->global->logger->format));
            $logger->setFormatter($formatter);

            return $logger;
        }, false);
    }

    /**
     * Init flash messages.
     *
     * @param DI $di Dependency Injection.
     *
     * @return void
     */
    protected function _initFlash($di)
    {
        $flashData = [
            'error' => 'alert alert-danger',
            'success' => 'alert alert-success',
            'warning' => 'alert alert-warning',
            'info' => 'alert alert-info',
        ];

        $di->set('flash', function () use ($flashData) {
            $flash = new PhFlashDirect($flashData);

            return $flash;
        });

        $di->set('flashSession', function () use ($flashData) {
            $flash = new PhFlashSession($flashData);

            return $flash;
        });
    }

    /**
     * Init file manager.
     *
     * @param DI $di Dependency Injection.
     *
     * @return void
     */
    protected function _initFile($di)
    {
        $di->set('file', function() {
            $cache = null;
            $filesystem = new FlySystem(
                new FlyLocalAdapter(PUBLIC_PATH),
                $cache
            );

            return $filesystem;
        });
    }
}
