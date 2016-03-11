<?php
namespace Engine\Interfaces;

use Phalcon\DI;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;

/**
 * Bootstrap interface.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
interface BootstrapInterface
{
    /**
     * Create Bootstrap.
     *
     * @param DiInterface $di Dependency injection.
     * @param Manager     $em Events manager.
     */
    public function __construct($di, $em);

    /**
     * Register module services.
     *
     * @return void
     */
    public function registerServices();
}