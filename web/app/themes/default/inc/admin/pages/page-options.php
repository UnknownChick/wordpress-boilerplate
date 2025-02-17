<?php defined('ABSPATH') || die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {
    Container::make('theme_options', __('Options du site'))
        ->set_page_file('options')
        ->set_icon('dashicons-admin-generic')
        ->set_page_menu_title('Options du site')
        ->add_tab('Informations de contact', [
            Field::make('complex', 'global_contact_info', __('Contacts'))
                ->add_fields([
                    Field::make('text', 'global_phone', __('Téléphone'))
                        ->set_attribute('type', 'tel'),
                    Field::make('text', 'global_email', __('Email'))
                        ->set_attribute('type', 'email'),
                    Field::make('textarea', 'global_address', __('Adresse')),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_header_template('Contact <%- $_index + 1 %>')
                ->setup_labels([
                    'plural_name' => 'Informations de contact',
                    'singular_name' => 'Information de contact',
                ]),
            Field::make('text', 'global_facebook', __('Facebook'))
                ->set_attribute('type', 'url'),
            Field::make('text', 'global_instagram', __('Instagram'))
                ->set_attribute('type', 'url'),
            Field::make('text', 'global_linkedin', __('LinkedIn'))
                ->set_attribute('type', 'url'),
        ])
        ->add_tab('Emails', [
            Field::make('complex', 'global_emails', __('Emails'))
                ->add_fields([
                    Field::make('text', 'global_receiver_email', __('Reception des emails'))
                        ->set_attribute('placeholder', 'contact@exemple.fr')
                        ->set_attribute('type', 'email')
                ])
                ->set_layout('tabbed-horizontal')
                ->set_header_template('<%- global_receiver_email %>')
                ->setup_labels([
                    'plural_name' => 'Emails',
                    'singular_name' => 'Email',
                ]),
        ]);
});
