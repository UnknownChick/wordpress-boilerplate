<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('base/post.twig', $context);
