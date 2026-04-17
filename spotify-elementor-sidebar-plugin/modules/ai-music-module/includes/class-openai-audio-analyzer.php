<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class OpenAI_Audio_Analyzer {

    protected string $api_key;
    protected string $model;
    protected string $endpoint;

    public function __construct() {
        $this->api_key  = Admin_Settings_Page::get_api_key();
        $this->model    = Admin_Settings_Page::get_model();
        $this->endpoint = 'https://api.openai.com/v1/chat/completions';
    }

    public function is_configured(): bool {
        return !empty($this->api_key);
    }

    public function analyze_audio_file(string $local_file_path, array $context = []) {
        if (!$this->is_configured()) {
            return new \WP_Error('aim_missing_api_key', 'Missing OpenAI API key.');
        }

        if (!file_exists($local_file_path)) {
            return new \WP_Error('aim_missing_file', 'Local audio file not found.');
        }

        $ext = strtolower(pathinfo($local_file_path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['mp3', 'wav'], true)) {
            return new \WP_Error('aim_unsupported_format', 'Only mp3 and wav are supported for input_audio.');
        }

        $audio_data = file_get_contents($local_file_path);
        if ($audio_data === false) {
            return new \WP_Error('aim_file_read_failed', 'Unable to read local audio file.');
        }

        $b64 = base64_encode($audio_data);

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
                            'text' => $this->build_user_context_text($context),
                        ],
                        [
                            'type' => 'input_audio',
                            'input_audio' => [
                                'data'   => $b64,
                                'format' => $ext,
                            ],
                        ],
                    ],
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $this->get_json_schema(),
            ],
            'temperature' => 0.2,
            'max_completion_tokens' => 700,
        ];

        $response = wp_remote_post($this->endpoint, [
            'timeout' => 180,
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
            return new \WP_Error('aim_openai_http_error', $body ?: 'OpenAI request failed.');
        }

        $content = $json['choices'][0]['message']['content'] ?? '';
        if (!$content) {
            return new \WP_Error('aim_invalid_openai_response', 'No content returned from model.');
        }

        $parsed = json_decode($content, true);
        if (!is_array($parsed)) {
            return new \WP_Error('aim_invalid_json', 'Model did not return valid JSON.');
        }

        return $this->normalize_result($parsed);
    }

    protected function get_system_prompt(): string {
        return <<<PROMPT
You are a music analysis assistant for a stock music catalog.

Analyze the provided music audio clip and return ONLY valid JSON.

Focus on:
- mood
- video scene suitability
- prominent instruments
- perceived energy level
- perceived tempo label
- musical structure tags
- concise recommendation summary

Do not mention uncertainty unless needed.
Do not output markdown.
Do not output text outside JSON.
PROMPT;
    }

    protected function build_user_context_text(array $context): string {
        $title       = (string) ($context['title'] ?? '');
        $description = (string) ($context['description'] ?? '');
        $duration    = (string) ($context['duration'] ?? '');

        return trim(
            "Analyze this stock music preview for catalog tagging.\n" .
            "Title: {$title}\n" .
            "Description: {$description}\n" .
            "Duration seconds: {$duration}\n"
        );
    }

    protected function get_json_schema(): array {
        return [
            'name'   => 'music_analysis',
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
                    'summary' => [
                        'type' => 'string',
                    ],
                    'confidence' => [
                        'type' => 'number',
                    ],
                ],
                'required' => [
                    'moods',
                    'scene_tags',
                    'instruments',
                    'energy_label',
                    'tempo_label',
                    'structure_tags',
                    'summary',
                    'confidence',
                ],
            ],
        ];
    }

    protected function normalize_result(array $data): array {
        return [
            'moods'          => $this->sanitize_string_list($data['moods'] ?? []),
            'scene_tags'     => $this->sanitize_string_list($data['scene_tags'] ?? []),
            'instruments'    => $this->sanitize_string_list($data['instruments'] ?? []),
            'energy_label'   => $this->sanitize_enum($data['energy_label'] ?? 'medium', ['low', 'medium', 'high'], 'medium'),
            'tempo_label'    => $this->sanitize_enum($data['tempo_label'] ?? 'medium', ['slow', 'medium', 'fast'], 'medium'),
            'structure_tags' => $this->sanitize_string_list($data['structure_tags'] ?? []),
            'summary'        => sanitize_text_field($data['summary'] ?? ''),
            'confidence'     => max(0, min(1, (float) ($data['confidence'] ?? 0.7))),
            'provider'       => 'openai',
            'model'          => $this->model,
            'version'        => Admin_Settings_Page::get_analysis_version(),
        ];
    }

    protected function sanitize_string_list($items): array {
        if (!is_array($items)) return [];
        $out = [];
        foreach ($items as $item) {
            $item = sanitize_text_field((string) $item);
            if ($item !== '') {
                $out[] = $item;
            }
        }
        return array_values(array_unique($out));
    }

    protected function sanitize_enum(string $value, array $allowed, string $default): string {
        $value = sanitize_text_field($value);
        return in_array($value, $allowed, true) ? $value : $default;
    }
}
