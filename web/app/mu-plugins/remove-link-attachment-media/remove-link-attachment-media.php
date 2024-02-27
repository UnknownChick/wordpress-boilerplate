<?php
/**
 * @package MymeType Support
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 * 
 * @wordpress-plugin
 * Plugin Name: Remove Link Attachment Media
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Removes page links for media attachments
 * Version: 1.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexxandre
 * License: MIT License
 */

defined('ABSPATH') || die();


/**
 * Disables attachment URLs.
 *
 * This function is used to disable the URLs of attachments in WordPress.
 * It takes the attachment data as input and modifies it to remove the attachment URLs.
 *
 * @param array $data The attachment data.
 * @return array The modified attachment data.
 */
function disable_attachment_urls($data) {
	if (isset( $data['post_type']) && $data['post_type'] == 'attachment') {
		$data['guid'] = '';
	}
	return $data;
}

add_filter('wp_insert_attachment_data', 'disable_attachment_urls', 10, 1)
?>