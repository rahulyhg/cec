<?php
namespace Engine;

use Phalcon\Mvc\Application as PhApplication;
use Phalcon\DI;
use Phalcon\Events\Manager as PhEventsManager;
use Phalcon\Registry as PhRegistry;

/**
 * Application class.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Application extends PhApplication
{
    use Init;

    const
        /**
         * Default module.
         */
        SYSTEM_DEFAULT_MODULE = 'core';

    /**
     * Application configuration.
     *
     * @var Config
     */
    protected $_config;

    /**
     * Loaders for different modes.
     *
     * @var array
     */
    private $_loaders = [
        'normal' => [
            'environment',
            'engine',
            'annotations',
            'db',
            'cache',
            'router',
            'session',
            'security',
            'cookie',
            'crypt',
            'file',
            'flash'
        ],
        'console' => [
            'environment',
            'db',
            'cache',
            'engine'
        ]
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        /**
         * Create default DI.
         */
        $di = new DI\FactoryDefault();

        /**
         * Get config.
         */
        $this->_config = Config::factory();

        /**
         * Adding modules to registry to load.
         * Module namespace - directory will be load from here.
         */
        $registry = new PhRegistry();
        $registry->modules = array_merge(
            [self::SYSTEM_DEFAULT_MODULE],
            $this->_config->modules->toArray()
        );

        $registry->directories = (object)[
            'engine' => ROOT_PATH . '/app/engine/',
            'modules' => ROOT_PATH . '/app/modules/'
        ];

        $di->set('registry', $registry);

        /**
         * Store config in the DI container.
         */
        $di->setShared('config', $this->_config);

        parent::__construct($di);
    }

    /**
     * Runs the application, performing all initializations.
     *
     * @param string $mode Mode name.
     *
     * @return void
     */
    public function run($mode = 'normal')
    {
        if (empty($this->_loaders[$mode])) {
            $mode = 'normal';
        }

        /**
         * Set application main objects.
         */
        $di = $this->_dependencyInjector;
        // set app service to check app is in cli mode or not
        $di->setShared('app', $this);
        $config = $this->_config;
        $eventsManager = new PhEventsManager();
        $this->setEventsManager($eventsManager);

        /**
         * Init base systems first.
         */
        $this->_initLogger($di, $config, $eventsManager);
        $this->_initLoader($di, $config, $eventsManager);

        /**
         * Attach module plugin event
         */
        $this->_attachEngineEvents($eventsManager, $config);

        /**
         * Init services and engine system.
         */
        foreach ($this->_loaders[$mode] as $service) {
            $serviceName = ucfirst($service);
            $eventsManager->fire('init:before' . $serviceName, null);
            $result = $this->{'_init' . $serviceName}($di, $config, $eventsManager);
            $eventsManager->fire('init:after' . $serviceName, $result);
        }

        $di->setShared('eventsManager', $eventsManager);
    }

    /**
     * Init modules and register them.
     *
     * @param array $modules Modules bootstrap classes.
     * @param null  $merge   Merge with existing.
     *
     * @return $this
     */
    public function registerModules(array $modules, $merge = null)
    {
        $bootstraps = [];
        $di = $this->getDI();
        foreach ($modules as $moduleName => $moduleClass) {
            if (isset($this->_modules[$moduleName])) {
                continue;
            }
            $bootstrap = new $moduleClass($di, $this->getEventsManager());
            $bootstraps[$moduleName] = function () use ($bootstrap, $di) {
                $bootstrap->registerServices();

                return $bootstrap;
            };
        }

        return parent::registerModules($bootstraps, $merge);
    }

    /**
     * Attach required events.
     *
     * @param EventsManager $eventsManager Events manager object.
     * @param Config        $config        Application configuration.
     *
     * @return void
     */
    protected function _attachEngineEvents($eventsManager, $config)
    {
        // Attach modules plugins events.
        $events = $config->events->toArray();
        $cache = [];
        foreach ($events as $item) {
            list ($class, $event) = explode('=', $item);
            if (isset($cache[$class])) {
                $object = $cache[$class];
            } else {
                $object = new $class();
                $cache[$class] = $object;
            }
            $eventsManager->attach($event, $object);
        }
    }

    /**
     * Get application output.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->handle()->getContent();
    }

    /**
     * Check if application is used from console.
     *
     * @return bool
     */
    public function isConsole()
    {
        return (php_sapi_name() == 'cli');
    }
}
