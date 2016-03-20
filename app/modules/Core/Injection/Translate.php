<?php
namespace Core\Injection;

use Engine\Injection\AbstractInjection;
use Engine\Config;
use Phalcon\Translate\Adapter\NativeArray as PhTranslateArray;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Mvc\Dispatcher;

/**
 * Core translate api.
 *
 * @category  Core
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Translate extends AbstractInjection
{
    const DEFAULT_LANG_PACK = 'Core';

    /**
     * This action is executed before execute any action in the application.
     *
     * @param PhalconEvent $event      Event object.
     * @param Dispatcher   $dispatcher Dispatcher object.
     *
     * @return mixed
     */
    public function beforeDispatch(PhEvent $event, Dispatcher $dispatcher)
    {
        $di = $this->getDI();
        $cookie = $di->getCookie();
        $session = $di->getSession();
        $config = $di->getConfig();
        $languageCode = '';

        if ($di->get('app')->isConsole()) {
            return;
        }

        // Detect language from cookie
        if ($cookie->has('languageCode')) {
            $languageCode = $cookie->get('languageCode')->getValue();
        } else {
            // Get default language from language model
            $languageCode = $config->global->defaultLanguage;
        }

        // Set language code to session
        if (($session->has('languageCode') && $session->get('languageCode') != $languageCode) || !$session->has('languageCode')) {
            $session->set('languageCode', $languageCode);
        }

        $messages = [];
        $directory = $di->get('registry')->directories->modules . ucfirst($dispatcher->getModuleName()) . '/Lang/'
            . $languageCode . '/'
            . strtolower($dispatcher->getControllerName());
        $extension = '.php';

        if (file_exists($directory . $extension)) {
            require $directory . $extension;
        }

        // add default core lang package
        require $di->get('registry')->directories->modules . self::DEFAULT_LANG_PACK . '/Lang/' . $languageCode . '/default.php';

        $translate = new PhTranslateArray([
            'content' => array_merge($messages, $default)
        ]);

        $di->set('lang', $translate);
        return !$event->isStopped();
    }
}
