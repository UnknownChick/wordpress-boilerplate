<?php

namespace Theme\Services;

defined('ABSPATH') || die();

use Exception;
use Theme\Contracts\Registerable;

class DatabaseOptimizer implements Registerable
{
	private const DEFAULT_REVISION_DAYS = 30;

	public function register(): void
	{
		add_action('wp_scheduled_delete', [$this, 'optimize']);
	}

	public function optimize(): void
	{
		global $wpdb;

		try {
			$this->optimizeTables($wpdb);
			$this->deleteOldRevisions($wpdb);
			$this->deleteExpiredTransients($wpdb);
			$this->deleteSpamComments($wpdb);

			error_log('WordPress database optimization successfully completed.');
		} catch (Exception $e) {
			error_log('Error during WordPress database optimization: ' . $e->getMessage());
		}
	}

	private function optimizeTables(\wpdb $wpdb): void
	{
		$tables = ['posts', 'postmeta', 'options', 'comments'];

		foreach ($tables as $table) {
			$wpdb->query("OPTIMIZE TABLE {$wpdb->$table}");
		}
	}

	private function deleteOldRevisions(\wpdb $wpdb): void
	{
		$days = self::DEFAULT_REVISION_DAYS;

		$wpdb->query($wpdb->prepare(
			"DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL %d DAY)",
			$days
		));
	}

	private function deleteExpiredTransients(\wpdb $wpdb): void
	{
		if (function_exists('delete_expired_transients')) {
			delete_expired_transients();
			return;
		}

		$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
		$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
	}

	private function deleteSpamComments(\wpdb $wpdb): void
	{
		$wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
	}
}
