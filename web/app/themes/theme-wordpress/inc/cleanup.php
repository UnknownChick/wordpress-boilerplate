<?php
defined('ABSPATH') || die();

/**
 * Cleans up the WordPress dashboard.
 */
function clean_dashboard() {
	remove_meta_box('dashboard_primary', 'dashboard', 'side');
	remove_meta_box('dashboard_secondary', 'dashboard', 'side');
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');

	remove_meta_box('dashboard_php_nag', 'dashboard', 'normal');
	remove_meta_box('dashboard_browser_nag', 'dashboard', 'normal');
	remove_meta_box('health_check_status', 'dashboard', 'normal');
	remove_meta_box('dashboard_activity', 'dashboard', 'normal');
	remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
	remove_meta_box('network_dashboard_right_now', 'dashboard', 'normal');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	remove_meta_box('dashboard_site_health', 'dashboard', 'normal');

	remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('wp_dashboard_setup', 'clean_dashboard', 20);


/**
 * Cleans the editor by removing unnecessary elements.
 */
function clean_editor() {
	remove_meta_box('postcustom', 'post', 'normal');
	remove_meta_box('slugdiv','post','normal');

	remove_meta_box('postcustom', 'page', 'normal');
	remove_meta_box('slugdiv','page','normal');

	remove_post_type_support('post', 'trackbacks');
	
	remove_post_type_support('page', 'trackbacks');
	remove_post_type_support('page', 'author');
}
add_action('admin_init', 'clean_editor');
?>