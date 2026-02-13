<?php defined('ABSPATH') || die();

use Twig\Environment;
use Twig\TwigFunction;
use Theme\Helpers\HmrHelper;

add_filter('timber/twig', function (Environment $twig) {
	$twig->addFunction(new TwigFunction('asset', function (string $path): string {
		$hmr = new HmrHelper();
		$path = ltrim($path, '/');

		if ($hmr->isHMRAvailable()) {
			return $hmr->getViteDevServerAddress() . '/assets/' . $path;
		}

		return get_stylesheet_directory_uri() . '/dist/' . basename($path);
	}));

	return $twig;
});
