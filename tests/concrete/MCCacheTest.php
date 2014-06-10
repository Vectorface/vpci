<?php

namespace VF\VCPI\tests\concrete;

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../src/concrete/MCCache.php");
require_once(__DIR__."/../../src/config/config.php");

use VF\VCPI\concrete\MCCache;
use VF\VCPI\config\Config;

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