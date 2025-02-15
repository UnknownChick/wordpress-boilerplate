<?php defined('ABSPATH') || die();

/**
 * Cleans up the WordPress dashboard.
 *
 * @return void
 */
add_action('wp_dashboard_setup', function (): void {
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
}, 20);


/**
 * Cleans the editor by removing unnecessary elements.
 *
 * @return void
 */
add_action('admin_init', function (): void {
    remove_meta_box('postcustom', 'post', 'normal');
    remove_meta_box('slugdiv', 'post', 'normal');

    remove_meta_box('postcustom', 'page', 'normal');
    remove_meta_box('slugdiv', 'page', 'normal');

    remove_post_type_support('post', 'trackbacks');

    remove_post_type_support('page', 'trackbacks');
    remove_post_type_support('page', 'author');
}, 20);


/**
 * Cleans up the help tabs.
 *
 * @return void
 */
add_action('admin_head', function (): void {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
});


/**
 * Cleans up the admin bar.
 *
 * @return void
 */
add_action('admin_bar_menu', function ($wp_admin_bar): void {
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('search');
}, 999);

add_filter('mce_buttons', function ($buttons) {
    $remove_buttons = array(
        'strikethrough',
        'hr',
        'unlink',
        'wp_more',
        'spellchecker',
        'dfw',
        'wp_adv',
    );
    foreach ($buttons as $button_key => $button_value) {
        if (in_array( $button_value, $remove_buttons)) {
            unset($buttons[$button_key]);
        }
    }
    return $buttons;
});

add_filter('mce_buttons_2', function ($buttons) {
    $remove_buttons = array(
        'formatselect',
        'forecolor',
        'pastetext',
        'removeformat',
        'charmap',
        'outdent',
        'indent',
        'wp_help',
    );
    foreach ($buttons as $button_key => $button_value) {
        if (in_array($button_value, $remove_buttons)) {
            unset($buttons[$button_key]);
        }
    }
    return $buttons;
});
