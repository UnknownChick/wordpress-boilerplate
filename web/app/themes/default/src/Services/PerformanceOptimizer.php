<?php

declare(strict_types=1);

namespace Theme\Services;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;

/**
 * Frontend performance hardening: strips WordPress' default head bloat,
 * disables unused core features and slims down the Heartbeat API.
 *
 * Nothing here is destructive: every piece can be safely removed if a
 * project actually needs emojis, oEmbed discovery, etc.
 */
class PerformanceOptimizer implements Registerable
{
	public function register(): void
	{
		$this->cleanHead();
		$this->disableEmojis();
		$this->disableEmbeds();
		$this->tuneHeartbeat();
	}

	/**
	 * Remove meta tags/links that leak version info or are rarely used.
	 */
	private function cleanHead(): void
	{
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'wp_shortlink_wp_head');
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
		remove_action('wp_head', 'rest_output_link_wp_head');

		add_filter('the_generator', '__return_empty_string');
	}

	/**
	 * Emoji detection script/styles add an extra request and inline CSS
	 * on every page load for a feature most browsers support natively.
	 */
	private function disableEmojis(): void
	{
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

		add_filter('tiny_mce_plugins', static fn (array $plugins): array => array_diff($plugins, ['wpemoji']));

		add_filter('wp_resource_hints', static function (array $urls, string $relationType): array {
			if ($relationType === 'dns-prefetch') {
				$urls = array_diff($urls, ['https://s.w.org']);
			}

			return $urls;
		}, 10, 2);
	}

	/**
	 * Disable oEmbed discovery/consumption unless the project needs it.
	 */
	private function disableEmbeds(): void
	{
		remove_action('wp_head', 'wp_oembed_add_discovery_links');
		remove_action('wp_head', 'wp_oembed_add_host_js');
		add_filter('embed_oembed_discover', '__return_false');

		add_action('wp_enqueue_scripts', static function (): void {
			wp_deregister_script('wp-embed');
		}, 100);
	}

	/**
	 * Reduce Heartbeat frequency and drop it entirely on the frontend
	 * instead of fully disabling it (autosave/post locking in wp-admin
	 * still rely on it).
	 */
	private function tuneHeartbeat(): void
	{
		add_filter('heartbeat_settings', static function (array $settings): array {
			$settings['interval'] = 60;

			return $settings;
		});

		add_action('wp_enqueue_scripts', static function (): void {
			if (!is_admin()) {
				wp_deregister_script('heartbeat');
			}
		}, 100);
	}
}
