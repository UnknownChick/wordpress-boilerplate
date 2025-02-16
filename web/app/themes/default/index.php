<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/index.twig', $context);
