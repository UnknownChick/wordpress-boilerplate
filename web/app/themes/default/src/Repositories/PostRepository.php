<?php

declare(strict_types=1);

namespace Theme\Repositories;

defined('ABSPATH') || die();

use Timber\PostQuery;
use Timber\Timber;
use Theme\Services\Cache;

/**
 * Centralizes post queries so controllers stay thin.
 *
 * The current request's main query (archives, search...) is never
 * cached here since it depends on pagination/filters, but "secondary"
 * queries (related posts, homepage widgets, sidebars...) are — this is
 * where you save real database round-trips on a busy site.
 */
class PostRepository
{
	public function __construct(private Cache $cache)
	{
	}

	/**
	 * Posts for the current main query (archive, search, ...).
	 */
	public function fromMainQuery(): PostQuery
	{
		return Timber::get_posts();
	}

	/**
	 * Cached "latest N posts of a type" query, useful for homepage
	 * sections, related posts, sidebars, etc.
	 *
	 * @param string $postType Post type to query.
	 * @param int $count Number of posts to return.
	 * @param array<string, mixed> $extraArgs Extra WP_Query args merged in.
	 * @return PostQuery Cached query result.
	 */
	public function latest(string $postType = 'post', int $count = 5, array $extraArgs = []): PostQuery
	{
		$cacheKey = 'posts_latest_' . md5($postType . $count . serialize($extraArgs));

		return $this->cache->remember($cacheKey, 15 * MINUTE_IN_SECONDS, function () use ($postType, $count, $extraArgs) {
			return Timber::get_posts(array_merge([
				'post_type' => $postType,
				'posts_per_page' => $count,
				'post_status' => 'publish',
				'no_found_rows' => true,
			], $extraArgs));
		});
	}
}
