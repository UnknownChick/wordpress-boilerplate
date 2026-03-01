<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;

class FrontPageController extends AbstractController
{
	public function view(): string
	{
		return 'pages/home.twig';
	}

	protected function data(): array
	{
		return [
			// Add homepage-specific data here
		];
	}
}
