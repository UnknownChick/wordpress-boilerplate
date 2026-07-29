<?php

declare(strict_types=1);

namespace Theme\Services;

defined('ABSPATH') || die();

/**
 * Thin wrapper around the WordPress transients API.
 *
 * Used by repositories to avoid repeating expensive lookups (ACF options,
 * WP_Query, ...) on every request. Transients automatically use a
 * persistent object cache (Redis/Memcached) when one is configured, so
 * this stays a good default even on larger sites.
 */
class Cache
{
	private const PREFIX = 'theme_';

	/**
	 * Return the cached value for $key, or compute, cache and return it.
	 *
	 * @param int $ttl Time to live in seconds. 0 = forever.
	 */
	public function remember(string $key, int $ttl, callable $callback): mixed
	{
		$cacheKey = self::PREFIX . $key;
		$cached = get_transient($cacheKey);

		if ($cached !== false) {
			return $cached;
		}

		$value = $callback();

		set_transient($cacheKey, $value, $ttl);

		return $value;
	}

	public function forget(string $key): void
	{
		delete_transient(self::PREFIX . $key);
	}
}
