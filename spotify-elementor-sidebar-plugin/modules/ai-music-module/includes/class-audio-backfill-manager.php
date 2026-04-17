<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Audio_Backfill_Manager {

    public static function enqueue_all(array $args = []): int {
        $defaults = [
            'limit'   => 500,
            'version' => Admin_Settings_Page::get_analysis_version(),
            'force'   => false,
        ];
        $args = wp_parse_args($args, $defaults);

        $products = wc_get_products([
            'status' => 'publish',
            'limit'  => (int) $args['limit'],
            'return' => 'objects',
            'type'   => ['simple', 'variable', 'variation'],
        ]);

        $count = 0;

        foreach ($products as $product) {
            if (!$product instanceof \WC_Product) continue;
            if (!$product->is_downloadable()) continue;

            $audio_url = Product_Audio_Helper::get_audio_url($product);
            if (!$audio_url) continue;

            $status  = (string) $product->get_meta('_aim_analysis_status', true);
            $version = (string) $product->get_meta('_aim_analysis_version', true);

            $should_enqueue = (bool) $args['force'];

            if (!$should_enqueue) {
                $should_enqueue = !($status === 'done' && $version === $args['version']);
            }

            if (!$should_enqueue) continue;

            $checksum = Product_Audio_Helper::build_checksum($audio_url, (string) $args['version']);

            $product->update_meta_data('_aim_audio_source_url', $audio_url);
            $product->update_meta_data('_aim_audio_source_type', Product_Audio_Helper::detect_source_type($product));
            $product->update_meta_data('_aim_audio_checksum', $checksum);
            $product->update_meta_data('_aim_analysis_status', 'pending');
            $product->update_meta_data('_aim_analysis_error', '');
            $product->save();

            Audio_Analysis_Queue::enqueue_product($product->get_id(), (bool) $args['force']);
            $count++;
        }

        return $count;
    }
}
