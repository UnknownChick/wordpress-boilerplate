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
$fileAutoload->addFiles('', [
    'optimizations.php',
    'scripts.php',
    'security.php',
	'twig.php',
]);
$fileAutoload->load();
