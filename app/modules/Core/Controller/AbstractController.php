<?php
namespace Core\Controller;

use Phalcon\Mvc\Controller as PhController;

/**
 * Abstract Sitse controller.
 *
 * @category  Core
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
abstract class AbstractController extends PhController
{
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

        if ($di->has('profiler')) {
            $this->profiler->start();
        }
    }

    public function afterExecuteRoute()
    {
       if ($this->di->has('profiler')) {
           $this->profiler->stop(get_called_class(), 'controller');
       }
    }
}
