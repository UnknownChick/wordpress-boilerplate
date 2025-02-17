<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/taxonomy.twig', $context);
