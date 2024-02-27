<?php
/**
 * @package MymeType Support
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 * 
 * @wordpress-plugin
 * Plugin Name: MimeType Support
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Accept SVG and PDF uploads
 * Version: 1.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexxandre
 * License: MIT License
 */

defined('ABSPATH') || die();


/**
 * Function to support MIME types.
 *
 * @param array $mime_types An array of MIME types.
 * @return void
 */
function mimeType_support($mime_types){
	$mime_types['svg'] = 'image/svg+xml';
	$mime_types['pdf'] = 'application/pdf';
	return $mime_types;
}
add_filter('upload_mimes', 'mimeType_support', 15, 1);
?>