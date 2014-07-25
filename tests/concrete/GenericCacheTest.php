<?php

namespace Vectorface\vpci\tests\concrete;

abstract class GenericCacheTest extends \PHPUnit_Framework_TestCase
{

    private static $called;
    /**
     * @dataProvider cacheDataProvider
     */
    public function testGet($key, $data, $ttl)
    {
        $this->cache->set($key, $data, $ttl);
        $actual = $this->cache->get($key);

        $this->assertEquals($data, $actual);

        $actual = $this->cache->get($key . ".fake");

        $this->assertFalse($actual);
    }

    /**
     * @dataProvider cacheDataProvider
     */
    public function testGetWithWriteThrough($key, $data, $ttl)
    {
        self::setCall($data);

        $actual = $this->cache->get($key, ["function"=>"Vectorface\\vpci\\tests\\concrete\\GenericCacheTest::getCall"], 3600);
        $this->assertEquals($data, $actual);

        $actual = $this->cache->get($key);
        $this->assertEquals($data, $actual);
    }

    /**
     * @dataProvider cacheDataProvider
     */
    public function testDelete($key, $data, $ttl)
    {
        $this->cache->set($key, $data, $ttl);
        $this->cache->delete([$key]);
        $actual = $this->cache->get($key);
        $this->assertFalse($actual);

        $keys = [$key];
        for ($i = 0; $i<4; $i++) {
            $keys[] = $key . $i;
        }

        $this->cache->delete($keys);

        foreach ($keys as $k) {
            $actual = $this->cache->get($key);
            $this->assertFalse($actual);
        }
    }

    /**
     * @dataProvider cacheDataProvider
     */
    public function testFlush($key, $data, $ttl)
    {
        $this->cache->set($key, $data, $ttl);
        $this->cache->set($key."2", $data, $ttl+50000);

        $this->cache->flush();

        $actual = $this->cache->get($key);
        $this->assertFalse($actual);

        $actual = $this->cache->get($key."2");
        $this->assertFalse($actual);
    }

    /**
     * @dataProvider cacheDataProvider
     */
    public function testPrefix($key, $data, $ttl)
    {
        $this->cache->set("test".$key, $data, $ttl);
        
        $this->config->setConfigValue("cachePrefix", "test");

        $actual = $this->cache->get($key);
        
        $this->assertEquals($data, $actual);

        $this->cache->set("other".$key, $data, $ttl);
        
        $this->config->setConfigValue("cachePrefix", "");

        $actual = $this->cache->get("testother".$key);

        $this->assertEquals($data, $actual);
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

    public static function getCall()
    {
        return self::$called;
    }

    private static function setCall($called)
    {
        self::$called = $called;
    }
}
