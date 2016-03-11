<?php
namespace Core\Controller;

use Phalcon\Mvc\Controller as PhController;
use Core\Plugin\AdminElements;
use Core\Plugin\AdminBreadcrumbs;

/**
 * Admin Base controller.
 *
 * @category  Admin
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
abstract class AbstractAdminController extends PhController
{
    protected $lang;

    /**
     * Initializes the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $di = $this->getDI();
        $config = $di->getConfig();
        $dispatcher = $di->getDispatcher();

        // Load Language service
        $this->lang = $di->get('lang');

        // Load profiler
        if ($di->has('profiler')) {
            $this->profiler->start();
        }

        // Set admin elements
        $di->set('elements', function() {
            return new AdminElements();
        });

        $this->bc = new AdminBreadcrumbs();
    }

    /**
     * get Current URL
     */
    public function getCurrentUrl()
    {
        $dispatcher = $this->getDI()->getDispatcher();
        $controllerName = strtolower($dispatcher->getControllerName());
        $moduleName = $dispatcher->getModuleName();
        $actionName = $dispatcher->getActionName();
        $url = $controllerName . '/' . $moduleName . '/' . $actionName;

        return str_replace('/index', '', $url);
    }
}
