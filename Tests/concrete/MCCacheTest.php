<?php

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../concrete/MCCache.php");
require_once(__DIR__."/../../config/config.php");

class MCCacheTest extends GenericCacheTest
{
	protected $cache;

	protected function setUp()
	{
		$this->cache = new MCCache();
	}
}