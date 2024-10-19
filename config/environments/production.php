<?php
/**
 * Configuration overrides for WP_ENV === 'production'
 */

use Roots\WPConfig\Config;

Config::define('DISALLOW_INDEXING', false);
Config::define('FORCE_SSL_ADMIN', true);
