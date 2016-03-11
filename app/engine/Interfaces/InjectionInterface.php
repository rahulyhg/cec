<?php
namespace Engine\Interfaces;

use Phalcon\DiInterface;

/**
 * Injection interface.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
interface InjectionInterface
{
    /**
     * Create api.
     *
     * @param DiInterface $di        Dependency injection.
     * @param array       $arguments Api arguments.
     */
    public function __construct(DiInterface $di, $arguments);
}
