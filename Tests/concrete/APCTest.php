<?php

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../concrete/APCCache.php");
require_once(__DIR__."/../../config/config.php");

class APCCacheTest extends GenericCacheTest
{
	protected $cache;
	protected $config;

	protected function setUp()
	{
		$this->config = new Config([], false);
		$this->cache = new APCCache($this->config);
	}

	protected function tearDown()
	{
		$this->config->setConfigValue("cachePrefix", "");
	}

	
}