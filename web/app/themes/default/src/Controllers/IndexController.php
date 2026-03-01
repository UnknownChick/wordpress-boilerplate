<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Theme\Core\AbstractController;

class IndexController extends AbstractController
{
	public function view(): string
	{
		return 'base/index.twig';
	}
}
