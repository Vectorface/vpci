<?php

namespace Vectorface\vpci\tests\helpers;

class MCUnloadedLoader implements ExtensionLoader
{
	public function is_loaded($ext) {
		if ($ext === "memcache") {
			return false;
		}
		return extension_loaded($ext);
	}
}