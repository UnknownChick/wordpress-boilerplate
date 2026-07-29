<?php

declare(strict_types=1);

namespace Theme\Services;

defined('ABSPATH') || die();

use Twig\Environment;
use Twig\TwigFunction;
use Theme\Contracts\Registerable;
use Theme\Helpers\HmrHelper;

class TwigExtensions implements Registerable
{
	public function __construct(private HmrHelper $hmr)
	{
	}

	public function register(): void
	{
		add_filter('timber/twig', [$this, 'addFunctions']);
	}

	public function addFunctions(Environment $twig): Environment
	{
		$twig->addFunction(new TwigFunction('asset', [$this, 'asset']));

		// WordPress translation helpers, usable directly in Twig views
		// (e.g. {{ __('Hello', 'default') }}), since Timber v2 no longer
		// registers them by default.
		$twig->addFunction(new TwigFunction('__', '__'));
		$twig->addFunction(new TwigFunction('_e', '_e'));
		$twig->addFunction(new TwigFunction('_n', '_n'));
		$twig->addFunction(new TwigFunction('_x', '_x'));

		return $twig;
	}

	public function asset(string $path): string
	{
		$path = ltrim($path, '/');

		if ($this->hmr->isHMRAvailable()) {
			return $this->hmr->getViteDevServerAddress() . '/assets/' . $path;
		}

		return get_stylesheet_directory_uri() . '/dist/' . basename($path);
	}
}
