<?php defined('ABSPATH') || die();

add_action('acf/init', function () {
	if (function_exists('acf_add_options_page')) {
		acf_add_options_page([
			'page_title'    => __('Options du site', 'justeuncroc'),
			'menu_title'    => __('Options du site', 'justeuncroc'),
			'menu_slug'     => 'options',
			'capability'    => 'edit_posts',
			'redirect'      => false,
			'icon_url'      => 'dashicons-admin-generic',
			'position'      => 2
		]);
	}

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group([
			'key' => 'contact_info',
			'title' => __('Informations de contact', 'justeuncroc'),
			'fields' => [
				[
					'key' => 'field_phone',
					'label' => __('Téléphone', 'justeuncroc'),
					'name' => 'phone',
					'type' => 'text',
					'instructions' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
				],
				[
					'key' => 'field_email',
					'label' => __('Email', 'justeuncroc'),
					'name' => 'email',
					'type' => 'email',
					'instructions' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
				],
				[
					'key' => 'field_address',
					'label' => __('Adresse', 'justeuncroc'),
					'name' => 'address',
					'type' => 'textarea',
					'instructions' => '',
					'placeholder' => '',
				],
				[
					'key' => 'field_facebook',
					'label' => __('Facebook', 'justeuncroc'),
					'name' => 'facebook',
					'type' => 'url',
				],
				[
					'key' => 'field_instagram',
					'label' => __('Instagram', 'justeuncroc'),
					'name' => 'instagram',
					'type' => 'url',
				],
				[
					'key' => 'field_tiktok',
					'label' => __('TikTok', 'justeuncroc'),
					'name' => 'tiktok',
					'type' => 'url',
				],
			],
			'location' => [
				[
					[
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'options',
					],
				],
			],
		]);

		acf_add_local_field_group([
			'key' => 'group_global_emails',
			'title' => __('Réglages des emails', 'justeuncroc'),
			'fields' => [
				[
					'key' => 'field_emails_receivers',
					'label' => __('Réception des emails', 'justeuncroc'),
					'name' => 'emails_receivers',
					'type' => 'repeater',
					'layout' => 'row',
					'sub_fields' => [
						[
							'key' => 'field_receiver_email',
							'label' => __('Email', 'justeuncroc'),
							'name' => 'receiver_email',
							'type' => 'email',
							'placeholder' => 'contact@exemple.fr',
						],
					],
				],
			],
			'location' => [
				[
					[
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'options',
					],
				],
			],
		]);
	}
});
