<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Product_Music_Matcher {

    public function find_matches(string $video_prompt, int $limit = 6) {
        $intent_analyzer = new Prompt_Intent_Analyzer();
        $intent = $intent_analyzer->analyze($video_prompt);

        if (is_wp_error($intent)) {
            return $intent;
        }

        $products = wc_get_products([
            'status' => 'publish',
            'limit'  => 300,
            'return' => 'objects',
        ]);

        $scored = [];

        foreach ($products as $product) {
            if (!$product instanceof \WC_Product) continue;
            if (!$product->is_downloadable()) continue;

            $status = (string) $product->get_meta('_aim_analysis_status', true);
            if ($status !== 'done') continue;

            $score_data = $this->score_product($product, $intent);
            if ($score_data['score'] <= 0) continue;

            $scored[] = [
                'product' => $product,
                'score'   => $score_data['score'],
                'reasons' => $score_data['reasons'],
            ];
        }

        usort($scored, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $scored = array_slice($scored, 0, $limit);

        $result = [];
        foreach ($scored as $row) {
            $product = $row['product'];
            $result[] = [
                'id'            => $product->get_id(),
                'title'         => $product->get_name(),
                'permalink'     => get_permalink($product->get_id()),
                'image'         => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
                'price_html'    => $product->get_price_html(),
                'summary'       => (string) $product->get_meta('_aim_ai_summary', true),
                'score'         => $row['score'],
                'match_reasons' => $row['reasons'],
                'moods'         => $this->decode_json_meta($product->get_meta('_aim_ai_moods', true)),
                'scene_tags'    => $this->decode_json_meta($product->get_meta('_aim_ai_scene_tags', true)),
                'instruments'   => $this->decode_json_meta($product->get_meta('_aim_ai_instruments', true)),
                'energy_label'  => (string) $product->get_meta('_aim_ai_energy_label', true),
                'tempo_label'   => (string) $product->get_meta('_aim_ai_tempo_label', true),
            ];
        }

        return [
            'intent'  => $intent,
            'tracks'  => $result,
        ];
    }

    protected function score_product(\WC_Product $product, array $intent): array {
        $score = 0;
        $reasons = [];

        $moods = $this->decode_json_meta($product->get_meta('_aim_ai_moods', true));
        $scene_tags = $this->decode_json_meta($product->get_meta('_aim_ai_scene_tags', true));
        $instruments = $this->decode_json_meta($product->get_meta('_aim_ai_instruments', true));
        $structure_tags = $this->decode_json_meta($product->get_meta('_aim_ai_structure_tags', true));
        $energy = (string) $product->get_meta('_aim_ai_energy_label', true);
        $tempo = (string) $product->get_meta('_aim_ai_tempo_label', true);

        $score += $this->score_overlap($intent['moods'], $moods, 4, $reasons, 'Mood match');
        $score += $this->score_overlap($intent['scene_tags'], $scene_tags, 5, $reasons, 'Scene fit');
        $score += $this->score_overlap($intent['instruments'], $instruments, 2, $reasons, 'Instrument fit');
        $score += $this->score_overlap($intent['structure_tags'], $structure_tags, 3, $reasons, 'Structure fit');

        if (!empty($intent['energy_label']) && $intent['energy_label'] === $energy) {
            $score += 4;
            $reasons[] = 'Energy matches';
        }

        if (!empty($intent['tempo_label']) && $intent['tempo_label'] === $tempo) {
            $score += 3;
            $reasons[] = 'Tempo matches';
        }

        $confidence = (float) $product->get_meta('_aim_ai_confidence', true);
        if ($confidence > 0) {
            $score += min(2, $confidence * 2);
        }

        return [
            'score' => round($score, 2),
            'reasons' => array_values(array_unique($reasons)),
        ];
    }

    protected function score_overlap(array $wanted, array $actual, int $weight, array &$reasons, string $reason_label): float {
        if (empty($wanted) || empty($actual)) {
            return 0;
        }

        $hits = array_intersect(
            array_map('strtolower', $wanted),
            array_map('strtolower', $actual)
        );

        if (empty($hits)) {
            return 0;
        }

        $reasons[] = $reason_label . ': ' . implode(', ', array_slice(array_values($hits), 0, 3));
        return count($hits) * $weight;
    }

    protected function decode_json_meta($value): array {
        if (is_array($value)) return $value;
        if (!$value) return [];
        $decoded = json_decode((string) $value, true);
        return is_array($decoded) ? array_values($decoded) : [];
    }
}
