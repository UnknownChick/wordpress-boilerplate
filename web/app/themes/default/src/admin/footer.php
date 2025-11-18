<?php defined('ABSPATH') || die();

add_action('admin_footer_text', function (): void {
	$theme = wp_get_theme();

	echo 'Developed by <a href="'.$theme->get('AuthorURI').'" target="_blank">'.$theme->get('Author').'</a> Copyright &copy; '.date('Y').' | Theme: '.$theme->get('Name').' v'.$theme->get('Version');
});
