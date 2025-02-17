<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('views/404.twig', $context);
