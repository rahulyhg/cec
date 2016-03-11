<?php
namespace Engine;

use Phalcon\Mvc\Dispatcher as PhDispatcher;

/**
 * Application dispatcher.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Dispatcher extends PhDispatcher
{
    /**
     * Dispatch.
     * Override it to use own logic.
     *
     * @throws \Exception
     * @return object
     */
    public function dispatch()
    {
        try {
            $parts = explode('_', $this->_handlerName);
            $finalHandlerName = '';

            foreach ($parts as $part) {
                $finalHandlerName .= ucfirst($part);
            }
            $this->_handlerName = $finalHandlerName;
            $this->_actionName = strtolower($this->_actionName);
            // var_dump($this->_handlerName, $this->_actionName);
            // die;
            return parent::dispatch();
        } catch (\Exception $e) {
            $this->_handleException($e);

            if (ENV == ENV_DEVELOPMENT) {
                throw $e;
            } else {
                $id = Exception::logError(
                    'Exception',
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine(),
                    $e->getTraceAsString()
                );

                $this->getDI()->setShared(
                    'currentErrorCode',
                    function () use ($id) {
                        return $id;
                    }
                );
            }
        }

        return parent::dispatch();
    }
}
