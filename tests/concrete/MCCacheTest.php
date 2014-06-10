<?php

namespace Vectorface\vpci\tests\concrete;

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../src/concrete/MCCache.php");
require_once(__DIR__."/../../src/config/config.php");

use Vectorface\vpci\concrete\MCCache;
use Vectorface\vpci\config\Config;

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