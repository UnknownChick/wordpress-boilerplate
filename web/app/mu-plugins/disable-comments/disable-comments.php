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
 * Version: 2.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

// Disable comments support and close comments by default
add_action('init', function(): void {
    // Remove support for comments and trackbacks from all post types
    foreach (get_post_types() as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }

    // Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);

    // Remove comments page in admin menu
    add_action('admin_menu', function(): void {
        remove_menu_page('edit-comments.php');
    });

    // Remove comments links from admin bar
    add_action('wp_before_admin_bar_render', function(): void {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });
});

// Disable comment-related admin pages
add_action('admin_init', function(): void {
    // Redirect any user trying to access comments page
    global $pagenow;

    if ($pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php') {
        wp_safe_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Remove comments-related fields from user profile
add_action('admin_init', function(): void {
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    remove_action('personal_options', 'comment_shortcuts');
});

// Remove comments column from posts and pages
add_filter('manage_posts_columns', function($columns) {
    unset($columns['comments']);
    return $columns;
});
add_filter('manage_pages_columns', function($columns) {
    unset($columns['comments']);
    return $columns;
});

// Remove comments from RSS/Atom feeds
add_filter('feed_links_show_comments_feed', '__return_false');

// Remove X-Pingback HTTP header
add_filter('wp_headers', function($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});

// Disable the REST API for comments
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/comments'])) {
        unset($endpoints['/wp/v2/comments']);
    }
    if (isset($endpoints['/wp/v2/comments/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

// Remove comment-related scripts and styles
add_action('wp_enqueue_scripts', function(): void {
    wp_dequeue_script('comment-reply');
}, 100);

// Remove comment count from admin bar
add_action('admin_bar_menu', function($wp_admin_bar): void {
    $wp_admin_bar->remove_node('comments');
}, 999);

// Remove comment-related widgets
add_action('widgets_init', function(): void {
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Comments');
}, 11);

// Remove comments from sitemap
add_filter('wp_sitemaps_add_provider', function($provider, $name) {
    if ('users' === $name || 'comments' === $name) {
        return false;
    }
    return $provider;
}, 10, 2);
