<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Gemini_Prompt_Analyzer {

    protected Gemini_Client $client;
    protected string $model;

    public function __construct() {
        $this->client = new Gemini_Client();
        $this->model  = Admin_Settings_Page::get_gemini_model_prompt();
    }

    public function analyze(string $prompt) {
        if (!$this->client->is_configured()) {
            return new \WP_Error('aim_missing_gemini_api_key', 'Missing Gemini API key.');
        }

        $schema_text = <<<TXT
Return ONLY valid JSON with this exact schema:
{
  "moods": ["string"],
  "scene_tags": ["string"],
  "instruments": ["string"],
  "energy_label": "low|medium|high",
  "tempo_label": "slow|medium|fast",
  "structure_tags": ["string"]
}
TXT;

        $system = "You analyze video prompts and convert them into music recommendation intent for stock music.";
        $user   = $schema_text . "\n\nVideo prompt:\n" . $prompt;

        $response = $this->client->generate_content(
            $this->model,
            [[
                'role' => 'user',
                'parts' => [
                    ['text' => $system . "\n\n" . $user],
                ],
            ]],
            [
                'temperature' => 0.2,
                'maxOutputTokens' => 400,
                'responseMimeType' => 'application/json',
            ]
        );

        if (is_wp_error($response)) {
            return $response;
        }

        $text = $this->client->extract_text($response);
        $parsed = json_decode($text, true);

        if (!is_array($parsed)) {
            return new \WP_Error('aim_invalid_prompt_json', 'Gemini prompt analyzer returned invalid JSON.');
        }

        return $this->normalize($parsed);
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