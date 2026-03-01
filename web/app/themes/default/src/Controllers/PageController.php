<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;

class PageController extends AbstractController
{
	public function view(): string
	{
		return 'base/page.twig';
	}

	protected function data(): array
	{
		return [
			// Add page-specific data here
		];
	}
}
