<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Gemini_Audio_Analyzer {

    protected Gemini_Client $client;
    protected string $model;

    public function __construct() {
        $this->client = new Gemini_Client();
        $this->model  = Admin_Settings_Page::get_gemini_model_audio();
    }

    public function analyze_audio_file(string $local_file_path, array $context = []) {
        if (!$this->client->is_configured()) {
            return new \WP_Error('aim_missing_gemini_api_key', 'Missing Gemini API key.');
        }

        if (!file_exists($local_file_path)) {
            return new \WP_Error('aim_missing_file', 'Local audio file not found.');
        }

        $ext = strtolower(pathinfo($local_file_path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['mp3', 'wav'], true)) {
            return new \WP_Error('aim_unsupported_format', 'Only mp3 and wav are supported.');
        }

        $mime = $ext === 'wav' ? 'audio/wav' : 'audio/mpeg';
        $audio_data = file_get_contents($local_file_path);
        if ($audio_data === false) {
            return new \WP_Error('aim_file_read_failed', 'Unable to read audio file.');
        }

        $title       = (string) ($context['title'] ?? '');
        $description = (string) ($context['description'] ?? '');
        $duration    = (string) ($context['duration'] ?? '');

        $instruction = <<<TXT
You are an expert music tagging AI for stock music platforms.

You MUST return a rich and detailed JSON.

Rules:
- moods must have at least 3 tags
- scene_tags must describe real video use cases
- instruments must list main instruments
- summary must be meaningful

Return ONLY valid JSON with this exact schema:
{
  "moods": ["string"],
  "scene_tags": ["string"],
  "instruments": ["string"],
  "energy_label": "low|medium|high",
  "tempo_label": "slow|medium|fast",
  "structure_tags": ["string"],
  "summary": "string",
  "confidence": 0.0
}

Focus on:
- music mood
- suitable video scenes
- prominent instruments
- perceived energy
- perceived tempo
- structure impression such as soft_intro, rising_build, strong_climax
TXT;

        $metadata = "Title: {$title}\nDescription: {$description}\nDuration seconds: {$duration}";

        $response = $this->client->generate_content(
            $this->model,
            [[
                'role' => 'user',
                'parts' => [
                    ['text' => $instruction . "\n\n" . $metadata],
                    [
                        'inlineData' => [
                            'mimeType' => $mime,
                            'data' => base64_encode($audio_data),
                        ],
                    ],
                ],
            ]],
            [
                'temperature' => 0.2,
                'maxOutputTokens' => 600,
                'responseMimeType' => 'application/json',
            ]
        );

        if (is_wp_error($response)) {
            return $response;
        }

        $text = $this->client->extract_text($response);
        $parsed = $this->extract_json($text);
        Analysis_Logger::log(1, 'GEMINI RESPONSE', 'GEMINI RAW: ' . print_r($response, true));
        Analysis_Logger::log(1, 'GEMINI TEXT', 'GEMINI TEXT: ' . $text);
        if (!is_array($parsed)) {
            return new \WP_Error('aim_invalid_audio_json', 'Gemini audio analyzer returned invalid JSON.');
        }

        return $this->normalize($parsed);
    }

    protected function extract_json(string $text): ?array {

        // tìm block {...}
        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $json = $matches[0];
            $decoded = json_decode($json, true);

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    protected function normalize(array $data): array {
        return [
            'moods'          => $this->sanitize_list($data['moods'] ?? []),
            'scene_tags'     => $this->sanitize_list($data['scene_tags'] ?? []),
            'instruments'    => $this->sanitize_list($data['instruments'] ?? []),
            'energy_label'   => $this->sanitize_enum($data['energy_label'] ?? 'medium', ['low','medium','high'], 'medium'),
            'tempo_label'    => $this->sanitize_enum($data['tempo_label'] ?? 'medium', ['slow','medium','fast'], 'medium'),
            'structure_tags' => $this->sanitize_list($data['structure_tags'] ?? []),
            'summary'        => sanitize_text_field((string) ($data['summary'] ?? '')),
            'confidence'     => max(0, min(1, (float) ($data['confidence'] ?? 0.7))),
            'provider'       => 'gemini',
            'model'          => $this->model,
            'version'        => Admin_Settings_Page::get_analysis_version(),
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