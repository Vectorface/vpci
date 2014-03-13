<?php

require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../concrete/APCCache.php");
require_once(__DIR__."/../../config/config.php");

class APCCacheTest extends GenericCacheTest
{
	protected $cache;

	protected function setUp()
	{
		$this->cache = new APCCache();
	}

	
}