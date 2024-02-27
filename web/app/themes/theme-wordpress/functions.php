<?php
defined('ABSPATH') || die();

$roots_includes = array(
	'/inc/hmr.php',
	'/inc/shortcodes.php',
);

foreach($roots_includes as $file){
	if(!$filepath = locate_template($file)) {
		trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
	}
	require_once $filepath;
}
unset($file, $filepath);

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('menus');

function register_assets() {
	wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0');
	// wp_enqueue_style('style-dist', get_stylesheet_directory_uri().'/dist/style.css', array(), '1.0');
	wp_enqueue_script('js-dist', get_stylesheet_directory_uri().'/dist/app.js', array(), '1.0', true);
}
add_action( 'wp_enqueue_scripts', 'register_assets' );

add_action(
	'wp_enqueue_scripts', function () {
		$handle = 'app';
		$dependencies = array();
		$version = null;

		if (isViteHMRAvailable()) {
			loadJSScriptAsESModule($handle);
			wp_enqueue_script(
				$handle,
				getViteDevServerAddress().'/assets/ts/app.ts',
				$dependencies,
				$version
			);
		}
	}
);

function get_path() {
	if (isViteHMRAvailable()) {
		return get_theme_root_uri() . '/' . get_stylesheet();
	} else {
		return get_stylesheet_directory_uri();
	}
}

function desactiver_commentaires_articles() {
	remove_post_type_support('post', 'comments');
}
add_action('init', 'desactiver_commentaires_articles');

function desactiver_commentaires_pages() {
	remove_post_type_support('page', 'comments');
}
add_action('init', 'desactiver_commentaires_pages');


add_filter('excerpt_length', function($length) {
	return 40;
});