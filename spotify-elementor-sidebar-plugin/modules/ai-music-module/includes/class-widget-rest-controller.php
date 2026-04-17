<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Widget_REST_Controller {

    public static function init(): void {
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
    }

    public static function register_routes(): void {
        register_rest_route('aim/v1', '/widget-recommend', [
            'methods'  => 'POST',
            'callback' => [__CLASS__, 'recommend'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function recommend(\WP_REST_Request $request) {
        $prompt = sanitize_textarea_field((string) $request->get_param('prompt'));
        $limit  = max(1, min(12, (int) $request->get_param('limit')));

        if (!$prompt) {
            return new \WP_Error('aim_empty_prompt', 'Prompt is required.', ['status' => 400]);
        }

        $matcher = new Product_Music_Matcher();
        $result = $matcher->find_matches($prompt, $limit ?: 6);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response($result);
    }
}
