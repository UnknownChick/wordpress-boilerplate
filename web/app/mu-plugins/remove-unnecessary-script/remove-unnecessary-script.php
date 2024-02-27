<?php

/**
 * @package Disable Unnecessary Script
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 * 
 * @wordpress-plugin
 * Plugin Name: Remove Unnecessary Script
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Removes unnecessary scripts (CSS and JS) that are not needed for the design and proper functioning of the site
 * Version: 1.1
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

defined('ABSPATH') || die();


/**
 * Removes unnecessary CSS block.
 */
function remove_block_css() {
	if (!is_admin()) {
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('wp-block-library-theme');
	}
}
add_action('wp_enqueue_scripts', 'remove_block_css');


/**
 * Removes emojis from the website.
 */
function remove_emojis() {
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
}
add_action('init', 'remove_emojis');


/**
 * Removes the Dashicons script from the WordPress site.
 */
function remove_dashicons() {
	if (current_user_can('administrator')) {
		return;
	}
	wp_deregister_style('dashicons');
}
add_action('wp_enqueue_scripts', 'remove_dashicons');

// Remove WLW Manifest link (Windows Live Writer)
remove_action('wp_head', 'wlwmanifest_link');

// Remove RSD (Really Simple Discovery) link
remove_action('wp_head', 'rsd_link');
