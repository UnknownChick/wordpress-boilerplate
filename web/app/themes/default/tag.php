<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/tag.twig', $context);
