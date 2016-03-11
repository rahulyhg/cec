<?php
namespace Core;

use Engine\Bootstrap as EngineBootstrap;
use Engine\Config;
use Phalcon\DI;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\View;

/**
 * Core Bootstrap.
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
    protected $_moduleName = 'Core';

    /**
     * Bootstrap construction.
     *
     * @param DiInterface $di Dependency injection.
     * @param Manager     $em Events manager object.
     */
    public function __construct($di, $em)
    {
        parent::__construct($di, $em);

        /**
         * Attach this bootstrap for all application initialization events.
         */
        $em->attach('init', $this);
    }

    /**
     * Init some subsystems after engine initialization.
     */
    public function afterEngine()
    {
        $di = $this->getDI();
        $config = $this->getConfig();

        /**
         * Listening to events in the dispatcher using the Translation.
         */
        $this->getEventsManager()->attach('dispatch', $di->get('core')->translate());

        /**
         * Listening to events in the dispatcher using the Acl.
         */
        $this->getEventsManager()->attach('dispatch', $di->get('core')->acl());

        /**
         * Listening to events in the dispatcher using the custom Ajax response.
         */
        $this->getEventsManager()->attach('dispatch', $di->get('core')->ajax());

        /**
         * Listening to events in the dispatcher using the custom template render.
         */
        $this->getEventsManager()->attach('dispatch', $di->get('core')->template());
    }
}
