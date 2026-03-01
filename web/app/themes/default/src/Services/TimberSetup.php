<?php

declare(strict_types=1);

namespace Theme\Services;

defined('ABSPATH') || die();

use Timber\Timber;
use Theme\Attributes\OnHook;
use Theme\Contracts\Registerable;
use Theme\Models\Page;
use Theme\Models\Post;

#[OnHook('after_setup_theme')]
class TimberSetup implements Registerable
{
	public function register(): void
	{
		Timber::init();

		$this->registerClassMap();
	}

	/**
	 * Map WordPress post types to custom Timber models.
	 *
	 * This ensures that Timber::get_post() / Timber::get_posts()
	 * return instances of our custom Model classes with their
	 * additional business methods.
	 */
	private function registerClassMap(): void
	{
		add_filter('timber/post/classmap', function (array $classmap): array {
			$classmap['post'] = Post::class;
			$classmap['page'] = Page::class;

			return $classmap;
		});
	}
}
