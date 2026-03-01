<?php

declare(strict_types=1);

namespace Theme\Contracts;

defined('ABSPATH') || die();

interface Registerable
{
	public function register(): void;
}
