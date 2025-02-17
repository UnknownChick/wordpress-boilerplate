<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('layouts/sidebar.twig', $context);
