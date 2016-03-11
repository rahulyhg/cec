<?php
namespace Engine\Plugin;

use Engine\Application as EngineApplication;
use Engine\Exception as EngineException;
use Phalcon\Mvc\Dispatcher\Exception as PhDispatchException;
use Phalcon\Mvc\User\Plugin as PhUserPlugin;

/**
 * Not found plugin.
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class DispatchErrorHandler extends PhUserPlugin
{
    /**
     * Before exception is happening.
     *
     * @param Event            $event      Event object.
     * @param Dispatcher       $dispatcher Dispatcher object.
     * @param PhalconException $exception  Exception object.
     *
     * @throws \Phalcon\Exception
     * @return bool
     */
    public function beforeException($event, $dispatcher, $exception)
    {
        // Handle 404 exceptions.
        if ($exception instanceof PhDispatchException) {
            if ($exception->getCode() == \Phalcon\Mvc\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND) {
                $dispatcher->forward([
                    'module' => EngineApplication::SYSTEM_DEFAULT_MODULE,
                    'namespace' => ucfirst(EngineApplication::SYSTEM_DEFAULT_MODULE) . '\Controller',
                    'controller' => 'Error',
                    'action' => 'show404'
                ]);

                return false;
            } else {
                return $this->getDI()->get('response')->redirect('admin/login');
            }

            return true;
        }

        if (ENV == ENV_DEVELOPMENT) {
            throw $exception;
        } else {
            EngineException::logException($exception);
        }

        // Handle other exceptions.
        $dispatcher->forward(
            [
                'module' => EngineApplication::SYSTEM_DEFAULT_MODULE,
                'namespace' => ucfirst(EngineApplication::SYSTEM_DEFAULT_MODULE) . '\Controller',
                'controller' => 'Error',
                'action' => 'show500'
            ]
        );

        return $event->isStopped();
    }
}
