<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('layouts/header.twig', $context);
