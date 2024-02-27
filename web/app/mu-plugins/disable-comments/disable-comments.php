<?php

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

defined('ABSPATH') || die();


/**
 * Initializes the disable_comments functionality.
 */
function disable_comments_init()
{
	remove_post_type_support('post', 'comments');
	remove_post_type_support('page', 'comments');

	update_option('default_comment_status', 'closed');
}
add_action('init', 'disable_comments_init');


/**
 * Disables comments in the admin area.
 */
function disable_comments_admin()
{
	remove_meta_box('commentstatusdiv', 'post', 'normal');
	remove_meta_box('commentsdiv', 'post', 'normal');

	remove_meta_box('commentstatusdiv', 'page', 'normal');
	remove_meta_box('commentsdiv', 'page', 'normal');
}
add_action('admin_init', 'disable_comments_admin');


/**
 * Disables the comments admin menu.
 */
function disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action( 'admin_menu', 'disable_comments_admin_menu' );
?>