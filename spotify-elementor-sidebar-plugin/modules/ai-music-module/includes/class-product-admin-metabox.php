<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Product_Admin_Metabox {

    public static function init(): void {
        add_action('add_meta_boxes', [__CLASS__, 'register_metabox']);
        add_action('admin_post_aim_manual_analyze_product', [__CLASS__, 'handle_manual_analyze']);
    }

    public static function register_metabox(): void {
        add_meta_box(
            'aim_music_analysis_box',
            'AI Music Analysis',
            [__CLASS__, 'render_metabox'],
            'product',
            'side',
            'high'
        );
    }

    public static function render_metabox($post): void {
        if (!current_user_can('edit_post', $post->ID)) {
            return;
        }

        $product = wc_get_product($post->ID);
        if (!$product instanceof \WC_Product) {
            echo '<p>Invalid product.</p>';
            return;
        }

        $status = (string) $product->get_meta('_aim_analysis_status', true);
        $error  = (string) $product->get_meta('_aim_analysis_error', true);
        $model  = (string) $product->get_meta('_aim_analysis_model', true);
        $updated= (string) $product->get_meta('_aim_analysis_updated_at', true);
        $audio  = Product_Audio_Helper::get_audio_url($product);

        echo '<p><strong>Status:</strong> ' . esc_html($status ?: 'not analyzed') . '</p>';
        echo '<p><strong>Model:</strong> ' . esc_html($model ?: '-') . '</p>';
        echo '<p><strong>Updated:</strong> ' . esc_html($updated ?: '-') . '</p>';

        if ($error) {
            echo '<p style="color:#b32d2e;"><strong>Error:</strong> ' . esc_html($error) . '</p>';
        }

        if (!$audio) {
            echo '<p>No supported mp3/wav audio source found.</p>';
            return;
        }

        $url = wp_nonce_url(
            admin_url('admin-post.php?action=aim_manual_analyze_product&product_id=' . $post->ID),
            'aim_manual_analyze_' . $post->ID
        );

        $force_url = wp_nonce_url(
            admin_url('admin-post.php?action=aim_manual_analyze_product&product_id=' . $post->ID . '&force=1'),
            'aim_manual_analyze_' . $post->ID
        );

        echo '<p><a class="button button-primary" href="' . esc_url($url) . '">Analyze now</a></p>';
        echo '<p><a class="button" href="' . esc_url($force_url) . '">Force reanalyze</a></p>';

        $logs = Analysis_Logger::get_recent_logs($post->ID, 5);
        if (!empty($logs)) {
            echo '<hr><p><strong>Recent logs</strong></p><ul style="max-height:160px;overflow:auto;">';
            foreach ($logs as $log) {
                echo '<li style="margin-bottom:8px;">';
                echo '<strong>' . esc_html($log['level']) . '</strong> - ';
                echo esc_html($log['message']) . '<br>';
                echo '<small>' . esc_html($log['created_at']) . '</small>';
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    public static function handle_manual_analyze(): void {
        $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
        $force = !empty($_GET['force']);

        if (!$product_id || !current_user_can('edit_post', $product_id)) {
            wp_die('Permission denied');
        }

        check_admin_referer('aim_manual_analyze_' . $product_id);

        $product = wc_get_product($product_id);
        if (!$product instanceof \WC_Product) {
            wp_die('Invalid product');
        }

        $audio_url = Product_Audio_Helper::get_audio_url($product);
        if (!$audio_url) {
            wp_safe_redirect(add_query_arg([
                'post' => $product_id,
                'action' => 'edit',
                'aim_notice' => 'no_audio',
            ], admin_url('post.php')));
            exit;
        }

        $version = Admin_Settings_Page::get_analysis_version();
        $checksum = Product_Audio_Helper::build_checksum($audio_url, $version);

        if ($force) {
            $product->update_meta_data('_aim_analysis_status', 'pending');
            $product->update_meta_data('_aim_analysis_error', '');
            $product->update_meta_data('_aim_audio_checksum', $checksum);
            $product->update_meta_data('_aim_audio_source_url', $audio_url);
            $product->update_meta_data('_aim_audio_source_type', Product_Audio_Helper::detect_source_type($product));
            $product->save();
        }

        Audio_Analysis_Queue::enqueue_product($product_id, $force);

        wp_safe_redirect(add_query_arg([
            'post' => $product_id,
            'action' => 'edit',
            'aim_notice' => 'queued',
        ], admin_url('post.php')));
        exit;
    }
}
