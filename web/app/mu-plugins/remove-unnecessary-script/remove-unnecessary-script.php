<?php defined('ABSPATH') || die();

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


/**
 * Removes unnecessary CSS block.
 */
add_action('wp_enqueue_scripts', function () {
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
    }
});


/**
 * Removes emojis from the website.
 */
add_action('init', function () {
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
});


/**
 * Removes the Dashicons script from the WordPress site.
 */
add_action('wp_enqueue_scripts', function () {
    if (current_user_can('administrator')) {
        return;
    }
    wp_deregister_style('dashicons');
});

// Remove WLW Manifest link (Windows Live Writer)
remove_action('wp_head', 'wlwmanifest_link');

// Remove RSD (Really Simple Discovery) link
remove_action('wp_head', 'rsd_link');
