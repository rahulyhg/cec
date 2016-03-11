<?php
namespace Engine\Injection;

use Engine\Behavior\DIBehavior;
use Engine\Exception;
use Phalcon\DI;

/**
 * Injection container.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Injector
{
    use DIBehavior {
        DIBehavior::__construct as protected __DIConstruct;
    }

    /**
     * Current module name.
     *
     * @var string
     */
    protected $_moduleName;

    /**
     * Create injection container.
     *
     * @param string $moduleName Module naming.
     * @param DI     $di         Dependency injection.
     */
    public function __construct($moduleName, $di)
    {
        $this->_moduleName = $moduleName;
        $this->__DIConstruct($di);
    }

    /**
     * Get injection from container.
     *
     * @param string $name      Injection name.
     * @param array  $arguments Injection params.
     *
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $injectionClassName = sprintf('%s\Injection\%s', ucfirst($this->_moduleName), ucfirst($name));
        $di = $this->getDI();

        if (!$di->has($injectionClassName)) {
            if (!class_exists($injectionClassName)) {
                throw new \Exception(sprintf('Can not find Injection with name "%s".', $name));
            }

            $injection = new $injectionClassName($this->getDI(), $arguments);
            $di->set($injectionClassName, $injection, true);
            return $injection;
        }

        return $di->get($injectionClassName);
    }
}
