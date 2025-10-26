<?php

namespace App;

defined('ABSPATH') || die();

//use Carbon_Fields\Carbon_Fields;
use App\Helpers\SmtpHelper;
use App\Utils\FileAutoload;
use Timber\Timber;

add_action('after_setup_theme', function () {
    //Carbon_Fields::boot();
    SmtpHelper::init();
    Timber::init();
});

$fileAutoload = FileAutoload::getInstance();
$fileAutoload->addFiles('admin', [
    //'pages/page-options.php',
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
