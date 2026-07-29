<?php

declare(strict_types=1);

namespace Theme\DTO;

defined('ABSPATH') || die();

/**
 * Data Transfer Object for the contact form submission.
 *
 * Never trust raw request superglobals in a controller/AJAX handler:
 * always pass them through a DTO first so sanitization and validation
 * live in one obvious place instead of being scattered around.
 *
 * @see \Theme\Ajax\ContactFormAjax
 */
final class ContactFormDTO
{
	public function __construct(
		public readonly string $name,
		public readonly string $email,
		public readonly string $phone,
		public readonly string $message,
		public readonly string $honeypot = '',
	) {
	}

	/**
	 * Build the DTO from a raw request payload (e.g. $_POST),
	 * sanitizing every field along the way.
	 *
	 * @param array<string, mixed> $data
	 */
	public static function fromRequest(array $data): self
	{
		return new self(
			name: sanitize_text_field($data['name'] ?? ''),
			email: sanitize_email($data['email'] ?? ''),
			phone: sanitize_text_field($data['phone'] ?? ''),
			message: sanitize_textarea_field($data['message'] ?? ''),
			// Honeypot: a hidden field real visitors never fill in.
			honeypot: sanitize_text_field($data['website'] ?? ''),
		);
	}

	/**
	 * @return array<string, string> Field => error message. Empty when valid.
	 */
	public function validate(): array
	{
		$errors = [];

		if ($this->honeypot !== '') {
			// Spam bots fill every field: reject without leaking the reason.
			$errors['honeypot'] = __('Une erreur est survenue.', 'default');

			return $errors;
		}

		if ($this->name === '') {
			$errors['name'] = __('Le nom est requis.', 'default');
		}

		if ($this->email === '' || !is_email($this->email)) {
			$errors['email'] = __('Une adresse email valide est requise.', 'default');
		}

		if ($this->message === '') {
			$errors['message'] = __('Le message est requis.', 'default');
		} elseif (mb_strlen($this->message) > 5000) {
			$errors['message'] = __('Le message est trop long (5000 caractères maximum).', 'default');
		}

		return $errors;
	}

	public function isValid(): bool
	{
		return empty($this->validate());
	}
}
