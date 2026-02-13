<?php

use Timber\Timber;

$context = Timber::context();

$context['menu'] = Timber::get_menu('main');

Timber::render('layouts/header.twig', $context);
