<?php
namespace Engine\Injection;

use Engine\Behavior\DIBehavior;
use Engine\Interfaces\InjectionInterface;
use Phalcon\DI;
use Phalcon\DiInterface;

/**
 * Abstract Injection.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
abstract class AbstractInjection implements InjectionInterface
{
    use DIBehavior {
        DIBehavior::__construct as protected __DIConstruct;
    }

    /**
     * Injection arguments.
     *
     * @var array
     */
    private $_arguments;

    /**
     * Create injection.
     *
     * @param DiInterface $di        Dependency injection.
     * @param array       $arguments Injection arguments.
     */
    public function __construct(DiInterface $di, $arguments)
    {
        $this->__DIConstruct($di);
        $this->_arguments = $arguments;
    }

    /**
     * Get Injection call arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->_arguments;
    }
}
