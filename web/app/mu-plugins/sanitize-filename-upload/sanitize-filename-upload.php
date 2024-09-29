<?php defined('ABSPATH') || die();
/**
 * @package Sanitize Filename Upload
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 * 
 * @wordpress-plugin
 * Plugin Name: Sanitize Filename Upload
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: When uploading a file: removes accents, replaces '_' with '-', and converts to lowercase
 * Version: 1.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexxandre
 * License: MIT License
 */


/**
 * Sanitizes a filename by removing any potentially harmful characters.
 *
 * @param string $filename The filename to sanitize.
 * @return string The sanitized filename.
 */
add_filter('sanitize_file_name', function (string $filename) {
    $sanitized_filename = remove_accents($filename);
    $sanitized_filename = str_replace('_', '-', $sanitized_filename);
    return strtolower($sanitized_filename);
}, 10, 1);
