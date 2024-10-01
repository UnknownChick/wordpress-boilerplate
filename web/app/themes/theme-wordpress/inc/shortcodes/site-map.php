<?php defined('ABSPATH') || die();

/**
 * Generates a site map.
 * 
 * [site_map type="$atts['type']"]
 *
 * @param array $atts An array of attributes for the site map.
 * @return string The generated site map HTML.
 */
function site_map(array $atts): string
{
	$pages = get_pages();
	$posts = get_posts();
	$authors = get_users();
	$categories = get_categories();
	
	$output = '<ul class="sitemap-'.$atts['type'].'">';
	
	if (isset($atts['type'])) {
		switch ($atts['type']) {
			case 'pages':
				foreach ($pages as $page) {
					$output .= '<li><a href="'.get_permalink($page->ID).'">'.$page->post_title.'</a></li>';
				}
				break;
			case 'posts':
				foreach ($posts as $post) {
					$output .= '<li><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></li>';
				}
				break;
			case 'authors':
				foreach ($authors as $author) {
					$output .= '<li><a href="'.get_permalink($author->ID).'">'.$author->data->user_login.'</a></li>';
				}
                break;
            case 'categories':
				foreach ($categories as $categorie) {
					$output .= '<li><a href="'.get_permalink($categorie->ID).'">'.$categorie->cat_name.'</a></li>';
				}
				break;
			default:

			break;
		}
	}
	$output .= '</ul>';
	
	return $output;
}
add_shortcode('site_map', 'site_map');
