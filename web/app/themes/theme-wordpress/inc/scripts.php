<?php defined('ABSPATH') || die();

use Theme\Helpers\HmrHelper;

add_action('wp_enqueue_scripts', function () {
	$hmr = new HmrHelper();
    $dependencies = array();
    $version = null;

    if ($hmr->isHMRAvailable()) {
        $ts_handle = 'app';
        $sass_handle = 'scss';
        $vite_handle = 'vite-client';

        wp_enqueue_script_module($ts_handle, $hmr->getViteDevServerAddress().'/assets/js/app.js', $dependencies, $version);
        wp_enqueue_script_module($vite_handle, $hmr->getViteDevServerAddress().'/@vite/client', $dependencies, $version);
        wp_enqueue_style($sass_handle, $hmr->getViteDevServerAddress().'/assets/scss/style.scss', $dependencies, $version);
    } else {
        wp_enqueue_style('style-theme', get_stylesheet_uri(), $dependencies, $version);
        wp_enqueue_style('style-dist', get_stylesheet_directory_uri().'/dist/style.css', $dependencies, $version);
        wp_enqueue_script('js-dist', get_stylesheet_directory_uri().'/dist/app.js', $dependencies, $version, true);
    }
});
