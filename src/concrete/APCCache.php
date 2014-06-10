<?php

namespace VF\CacheInterface\concrete;

require_once(__DIR__."/Cache.php");

use VF\CacheInterface\concrete\Cache;

/**
 * This cache is ridiculously fast, according to basic benchmarks:
 *
 * Parameters:
 *   APC 3.0.19
 *   9-byte key
 *   151-byte value
 *   10000-iteration test
 *
 * Result:
 *   0.0652229785919 seconds
 *
 * Conclusion:
 *   Capable of approximately 153374.23 requests/second
 */

class APCCache extends Cache {

	public function __construct($config = null) {
		if (!extension_loaded('apc')) {
			throw new \Exception('Unable to initialize APCCache: APC extension not loaded.');
		}
		parent::__construct($config);
	}

	public function getConcrete($key) {
		return apc_fetch($this->prefixKey($key));
	}

	public function set($key, $value, $ttl) {
		return apc_store($this->prefixKey($key), $value, $ttl);
	}

	/**
	 * Delete an entry in the cache by key regaurdless of TTL
	 * @param  Array($string) $keys An array of keys/indexes of cache entries
	 */
	public function delete($keys)
	{
		foreach ($keys as $key) {
			apc_delete($this->prefixKey($key));
		}
		return null;
	}

	public function clean() {
		return NULL;
	}

	public function flush() {
		if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
			return apc_clear_cache();
		}
		return apc_clear_cache('user');
	}
}
