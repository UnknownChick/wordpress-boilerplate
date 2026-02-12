<?php

namespace Theme\Admin;

defined('ABSPATH') || die();

use Theme\Contracts\Registerable;

class DuplicatePost implements Registerable
{
	private const NONCE_ACTION  = 'duplicate_post_nonce';
	private const NONCE_KEY     = 'duplicate_nonce';
	private const ACTION        = 'duplicate_post_as_draft';
	private const SUCCESS_PARAM = 'post_duplication_created';

	public function register(): void
	{
		add_filter('post_row_actions', [$this, 'addRowAction'], 10, 2);
		add_action('admin_action_' . self::ACTION, [$this, 'handle']);
		add_action('admin_notices', [$this, 'notice']);
	}

	public function addRowAction(array $actions, \WP_Post $post): array
	{
		if (!current_user_can('edit_posts')) {
			return $actions;
		}

		$url = wp_nonce_url(
			add_query_arg([
				'action' => self::ACTION,
				'post'   => $post->ID,
			], 'admin.php'),
			self::NONCE_ACTION,
			self::NONCE_KEY
		);

		$actions['duplicate'] = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url($url),
			esc_attr__('Dupliquer cet article', 'theme'),
			esc_html__('Dupliquer', 'theme')
		);

		return $actions;
	}

	public function handle(): void
	{
		if (empty($_GET['post'])) {
			wp_die(__("Aucun article à dupliquer n'a été fourni.", 'theme'));
		}

		if (!current_user_can('edit_posts')) {
			wp_die(__("Vous n'êtes pas autorisé à dupliquer cet article.", 'theme'));
		}

		if (!isset($_GET[self::NONCE_KEY]) || !wp_verify_nonce($_GET[self::NONCE_KEY], self::NONCE_ACTION)) {
			wp_die(__('Nonce invalide.', 'theme'));
		}

		$postId = absint($_GET['post']);
		$post   = get_post($postId);

		if (!$post) {
			wp_die(__("Impossible de trouver l'article original.", 'theme'));
		}

		$newPostId = wp_insert_post([
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => wp_get_current_user()->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		]);

		if (is_wp_error($newPostId)) {
			wp_die(__("La création de l'article a échoué.", 'theme'));
		}

		$this->copyTaxonomies($postId, $newPostId, $post);
		$this->copyMeta($postId, $newPostId);

		wp_safe_redirect(add_query_arg([
			'post_type' => get_post_type($post) !== 'post' ? get_post_type($post) : false,
			'saved'     => self::SUCCESS_PARAM,
		], admin_url('edit.php')));

		exit;
	}

	public function notice(): void
	{
		if (get_current_screen()->base !== 'edit') {
			return;
		}

		if (isset($_GET['saved']) && $_GET['saved'] === self::SUCCESS_PARAM) {
			echo '<div class="notice notice-success is-dismissible"><p>'
				. esc_html__("Copie de l'article créée.", 'theme')
				. '</p></div>';
		}
	}

	private function copyTaxonomies(int $postId, int $newPostId, \WP_Post $post): void
	{
		$taxonomies = get_object_taxonomies(get_post_type($post));

		foreach ($taxonomies as $taxonomy) {
			$terms = wp_get_object_terms($postId, $taxonomy, ['fields' => 'slugs']);
			wp_set_object_terms($newPostId, $terms, $taxonomy);
		}
	}

	private function copyMeta(int $postId, int $newPostId): void
	{
		$metas = get_post_meta($postId);

		foreach ($metas as $key => $values) {
			if ($key === '_wp_old_slug') {
				continue;
			}

			foreach ($values as $value) {
				add_post_meta($newPostId, $key, $value);
			}
		}
	}
}
