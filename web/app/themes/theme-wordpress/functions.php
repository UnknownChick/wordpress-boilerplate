<?php defined('ABSPATH') || die();

$roots_includes = array(
    '/inc/cleanup.php',
    '/inc/custom-shortcodes/index.php',
    '/inc/custom-post-types/index.php',
    '/inc/hmr.php',
);

foreach ($roots_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
    }
    require_once $filepath;
}
unset($file, $filepath);

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
// add_theme_support('widgets');


add_action('wp_enqueue_scripts', function () {
    $dependencies = array();
    $version = null;

    if (isViteHMRAvailable()) {
        $ts_handle = 'app';
        $sass_handle = 'sass';
        $vite_handle = 'vite-client';

        wp_enqueue_script($ts_handle, getViteDevServerAddress().'/assets/ts/app.ts', $dependencies, $version);
        wp_enqueue_script($vite_handle, getViteDevServerAddress().'/@vite/client', $dependencies, $version);
        wp_enqueue_style($sass_handle, getViteDevServerAddress().'/assets/sass/style.scss', $dependencies, $version);

        loadJSScriptAsESModule($vite_handle);
        loadJSScriptAsESModule($ts_handle);
    } else {
        wp_enqueue_style('style-theme', get_stylesheet_uri(), $dependencies, $version);
        wp_enqueue_style('style-dist', get_stylesheet_directory_uri().'/dist/style.css', $dependencies, $version);
        wp_enqueue_script('js-dist', get_stylesheet_directory_uri().'/dist/app.js', $dependencies, $version, true);
    }
});

add_filter('excerpt_length', function ($length) {
    return 40;
});

add_filter('use_block_editor_for_post', '__return_false', 10);
