<?php

namespace VF\VCPI\tests\concrete;

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../src/concrete/APCCache.php");
require_once(__DIR__."/../../src/config/config.php");

use VF\VCPI\concrete\APCCache;
use VF\VCPI\config\Config;
use VF\VCPI\tests\concrete\GenericCacheTest;

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