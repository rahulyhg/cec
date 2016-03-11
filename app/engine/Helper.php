<?php
namespace Engine;

use Phalcon\DI;
use Phalcon\DiInterface;
use Phalcon\Tag;

/**
 * Helper class.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
abstract class Helper extends Tag
{
    /**
     * Helper constructor is protected.
     *
     * @param DiInterface $di Dependency injection.
     */
    protected function __construct($di)
    {
        $this->setDI($di);
    }

    /**
     * Get helper instance.
     *
     * @param DiInterface|string $nameOrDI Helper name.
     * @param string             $module   Module name.
     *
     * @return mixed
     * @throws Exception
     */
    public static function getInstance($nameOrDI, $module = 'engine')
    {
        if ($nameOrDI instanceof DiInterface) {
            $di = $nameOrDI;
            $helperClassName = get_called_class();
        } else {
            $di = DI::getDefault();
            $nameOrDI = ucfirst($nameOrDI);
            $module = ucfirst($module);
            $helperClassName = sprintf('%s\Helper\%s', $module, $nameOrDI);
        }

        if (!$di->has($helperClassName)) {
            /** @var Helper $helperClassName */
            if (!class_exists($helperClassName)) {
                throw new Exception(
                    sprintf('Can not find Helper with name "%s". Searched in module: %s', $nameOrDI, $module)
                );
            }

            $helper = new $helperClassName($di);
            $di->set($helperClassName, $helper, true);
            return $helper;
        }

        return $di->get($helperClassName);
    }
}