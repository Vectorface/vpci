<?php

namespace VF\vpci\tests\concrete;

require_once(__DIR__."/helpers/DateTimeHelper.php");
require_once(__DIR__."/GenericCacheTest.php");
require_once(__DIR__."/../../src/concrete/TempFileCache.php");
require_once(__DIR__."/../../src/config/config.php");

use VF\vpci\concrete\TempFileCache;
use VF\vpci\config\Config;
use VF\vpci\tests\concrete\helpers\DateTimeHelper;

class TempFileCacheTest extends GenericCacheTest
{
	protected $cache;
	protected $timehelper;
	protected $config;

	protected function setUp()
	{
		$this->timehelper = new DateTimeHelper();
		$this->config = new Config([], false);
		$this->cache = new TempFileCache($this->config, $this->timehelper);
	}

	protected function tearDown()
	{
		$this->cache->flush();
	}

	/**
     * @dataProvider cacheDataProvider
     */
	public function testSet($key, $data, $ttl)
	{
		$this->cache->set($key, $data, $ttl);
		$this->assertTrue(file_exists($this->filename($key)));
	}

	/**
     * @dataProvider cacheDataProvider
     */
	public function testGetWithTimeout($key, $data, $ttl)
	{
		$this->cache->set($key, $data, $ttl);
		$this->timehelper->change_time($this->timehelper->getTimestamp() + $ttl + 1000);

		$actual = $this->cache->get($key);
		$this->assertFalse($actual);
		$this->assertFalse(file_exists($this->filename($key)));
	}

	/**
     * @dataProvider cacheDataProvider
     */
	public function testDelete($key, $data, $ttl)
	{
		$this->cache->set($key, $data, $ttl);
		$this->cache->delete([$key]);
		$this->assertFalse(file_exists($this->filename($key)));

		$keys = [$key];
		for($i = 0; $i<4; $i++) {
			$keys[] = $key . $i;
		}

		$this->cache->delete($keys);

		foreach($keys as $k) {
			$this->assertFalse(file_exists($this->filename($k)));
		}
	}

	/**
     * @dataProvider cacheDataProvider
     */
    public function testClean($key, $data, $ttl)
    {
		$this->cache->set($key, $data, $ttl);
		$this->cache->set($key."2", $data, $ttl+50000);
		$this->timehelper->change_time($this->timehelper->getTimestamp() + $ttl + 1000);

		$this->cache->clean();


		$this->assertFalse(file_exists($this->filename($key)));
		$this->assertTrue(file_exists($this->filename($key."2")));
    }

    /**
     * @dataProvider cacheDataProvider
     */
    public function testFlush($key, $data, $ttl)
    {
		$this->cache->set($key, $data, $ttl);
		$this->cache->set($key."2", $data, $ttl+50000);

		$this->cache->flush();

		$this->assertFalse(file_exists($this->filename($key)));
		$this->assertFalse(file_exists($this->filename($key."2")));

    }

	public function cacheDataProvider()
	{
		return [
			[
				"testKey1",
				"testData1", 
				5*60
			],
			[
				"AnotherKey",
				"Here is some more data that I would like to test with",
				3
			],
			[
				"IntData",
				17,
				3
			],
		];
	}

	/**
	 * This is a helper function to turn a cache key into
	 * its correlating file name
	 * @param  String $key The cache item key
	 * @return String      The file path for the cache item
	 */
	private function filename($key)
	{
		$file = sys_get_temp_dir() .
			"/" .
			Config::get('tempFileCacheDir') . 
			"/" .
			hash("sha256", $key) . 
			"." . 
			Config::get('tempFileExt');
		return $file;
	}
}