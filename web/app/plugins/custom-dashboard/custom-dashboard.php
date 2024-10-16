<?php defined('ABSPATH') || die();
/*
Plugin Name: Custom Dashboard
Description: Custom dashboard style
Version: 1.0
Author: Alexandre Ferreira
*/

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('custom-dashboard-style', plugins_url('css/dashboard-style.css', __FILE__));
});
