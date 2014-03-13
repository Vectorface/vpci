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

class MCCache implements Cache {
	protected static $instance;

	public static function getInstance() {
		if (!(self::$instance instanceof self)) {
			if (!class_exists('Config')) {
				throw new Exception('Unable to create instance of ' . __CLASS__ . ': class Config not found');
			}
			self::$instance = new self(Config::getMemcache());
		}
		return self::$instance;
	}

	private $mc;

	public function __construct(&$mc = null) {
		if (isset($mc)) {
			if (!($mc instanceof Memcache)) {
				throw new Exception('Invalid parameter: expected a Memcache object.');
			}
			$this->mc = $mc;
		} else {
			$this->mc = Config::getMemcache();
		}
	}

	public function get($entry) {
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
}
