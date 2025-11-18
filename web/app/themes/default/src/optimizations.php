<?php defined('ABSPATH') || die();

add_action('wp_scheduled_delete', function () {
    global $wpdb;

    try {
        // Optimization of main tables
        $tables_to_optimize = ['posts', 'postmeta', 'options', 'comments'];
        foreach ($tables_to_optimize as $table) {
            $wpdb->query("OPTIMIZE TABLE {$wpdb->$table}");
        }

        // Deleting old revisions
        $days = defined('WP_REVISION_DAYS') ? WP_REVISION_DAYS : 30;
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL %d DAY)",
            $days
        ));

        // Deleting expired transient options
        if (function_exists('delete_expired_transients')) {
            delete_expired_transients();
        } else {
            $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
            $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
        }

        // Deleting spam comments
        $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");

        // Logging success
        error_log('WordPress database optimization successfully completed.');
    } catch (Exception $e) {
        // Logging errors
        error_log('Error during WordPress database optimization: '.$e->getMessage());
    }
});
