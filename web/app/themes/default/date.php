<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/date.twig', $context);
