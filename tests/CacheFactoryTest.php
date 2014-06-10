<?php

namespace VF\CacheInterface\tests;

require_once(__DIR__."/../src/CacheSingleton.php");
require_once(__DIR__."/helpers/ExtensionLoader.php");

use VF\CacheInterface\CacheSingleton;
use VF\CacheInterface\tests\helpers as helpers;

Class CacheFactoryTest extends \PHPUnit_Framework_TestCase
{

	public function testGetAPC()
	{
		$cache = CacheSingleton::getConcreteCache();
		$this->assertEquals("VF\CacheInterface\concrete\APCCache", get_class($cache));
	}

	public function testGetMCCache()
	{
		$cache = CacheSingleton::getConcreteCache(new helpers\APCUnloadedLoader());
		$this->assertEquals("VF\CacheInterface\concrete\MCCache", get_class($cache));
	}

	public function testGetTempFileCache()
	{
		$cache = CacheSingleton::getConcreteCache(new helpers\APCAndMCUnloadedLoader());
		$this->assertEquals("VF\CacheInterface\concrete\TempFileCache", get_class($cache));
	}
}