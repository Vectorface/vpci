<?php

/**
 * Cache: A common interface to various types of caches
 */
interface Cache {
	/**
	 * Cache singleton: fetch an instance of this cache type.
	 *
	 * @return Cache An initialized instance of the cache.
	 */
	public static function getInstance();

	/**
	 * Fetch a cache entry by key.
	 *
	 * @param String $key The key for the entry to fetch
	 * @return mixed The value stored in the cache for $key
	 */
	public function get($key);

	/**
	 * Set an entry in the cache.
	 *
	 * @param String $key The key/index for the cache entry
	 * @param mixed $value The item to store in the cache
	 * @param int $ttl The time to live (or expiry) of the cached item. Not all caches honor the TTL.
	 */
	public function set($key, $value, $ttl);

	/**
	 * Manually clean out entries older than their TTL
	 */
	public function clean();

	/**
	 * Clear the cache.
	 */
	public function flush();
}
