<?php

namespace Theme\Fields;

use Extended\ACF\Fields\Email;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\URL;
use Extended\ACF\Location;
use Theme\Contracts\Registerable;

defined('ABSPATH') || die();

class AdminPageOptionsFields implements Registerable
{
	public function register(): void
	{
		add_action('acf/init', function () {
			if (!function_exists('acf_add_options_page')) return;

			acf_add_options_page([
				'page_title'    => __('Options du site', 'nkdesign'),
				'menu_title'    => __('Options du site', 'nkdesign'),
				'menu_slug'     => 'options',
				'capability'    => 'edit_posts',
				'redirect'      => false,
				'icon_url'      => 'dashicons-admin-generic',
				'position'      => 2
			]);
		});

		add_action('acf/include_fields', function() {
			register_extended_field_group([
				'title' => __('Informations de contact', 'nkdesign'),
				'key' => 'contact_info',
				'style' => 'default',
				'fields' => [
					Text::make(__('Nom de contact', 'nkdesign'), 'contact_name')
						->placeholder(__('Nom de la personne à contacter', 'nkdesign')),

					Text::make(__('Téléphone', 'nkdesign'), 'phone')
						->placeholder(__('Numéro de téléphone pour vous contacter', 'nkdesign'))
						->maxLength(14),

					Email::make(__('Email', 'nkdesign'), 'email')
						->placeholder(__('Adresse email pour vous contacter', 'nkdesign')),

					Textarea::make(__('Adresse', 'nkdesign'), 'address')
						->placeholder(__('Adresse physique de votre entreprise', 'nkdesign')),

					URL::make(__('Instagram', 'nkdesign'), 'instagram')
						->placeholder(__('Lien vers votre page Instagram', 'nkdesign')),
					URL::make(__('Facebook', 'nkdesign'), 'facebook')
						->placeholder(__('Lien vers votre page Facebook', 'nkdesign')),
					URL::make(__('LinkedIn', 'nkdesign'), 'linkedin')
						->placeholder(__('Lien vers votre page LinkedIn', 'nkdesign')),
				],
				'location' => [
					Location::where('options_page', 'options'),
				],
			]);

			register_extended_field_group([
				'title' => __('Réglages des emails', 'nkdesign'),
				'key' => 'email_receivers',
				'style' => 'default',
				'fields' => [
					Repeater::make(__('Destinataires des emails', 'nkdesign'), 'email_receivers')
						->fields([
							Email::make(__('Emails', 'nkdesign'), 'email')
								->placeholder(__('Adresse email pour recevoir les messages du formulaire de contact', 'nkdesign')),
						])
						->minRows(1)
						->helperText(__('Vous pouvez ajouter plusieurs destinataires pour recevoir les messages du formulaire de contact.', 'nkdesign'))
						->button(__('Ajouter un destinataire', 'nkdesign'))
						->layout('table'),
				],
				'location' => [
					Location::where('options_page', 'options'),
				],
			]);
		});
	}
}
