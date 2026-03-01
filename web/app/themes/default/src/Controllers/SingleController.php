<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;

class SingleController extends AbstractController
{
	public function view(): string
	{
		return 'base/single.twig';
	}

	protected function data(): array
	{
		return [
			// Add single-specific data here
		];
	}
}
