<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;

class ErrorController extends AbstractController
{
	public function view(): string
	{
		return 'pages/404.twig';
	}

	protected function data(): array
	{
		return [
			'title' => __('Page non trouvée', 'default'),
			'message' => __('La page que vous recherchez n\'existe pas ou a été déplacée.', 'default'),
		];
	}
}
