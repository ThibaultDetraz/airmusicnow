<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Product_Meta_Registry {

    public static function init(): void {
        add_action('init', [__CLASS__, 'register_meta']);
    }

    public static function register_meta(): void {
        $single_string = [
            '_aim_audio_source_url',
            '_aim_audio_source_type',
            '_aim_audio_checksum',
            '_aim_audio_mime',

            '_aim_ai_energy_label',
            '_aim_ai_tempo_label',
            '_aim_ai_summary',

            '_aim_analysis_status',
            '_aim_analysis_provider',
            '_aim_analysis_model',
            '_aim_analysis_version',
            '_aim_analysis_updated_at',
            '_aim_analysis_error',

            '_aim_embedding',
            '_aim_embedding_model',

            '_aim_last_retry_at',
        ];

        foreach ($single_string as $meta_key) {
            register_post_meta('product', $meta_key, [
                'type'         => 'string',
                'single'       => true,
                'show_in_rest' => true,
                'auth_callback'=> [__CLASS__, 'can_edit_products'],
            ]);
        }

        $single_number = [
            '_aim_audio_duration',
            '_aim_ai_confidence',
            '_aim_retry_attempts',
        ];

        foreach ($single_number as $meta_key) {
            register_post_meta('product', $meta_key, [
                'type'         => 'number',
                'single'       => true,
                'show_in_rest' => true,
                'auth_callback'=> [__CLASS__, 'can_edit_products'],
            ]);
        }

        $json_string_fields = [
            '_aim_ai_moods',
            '_aim_ai_scene_tags',
            '_aim_ai_instruments',
            '_aim_ai_structure_tags',
        ];

        foreach ($json_string_fields as $meta_key) {
            register_post_meta('product', $meta_key, [
                'type'         => 'string',
                'single'       => true,
                'show_in_rest' => true,
                'auth_callback'=> [__CLASS__, 'can_edit_products'],
            ]);
        }
    }

    public static function can_edit_products(): bool {
        return current_user_can('edit_products');
    }
}
