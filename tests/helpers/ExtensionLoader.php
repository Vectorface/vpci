<?php

namespace VF\CacheInterface\tests\helpers;

interface ExtensionLoader
{
	public function is_loaded($ext);
}

class APCUnloadedLoader implements ExtensionLoader
{
	public function is_loaded($ext) {
		if ($ext === "apc") {
			return false;
		}
		return extension_loaded($ext);
	}
}

class MCUnloadedLoader implements ExtensionLoader
{
	public function is_loaded($ext) {
		if ($ext === "memcache") {
			return false;
		}
		return extension_loaded($ext);
	}
}

class APCAndMCUnloadedLoader implements ExtensionLoader
{
	public function is_loaded($ext) {
		if ($ext === "apc" || $ext === "memcache") {
			return false;
		}
		return extension_loaded($ext);
	}
}