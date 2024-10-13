<?php
/*
Plugin Name: Custom Dashboard Style
Description: Personnalise le style du tableau de bord WordPress
Version: 1.0
Author: Votre Nom
*/

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}

// Fonction pour enregistrer et charger le CSS personnalisé
function custom_dashboard_style() {
    wp_enqueue_style('custom-dashboard-style', plugins_url('css/dashboard-style.css', __FILE__));
}

// Ajouter l'action pour charger le CSS dans l'interface d'administration
add_action('admin_enqueue_scripts', 'custom_dashboard_style');