<?php

declare(strict_types=1);

namespace Theme\Services;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;
use Theme\Core\AjaxController;
use Theme\Helpers\HmrHelper;

class AssetManager implements Registerable
{
	public function __construct(private HmrHelper $hmr)
	{
	}

	public function register(): void
	{
		add_action('wp_enqueue_scripts', [$this, 'enqueue']);
		add_action('wp_head', [$this, 'localizeAjax'], 1);
	}

	public function enqueue(): void
	{
		if ($this->hmr->isHMRAvailable()) {
			$this->enqueueDevAssets();
		} else {
			$this->enqueueProductionAssets();
		}
	}

	/**
	 * Expose admin-ajax.php's URL and a CSRF nonce as `window.themeAjax`
	 * so frontend JS can call AJAX endpoints (see Theme\Core\AjaxController
	 * and assets/js/features/contact-form.js). Printed as a plain inline
	 * script rather than wp_localize_script() so it works the same way
	 * whether assets are loaded as classic scripts (production) or ES
	 * modules via the Vite dev server (HMR).
	 */
	public function localizeAjax(): void
	{
		printf(
			'<script>window.themeAjax = %s;</script>' . "\n",
			wp_json_encode([
				'url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce(AjaxController::NONCE_ACTION),
			])
		);
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
