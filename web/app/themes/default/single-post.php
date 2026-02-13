<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('single/post.twig', $context);
