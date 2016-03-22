<?php
namespace Engine;

use Phalcon\Config as PhConfig;

/**
 * Application config.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Config extends PhConfig
{
    const
        /**
         * System config location.
         */
        CONFIG_PATH = '/app/config/',

        /**
         * System config location.
         */
        CONFIG_CACHE_PATH = '/app/var/cache/data/config.php',

        /**
         * Listing modules will be load in application.
         */
        CONFIG_MODULES_WILL_LOAD = 'user,category,article,pcategory,product,slug,company',

        /**
         * Default configuration section.
         */
        CONFIG_DEFAULT_SECTION = 'application';

    /**
     * Current config stage.
     * @var string
     */
    private $_currentStage;

    /**
     * Create configuration object.
     *
     * @param array|null  $arrayConfig Configuration data.
     * @param string|null $stage       Configuration stage.
     */
    public function __construct($arrayConfig = [], $stage = null)
    {
        $this->_currentStage = $stage;
        parent::__construct($arrayConfig);
    }

    /**
     * Load configuration according to selected stage.
     *
     * @param string $stage Configuration stage.
     *
     * @return Config
     */
    public static function factory($stage = null)
    {
        if (!$stage) {
            $stage = ENV;
        }

        if ($stage == ENV_DEVELOPMENT) {
            $config = self::_getConfiguration($stage);
        } else {
            if (file_exists(self::CONFIG_CACHE_PATH)) {
                $config = new Config(include_once(self::CONFIG_CACHE_PATH), $stage);
            } else {
                $config = self::_getConfiguration($stage);
                $config->refreshCache();
            }
        }

        return $config;
    }

    /**
     * Load configuration from all files.
     *
     * @param string $stage Configuration stage.
     *
     * @throws Exception
     * @return Config
     */
    protected static function _getConfiguration($stage)
    {
        $config = new Config([], $stage);
        $configDirectory = ROOT_PATH . self::CONFIG_PATH . $stage;
        $configFiles = glob($configDirectory .'/*.php');

        foreach ($configFiles as $file) {
            $data = include_once($file);
            $config->offsetSet(basename($file, ".php"), $data);
        }

        $config->offsetSet('events', []);
        $config->offsetSet('modules', explode(',', self::CONFIG_MODULES_WILL_LOAD));

        return $config;
    }

    /**
     * Save config file into cached config file.
     *
     * @return void
     */
    public function refreshCache()
    {
        file_put_contents(ROOT_PATH . self::CONFIG_CACHE_PATH, $this->_toConfigurationString());
    }

    /**
     * Save application config to file.
     *
     * @param array|null $data Configuration data.
     *
     * @return void
     */
    protected function _toConfigurationString($data = null)
    {
        if (!$data) {
            $data = $this->toArray();
        }

        $configText = var_export($data, true);

        // Fix pathes. This related to windows directory separator.
        $configText = str_replace('\\\\', DS, $configText);

        $configText = str_replace("'" . PUBLIC_PATH, "PUBLIC_PATH . '", $configText);
        $configText = str_replace("'" . ROOT_PATH, "ROOT_PATH . '", $configText);
        $headerText = '<?php
/**
* WARNING
*
* Manual changes to this file may cause a malfunction of the system.
* Be careful when changing settings!
*
*/

return ';

        return $headerText . $configText . ';';
    }
}
