<?php
namespace Product;

use Engine\Bootstrap as EngineBootstrap;
use Engine\Config;
use Phalcon\DI;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\View;

/**
 * Product Bootstrap.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Bootstrap extends EngineBootstrap
{
    /**
     * Current module name.
     *
     * @var string
     */
    protected $_moduleName = 'Product';

    /**
     * Bootstrap construction.
     *
     * @param DiInterface $di Dependency injection.
     * @param Manager     $em Events manager object.
     */
    public function __construct($di, $em)
    {
        parent::__construct($di, $em);
    }
}
