<?php defined('ABSPATH') || die();

add_action('admin_menu', function ():void {
    $user = wp_get_current_user();
    if (current_user_can('editor')) {
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('tools.php');
    }
}, 999);
