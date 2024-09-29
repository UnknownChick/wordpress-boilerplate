<?php defined('ABSPATH') || die();

/**
 * @package Disable comments
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 * 
 * @wordpress-plugin
 * Plugin Name: Disable comments
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Disable comments
 * Version: 1.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */


/**
 * Initializes the disable_comments functionality.
 */
add_action('init', function () {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');

    update_option('default_comment_status', 'closed');
});


/**
 * Disables comments in the admin area.
 */
add_action('admin_init', function () {
    remove_meta_box('commentstatusdiv', 'post', 'normal');
    remove_meta_box('commentsdiv', 'post', 'normal');

    remove_meta_box('commentstatusdiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
});


/**
 * Removes the comments menu item from the admin menu.
 */
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
});
