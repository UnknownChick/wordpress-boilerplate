<?php

namespace App;

defined('ABSPATH') || die();

use Carbon_Fields\Carbon_Fields;
use App\Helpers\SmtpHelper;

add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
    SmtpHelper::init();
});

$roots_includes = array(
    '/inc/admin/pages/page-options.php',
    '/inc/admin/capabilities.php',
    '/inc/admin/cleanup.php',
    '/inc/admin/duplicate-post.php',
    '/inc/admin/footer.php',

    '/inc/shortcodes/site-map.php',

    '/inc/core.php',
    '/inc/optimizations.php',
    '/inc/scripts.php',
    '/inc/security.php',
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
