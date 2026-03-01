<?php defined('ABSPATH') || die();
/**
 * @package MimeType Support
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 *
 * @wordpress-plugin
 * Plugin Name: MimeType Support
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Accept SVG, WebP, and AVIF uploads with basic SVG sanitization
 * Version: 1.1
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

/**
 * Add support for additional MIME types.
 *
 * @param array $mime_types An array of allowed MIME types.
 * @return array The modified array with additional MIME types.
 */
add_filter('upload_mimes', function (array $mime_types): array {
	$mime_types['svg']  = 'image/svg+xml';
	$mime_types['svgz'] = 'image/svg+xml';
	$mime_types['webp'] = 'image/webp';
	$mime_types['avif'] = 'image/avif';
	return $mime_types;
});

/**
 * Fix file type detection for SVG uploads.
 *
 * @param array  $data     File data array containing 'ext' and 'type'.
 * @param string $file     Full path to the file.
 * @param string $filename The name of the file.
 * @return array The corrected file data.
 */
add_filter('wp_check_filetype_and_ext', function (array $data, string $file, string $filename): array {
	$ext = pathinfo($filename, PATHINFO_EXTENSION);

	if ($ext === 'svg' || $ext === 'svgz') {
		$data['ext']  = $ext;
		$data['type'] = 'image/svg+xml';
	}

	return $data;
}, 10, 3);

/**
 * Sanitize SVG files on upload to prevent XSS attacks.
 *
 * @param array $upload The upload data array.
 * @return array The sanitized upload data.
 */
add_filter('wp_handle_upload_prefilter', function (array $upload): array {
	if ($upload['type'] !== 'image/svg+xml') {
		return $upload;
	}

	$content = file_get_contents($upload['tmp_name']);

	if ($content === false) {
		$upload['error'] = __('Unable to read SVG file.', 'alexandre');
		return $upload;
	}

	// Block dangerous SVG content
	$dangerous_patterns = [
		'/<script\b/i',
		'/on\w+\s*=/i',
		'/javascript\s*:/i',
		'/data\s*:/i',
		'/<foreignObject/i',
		'/<use\b[^>]*href\s*=\s*["\'](?!#)/i',
	];

	foreach ($dangerous_patterns as $pattern) {
		if (preg_match($pattern, $content)) {
			$upload['error'] = __('This SVG file contains potentially dangerous content and has been blocked.', 'alexandre');
			return $upload;
		}
	}

	return $upload;
});
