<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/category.twig', $context);
