<?php

declare(strict_types=1);

namespace Theme\DTO;

defined('ABSPATH') || die();

/**
 * Immutable, typed representation of the "Options du site" ACF options
 * page (see Theme\Fields\AdminPageOptionsFields).
 *
 * Fetched and cached by Theme\Repositories\SiteOptionsRepository, then
 * exposed globally in Twig as `site_options` (see Theme\Services\TimberSetup).
 * Prefer this over scattering raw get_field('x', 'options') calls
 * throughout controllers and views.
 */
final class SiteOptionsDTO
{
	/**
	 * @param array<int, string> $emailReceivers
	 */
	public function __construct(
		public readonly string $contactName = '',
		public readonly string $phone = '',
		public readonly string $email = '',
		public readonly string $address = '',
		public readonly string $instagram = '',
		public readonly string $facebook = '',
		public readonly string $linkedin = '',
	) {
	}

	/**
	 * Build the DTO from raw ACF field values.
	 *
	 * @param array<string, mixed> $fields
	 */
	public static function fromFields(array $fields): self
	{
		return new self(
			contactName: (string) ($fields['contact_name'] ?? ''),
			phone: (string) ($fields['phone'] ?? ''),
			email: (string) ($fields['email'] ?? ''),
			address: (string) ($fields['address'] ?? ''),
			instagram: (string) ($fields['instagram'] ?? ''),
			facebook: (string) ($fields['facebook'] ?? ''),
			linkedin: (string) ($fields['linkedin'] ?? '')
		);
	}
}
