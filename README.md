# VPCI (Vectorface PHP Caching Interface)
---

VPCI is a lightweight, straight forward cache interface that employs a strategy pattern to allow users to use multiple types of cache with a single unified interface.  It has optional built in write through caching to handle information retrieval with a simple, single function call.

Using VPCI allows developers to manage information retrieval and caching without having to worry about imlpementation.

---

## Contents

1. [Currently Supported Cache Types](#currently-supported-cache-types)
1. [Configurability](#configurability)
1. [Use](#use)
	2. [Get](#get)
	2. [Set](#set)
	2. [Clean](#clean)
	2. [Flush](#flush)

---

## Currently Supported Cache Types:

* APC User Cache (for both PHP 5.4 and PHP 5.5)
* Memcache
* TempFileCache (A caching system that uses temporary files)

---

## Configurability

VPCI comes with a easy to set-up configuration system.  Its default behaviour is to select the "best" (ordered as in the [Currently Supported](#currently-supported-cache-types) section) caching system.  The retieval of the cache is done with a singleton, meaning that you will not loose your reference to your cache once it has been created.  Instead, simply call CacheSingleton::GetCache() whenever the reference is needed.

---

## Use

VPCI is desinged to run entirely on four commands: `get()`, `set()`, `clean()` and `flush()`.

---

### Get

The get function takes a single `$key` parameter.  This is the key of the item in the cache.  It returns the value stored with the given key.

	$key = "cache_item";
	$cached_data = $cache->get(key);

---

### Set

The set function is used to store data in the cache.  It takes 3 parameters:

1. `$key` the key used to retrieve the cached item later
1. `$data` the data to be stored in the cache
1. `$ttl` the time to live of the cache item (the amount of time that the item should remain valid)

	$key = "cache_item";
	$data = "This will be stored in the cache"
	$ttl = 3600 //This cache item will expire in one hour
	$cache->set($key, $data, $ttl);

---

### Clean

The clean function is used to clear any expired items out of the cache.  Note that for many caching implementations this might not be nessarry.  Some cache implementations such as APC and Memcache clean themselves automatically.

	$cache->clean();

---

### Flush

The flush function deletes all items in the cache.

	$cache->flush();