<?php

namespace VF\vpci\concrete;

require_once(__DIR__."/../config/config.php");

use VF\vpci\config\Config;

/**
 * Cache: A common interface to various types of caches
 */
abstract class Cache {

	private $prefix;

	public function __construct($config = null)
	{
		$this->config = isset($config) ? $config : new Config();
		
	}

	/**
	 * Fetch a cache entry by key.
	 *
	 * @param String $key The key for the entry to fetch
	 * @return mixed The value stored in the cache for $key
	 */
	public abstract function getConcrete($key);

	/**
	 * Set an entry in the cache.
	 *
	 * @param String $key The key/index for the cache entry
	 * @param mixed $value The item to store in the cache
	 * @param int $ttl The time to live (or expiry) of the cached item. Not all caches honor the TTL.
	 */
	public abstract function set($key, $value, $ttl);

	/**
	 * Manually clean out entries older than their TTL
	 */
	public abstract function clean();

	/**
	 * Clear the cache.
	 */
	public abstract function flush();


	public function get($key, $callable = [], $ttl = -1)
	{
		$data = $this->getConcrete($key);

		if (empty($data) && !empty($callable)) {
			if (isset($callable["args"])) {
				$data = call_user_func($callable["function"], $callable["args"]);
			} else {
				$data = call_user_func($callable["function"]);
			}
			if ($ttl > 0 && !empty($data)) {
				$this->set($key, $data, $ttl);
			}
		}

		return $data;
	}

	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

	public function prefixKey($key)
	{
		$prefix = $this->config->getConfigVal("cachePrefix");
		$new_key = $prefix . $key;
		return  $new_key;
	}

	public function getConfigVal($key)
	{
		return $this->config->getConfigVal($key);
	}
}
