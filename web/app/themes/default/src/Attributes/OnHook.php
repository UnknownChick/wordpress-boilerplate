<?php

namespace Theme\Attributes;

defined('ABSPATH') || die();

/**
 * Declare hook(s) on which register() will be called
 *
 * Usage :
 *   #[OnHook('init')]
 *   #[OnHook('acf/init')]
 *   #[OnHook('after_setup_theme', priority: 5)]
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class OnHook
{
	public function __construct(
		public readonly string $hook     = 'init',
		public readonly int    $priority = 10,
	) {}
}
