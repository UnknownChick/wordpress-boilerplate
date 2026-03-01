<?php

declare(strict_types=1);

namespace Theme\Core;

defined('ABSPATH') || die();

use Timber\Timber;
use Theme\Contracts\Renderable;

abstract class AbstractController implements Renderable
{
	/**
	 * Build the base Timber context and merge with controller-specific data.
	 *
	 * @return array<string, mixed>
	 */
	public function context(): array
	{
		return Timber::context($this->data());
	}

	/**
	 * Controller-specific data to inject into the context.
	 * Override this in child controllers.
	 *
	 * @return array<string, mixed>
	 */
	protected function data(): array
	{
		return [];
	}

	/**
	 * Render the Twig view with the prepared context.
	 */
	public function render(): void
	{
		Timber::render($this->view(), $this->context());
	}

	/**
	 * Static factory: resolve controller from container and render.
	 *
	 * @param class-string<AbstractController> $controller
	 */
	public static function dispatch(string $controller): void
	{
		/** @var AbstractController $instance */
		$instance = Container::getInstance()->get($controller);
		$instance->render();
	}
}
