<?php defined('ABSPATH') || die();

/**
 * @package Remove Unnecessary Scripts
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 *
 * @wordpress-plugin
 * Plugin Name: Remove Unnecessary Scripts
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Removes unnecessary scripts, styles, and metadata from WordPress output
 * Version: 2.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

/**
 * Remove unnecessary block library CSS on the frontend.
 */
add_action('wp_enqueue_scripts', function (): void {
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('global-styles');
	wp_dequeue_style('classic-theme-styles');
	wp_dequeue_style('wc-blocks-style');
	wp_dequeue_style('storefront-gutenberg-blocks');
	wp_dequeue_style('twentytwenty-block-style');
	wp_dequeue_style('core-block-supports-duotone');
});

/**
 * Remove emoji scripts, styles, and related filters.
 */
add_action('init', function (): void {
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
});

add_filter('emoji_svg_url', '__return_false');

/**
 * Clean up wp_head output: remove unnecessary meta tags and links.
 */
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_resource_hints', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');

/**
 * Remove Dashicons from frontend when the admin bar is not visible.
 */
add_action('wp_enqueue_scripts', function (): void {
	if (!is_admin_bar_showing()) {
		wp_dequeue_style('dashicons');
		wp_deregister_style('dashicons');
	}
});

/**
 * Remove jQuery Migrate on the frontend.
 */
add_action('wp_default_scripts', function (\WP_Scripts $scripts): void {
	if (is_admin()) {
		return;
	}

	$jquery = $scripts->registered['jquery'] ?? null;

	if ($jquery && !empty($jquery->deps)) {
		$jquery->deps = array_diff($jquery->deps, ['jquery-migrate']);
	}
});
