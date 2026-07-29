<?php

declare(strict_types=1);

namespace Theme\Repositories;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;
use Theme\DTO\SiteOptionsDTO;
use Theme\Services\Cache;

/**
 * Reads the "Options du site" ACF options page (see
 * Theme\Fields\AdminPageOptionsFields) and exposes it as a typed, cached
 * SiteOptionsDTO instead of scattering untyped get_field() calls
 * throughout controllers and views.
 */
class SiteOptionsRepository implements Registerable
{
	private const CACHE_KEY = 'site_options';
	private const CACHE_TTL = HOUR_IN_SECONDS;

	public function __construct(private Cache $cache)
	{
	}

	/**
	 * Bust the cache whenever the options page is saved, so changes are
	 * reflected immediately instead of waiting for the TTL to expire.
	 */
	public function register(): void
	{
		add_action('acf/save_post', function ($postId): void {
			if ($postId === 'options') {
				$this->cache->forget(self::CACHE_KEY);
			}
		}, 20);
	}

	public function get(): SiteOptionsDTO
	{
		return $this->cache->remember(self::CACHE_KEY, self::CACHE_TTL, function (): SiteOptionsDTO {
			if (!function_exists('get_field')) {
				return new SiteOptionsDTO();
			}

			return SiteOptionsDTO::fromFields([
				'contact_name' => get_field('contact_name', 'options'),
				'phone' => get_field('phone', 'options'),
				'email' => get_field('email', 'options'),
				'address' => get_field('address', 'options'),
				'instagram' => get_field('instagram', 'options'),
				'facebook' => get_field('facebook', 'options'),
				'linkedin' => get_field('linkedin', 'options'),
				'email_receivers' => get_field('email_receivers', 'options') ?: [],
			]);
		});
	}
}
