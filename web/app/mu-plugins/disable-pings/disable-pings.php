<?php defined('ABSPATH') || die();
/**
 * @package Disable Pings
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 *
 * @wordpress-plugin
 * Plugin Name: Disable Pings
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Disable self-pings, pingbacks, and trackbacks
 * Version: 1.1
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

/**
 * Disable self-pings by removing internal URLs from the links array.
 *
 * @param array $links The array of links to ping.
 * @return void
 */
add_action('pre_ping', function (array &$links): void {
	$home = get_option('home');
	$links = array_filter($links, fn(string $link): bool => !str_starts_with($link, $home));
});

/**
 * Disable XML-RPC pingback method to prevent abuse.
 *
 * @param array $methods The available XML-RPC methods.
 * @return array The filtered methods.
 */
add_filter('xmlrpc_methods', function (array $methods): array {
	unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks']);
	return $methods;
});

/**
 * Disable pingback and trackback functionality entirely.
 */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('pings_open', '__return_false', 20, 2);
