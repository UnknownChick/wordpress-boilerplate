<?php

namespace Theme;

defined('ABSPATH') || die();

use App\Helpers\SmtpHelper;
use App\Utils\FileAutoload;
use Timber\Timber;

add_action('after_setup_theme', function () {
    SmtpHelper::init();
    Timber::init();
});

$fileAutoload = FileAutoload::getInstance();
$fileAutoload->addFiles('admin', [
    'pages/page-options.php',
    'capabilities.php',
    'cleanup.php',
    'duplicate-post.php',
    'footer.php',
]);
$fileAutoload->addFiles('', [
    'core.php',
    'optimizations.php',
    'scripts.php',
    'security.php',
]);
$fileAutoload->load();
