<?php

namespace Theme;

defined('ABSPATH') || die();

use Timber\Timber;
use Theme\Core\Theme;
use Theme\Core\Container;
use Theme\Helpers\SmtpHelper;
use Theme\Utils\FileAutoload;

add_action('after_setup_theme', function () {
	SmtpHelper::init();
	Timber::init();
});

$container = Container::getInstance();

$theme = new Theme($container);
$theme->init();

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
