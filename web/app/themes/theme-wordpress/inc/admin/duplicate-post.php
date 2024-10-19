<?php defined('ABSPATH') || die();

define('DUPLICATE_NONCE', basename(__FILE__));
const RD_DUPLICATE_ACTION = 'duplicate_post_as_draft';
const RD_DUPLICATE_SUCCESS = 'post_duplication_created';

/**
 * Adds a "Duplicate" link to the post row actions.
 *
 * @param array $actions The existing actions.
 * @param WP_Post $post The current post object.
 * @return array The modified actions.
 */
add_filter('post_row_actions', function (array $actions, WP_Post $post): array {
    if (!current_user_can('edit_posts')) {
        return $actions;
    }

    $url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => RD_DUPLICATE_ACTION,
                'post' => $post->ID,
            ),
            'admin.php'
        ),
        DUPLICATE_NONCE,
        'duplicate_nonce'
    );

    $actions['duplicate'] = '<a href="'.$url.'" title="Dupliquer" rel="permalink">Dupliquer</a>';

    return $actions;
}, 10, 2);

/**
 * Handles the duplication of a post.
 *
 * @return void
 */
add_action('admin_action_'.RD_DUPLICATE_ACTION, function (): void {
    if (empty($_GET['post'])) {
        wp_die('Aucun article à dupliquer n\'a été fourni!');
    }

    if (!current_user_can('edit_posts')) {
        wp_die('Vous n\'êtes pas autorisé à dupliquer cet article.');
    }

    if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], DUPLICATE_NONCE)) {
        return;
    }

    $post_id = absint($_GET['post']);
    $post = get_post($post_id);
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    if ($post) {
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status' => $post->ping_status,
            'post_author' => $new_post_author,
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_name' => $post->post_name,
            'post_parent' => $post->post_parent,
            'post_password' => $post->post_password,
            'post_status' => 'draft',
            'post_title' => $post->post_title,
            'post_type' => $post->post_type,
            'to_ping' => $post->to_ping,
            'menu_order' => $post->menu_order
        );

        $new_post_id = wp_insert_post($args);

        if (is_wp_error($new_post_id)) {
            wp_die('La création de l\'article a échoué.');
        }

        $taxonomies = get_object_taxonomies(get_post_type($post));
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy);
            }
        }

        $post_meta = get_post_meta($post_id);
        if ($post_meta) {
            foreach ($post_meta as $meta_key => $meta_values) {
                if ('_wp_old_slug' == $meta_key) {
                    continue;
                }

                foreach ($meta_values as $meta_value) {
                    add_post_meta($new_post_id, $meta_key, $meta_value);
                }
            }
        }

        wp_safe_redirect(
            add_query_arg(
                array(
                    'post_type' => ('post' !== get_post_type($post) ? get_post_type($post) : false),
                    'saved' => RD_DUPLICATE_SUCCESS
                ),
                admin_url('edit.php')
            )
        );
        exit;
    } else {
        wp_die('La création de l\'article a échoué, impossible de trouver l\'article original.');
    }
});

/**
 * Displays a success notice when the post has been duplicated.
 *
 * @return void
 */
add_action('admin_notices', function (): void {
    $screen = get_current_screen();

    if ('edit' !== $screen->base) {
        return;
    }

    if (isset($_GET['saved']) && RD_DUPLICATE_SUCCESS == $_GET['saved']) {
        echo '<div class="notice notice-success is-dismissible"><p>Copie de l\'article créée.</p></div>';
    }
});
