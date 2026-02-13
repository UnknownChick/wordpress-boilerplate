<?php

namespace Theme\Services;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;
use Theme\Helpers\HmrHelper;

class AssetManager implements Registerable
{
	public function __construct(private HmrHelper $hmr)
	{
	}

	public function register(): void
	{
		add_action('wp_enqueue_scripts', [$this, 'enqueue']);
	}

	public function enqueue(): void
	{
		if ($this->hmr->isHMRAvailable()) {
			$this->enqueueDevAssets();
		} else {
			$this->enqueueProductionAssets();
		}
	}

	private function enqueueDevAssets(): void
	{
		$baseUrl = $this->hmr->getViteDevServerAddress();

		wp_enqueue_script_module('app', $baseUrl . '/assets/js/app.js');
		wp_enqueue_script_module('vite-client', $baseUrl . '/@vite/client');
		wp_enqueue_style('scss', $baseUrl . '/assets/scss/style.scss');
	}

	private function enqueueProductionAssets(): void
	{
		$themeUri = get_stylesheet_directory_uri();

		wp_enqueue_style('style-theme', get_stylesheet_uri());
		wp_enqueue_style('style-dist', $themeUri . '/dist/style.css');
		wp_enqueue_script('js-dist', $themeUri . '/dist/app.js', [], null, true);
	}
}
