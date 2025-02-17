<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('layouts/footer.twig', $context);
