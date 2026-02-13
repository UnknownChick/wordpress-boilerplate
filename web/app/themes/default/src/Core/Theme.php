<?php

namespace Theme\Core;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;

class Theme
{
	public function __construct(private Container $container)
	{
	}

	/**
	 * Recursively discover and boot all Registerable classes in src/
	 */
	public function boot(): void
	{
		$classes = ClassDiscovery::find(
			get_template_directory() . '/src',
			'Theme'
		);

		foreach ($classes as $class) {
			/** @var Registerable $instance */
			$instance = $this->container->get($class);
			ClassDiscovery::boot($class, $instance);
		}
	}
}
