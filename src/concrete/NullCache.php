<?php

namespace Vectorface\vpci\concrete;

require_once(__DIR__."/Cache.php");

use Vectorface\vpci\concrete\Cache;
use Vectorface\vpci\config\Config;

/**
 * This does not actually cache anything.  It simply acts as though it has a cache
 * every time it is called upon.  This is intended to allow a developer to write
 * code in such a way that they use the cache interface, without actual caching.
 */
class NullCache extends Cache
{
    public function __construct($config = null)
    {
        parent::__construct($config);
    }

    public function getConcrete($key)
    {
        return false;
    }

    public function set($key, $value, $ttl) {}

    public function clean() {}

    public function flush() {}

    public function delete($keys) {}
}