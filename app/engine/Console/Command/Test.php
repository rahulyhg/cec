<?php
namespace Engine\Console\Command;

use Engine\Console\AbstractCommand;
use Engine\Interfaces\CommandInterface;
use Engine\Console\ConsoleUtil;
use Phalcon\DI;

/**
 * Test command.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @CommandName(['test'])
 * @CommandDescription('test command controller.')
 */
class Test extends AbstractCommand implements CommandInterface
{
    /**
     * Test action.
     *
     * @return void
     */
    public function testAction($param1 = null, $param2 = null)
    {
        print ConsoleUtil::success('Test command success - param1: '. $param1 .' - param2: '. $param2 .'.') . PHP_EOL;
    }
}