<?php

namespace VF\CacheInterface\tests\concrete;

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../src/concrete/MCCache.php");
require_once(__DIR__."/../../src/config/config.php");

use VF\CacheInterface\concrete\MCCache;
use VF\CacheInterface\config\Config;

class MCCacheTest extends GenericCacheTest
{
	protected $cache;
	protected $config;

	protected function setUp()
	{
		$this->config = new Config([], false);
		$this->cache = new MCCache($this->config);
	}
}