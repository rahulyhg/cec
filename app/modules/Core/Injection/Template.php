<?php
namespace Core\Injection;

use Engine\Injection\AbstractInjection;
use Phalcon\DI;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;

/**
 * Template Injection.
 *
 * @category  Core
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Template extends AbstractInjection
{
    /**
     * This action is executed after execute any action in the application.
     *
     * @param PhalconEvent $event      Event object.
     * @param Dispatcher   $dispatcher Dispatcher object.
     *
     * @return mixed
     */
    public function afterExecuteRoute(PhEvent $event, Dispatcher $dispatcher)
    {
        $config = $this->getDI()->get('config')->toArray();
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();
        // var_dump($controllerName, $actionName);die;
        $viewDir = $controllerName . '/' . $config['global']['template'][$controllerName] . '/' . $actionName;

        if (SUBDOMAIN == 'm' && $controllerName == 'Site') {
            $viewDir = $controllerName . '/' . $config['global']['template'][$controllerName] . '/m.' . $actionName;
        }

        $this->getDI()->get('view')->pick($viewDir);
    }
}
