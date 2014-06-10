<?php

namespace Vectorface\vpci\tests;

require_once(__DIR__."/../src/CacheSingleton.php");
require_once(__DIR__."/helpers/ExtensionLoader.php");

use Vectorface\vpci\CacheSingleton;
use Vectorface\vpci\tests\helpers as helpers;

Class CacheFactoryTest extends \PHPUnit_Framework_TestCase
{

	public function testGetAPC()
	{
		$cache = CacheSingleton::getConcreteCache();
		$this->assertEquals("Vectorface\\vpci\\concrete\\APCCache", get_class($cache));
	}

	public function testGetMCCache()
	{
		$cache = CacheSingleton::getConcreteCache(new helpers\APCUnloadedLoader());
		$this->assertEquals("Vectorface\\vpci\\concrete\\MCCache", get_class($cache));
	}

	public function testGetTempFileCache()
	{
		$cache = CacheSingleton::getConcreteCache(new helpers\APCAndMCUnloadedLoader());
		$this->assertEquals("Vectorface\\vpci\\concrete\\TempFileCache", get_class($cache));
	}
}