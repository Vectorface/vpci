<?php

namespace VF\CacheInterface\concrete;

require_once(__DIR__."/Cache.php");

use VF\CacheInterface\concrete\Cache;
use VF\CacheInterface\config\Config;

class TempFileCache extends Cache
{
	private $directory;
	private $extension;
	private $datetime;

	public function __construct($config = null, $datetime = null)
	{
		$config = isset($config) ? $config : new Config();

		$this->directory = sys_get_temp_dir();

		// Checking to see if a directory has been set in config.php
		if($config->getConfigVal('tempFileCacheDir')) {
			// If a directory has been set in config.php, dir is formatted as <systemTemp>/<configdir>
			$this->directory .= "/" .Config::get('tempFileCacheDir');
		}

		// If the directory does not already exist, it will be created
		if(!file_exists($this->directory)) {
			mkdir($this->directory);
		}

		// Grab file extension from config
		$this->extension = $config->getConfigVal('tempFileExt');


		// Checks for custom DateTime object
		// This is primarily used for dependency injection in testing
		$this->datetime = isset($datetime) ? $datetime : new \DateTime();
		parent::__construct($config);
	}

	/**
	 * Fetch a cache entry by key.
	 *
	 * @param String $key The key for the entry to fetch
	 * @return mixed 	  The value stored in the cache for $key
	 *                    null if no valid data exists
	 */
	public function getConcrete($key)
	{
		// Turns the key into a filepath and checks that the path exists
		$file = $this->makePath($key);
		if (!file_exists($file)) {
			return false;
		}

		//Grabs the JSON object from the key file and decodes it
		$json_data = file_get_contents($file);
		$data = json_decode($json_data, true);

		//Makes sure that the data has not expired
		if($data["expire"] < $this->datetime->getTimestamp()) {
			$this->delete([$key]);
			return false;
		}

		//Returns the data
		return $data["val"];
	}

	/**
	 * Set an entry in the cache.
	 *
	 * @param String $key The key/index for the cache entry
	 * @param mixed $val The item to store in the cache
	 * @param int $ttl The time to live (or expiry) of the cached item. Not all caches honor the TTL.
	 */
	public function set($key, $val, $ttl)
	{
		// Creates an associative array representing the cache data
		$data = ["val" => $val, "expire" => $this->datetime->getTimestamp() + $ttl];
		// Uses the key to generate a filepath
		$file = $this->makePath($key);
		// Outputs a file with the cache entry data using JSON formatting
		file_put_contents($file, json_encode($data));
	}

	/**
	 * Delete an entry in the cache by key regaurdless of TTL
	 * @param  Array($string) $keys An array of keys/indexes of cache entries
	 */
	public function delete($keys)
	{
		// Finds the filepath of each key and calls the deleteFile function of the filepath
		foreach ($keys as $key) {
			$file = $this->makePath($key);
			$this->deleteFile($file);
		}
	}

	/**
	 * Takes a given filepath and deletes it if it exists
	 * @param  String $file Full file path
	 */
	private function deleteFile($file)
	{
		if (file_exists($file)) {
			unlink($file);
		}
	}

	/**
	 * Manually clean out entries older than their TTL
	 */
	public function clean()
	{
		// Finds all cache files
		$files = $this->getCacheFiles();

		// Checks each file's expiration date and deletes all that have expired
		foreach($files as $f) {
			if($f["expire"] < $this->datetime->getTimestamp()) {
				$this->deleteFile($f["file"]);
			}
		}
	}

	/**
	 * Clear the cache
	 *
	 */
	public function flush()
	{
		// Finds all cache files
		$files = $this->getCacheFiles();
		// Deletes all of the files
		foreach($files as $f) {
			$this->deleteFile($f["file"]);
		}
	}

	/**
	 * Generates a hash based on a give key
	 * @param  String $key The key to be hashed
	 * @return String      The hash to be returned
	 */
	private function hashKey($key)
	{
		return hash("sha256", $this->prefixKey($key));
	}

	/**
	 * Creates a file path in the form directory/key.extension
	 * @param  String $key the key of the cached element
	 * @return String      the directory of the cached element
	 */
	private function makePath($key)
	{
		return $this->directory . "/" . $this->hashKey($key) . "." . $this->extension;
	}

	/**
	 * Finds all files with the cache extension in the cache directory
	 * @return Array Returns an array of associative arrays containing cache data
	 */
	private function getCacheFiles()
	{
		//Finds all files in the temp directory
		$files = scandir($this->directory, 1);

		// Finds all files with the tempcache extension
		// formats their data and returns them as an array
		$file_data = [];
		foreach($files as $f) {
			$file_parts = pathinfo($f);

			if($file_parts['extension'] === $this->extension) {
				$file_data[] = $this->formatFileData($f);
			}
		}

		return $file_data;
	}

	/**
	 * Gathers data from a cache file
	 * @param  String $filename the path to the cache file
	 * @return Array 			Returns an array of associative arrays containing cache data
	 */
	private function formatFileData($f)
	{
		// Grabs data from the given file to a json object
		$f = $this->directory . "/" . $f;
		$json_data = file_get_contents($f);
		$data = json_decode($json_data, true);
		// adds the filepath to the json object
		$data["file"] = $f;
		// returns the json object
		return $data;
	}
}