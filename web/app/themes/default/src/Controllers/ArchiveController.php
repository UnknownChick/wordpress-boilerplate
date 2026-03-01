<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Timber\Timber;
use Theme\Core\AbstractController;

class ArchiveController extends AbstractController
{
	public function view(): string
	{
		return 'base/archive.twig';
	}

	protected function data(): array
	{
		return [
			'posts' => Timber::get_posts(),
		];
	}
}
