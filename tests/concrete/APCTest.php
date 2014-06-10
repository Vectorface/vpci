<?php

namespace Vectorface\vpci\tests\concrete;

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../src/concrete/APCCache.php");
require_once(__DIR__."/../../src/config/config.php");

use Vectorface\vpci\concrete\APCCache;
use Vectorface\vpci\config\Config;
use Vectorface\vpci\tests\concrete\GenericCacheTest;

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