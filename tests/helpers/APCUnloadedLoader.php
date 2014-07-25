<?php

namespace Vectorface\vpci\tests\helpers;

class APCUnloadedLoader implements ExtensionLoader
{
	public function is_loaded($ext) {
		if ($ext === "apc") {
			return false;
		}
		return extension_loaded($ext);
	}
}