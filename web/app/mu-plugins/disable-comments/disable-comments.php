<?php defined('ABSPATH') || die();

/**
 * @package Disable comments completely
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 *
 * @wordpress-plugin
 * Plugin Name: Disable comments completely
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Completely disable and remove all comment functionality from WordPress
 * Version: 2.1
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

use WP_Admin_Bar;

if (!class_exists('WP_Admin_Bar')) return;

// Remove support for comments and trackbacks from all post types
add_action('init', function (): void {
	foreach (get_post_types() as $post_type) {
		remove_post_type_support($post_type, 'comments');
		remove_post_type_support($post_type, 'trackbacks');
	}
});

// Close comments and pings on the front-end, hide existing comments
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments from RSS/Atom feeds
add_filter('feed_links_show_comments_feed', '__return_false');

// Remove X-Pingback HTTP header
add_filter('wp_headers', function (array $headers): array {
	unset($headers['X-Pingback']);
	return $headers;
});

// Remove comments page in admin menu
add_action('admin_menu', function (): void {
	remove_menu_page('edit-comments.php');
	remove_submenu_page('options-general.php', 'options-discussion.php');
});

// Disable comment-related admin pages and metaboxes
add_action('admin_init', function (): void {
	global $pagenow;

	if ($pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php') {
		wp_safe_redirect(admin_url());
		exit;
	}

	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

	foreach (get_post_types() as $post_type) {
		remove_meta_box('commentsdiv', $post_type, 'normal');
		remove_meta_box('commentstatusdiv', $post_type, 'normal');
	}
});

// Remove comments column from posts and pages
add_filter('manage_posts_columns', function (array $columns): array {
	unset($columns['comments']);
	return $columns;
});
add_filter('manage_pages_columns', function (array $columns): array {
	unset($columns['comments']);
	return $columns;
});

// Disable the REST API for comments
add_filter('rest_endpoints', function (array $endpoints): array {
	unset($endpoints['/wp/v2/comments'], $endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
	return $endpoints;
});

// Remove comment-related scripts
add_action('wp_enqueue_scripts', function (): void {
	wp_dequeue_script('comment-reply');
}, 100);

// Remove comment nodes from admin bar
add_action('admin_bar_menu', function (WP_Admin_Bar $wp_admin_bar): void {
	$wp_admin_bar->remove_node('comments');
}, 999);

// Remove comment-related widgets
add_action('widgets_init', function (): void {
	unregister_widget('WP_Widget_Recent_Comments');
}, 11);

// Remove comments from sitemap
add_filter('wp_sitemaps_add_provider', function ($provider, string $name) {
	return $name === 'comments' ? false : $provider;
}, 10, 2);
