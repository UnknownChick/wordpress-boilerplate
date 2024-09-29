<?php defined('ABSPATH') || die();
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
 * Text Domain: alexandre
 * License: MIT License
 */


/**
 * Function to support MIME types.
 *
 * @param array $mime_types An array of MIME types.
 * @return array
 */
add_filter('upload_mimes', function (array $mime_types) {
    $mime_types['svg'] = 'image/svg+xml';
    $mime_types['pdf'] = 'application/pdf';
    return $mime_types;
}, 15, 1);
