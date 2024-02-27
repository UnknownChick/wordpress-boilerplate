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
 * Function to disable comments on articles.
 */
function desactiver_commentaires_articles() {
	remove_post_type_support('post', 'comments');
	remove_post_type_support('page', 'comments');
}
add_action('init', 'desactiver_commentaires_articles');

?>