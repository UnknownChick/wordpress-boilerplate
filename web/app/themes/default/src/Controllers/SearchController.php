<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Timber\Timber;
use Theme\Core\AbstractController;

class SearchController extends AbstractController
{
	public function view(): string
	{
		return 'base/search.twig';
	}

	protected function data(): array
	{
		return [
			'query' => get_search_query(),
			'posts' => Timber::get_posts(),
		];
	}
}
