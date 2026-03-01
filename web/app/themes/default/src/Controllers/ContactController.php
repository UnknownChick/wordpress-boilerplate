<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;

class ContactController extends AbstractController
{
	public function view(): string
	{
		return 'pages/contact.twig';
	}

	protected function data(): array
	{
		return [
			// Add contact-specific data here (form handling, etc.)
		];
	}
}
