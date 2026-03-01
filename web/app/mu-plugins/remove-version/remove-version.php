<?php defined('ABSPATH') || die();
/**
 * @package Remove Version
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 *
 * @wordpress-plugin
 * Plugin Name: Remove Version
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Removes WordPress version number and version query strings from CSS/JS assets
 * Version: 1.1
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

// Remove the WordPress version meta tag
add_filter('the_generator', '__return_empty_string');
remove_action('wp_head', 'wp_generator');

// Remove version query strings from CSS and JS assets
add_filter('style_loader_src', function (string $src): string {
	return str_contains($src, '?ver=') ? remove_query_arg('ver', $src) : $src;
});

add_filter('script_loader_src', function (string $src): string {
	return str_contains($src, '?ver=') ? remove_query_arg('ver', $src) : $src;
});
