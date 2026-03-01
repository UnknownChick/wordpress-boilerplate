<?php

declare(strict_types=1);

namespace Theme\Contracts;

defined('ABSPATH') || die();

interface Renderable
{
	/**
	 * Prepare the context data for the view.
	 *
	 * @return array<string, mixed>
	 */
	public function context(): array;

	/**
	 * Return the Twig view path(s) to render.
	 *
	 * @return string|array<int, string>
	 */
	public function view(): string|array;

	/**
	 * Render the view with its context.
	 */
	public function render(): void;
}
