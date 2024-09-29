<?php defined('ABSPATH') || die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {
    Container::make('theme_options', __('Options du site'))
        ->set_page_file('options')
        ->set_icon('dashicons-admin-generic')
        ->set_page_menu_title('Options du site')
        ->add_tab('Informations de contact', [
            Field::make('text', 'global_phone', __('Téléphone'))
                ->set_attribute('type', 'tel'),
            Field::make('text', 'global_email', __('Email'))
                ->set_attribute('type', 'email'),
            Field::make('textarea', 'global_address', __('Adresse')),
            Field::make('text', 'global_facebook', __('Facebook'))
                ->set_attribute('type', 'url'),
            Field::make('text', 'global_instagram', __('Instagram'))
                ->set_attribute('type', 'url'),
            Field::make('text', 'global_linkedin', __('LinkedIn'))
                ->set_attribute('type', 'url'),
        ])
        ->add_tab('Emails', [
            Field::make('text', 'global_receiver_email', __('Reception des emails'))
                ->set_attribute('placeholder', 'contact@exemple.fr')
                ->set_attribute('type', 'email'),
        ]);
});
