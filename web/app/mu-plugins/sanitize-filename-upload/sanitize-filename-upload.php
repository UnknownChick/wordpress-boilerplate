<?php defined('ABSPATH') || die();
/**
 * @package Sanitize Filename Upload
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 *
 * @wordpress-plugin
 * Plugin Name: Sanitize Filename Upload
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Sanitizes uploaded filenames: removes accents, special characters, replaces spaces/underscores with dashes, and converts to lowercase
 * Version: 2.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

/**
 * Sanitize a filename on upload.
 *
 * - Remove accents (e.g. é → e)
 * - Replace underscores and spaces with dashes
 * - Remove all non-alphanumeric characters except dashes and dots
 * - Collapse consecutive dashes
 * - Trim leading/trailing dashes from the name (not extension)
 * - Convert to lowercase
 *
 * @param string $filename The original filename.
 * @return string The sanitized filename.
 */
add_filter('sanitize_file_name', function (string $filename): string {
	$extension = pathinfo($filename, PATHINFO_EXTENSION);
	$name      = pathinfo($filename, PATHINFO_FILENAME);

	$name = remove_accents($name);
	$name = str_replace(['_', ' '], '-', $name);
	$name = preg_replace('/[^a-zA-Z0-9\-]/', '', $name);
	$name = preg_replace('/-+/', '-', $name);
	$name = trim($name, '-');
	$name = strtolower($name);

	return $extension ? "{$name}.{$extension}" : $name;
});
