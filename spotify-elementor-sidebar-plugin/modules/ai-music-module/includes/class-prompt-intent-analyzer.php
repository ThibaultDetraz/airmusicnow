<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Prompt_Intent_Analyzer {

    protected string $api_key;
    protected string $model;
    protected string $endpoint;

    public function __construct() {
        $this->api_key  = Admin_Settings_Page::get_api_key();
        $this->model    = Admin_Settings_Page::get_model();
        $this->endpoint = 'https://api.openai.com/v1/chat/completions';
    }

    public function analyze(string $prompt) {
        if (!$this->api_key) {
            return new \WP_Error('aim_missing_api_key', 'Missing OpenAI API key.');
        }

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $this->get_system_prompt(),
                        ],
                    ],
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $this->get_json_schema(),
            ],
            'temperature' => 0.2,
            'max_completion_tokens' => 500,
        ];

        $response = wp_remote_post($this->endpoint, [
            'timeout' => 90,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type'  => 'application/json',
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
            return new \WP_Error('aim_openai_prompt_http_error', $body ?: 'Prompt analysis failed.');
        }

        $content = $json['choices'][0]['message']['content'] ?? '';
        $parsed = json_decode($content, true);

        if (!is_array($parsed)) {
            return new \WP_Error('aim_invalid_prompt_json', 'Prompt analyzer returned invalid JSON.');
        }

        return $this->normalize($parsed);
    }

    protected function get_system_prompt(): string {
        return <<<PROMPT
You analyze video prompts and convert them into music recommendation intent.

Return only valid JSON.

Focus on:
- desired moods
- desired scene tags
- desired instruments
- desired energy
- desired tempo
- structure preference

Think in terms of stock music recommendation for video editing.
PROMPT;
    }

    protected function get_json_schema(): array {
        return [
            'name'   => 'video_music_intent',
            'schema' => [
                'type' => 'object',
                'additionalProperties' => false,
                'properties' => [
                    'moods' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                    ],
                    'scene_tags' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                    ],
                    'instruments' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                    ],
                    'energy_label' => [
                        'type' => 'string',
                        'enum' => ['low', 'medium', 'high'],
                    ],
                    'tempo_label' => [
                        'type' => 'string',
                        'enum' => ['slow', 'medium', 'fast'],
                    ],
                    'structure_tags' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                    ],
                ],
                'required' => [
                    'moods',
                    'scene_tags',
                    'instruments',
                    'energy_label',
                    'tempo_label',
                    'structure_tags',
                ],
            ],
        ];
    }

    protected function normalize(array $data): array {
        return [
            'moods'          => $this->sanitize_list($data['moods'] ?? []),
            'scene_tags'     => $this->sanitize_list($data['scene_tags'] ?? []),
            'instruments'    => $this->sanitize_list($data['instruments'] ?? []),
            'energy_label'   => $this->sanitize_enum($data['energy_label'] ?? 'medium', ['low','medium','high'], 'medium'),
            'tempo_label'    => $this->sanitize_enum($data['tempo_label'] ?? 'medium', ['slow','medium','fast'], 'medium'),
            'structure_tags' => $this->sanitize_list($data['structure_tags'] ?? []),
        ];
    }

    protected function sanitize_list($items): array {
        if (!is_array($items)) return [];
        $out = [];
        foreach ($items as $item) {
            $item = sanitize_text_field((string) $item);
            if ($item !== '') $out[] = $item;
        }
        return array_values(array_unique($out));
    }

    protected function sanitize_enum(string $value, array $allowed, string $default): string {
        $value = sanitize_text_field($value);
        return in_array($value, $allowed, true) ? $value : $default;
    }
}
