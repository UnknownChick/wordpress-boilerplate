<?php

use Timber\Timber;

$context = Timber::context();
$context['searchQuery'] = get_search_query();

Timber::render('components/search.twig', $context);
