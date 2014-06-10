<?php

namespace Vectorface\vpci\tests;

require_once(__DIR__."/concrete/GenericCacheTest.php");
require_once(__DIR__."/../src/CacheSingleton.php");

use Vectorface\vpci\CacheSingleton;
use Vectorface\vpci\config\Config;
use Vectorface\vpci\tests\concrete\GenericCacheTest;

class CacheSingletonTest extends GenericCacheTest
{
	protected $cache;
	protected $config;

	protected function setUp()
	{
		$this->config = new Config([], false);
		$this->cache = CacheSingleton::getCache($this->config);
	}

	public function checkSingularity()
	{
		$key = "singular";
		$data = "this is a singularity";
		$ttl = 5000;

		$this->cache->flush();
		$this->cache->set($key, $data, $ttl);
		
		$second_cache = CacheSingleton::getCache();

		$this->assertEquals($second_cache->get($key), $this->cache->get($key));
	}
}