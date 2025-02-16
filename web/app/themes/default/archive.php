<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/archive.twig', $context);
