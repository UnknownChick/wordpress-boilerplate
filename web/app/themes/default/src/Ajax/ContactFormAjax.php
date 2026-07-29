<?php

declare(strict_types=1);

namespace Theme\Ajax;

defined('ABSPATH') || die();

use Theme\Core\AjaxController;
use Theme\Core\AjaxException;
use Theme\DTO\ContactFormDTO;
use Theme\Repositories\SiteOptionsRepository;
use Theme\Services\Cache;

/**
 * Handles the contact form submission (see views/pages/contact.twig and
 * assets/js/features/contact-form.js).
 *
 * JS POSTs to admin-ajax.php?action=contact_form with a `nonce` field;
 * the SMTP transport itself is configured globally in
 * Theme\Services\MailService, so a plain wp_mail() call is enough here.
 */
class ContactFormAjax extends AjaxController
{
	private const RATE_LIMIT_TTL = MINUTE_IN_SECONDS;
	private const RATE_LIMIT_MAX = 3;

	public function __construct(
		private SiteOptionsRepository $siteOptions,
		private Cache $cache,
	) {
	}

	protected function action(): string
	{
		return 'contact_form';
	}

	protected function handle(): array
	{
		$this->guardRateLimit();

		$dto = ContactFormDTO::fromRequest($_POST);
		$errors = $dto->validate();

		if (!empty($errors)) {
			throw new AjaxException(reset($errors), 422);
		}

		$this->send($dto);

		return [
			'message' => __('Votre message a bien été envoyé, merci !', 'default'),
		];
	}

	private function send(ContactFormDTO $dto): void
	{
		$recipients = $this->siteOptions->get()->emailReceivers ?: [get_option('admin_email')];

		$subject = sprintf(__('[%s] Nouveau message de contact', 'default'), get_bloginfo('name'));

		$body = sprintf(
			"Nom : %s\nEmail : %s\nTéléphone : %s\n\nMessage :\n%s",
			$dto->name,
			$dto->email,
			$dto->phone ?: '—',
			$dto->message
		);

		$sent = wp_mail(
			$recipients,
			$subject,
			$body,
			['Reply-To: ' . $dto->name . ' <' . $dto->email . '>']
		);

		if (!$sent) {
			throw new AjaxException(__("L'envoi du message a échoué, veuillez réessayer plus tard.", 'default'), 500);
		}
	}

	/**
	 * Basic IP-based throttling to slow down spam/bots hitting the endpoint.
	 */
	private function guardRateLimit(): void
	{
		$ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
		$key = 'contact_form_rate_' . md5($ip);

		$attempts = (int) $this->cache->remember($key, self::RATE_LIMIT_TTL, static fn () => 0);

		if ($attempts >= self::RATE_LIMIT_MAX) {
			throw new AjaxException(__('Trop de tentatives, veuillez patienter avant de réessayer.', 'default'), 429);
		}

		$this->cache->forget($key);
		$this->cache->remember($key, self::RATE_LIMIT_TTL, static fn () => $attempts + 1);
	}
}
