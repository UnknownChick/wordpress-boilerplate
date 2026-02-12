<?php

namespace Theme\Core;

defined('ABSPATH') || die();

use Exception;
use Theme\Core\Container;
use Theme\Contracts\Registerable;

class Theme
{
	private Container $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function init(): void
	{
		$this->registerServices();
		$this->registerPostTypes();
		$this->registerTaxonomies();
		$this->registerFields();
		$this->registerAdminFeatures();
		$this->setupHooks();
	}

	/**
	 * Scan a directory for classes implementing Registerable, resolve them via the container and call register()
	 *
	 * @param string $directory Absolute path to the directory to scan
	 * @param string $namespace Corresponding namespace (e.g. "Theme\PostTypes")
	 * @return void
	 */
	private function autodiscover(string $directory, string $namespace): void
	{
		$classes = ClassDiscovery::find($directory, $namespace, Registerable::class);

		foreach ($classes as $class) {
			$this->container->get($class)->register();
		}
	}

	private function registerServices(): void
	{

	}

	private function registerPostTypes(): void
	{
		$this->autodiscover(
			get_template_directory() . '/src/PostTypes',
			'Theme\\PostTypes'
		);
	}

	private function registerTaxonomies(): void
	{
		$this->autodiscover(
			get_template_directory() . '/src/Taxonomies',
			'Theme\\Taxonomies'
		);
	}

	private function registerFields(): void
	{
		if (!function_exists('register_extended_field_group')) {
			Throw new Exception('The package "acf-extended" is required to use the field registration features of this theme. Please install and activate it.');
		}

		$this->autodiscover(
			get_template_directory() . '/src/Fields',
			'Theme\\Fields'
		);
	}

	private function registerAdminFeatures(): void
	{
		if (!is_admin()) return;

		$this->autodiscover(
			get_template_directory() . '/src/Admin',
			'Theme\\Admin'
		);
	}

	private function setupHooks(): void
	{
		add_action('after_setup_theme', [$this, 'themeSupport']);
		add_action('after_setup_theme', [$this, 'registerMenus']);
		// add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
		// add_action('widgets_init', [$this, 'registerSidebars']);
	}

	public function themeSupport(): void
	{
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('menus');
		add_theme_support('html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]);

		remove_theme_support('core-block-patterns');
	}

	public function enqueueAssets(): void
	{
		$version = wp_get_theme()->get('Version');

		wp_enqueue_style(
			'theme-style',
			get_stylesheet_uri(),
			[],
			$version
		);
	}

	public function registerMenus(): void
	{
		register_nav_menus([
			'main' => 'Main Menu',
			'legals' => 'Legals Menu',
		]);
	}

	public function registerSidebars(): void
	{
		register_sidebar([
			'name'          => __('Main Sidebar', 'theme'),
			'id'            => 'sidebar-1',
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		]);
	}
}
