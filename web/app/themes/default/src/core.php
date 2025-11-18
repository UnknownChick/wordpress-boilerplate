<?php defined('ABSPATH') || die();

// Menus
register_nav_menus([
    'main' => 'Main Menu',
]);

// Replace empty href attributes with span tags
add_filter('wp_nav_menu', function ($nav_menu, $args) {
    return preg_replace('/<a(?![^>]*href)([^>]*)>(.*?)<\/a>/', '<span$1>$2</span>', $nav_menu);
}, 10, 2);

// Remove <p> tags from content and excerpt
add_filter('the_content', function ($content) {
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
    return $content;
}, 0);

// Limit excerpt length
add_filter('excerpt_length', function ($length) {
    return 40;
});

// Theme support
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
// add_theme_support('widgets');

// Disable the block editor
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);

// Show admin page create with carbon fields for all users
add_filter('carbon_fields_theme_options_container_admin_only_access', '__return_false');
