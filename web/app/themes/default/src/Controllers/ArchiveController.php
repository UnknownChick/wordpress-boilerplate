<?php

declare(strict_types=1);

namespace Theme\Controllers;

defined('ABSPATH') || die();

use Timber\Term;
use Timber\Timber;
use Timber\User;
use Theme\Core\AbstractController;

class ArchiveController extends AbstractController
{
	public function view(): string
	{
		return 'base/archive.twig';
	}

	protected function data(): array
	{
		return [
			'title' => wp_strip_all_tags(get_the_archive_title()),
			'posts' => Timber::get_posts(),
			'term' => $this->currentTerm(),
			'author' => $this->currentAuthor(),
		];
	}

	/**
	 * Populated on category/tag/custom taxonomy archives, null otherwise.
	 */
	private function currentTerm(): ?Term
	{
		if (is_category() || is_tag() || is_tax()) {
			return Timber::get_term(get_queried_object());
		}

		return null;
	}

	/**
	 * Populated on author archives, null otherwise.
	 */
	private function currentAuthor(): ?User
	{
		if (is_author()) {
			return Timber::get_user(get_queried_object_id());
		}

		return null;
	}
}
