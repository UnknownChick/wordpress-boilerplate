<?php defined('ABSPATH') || die();

$roots_includes = array(
    '/inc/custom-shortcodes/index.php',
    '/inc/custom-post-types/index.php',
    '/inc/cleanup.php',
    '/inc/core.php',
    '/inc/hmr.php',
    '/inc/scripts.php',
);

foreach ($roots_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
    }
    require_once $filepath;
}
unset($file, $filepath);
