<?php

namespace Theme\Services;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;

class Security implements Registerable
{
	public function register(): void
	{
		$this->hideLoginErrors();
		$this->disableRestUsers();
		$this->disableXmlRpc();
		$this->addSecurityHeaders();
		$this->customizeSessionName();
	}

	private function hideLoginErrors(): void
	{
		add_filter('login_errors', fn() => __('Une erreur est survenue lors de la connexion.', 'theme'));
	}

	private function disableRestUsers(): void
	{
		add_filter('rest_endpoints', function (array $endpoints): array {
			unset(
				$endpoints['/wp/v2/users'],
				$endpoints['/wp/v2/users/(?P<id>[\d]+)']
			);
			return $endpoints;
		});
	}

	private function disableXmlRpc(): void
	{
		add_filter('xmlrpc_enabled', '__return_false');
	}

	private function addSecurityHeaders(): void
	{
		add_action('send_headers', function (): void {
			header_remove('X-Powered-By');
			header_remove('X-Pingback');

			header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
			header('X-XSS-Protection: 1; mode=block');
			header('X-Frame-Options: SAMEORIGIN');
			header('X-Content-Type-Options: nosniff');
			header('Referrer-Policy: strict-origin-when-cross-origin');
			header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
		});
	}

	private function customizeSessionName(): void
	{
		add_filter('session_name', fn() => 'wp_' . hash('sha256', AUTH_KEY . SECURE_AUTH_KEY));
	}
}
