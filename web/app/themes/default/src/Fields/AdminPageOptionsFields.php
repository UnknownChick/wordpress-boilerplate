<?php

declare(strict_types=1);

namespace Theme\Fields;

defined('ABSPATH') || die();

use Extended\ACF\Fields\Email;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\URL;
use Extended\ACF\Location;
use Theme\Attributes\Condition;
use Theme\Attributes\OnHook;
use Theme\Contracts\Registerable;

#[Condition('is_admin')]
#[OnHook('after_setup_theme')]
class AdminPageOptionsFields implements Registerable
{
	public function register(): void
	{
		add_action('acf/init', function () {
			if (!function_exists('acf_add_options_page')) return;

			acf_add_options_page([
				'page_title'    => __('Options du site', 'default'),
				'menu_title'    => __('Options du site', 'default'),
				'menu_slug'     => 'options',
				'capability'    => 'edit_posts',
				'redirect'      => false,
				'icon_url'      => 'dashicons-admin-generic',
				'position'      => 2
			]);
		});

		add_action('acf/include_fields', function() {
			register_extended_field_group([
				'title' => __('Options du site', 'default'),
				'key' => 'site_options',
				'style' => 'seamless',
				'fields' => [
					Tab::make(__('Informations de contact', 'default'), 'contact_info_tab'),
						Text::make(__('Nom de contact', 'default'), 'contact_name')
							->placeholder(__('Nom de la personne à contacter', 'default')),

						Text::make(__('Téléphone', 'default'), 'phone')
							->placeholder(__('Numéro de téléphone pour vous contacter', 'default'))
							->maxLength(14),

						Email::make(__('Email', 'default'), 'email')
							->placeholder(__('Adresse email pour vous contacter', 'default')),

						Textarea::make(__('Adresse', 'default'), 'address')
							->placeholder(__('Adresse physique de votre entreprise', 'default')),

						URL::make(__('Instagram', 'default'), 'instagram')
							->placeholder(__('Lien vers votre page Instagram', 'default')),
						URL::make(__('Facebook', 'default'), 'facebook')
							->placeholder(__('Lien vers votre page Facebook', 'default')),
						URL::make(__('LinkedIn', 'default'), 'linkedin')
							->placeholder(__('Lien vers votre page LinkedIn', 'default')),
					Tab::make(__('Réglages des emails', 'default'), 'email_settings_tab'),
						Repeater::make(__('Destinataires des emails', 'default'), 'email_receivers')
						->fields([
							Email::make(__('Emails', 'default'), 'email')
								->placeholder(__('Adresse email pour recevoir les messages du formulaire de contact', 'default')),
						])
						->minRows(1)
						->helperText(__('Vous pouvez ajouter plusieurs destinataires pour recevoir les messages du formulaire de contact.', 'default'))
						->button(__('Ajouter un destinataire', 'default'))
						->layout('table'),
				],
				'location' => [
					Location::where('options_page', 'options'),
				],
			]);
		});
	}
}
