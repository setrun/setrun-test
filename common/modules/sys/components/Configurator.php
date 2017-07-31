<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components;

use Yii;
use yii\db\Query;
use yii\db\Connection;
use yii\caching\FileCache;
use sys\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use sys\interfaces\ConfiguratorInterface;

/**
 * Class Configurator.
 */
class Configurator implements ConfiguratorInterface
{
    public const WEB        = 'application.web';
    public const CONSOLE    = 'application.console';
    public const COMPONENTS = 'components';

    /**
     * List of configurations application.
     * @var array
     */
    protected $configApplication = [];

    /**
     * List of configurations components.
     * @var array
     */
    protected $configComponents = [];

    /**
     * Env state.
     * @var string
     */
    protected $env = self::WEB;

    /**
     * Object of cache.
     * @var FileCache
     */
    protected $cache = null;

    /**
     * Caching path.
     * @var string
     */
    protected $cachePath = '@app/runtime/cache_configurator';

    /**
     * List of system files.
     * @var array
     */
    protected $sysFiles = [
        self::WEB     => [
            '@sys/config/main.php'
        ],
        self::CONSOLE => [
            '@sys/config/console/main.php'
        ],
    ];

    /**
     * Name of table.
     * @var string
     */
    protected $table = '{{%component_setting}}';

    /**
     * Set of env state.
     * @param string $env
     * @return void
     */
    public function setEnv(string $env) : void
    {
        $this->env = $env;
    }

    /**
     * Set of caching path.
     * @param string $path
     * @return void
     */
    public function setCachePath(string $path) : void
    {
        $this->cachePath = $path;
    }

    /**
     * Get a configuration of key.
     * @param null $key
     * @param null $default
     * @return array|mixed
     */
    public function component($key = null, $default = null)
    {
        if ($key == null) {
            return $this->configComponents;
        }
        return ArrayHelper::getValue($this->configComponents, $key, $default);
    }

    /**
     * Load a configuration of app.
     * @param array $files
     * @return void
     */
    public function load(array $files) : void
    {
        $this->configComponents = $this->getCache()->getOrSet(self::COMPONENTS, function(){
            return $this->loadComponentsConfig();
        });
        $this->configApplication = $this->getCache()->getOrSet($this->env, function() use ($files){
            $baseConfig            = $this->loadBaseConfig($files);
            $installedModuleConfig = $this->loadInstalledModuleConfig();
            return ArrayHelper::merge($baseConfig, $installedModuleConfig);
       });
    }

    /**
     * Update configuration by user.
     * @param $uid
     * @return void
     */
    public function updateByUser($uid) : void
    {
        $key    = self::COMPONENTS . '_user' . $uid;
        $config = $this->getCache()->getOrSet($key, function() use ($uid) {
            return $this->loadComponentsConfigByUser($uid);
        });
        $this->configComponents = array_replace_recursive($this->configComponents, $config);
        unset($config);
    }

    /**
     * Update configuration by user.
     * @param $did
     * @return void
     */
    public function updateByDomain($did) : void
    {
        $key    = self::COMPONENTS . '_domain' . $did;
        $config = $this->getCache()->getOrSet($key, function() use ($did) {
            return $this->loadComponentsConfigByDomain($did);
        });
        $this->configComponents = array_replace_recursive($this->configComponents, $config);
        unset($config);
    }

    /**
     * Load a base configuration.
     * @param array $files
     * @return array
     */
    protected function loadBaseConfig(array $files) : array
    {
        $files = ArrayHelper::merge($files, $this->sysFiles[$this->env] ?? []);
        $config = [];
        foreach ($files as $file) {
            if (file_exists($file = Yii::getAlias($file))) {
                $config = ArrayHelper::merge($config, (array) require $file);
            }
        }
        return $config;
    }

    /**
     * Load a base configuration of installed modules.
     * @return array
     */
    protected function loadInstalledModuleConfig() : array
    {
        $config = [];
        $appPath            = defined('APP_PATH') ? APP_PATH : ROOT_PATH . '/applications/master';
        $installedPath      = $appPath . '/config/modules' . ($this->env === self::WEB ? '' : '/console');
        $installedLocalPath = $installedPath . '/local';
        $modulesPath        = ROOT_PATH . '/common/modules';
        /** @var $item \SplFileInfo  */
        foreach (new \GlobIterator($installedPath . '/*.php') as $item) {
            $name = $item->getBaseName('.php');
            if (!is_dir($modulesPath . '/' . $name)) {
                continue;
            }
            $module = require $item->getRealPath();
            $local  = new \SplFileInfo($installedLocalPath . '/' . $item->getFileName());
            if ($local->isFile()) {
                $module = ArrayHelper::merge($module, (array) require $local->getRealPath());
            }
            $config = ArrayHelper::merge($config, $module);
        }
        return $config;
    }

    /**
     * Load a configuration of modules.
     * @return array
     */
    protected function loadComponentsConfig() : array
    {
        $config = [];
        $query  = (new Query())->select('*')
                               ->from($this->table)
                               ->where(['user_id' => null])
                               ->all($this->getDb());
        foreach ($query as $row) {
            $config[$row['name']] = json_decode($row['json_value'], true);
        }
        return $config;
    }

    /**
     * Load a configuration of modules bu user.
     * @param $uid
     * @return array
     */
    protected function loadComponentsConfigByUser($uid) : array
    {
        $config = [];
        $query  = (new Query())->select('*')
                               ->from($this->table)
                               ->where(['user_id' => $uid])
                               ->all($this->getDb());
        foreach ($query as $row) {
            $config[$row['name']] = json_decode($row['json_value'], true);
        }
        return $config;
    }

    /**
     * Load a configuration of modules bu domain.
     * @param $did
     * @return array
     */
    protected function loadComponentsConfigByDomain($did) : array
    {
        $config = [];
        $query  = (new Query())->select('*')
            ->from($this->table)
            ->where(['did' => $did])
            ->all($this->getDb());
        foreach ($query as $row) {
            $config[$row['name']] = json_decode($row['json_value'], true);
        }
        return $config;
    }

    /**
     * Get a configurations of launch app.
     * @param bool $null
     * @return array
     */
    public function configure(bool $null = true) : array
    {
        $config = $this->configApplication;
        if ($null) {
          $this->configApplication = [];
        }
        return $config;
    }

    /**
     * Get a cache object.
     * @return object|FileCache
     */
    public function getCache() : FileCache
    {
        if (!$this->cache) {
            $this->cache = Yii::createObject([
                'class'     => FileCache::className(),
                'cachePath' => Yii::getAlias($this->cachePath)
            ]);
        }
        return $this->cache;
    }

    /**
     * Get a db object.
     * @return Connection
     * @throws InvalidConfigException
     */
    public function getDb() : Connection
    {
        $file = APP_PATH . '/config/db-local.php';
        if (!file_exists($file)) {
            throw new InvalidConfigException('Failed to instantiate component or class "db".');
        }
        $db = require $file;
        Yii::$container->setSingleton($db['class'], $db);
        return Yii::$container->get($db['class']);
    }
}