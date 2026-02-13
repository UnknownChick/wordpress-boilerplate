<?php

namespace Theme\Admin;

defined('ABSPATH') || die();

use Theme\Attributes\Condition;
use Theme\Contracts\Registerable;

#[Condition('is_admin')]
class Admin implements Registerable
{
	public function register(): void
	{
		$this->capabilities();
		$this->cleanup();
		$this->footer();
	}

	private function capabilities(): void
	{
		add_action('admin_menu', function ():void {
			$user = wp_get_current_user();
			if (current_user_can('editor')) {
				remove_menu_page('edit.php?post_type=page');
				remove_menu_page('tools.php');
			}
		}, 999);
	}

	private function cleanup(): void
	{
		add_filter('should_load_remote_block_patterns', '__return_false');
		add_filter('use_block_editor_for_post', '__return_false', 10);
		add_filter('use_block_editor_for_post_type', '__return_false', 10);
		add_filter('acf/settings/show_admin', '__return_false');

		add_action('wp_dashboard_setup', function (): void {
			remove_meta_box('dashboard_primary', 'dashboard', 'side');
			remove_meta_box('dashboard_secondary', 'dashboard', 'side');
			remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
			remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
			remove_meta_box('dashboard_php_nag', 'dashboard', 'normal');
			remove_meta_box('dashboard_browser_nag', 'dashboard', 'normal');
			remove_meta_box('health_check_status', 'dashboard', 'normal');
			remove_meta_box('dashboard_activity', 'dashboard', 'normal');
			remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
			remove_meta_box('network_dashboard_right_now', 'dashboard', 'normal');
			remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
			remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
			remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
			remove_meta_box('dashboard_site_health', 'dashboard', 'normal');

			remove_action('welcome_panel', 'wp_welcome_panel');
		}, 20);

		add_action('admin_init', function (): void {
			remove_meta_box('postcustom', 'post', 'normal');
			remove_meta_box('postcustom', 'page', 'normal');
			//remove_meta_box('slugdiv', 'post', 'normal');
			//remove_meta_box('slugdiv', 'page', 'normal');

			remove_post_type_support('post', 'trackbacks');
			remove_post_type_support('page', 'trackbacks');
			remove_post_type_support('page', 'author');
		}, 20);

		add_action('admin_head', function (): void {
			$screen = get_current_screen();
			$screen->remove_help_tabs();
		});

		add_action('admin_bar_menu', function ($wp_admin_bar): void {
			$wp_admin_bar->remove_node('contribute');
			$wp_admin_bar->remove_node('customize');
			$wp_admin_bar->remove_node('search');
			$wp_admin_bar->remove_node('wp-logo');
		}, 999);

		// add_filter('mce_buttons', function ($buttons) {
		// 	$remove_buttons = array(
		// 		'strikethrough',
		// 		'hr',
		// 		'unlink',
		// 		'wp_more',
		// 		'spellchecker',
		// 		'dfw',
		// 		'wp_adv',
		// 	);
		// 	foreach ($buttons as $button_key => $button_value) {
		// 		if (in_array( $button_value, $remove_buttons)) {
		// 			unset($buttons[$button_key]);
		// 		}
		// 	}
		// 	return $buttons;
		// });

		// add_filter('mce_buttons_2', function ($buttons) {
		// 	$remove_buttons = array(
		// 		'formatselect',
		// 		'forecolor',
		// 		'pastetext',
		// 		'removeformat',
		// 		'charmap',
		// 		'outdent',
		// 		'indent',
		// 		'wp_help',
		// 	);
		// 	foreach ($buttons as $button_key => $button_value) {
		// 		if (in_array($button_value, $remove_buttons)) {
		// 			unset($buttons[$button_key]);
		// 		}
		// 	}
		// 	return $buttons;
		// });
	}

	private function footer(): void
	{
		add_action('admin_footer_text', function (): void {
			$theme = wp_get_theme();

			echo 'Developed by <a href="'.$theme->get('AuthorURI').'" target="_blank">'.$theme->get('Author').'</a> Copyright &copy; '.date('Y').' | Theme: '.$theme->get('Name').' v'.$theme->get('Version');
		});
	}
}
