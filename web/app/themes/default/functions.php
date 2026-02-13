<?php

namespace Theme;

defined('ABSPATH') || die();

use Theme\Core\Theme;
use Theme\Core\Container;

$container = Container::getInstance();

$theme = new Theme($container);
$theme->boot();
