<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\configurator;

use yii\caching\FileCache;
use yii\db\Connection;
use sys\helpers\ArrayHelper;

/**
 * Class Storage.
 */
class Storage
{
    protected const KEY = 'application.storage';

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var FileCache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $storage = [];

    /**
     * Storage constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db, FileCache $cache)
    {
        $this->db      = $db;
        $this->cache   = $cache;
        $this->storage = $this->cache->get(self::KEY, []);
    }

    /**
     * Get array value.
     * @param null $key
     * @param null $default
     * @return array|mixed
     */
    public function get($key = null, $default = null)
    {
        if ($key == null) {
            return $this->storage;
        }
        return ArrayHelper::getValue($this->storage, $key, $default);
    }

    /**
     * Set array value.
     * @param $key
     * @param null $value
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->storage  = array_replace_recursive($this->storage, $key);
        } elseif ($value !== null) {
            ArrayHelper::set($this->storage, $key, $value);
        }
        $this->cache->set(self::KEY, $this->storage);
    }

    /**
     * Delete array value.
     * @param $key
     */
    public  function delete($key)
    {
        ArrayHelper::delete($this->storage, $key);
        $this->cache->set(self::KEY, $this->storage);
    }

    /**
     * Get or ser array value.
     * @param $key
     * @param \Closure $fn
     * @return array|mixed
     */
    public function getOrSet($key, \Closure $fn)
    {
        $value = $this->get($key);
        if ($value) {
            return $value;
        }
        if (is_callable($fn)) {
            $value = $fn($this);
            $this->set($key, $value);
        }
    }

    /**
     * Get and delete array value.
     * @param $key
     * @return array|mixed
     */
    public function getAndDelete($key)
    {
        $value = $this->get($key);
        $this->delete($key);
        return $value;
    }
}