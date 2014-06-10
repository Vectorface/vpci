<?php

namespace VF\CacheInterface;

require_once(__DIR__."/concrete/APCCache.php");
require_once(__DIR__."/concrete/MCCache.php");
require_once(__DIR__."/concrete/TempFileCache.php");
require_once(__DIR__."/config/config.php");

use VF\CacheInterface\concrete\TempFileCache;
use VF\CacheInterface\concrete\APCCache;
use VF\CacheInterface\concrete\MCCache;

class CacheSingleton
{
	private static $instance;

	/**
	 * Grabs an instance of the CacheSingleton and returns its cache instance
	 * @return Cache 		An object that implements the Cache interface
	 */
	public static function getCache($config = null)
	{
		$config = isset($config) ? $config : new Config();
		if(!isset($instance)) {
			self::$instance = self::getConcreteCache(null, $config);
		}
		return self::$instance;
	}

	/**
	 * Returns the highest quality cache service available
	 * @param  ExtensionLoader $extension_loader Used for dependency injection
	 *                                           to test cache types
	 * @return Cache                   An object that implements the Cache interface
	 */
	public static function getConcreteCache($extension_loader = null, $config = null)
	{
		if (isset($extension_loader)) {
			if ($extension_loader->is_loaded('apc')) {
				return new APCCache($config);
			}
			
			if ($extension_loader->is_loaded('memcache')) {
				return new MCCache($config);
			}

			return new TempFileCache($config);
		}

		if (extension_loaded('apc')) {
			return new APCCache($config);
		}
		
		if (extension_loaded('memcache')) {
			return new MCCache($config);
		}

		return new TempFileCache($config);
	}
}