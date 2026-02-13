<?php

namespace Theme\Attributes;

defined('ABSPATH') || die();

/**
 * Condition to check before calling register()
 *
 * Usage :
 *   #[Condition('is_admin')]
 *   #[Condition('function_exists', ['register_extended_field_group'])]
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Condition
{
	public function __construct(
		public readonly string $fn,
		public readonly array $args = [],
	) {}

	public function evaluate(): bool
	{
		return (bool) call_user_func($this->fn, ...$this->args);
	}
}
