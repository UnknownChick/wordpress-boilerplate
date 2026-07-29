<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;
use Theme\Repositories\PostRepository;

class FrontPageController extends AbstractController
{
	public function __construct(private PostRepository $posts)
	{
	}

	public function view(): string
	{
		return 'pages/home.twig';
	}

	protected function data(): array
	{
		return [
			// Cached for 15 minutes (see PostRepository::latest()): safe to
			// use for "recent posts" style widgets that don't need to be
			// second-by-second accurate.
			'latest_posts' => $this->posts->latest('post', 3),
		];
	}
}
