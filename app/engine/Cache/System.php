<?php
namespace Engine\Cache;

use Phalcon\Cache\Backend;

/**
 * System cache keys.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 */
class System
{
    const
        /**
         * Cache key for router data.
         */
        CACHE_KEY_ROUTER_DATA = 'router.cache';
}