<?php

namespace Theme\Services;

defined('ABSPATH') || die();

use Theme\Attributes\OnHook;
use Theme\Contracts\Registerable;

#[OnHook('after_setup_theme')]
class ThemeSetup implements Registerable
{
	public function register(): void
	{
		$this->addThemeSupport();
		$this->registerMenus();
	}

	private function addThemeSupport(): void
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

	private function registerMenus(): void
	{
		register_nav_menus([
			'main'   => __('Main Menu', 'theme'),
			'legals' => __('Legals Menu', 'theme'),
		]);
	}
}
