<?php defined('ABSPATH') || die();
/*
Plugin Name: Custom Dashboard
Description: Custom dashboard style
Version: 1.0
Author: Alexandre Ferreira
*/

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('dashboard-buttons', plugins_url('css/buttons.css', __FILE__));
    wp_enqueue_style('dashboard-forms', plugins_url('css/forms.css', __FILE__));
    wp_enqueue_style('dashboard-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('dashboard-tables', plugins_url('css/tables', __FILE__));
});
