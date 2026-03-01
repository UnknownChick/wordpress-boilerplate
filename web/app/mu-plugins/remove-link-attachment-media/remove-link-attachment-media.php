<?php defined('ABSPATH') || die();
/**
 * @package Remove Link Attachment Media
 * @author Alexandre Ferreira
 * @link https://alexandre-ferreira.fr
 *
 * @wordpress-plugin
 * Plugin Name: Remove Link Attachment Media
 * Plugin URI: https://alexandre-ferreira.fr
 * Description: Disables attachment pages, prevents slug conflicts between media and content
 * Version: 2.0
 * Author: Alexandre Ferreira
 * Author URI: https://alexandre-ferreira.fr
 * Text Domain: alexandre
 * License: MIT License
 */

/**
 * Redirect attachment pages to parent post or homepage.
 *
 * Instead of modifying the GUID (which is used for RSS feeds and should remain intact), this redirects attachment pages with a 301 status to prevent SEO issues.
 */
add_action('template_redirect', function (): void {
	if (!is_attachment()) {
		return;
	}

	$post = get_post();

	if ($post && $post->post_parent) {
		$redirect_url = get_permalink($post->post_parent);
	} else {
		$redirect_url = home_url('/');
	}

	wp_redirect($redirect_url, 301);
	exit;
});

/**
 * Remove attachment pages from the sitemap.
 */
add_filter('wp_sitemaps_post_types', function (array $post_types): array {
	unset($post_types['attachment']);
	return $post_types;
});

/**
 * Prefix attachment slugs to prevent conflicts with pages/posts.
 *
 * Without this, uploading "contact.png" creates an attachment with the slug "contact",
 * which forces a page named "Contact" to become "/contact-2".
 * This filter prefixes all attachment slugs with "media-" (e.g. "media-contact").
 *
 * @param array $data    The sanitized attachment data.
 * @param array $postarr The raw attachment data.
 * @return array The modified attachment data.
 */
add_filter('wp_insert_attachment_data', function (array $data, array $postarr): array {
	if (
		isset($data['post_type']) &&
		$data['post_type'] === 'attachment' &&
		!empty($data['post_name']) &&
		!str_starts_with($data['post_name'], 'media-')
	) {
		$data['post_name'] = 'media-' . $data['post_name'];
	}

	return $data;
}, 10, 2);
