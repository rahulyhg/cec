<?php
namespace Engine;

use Phalcon\Db\Profiler as DatabaseProfiler;

/**
 * Profiler.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Profiler
{
    /**
     * Database profiler.
     *
     * @var DatabaseProfiler
     */
    protected $_dbProfile;

    /**
     * Current time.
     *
     * @var float
     */
    protected $_time;

    /**
     * Current memory.
     *
     * @var float
     */
    protected $_memory;

    /**
     * Time data.
     *
     * @var array
     */
    protected $_timeData = [];

    /**
     * Memory data.
     *
     * @var array
     */
    protected $_memoryData = [];

    /**
     * Errors data.
     *
     * @var array
     */
    protected $_errorData = [];

    /**
     * Allowed object types for time and memory collection.
     *
     * @var array
     */
    public static $objectTypes = [
        'controller',
        'view',
        'helper'
    ];

    /**
     * Start profiling.
     *
     * @return void
     */
    public function start()
    {
        $this->_time = microtime(true);
        $this->_memory = memory_get_usage();
    }

    /**
     * Stop profiling and collect data.
     *
     * @param string $class      Object class name.
     * @param string $objectType Object type.
     */
    public function stop($class, $objectType)
    {
        if (!isset($this->_timeData[$objectType])) {
            $this->_timeData[$objectType] = [];
        }
        $this->_timeData[$objectType][$class] = microtime(true) - $this->_time;

        $memory = memory_get_usage() - $this->_memory;
        if ($memory < 0) {
            $memory = 0;
        }
        if (!isset($this->_memoryData[$objectType])) {
            $this->_memoryData[$objectType] = [];
        }
        $this->_memoryData[$objectType][$class] = $memory;
    }

    /**
     * Get collected data.
     *
     * @param string $type       Profiling type (time, memory, etc).
     * @param string $objectType Object type (controller, widget, etc).
     *
     * @return array
     */
    public function getData($type, $objectType = null)
    {
        $var = "_{$type}Data";
        $data = $this->$var;

        if (!$objectType) {
            return $data;
        }

        if (empty($data[$objectType])) {
            return [];
        }

        return $data[$objectType];
    }


    /**
     * Collect errors.
     *
     * @param string $error Error text.
     * @param string $trace Error trace.
     *
     * @return void
     */
    public function addError($error, $trace)
    {
        $this->_errorData[] = [
            'error' => $error,
            'trace' => $trace
        ];
    }

    /**
     * Set Phalcon database profiler.
     *
     * @param DatabaseProfiler $profiler Profiler object.
     *
     * @return void
     */
    public function setDbProfiler($profiler)
    {
        $this->_dbProfiler = $profiler;
    }

    /**
     * Get Phalcon database profiler.
     *
     * @return DatabaseProfiler
     */
    public function getDbProfiler()
    {
        return $this->_dbProfiler;
    }
}
