<?php

require_once(__DIR__."/../src/CacheSingleton.php");
require_once(__DIR__."/helpers/ExtensionLoader.php");

Class CacheFactoryTest extends PHPUnit_Framework_TestCase
{

	public function testGetAPC()
	{
		$cache = CacheSingleton::getConcreteCache();
		$this->assertEquals("APCCache", get_class($cache));
	}

	public function testGetMCCache()
	{
		$cache = CacheSingleton::getConcreteCache(new APCUnloadedLoader());
		$this->assertEquals("MCCache", get_class($cache));
	}

	public function testGetTempFileCache()
	{
		$cache = CacheSingleton::getConcreteCache(new APCAndMCUnloadedLoader());
		$this->assertEquals("TempFileCache", get_class($cache));
	}
}