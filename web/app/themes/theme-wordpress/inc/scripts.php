<?php defined('ABSPATH') || die();

add_action('wp_enqueue_scripts', function () {
    $dependencies = array();
    $version = null;

    if (isViteHMRAvailable()) {
        $ts_handle = 'app';
        $sass_handle = 'scss';
        $vite_handle = 'vite-client';

        wp_enqueue_script($ts_handle, getViteDevServerAddress().'/assets/js/app.js', $dependencies, $version);
        wp_enqueue_script($vite_handle, getViteDevServerAddress().'/@vite/client', $dependencies, $version);
        wp_enqueue_style($sass_handle, getViteDevServerAddress().'/assets/scss/style.scss', $dependencies, $version);

        loadJSScriptAsESModule($vite_handle);
        loadJSScriptAsESModule($ts_handle);
    } else {
        wp_enqueue_style('style-theme', get_stylesheet_uri(), $dependencies, $version);
        wp_enqueue_style('style-dist', get_stylesheet_directory_uri().'/dist/style.css', $dependencies, $version);
        wp_enqueue_script('js-dist', get_stylesheet_directory_uri().'/dist/app.js', $dependencies, $version, true);
    }
});
