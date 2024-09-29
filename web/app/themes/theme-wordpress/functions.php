<?php defined('ABSPATH') || die();

use Carbon_Fields\Carbon_Fields;

add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
});

$roots_includes = array(
    '/inc/admin/cleanup.php',
    '/inc/custom-shortcodes/index.php',
    '/inc/custom-post-types/index.php',
    '/inc/core.php',
    '/inc/hmr.php',
    '/inc/scripts.php',
    '/inc/smtp.php',
);

foreach ($roots_includes as $file) {
    $filepath = locate_template($file);
    if ($filepath && file_exists($filepath)) {
        require_once $filepath;
    } else {
        trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
    }
}
unset($file, $filepath);
