<?php
require_once(__DIR__."/Cache.php");
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
			throw new Exception('Unable to initialize APCCache: APC extension not loaded.');
		}
	}

	public function getConcrete($entry) {
		return apc_fetch($entry);
	}

	public function set($entry, $value, $ttl) {
		return apc_store($entry, $value, $ttl);
	}

	/**
	 * Delete an entry in the cache by key regaurdless of TTL
	 * @param  Array($string) $keys An array of keys/indexes of cache entries
	 */
	public function delete($keys)
	{
		foreach ($keys as $key) {
			apc_delete($key);
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
