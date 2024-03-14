<?php
defined('ABSPATH') || die();
/**
 * Initializes the shortcodes.
 */
add_action('init', function() {
	/**
	 * Generates a site map.
	 *
	 * @param array $atts An array of attributes for the site map.
	 * @return string The generated site map HTML.
	 */
	function site_map($atts) {
		$pages = get_pages();
		$posts = get_posts();
		$authors = get_users();
		$categories = get_categories();
		
		$site_map = '<ul class="sitemap_'.$atts['class'].'">';
		
		if (isset($atts['type'])) {
			switch ($atts['type']) {
				case 'pages':
					foreach ($pages as $page) {
						$site_map .= '<li><a href="'.get_permalink($page->ID).'">'.$page->post_title.'</a></li>';
					}
					break;
				case 'posts':
					foreach ($posts as $post) {
						$site_map .= '<li><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></li>';
					}
					break;
				case 'authors':
					foreach ($authors as $author) {
						$site_map .= '<li><a href="'.get_permalink($author->ID).'">'.$author->data->user_login.'</a></li>';
					}
					break;
				case 'categories':
					foreach ($categories as $categorie) {
						$site_map .= '<li><a href="'.get_permalink($categorie->ID).'">'.$categorie->cat_name.'</a></li>';
					}
					break;
				default:

				break;
			}
		}
		$site_map .= '</ul>';
		
		echo $site_map;
	}
	add_shortcode('site_map', 'site_map');
});
?>