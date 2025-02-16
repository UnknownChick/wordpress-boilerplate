<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/singular.twig', $context);
