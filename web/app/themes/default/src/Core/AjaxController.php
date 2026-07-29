<?php

declare(strict_types=1);

namespace Theme\Core;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;

/**
 * Base class for admin-ajax.php based AJAX endpoints.
 *
 * A child class only needs to declare an action name and implement
 * handle(). Nonce verification (CSRF protection) and JSON responses are
 * handled here, and the wp_ajax_/wp_ajax_nopriv_ hooks are wired
 * automatically thanks to the Registerable auto-discovery (see
 * Theme\Core\Theme::boot()).
 *
 * @see \Theme\Ajax\ContactFormAjax for a full example.
 */
abstract class AjaxController implements Registerable
{
	/**
	 * WordPress nonce action shared by every AJAX endpoint. The matching
	 * nonce is localized to the frontend in Theme\Services\AssetManager
	 * as `window.themeAjax.nonce`.
	 */
	public const NONCE_ACTION = 'theme_ajax';
	public const NONCE_FIELD = 'nonce';

	/**
	 * The `action` request parameter used to route to this endpoint
	 * (i.e. the suffix of `wp_ajax_{action}`).
	 */
	abstract protected function action(): string;

	/**
	 * Whether logged-out visitors can call this endpoint. Wires
	 * wp_ajax_nopriv_{action} in addition to wp_ajax_{action} when true.
	 */
	protected function isPublic(): bool
	{
		return true;
	}

	/**
	 * Handle the request and return the response payload.
	 * Throw a Theme\Core\AjaxException to short-circuit with a JSON
	 * error response (e.g. validation failure).
	 *
	 * @return array<string, mixed>
	 */
	abstract protected function handle(): array;

	public function register(): void
	{
		add_action('wp_ajax_' . $this->action(), [$this, 'dispatch']);

		if ($this->isPublic()) {
			add_action('wp_ajax_nopriv_' . $this->action(), [$this, 'dispatch']);
		}
	}

	/**
	 * Entry point wired to the wp_ajax_* hooks. Verifies the nonce, runs
	 * handle() and always terminates the request with a JSON response.
	 */
	public function dispatch(): void
	{
		if (!$this->verifyNonce()) {
			$this->error(__('Requête invalide ou expirée, veuillez rafraîchir la page.', 'default'), 403);
		}

		try {
			$this->success($this->handle());
		} catch (AjaxException $exception) {
			$this->error($exception->getMessage(), $exception->getCode() ?: 400);
		}
	}

	private function verifyNonce(): bool
	{
		$nonce = sanitize_text_field($_REQUEST[self::NONCE_FIELD] ?? '');

		return (bool) wp_verify_nonce($nonce, self::NONCE_ACTION);
	}

	/**
	 * Sends a JSON success response and terminates the request
	 * (wp_send_json_success() always calls wp_die() internally).
	 *
	 * @param array<string, mixed> $data
	 */
	protected function success(array $data = []): void
	{
		wp_send_json_success($data);
	}

	/**
	 * Sends a JSON error response and terminates the request
	 * (wp_send_json_error() always calls wp_die() internally).
	 */
	protected function error(string $message, int $status = 400): void
	{
		wp_send_json_error(['message' => $message], $status);
	}
}
