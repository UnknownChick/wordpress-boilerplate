<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/comments.twig', $context);
