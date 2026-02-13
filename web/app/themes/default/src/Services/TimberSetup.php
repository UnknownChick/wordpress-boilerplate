<?php

namespace Theme\Services;

defined('ABSPATH') || die();

use Timber\Timber;
use Theme\Attributes\OnHook;
use Theme\Contracts\Registerable;

#[OnHook('after_setup_theme')]
class TimberSetup implements Registerable
{
	public function register(): void
	{
		Timber::init();
	}
}
