<?php

namespace Vectorface\vpci\tests\helpers;

class APCAndMCUnloadedLoader implements ExtensionLoader
{
	public function is_loaded($ext) {
		if ($ext === "apc" || $ext === "memcache") {
			return false;
		}
		return extension_loaded($ext);
	}
}