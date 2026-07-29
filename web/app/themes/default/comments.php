<?php

/**
 * Comments template.
 *
 * WordPress only ever includes a file literally named "comments.php" via
 * comments_template() — it can never be a Twig view directly. This file
 * therefore stays a thin PHP shell that hands off to Timber as soon as
 * possible. It is included from views/singles/post.twig and
 * views/base/single.twig via `{{ function('comments_template') }}`.
 */

use Timber\Timber;

defined('ABSPATH') || die();

// Never expose comments on a password protected post.
if (post_password_required()) {
	return;
}

// Nothing to show and no form to render: skip entirely.
if (!comments_open() && get_comments_number() === 0) {
	return;
}

$context = Timber::context();
$context['post'] = Timber::get_post();

Timber::render('partials/comments.twig', $context);
