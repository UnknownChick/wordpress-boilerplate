<?php defined('ABSPATH') || die();

// Hide the login error message
add_filter('login_errors', function () {
    return 'Une erreur est survenue lors de la connexion.';
});

// Remove authors in the REST API
add_filter('rest_endpoints', function ($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Add security headers
add_action('send_headers', function () {
    header_remove('X-Powered-By');
    header_remove('X-Pingback');

    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
});

// Protect against SQL injection
function cleanup_query($query) {
    global $wpdb;
    return $wpdb->prepare($query);
}

add_filter('session_name', function () {
    return 'wp_' . hash('sha256', AUTH_KEY . SECURE_AUTH_KEY);
});