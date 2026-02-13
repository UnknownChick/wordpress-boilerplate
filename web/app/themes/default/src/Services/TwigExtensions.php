<?php

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
