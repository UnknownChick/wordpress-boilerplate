<?php
/**
 * @package Remove Version
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 * 
 * @wordpress-plugin
 * Plugin Name: Remove Version
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Removes WordPress version, CSS, and JS version strings
 * Version: 1.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexxandre
 * License: MIT License
 */

defined('ABSPATH') || die();

/**
 * Removes the WordPress version number from the website.
 *
 * @return void
 */
function wp_version_remove() {
	return '';
}
add_filter('the_generator', 'wp_version_remove');

/**
 * Removes the WordPress version strings from the given source.
 *
 * @param string $src The source code or URL.
 * @return string The modified source code or URL without the WordPress version strings.
 */
function remove_css_js_version($src) {
	if(strpos($src, '?ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
add_filter('style_loader_src', 'remove_css_js_version', 10, 2);
add_filter('script_loader_src', 'remove_css_js_version', 10, 2);

remove_action('wp_head', 'wp_generator');
?>