<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Product_Save_Analyzer_Hook {

    public static function init(): void {
        add_action('save_post_product', [__CLASS__, 'handle_product_save'], 20, 3);
    }

    public static function handle_product_save($post_id, $post, $update): void {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (wp_is_post_revision($post_id)) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $product = wc_get_product($post_id);
        if (!$product instanceof \WC_Product) return;
        if (!$product->is_downloadable()) return;

        $audio_url = Product_Audio_Helper::get_audio_url($product);
        if (!$audio_url) return;

        $version       = Admin_Settings_Page::get_analysis_version();
        $new_checksum  = Product_Audio_Helper::build_checksum($audio_url, $version);
        $old_checksum  = (string) $product->get_meta('_aim_audio_checksum', true);
        $old_version   = (string) $product->get_meta('_aim_analysis_version', true);

        $needs_analysis = (!$old_checksum || $old_checksum !== $new_checksum || $old_version !== $version);

        if (!$needs_analysis) {
            return;
        }

        $product->update_meta_data('_aim_audio_source_url', $audio_url);
        $product->update_meta_data('_aim_audio_source_type', Product_Audio_Helper::detect_source_type($product));
        $product->update_meta_data('_aim_audio_checksum', $new_checksum);
        $product->update_meta_data('_aim_analysis_status', 'pending');
        $product->update_meta_data('_aim_analysis_error', '');
        $product->save();

        Audio_Analysis_Queue::enqueue_product($post_id);
    }
}
