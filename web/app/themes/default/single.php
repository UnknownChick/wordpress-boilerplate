<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/single.twig', $context);
