<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;

class SinglePostController extends AbstractController
{
	public function view(): string
	{
		return 'singles/post.twig';
	}

	protected function data(): array
	{
		return [
			// Add post-specific data here (related posts, categories, etc.)
		];
	}
}
