<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/author.twig', $context);
