<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Widget_Assets {

    public static function init(): void {
        add_action('wp_enqueue_scripts', [__CLASS__, 'register_assets']);
    }

    public static function register_assets(): void {
        wp_register_script(
            'aim-widget-frontend',
            YOURPLUGIN_AIM_MODULE_URL . 'assets/js/aim-widget-frontend.js',
            [],
            YOURPLUGIN_AIM_MODULE_VERSION,
            true
        );

        wp_localize_script('aim-widget-frontend', 'AIMWidget', [
            'endpoint' => rest_url('aim/v1/widget-recommend'),
            'nonce'    => wp_create_nonce('wp_rest'),
        ]);

        wp_register_style(
            'aim-widget-frontend',
            YOURPLUGIN_AIM_MODULE_URL . 'assets/css/aim-widget-frontend.css',
            [],
            YOURPLUGIN_AIM_MODULE_VERSION
        );
    }
}
