<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Gemini_Client {

    protected string $api_key;
    protected string $base_url = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct() {
        $this->api_key = Admin_Settings_Page::get_gemini_api_key();
    }

    public function is_configured(): bool {
        return !empty($this->api_key);
    }

    public function generate_content(string $model, array $contents, array $generation_config = [], array $safety_settings = []) {
        if (!$this->is_configured()) {
            return new \WP_Error('aim_missing_gemini_api_key', 'Missing Gemini API key.');
        }

        $url = $this->base_url . rawurlencode($model) . ':generateContent?key=' . rawurlencode($this->api_key);

        $payload = [
            'contents' => $contents,
        ];

        if (!empty($generation_config)) {
            $payload['generationConfig'] = $generation_config;
        }

        if (!empty($safety_settings)) {
            $payload['safetySettings'] = $safety_settings;
        }

        $response = wp_remote_post($url, [
            'timeout' => 180,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode($payload),
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body, true);

        if ($code < 200 || $code >= 300) {
            return new \WP_Error('aim_gemini_http_error', $body ?: 'Gemini request failed.');
        }

        return is_array($json) ? $json : new \WP_Error('aim_gemini_invalid_json', 'Invalid Gemini response.');
    }

    public function extract_text(array $response): string {
        return (string) ($response['candidates'][0]['content']['parts'][0]['text'] ?? '');
    }
}