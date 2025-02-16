<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('pages/home.twig', $context);
