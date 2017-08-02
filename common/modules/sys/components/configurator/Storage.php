<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\configurator;

use yii\db\Connection;
use yii\caching\FileCache;
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
        if(!($this->storage = $this->cache->get(self::KEY)))
        {
            $this->storage = [];
        }

    }

    /**
     * Get array value.
     * @param null $key
     * @param null $default
     * @return array|mixed
     */
    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->storage;
        }
        return ArrayHelper::getValue($this->storage, $key, $default);
    }

    /**
     * Set array value.
     * @param $key
     * @param null $value
     * @return void
     */
    public function set($key, $value = null) : void
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
     * @return void
     */
    public  function delete($key) : void
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
        if (($value = $this->get($key)) !== null) {
            return $value;
        }
        if ($fn instanceof \Closure) {
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

    /**
     * Key exists or ser array value.
     * @param $key
     * @param \Closure $fn
     * @return bool
     */
    public function existsOrSet($key, \Closure $fn) : bool
    {
        if (!ArrayHelper::key_exists($this->storage, $key)) {
            if ($fn instanceof \Closure) {
                $value = $fn($this);
                $this->set($key, $value);
            }
            return false;
        }
        return true;
    }
}