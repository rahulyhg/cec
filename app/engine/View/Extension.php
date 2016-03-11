<?php
namespace Engine\View;

use Phalcon\DI;

/**
 * Volt function extension.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Extension extends DI\Injectable
{
    /**
     * Compile functions for volt.
     *
     * @param string $name      Function name.
     * @param string $arguments Function arguments as string.
     * @param array  $params    Function parsed params.
     *
     * @return string|void
     */
    public function compileFunction($name, $arguments, $params)
    {
        switch ($name) {
            case 'php':
                $code = '';
                if (isset($params[0]) && isset($params[0]['expr']['value'])) {
                    $code = $params[0]['expr']['value'];
                }
                return $code;

            case 'helper':
                return '\Engine\Helper::getInstance(' . $arguments . ')';

            case 'classof':
                return 'get_class(' . $arguments . ')';

            case 'instanceof':
                $resolvedArgs = explode(',', $arguments);
                $resolvedArgs[1] = trim(str_replace(["'", '"'], ['', ''], $resolvedArgs[1]));
                return $resolvedArgs[0] . ' instanceof ' . $resolvedArgs[1];
        }
    }

    /**
     * Compile filters for volt.
     *
     * @param string $name      Function name.
     * @param string $arguments Function arguments as string.
     *
     * @return string|void
     */
    public function compileFilter($name, $arguments)
    {
        switch ($name) {
            case 'i18n':
                return '$this->lang->query(' . $arguments . ')';
        }
    }
}
