<?php
namespace Engine\Interfaces;

use Phalcon\DI;

/**
 * Command interface.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
interface CommandInterface
{
    /**
     * Get command name.
     *
     * @return string
     */
    public function getName();

    /**
     * Prints help on the usage of the command.
     *
     * @return void
     */
    public function getHelp();

    /**
     * Dispatch command. Find out action and exec it with parameters.
     *
     * @return mixed
     */
    public function dispatch();
}