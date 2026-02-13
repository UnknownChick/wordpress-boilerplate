<?php

namespace Theme\Attributes;

defined('ABSPATH') || die();

/**
 * Condition to check before calling register()
 *
 * Usage :
 *   #[Condition('is_admin')]
 *   #[Condition('is_user_logged_in')]
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Condition
{
    public function __construct(
        public readonly string $fn,
    ) {}
}
