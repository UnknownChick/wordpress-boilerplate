<?php

declare(strict_types=1);

namespace Theme\Models;

defined('ABSPATH') || die();

/**
 * Page model.
 *
 * Extend the base Post model with page-specific methods.
 */
class Page extends Post
{
	/**
	 * Get the page's children pages.
	 *
	 * @return array<int, Page>
	 */
	public function childPages(): array
	{
		return array_values($this->children('page')->to_array());
	}

	/**
	 * Check if this page has a parent page.
	 */
	public function hasParent(): bool
	{
		return (bool) $this->parent();
	}

	/**
	 * Get the breadcrumb trail for this page.
	 *
	 * @return array<int, array{title: string, url: string}>
	 */
	public function breadcrumbTrail(): array
	{
		$trail = [];
		$current = $this;

		while ($current) {
			array_unshift($trail, [
				'title' => $current->title(),
				'url' => $current->link(),
			]);
			$current = $current->parent();
		}

		return $trail;
	}
}
