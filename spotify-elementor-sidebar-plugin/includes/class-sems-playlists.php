<?php

if (!defined('ABSPATH')) {
    exit;
}

final class SEMS_Playlists {
    private const POST_TYPE = 'sems_playlist';
    private const MAX_COVER_SIZE = 2097152;

    public static function init(): void {
        add_action('init', [self::class, 'register_post_type']);
        add_action('admin_post_sems_create_playlist', [self::class, 'handle_create_playlist']);
    }

    public static function register_post_type(): void {
        register_post_type(
            self::POST_TYPE,
            [
                'labels' => [
                    'name' => esc_html__('Playlists', 'spotify-elementor-sidebar-menu'),
                    'singular_name' => esc_html__('Playlist', 'spotify-elementor-sidebar-menu'),
                ],
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'supports' => ['title', 'thumbnail', 'author'],
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'menu_icon' => 'dashicons-playlist-audio',
            ]
        );
    }

    public static function get_post_type(): string {
        return self::POST_TYPE;
    }

    public static function get_all_published_product_ids(): array {
        if (!function_exists('wc_get_products')) {
            return [];
        }

        $product_ids = wc_get_products(
            [
                'status' => 'publish',
                'limit' => -1,
                'return' => 'ids',
            ]
        );

        if (!is_array($product_ids)) {
            return [];
        }

        return array_values(array_unique(array_map('intval', array_filter($product_ids))));
    }

    public static function get_product_tag_options(): array {
        $terms = get_terms(
            [
                'taxonomy' => 'product_tag',
                'hide_empty' => false,
            ]
        );

        if (is_wp_error($terms) || !is_array($terms)) {
            return [];
        }

        $options = [];
        foreach ($terms as $term) {
            if (!($term instanceof WP_Term)) {
                continue;
            }

            $options[] = [
                'id' => (int) $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            ];
        }

        return $options;
    }

    public static function get_user_downloadable_product_ids(int $user_id): array {
        if ($user_id <= 0) {
            return [];
        }

        $product_ids = [];

        if (function_exists('wc_get_customer_available_downloads')) {
            $downloads = wc_get_customer_available_downloads($user_id);
            if (is_array($downloads) && !empty($downloads)) {
                foreach ($downloads as $download) {
                    if (!empty($download['product_id'])) {
                        $product_ids[] = (int) $download['product_id'];
                    }
                }
            }
        }

        if (function_exists('wc_get_orders')) {
            $orders = wc_get_orders(
                [
                    'customer_id' => $user_id,
                    'status' => ['wc-completed', 'wc-processing', 'wc-on-hold'],
                    'limit' => -1,
                    'return' => 'objects',
                ]
            );

            foreach ($orders as $order) {
                if (!is_a($order, 'WC_Order')) {
                    continue;
                }

                foreach ($order->get_items('line_item') as $item) {
                    $product = $item->get_product();
                    if (!$product || !$product->is_downloadable()) {
                        continue;
                    }

                    $product_ids[] = (int) $product->get_id();
                }
            }
        }

        $product_ids = array_values(array_unique(array_filter($product_ids)));

        return $product_ids;
    }

    public static function handle_create_playlist(): void {
        $redirect_url = self::get_redirect_url();

        if (!is_user_logged_in()) {
            self::redirect_with_status('error', esc_html__('You need to be logged in to create a playlist.', 'spotify-elementor-sidebar-menu'), $redirect_url);
        }

        if (!isset($_POST['sems_create_playlist_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sems_create_playlist_nonce'])), 'sems_create_playlist')) {
            self::redirect_with_status('error', esc_html__('Security check failed. Please try again.', 'spotify-elementor-sidebar-menu'), $redirect_url);
        }

        $playlist_name = isset($_POST['playlist_name']) ? sanitize_text_field(wp_unslash($_POST['playlist_name'])) : '';
        if ('' === trim($playlist_name)) {
            self::redirect_with_status('error', esc_html__('Please enter a playlist name.', 'spotify-elementor-sidebar-menu'), $redirect_url);
        }

        $user_id = get_current_user_id();
        $available_product_ids = self::get_all_published_product_ids();
        if (empty($available_product_ids)) {
            self::redirect_with_status('error', esc_html__('No published tracks were found.', 'spotify-elementor-sidebar-menu'), $redirect_url);
        }

        $selected_products_raw = isset($_POST['playlist_products']) && is_array($_POST['playlist_products']) ? wp_unslash($_POST['playlist_products']) : [];
        $selected_products = array_map('intval', $selected_products_raw);
        $selected_products = array_values(array_intersect($selected_products, $available_product_ids));

        if (empty($selected_products)) {
            self::redirect_with_status('error', esc_html__('Please select at least one track.', 'spotify-elementor-sidebar-menu'), $redirect_url);
        }

        $cover_attachment_id = 0;
        if (!empty($_FILES['playlist_cover']['name'])) {
            $file_size = isset($_FILES['playlist_cover']['size']) ? (int) $_FILES['playlist_cover']['size'] : 0;
            if ($file_size > self::MAX_COVER_SIZE) {
                self::redirect_with_status('error', esc_html__('Cover image must be 2MB or smaller.', 'spotify-elementor-sidebar-menu'), $redirect_url);
            }

            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';

            $cover_attachment_id = media_handle_upload('playlist_cover', 0);
            if (is_wp_error($cover_attachment_id)) {
                self::redirect_with_status('error', esc_html__('Cover upload failed. Please try another image.', 'spotify-elementor-sidebar-menu'), $redirect_url);
            }

            if (!wp_attachment_is_image($cover_attachment_id)) {
                wp_delete_attachment($cover_attachment_id, true);
                self::redirect_with_status('error', esc_html__('Cover must be an image file.', 'spotify-elementor-sidebar-menu'), $redirect_url);
            }
        }

        $playlist_id = wp_insert_post(
            [
                'post_type' => self::POST_TYPE,
                'post_status' => 'publish',
                'post_title' => $playlist_name,
                'post_author' => $user_id,
            ],
            true
        );

        if (is_wp_error($playlist_id)) {
            if ($cover_attachment_id > 0) {
                wp_delete_attachment($cover_attachment_id, true);
            }

            self::redirect_with_status('error', esc_html__('Unable to save playlist right now.', 'spotify-elementor-sidebar-menu'), $redirect_url);
        }

        update_post_meta($playlist_id, '_sems_playlist_products', $selected_products);

        if ($cover_attachment_id > 0) {
            set_post_thumbnail($playlist_id, $cover_attachment_id);
        }

        self::redirect_with_status('success', esc_html__('Playlist saved successfully.', 'spotify-elementor-sidebar-menu'), $redirect_url);
    }

    private static function get_redirect_url(): string {
        if (!empty($_POST['sems_return_url'])) {
            return esc_url_raw(wp_unslash($_POST['sems_return_url']));
        }

        $referer = wp_get_referer();

        if ($referer) {
            return $referer;
        }

        return home_url('/');
    }

    private static function redirect_with_status(string $status, string $message, string $redirect_url): void {
        $url = add_query_arg(
            [
                'sems_playlist_status' => $status,
                'sems_playlist_message' => rawurlencode($message),
            ],
            $redirect_url
        );

        wp_safe_redirect($url);
        exit;
    }
}
