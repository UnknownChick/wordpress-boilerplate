<?php

namespace Theme\Shortcodes;

defined('ABSPATH') || die();

use WP_Post_Type;
use Theme\Contracts\Registerable;

class SiteMap implements Registerable
{
	public function register(): void
	{
		add_shortcode('site_map', [$this, 'generateShortcode']);
	}

	/**
	 * Generates a site map.
	 *
	 * @param array $atts An array of attributes for the site map.
	 * @return string The generated site map HTML.
	 */
	public function generateShortcode(array $atts = []): string
	{
		if (!isset($atts['type'])) {
			return '';
		}

		$post_types = get_post_types([
			'public' => true
		], 'objects');

		$output = '';

		if (isset($post_types[$atts['type']])) {
			$items = get_posts([
				'post_type' => $atts['type'],
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'post_status' => 'publish'
			]);

			if (!empty($items)) {
				$output = $this->generatePostTypeList(
					$post_types[$atts['type']],
					$items
				);
			}
		}

		return $output;
	}

	/**
	 * Generates a list of posts for a given post type
	 *
	 * @param WP_Post_Type $post_type
	 * @param array $items
	 * @return string The generated list of posts
	 */
	private function generatePostTypeList(WP_Post_Type $post_type, array $items): string
	{
		$html = '<article role="article">';
		$html .= '<h2 role="heading">' . $post_type->labels->name . '</h2>';
		$html .= '<ul role="menu">';

		foreach ($items as $item) {
			$html .= '<li role="menuitem">';
			$html .= '<a href="' . get_permalink($item->ID) . '">';
			$html .= $item->post_title;
			$html .= '</a></li>';
		}

		$html .= '</ul></article>';

		return $html;
	}
}
