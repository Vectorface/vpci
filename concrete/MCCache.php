<?php

require_once(__DIR__."/Cache.php");

/**
 * This cache is very fast, according to basic benchmarks:
 *
 * Parameters:
 *   Memcache 1.2.2, running locally
 *   9-byte key
 *   151-byte value
 *   10000-iteration test
 *
 * Result:
 *   0.859622001648 seconds
 *
 * Conclusion:
 *   Capable of approximately 11678 requests/second
 */

class MCCache extends Cache {

	private $mc;
	private $config;

	public function __construct($config = null, &$mc = null) {
		$this->config = isset($config) ? $config : new Config();
		if (isset($mc)) {
			if (!($mc instanceof Memcache)) {
				throw new Exception('Invalid parameter: expected a Memcache object.');
			}
			$this->mc = $mc;
		} else {
			$this->mc = $this->getMemcache();
		}
	}

	public function getConcrete($entry) {
		return $this->mc->get($entry);
	}

	public function set($entry, $value, $ttl) {
		return $this->mc->set($entry, $value, NULL, $ttl);
	}

	/**
	 * Delete an entry in the cache by key regaurdless of TTL
	 * @param  Array($string) $keys An array of keys/indexes of cache entries
	 */
	public function delete($keys)
	{
		foreach ($keys as $key) {
			$this->mc->delete($key);
		}
		return null;
	}

	public function clean() {
		return NULL;
	}

	public function flush() {
		return $this->mc->flush();
	}

	private function getMemcache() {
		$cache = new Memcache();

		$servers = $this->config->getConfigVal("memcacheServers");

		foreach ($servers as $s) {
			$cache->addServer($s['host'], $s['port']);
		}

		return $cache;
	}
}
