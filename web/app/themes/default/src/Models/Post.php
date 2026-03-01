<?php

declare(strict_types=1);

namespace Theme\Models;

defined('ABSPATH') || die();

use Timber\Post as TimberPost;

/**
 * Base Post model.
 *
 * Extend Timber\Post with custom methods shared across all post types.
 * Register this class map in TimberSetup so Timber uses it automatically.
 */
class Post extends TimberPost
{
	/**
	 * Get the reading time in minutes.
	 */
	public function readingTime(int $wordsPerMinute = 200): int
	{
		$content = wp_strip_all_tags($this->content());
		$wordCount = str_word_count($content);

		return max(1, (int) ceil($wordCount / $wordsPerMinute));
	}

	/**
	 * Get the post excerpt, with a fallback to truncated content.
	 */
	public function summary(int $length = 160): string
	{
		if ($this->post_excerpt) {
			return $this->post_excerpt;
		}

		$content = wp_strip_all_tags($this->content());

		return mb_strlen($content) > $length
			? mb_substr($content, 0, $length) . '…'
			: $content;
	}

	/**
	 * Check if the post has a featured image.
	 */
	public function hasThumbnail(): bool
	{
		return (bool) $this->thumbnail();
	}
}
