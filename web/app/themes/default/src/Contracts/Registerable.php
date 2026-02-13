<?php

namespace Theme\Contracts;

defined('ABSPATH') || die();

interface Registerable
{
	public function register(): void;
}
