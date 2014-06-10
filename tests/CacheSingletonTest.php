<?php

namespace VF\vpci\tests;

require_once(__DIR__."/concrete/GenericCacheTest.php");
require_once(__DIR__."/../src/CacheSingleton.php");

use VF\vpci\CacheSingleton;
use VF\vpci\config\Config;
use VF\vpci\tests\concrete\GenericCacheTest;

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