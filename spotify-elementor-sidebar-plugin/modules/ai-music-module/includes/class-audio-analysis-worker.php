<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Audio_Analysis_Worker {

    public function process(int $product_id): void {
        $product = wc_get_product($product_id);
        if (!$product instanceof \WC_Product) {
            return;
        }

        Analysis_Logger::log($product_id, 'info', 'Worker started');

        $audio_url = (string) $product->get_meta('_aim_audio_source_url', true);
        if (!$audio_url) {
            $audio_url = Product_Audio_Helper::get_audio_url($product);
        }

        if (!$audio_url) {
            $this->fail_with_retry($product, 'No valid audio URL found.');
            return;
        }

        $product->update_meta_data('_aim_analysis_status', 'processing');
        $product->update_meta_data('_aim_analysis_error', '');
        $product->save();

        $tmp = Temp_Audio_File_Manager::download_to_temp($audio_url);
        if (is_wp_error($tmp)) {
            $this->fail_with_retry($product, $tmp->get_error_message());
            return;
        }

        $preview_path = $tmp;

        try {
            Analysis_Logger::log($product_id, 'info', 'Audio temp file ready', [
                'audio_url' => $audio_url,
                'tmp_path'  => basename($tmp),
            ]);

            $preview = Audio_Preview_Trimmer::create_preview_clip($tmp, 0, 30);
            if (is_wp_error($preview)) {
                Analysis_Logger::log($product_id, 'warning', 'Preview trim failed, fallback to original audio', [
                    'error' => $preview->get_error_message(),
                ]);
            } else {
                $preview_path = $preview;
            }

            $provider = Admin_Settings_Page::get_provider();

            if ($provider === 'gemini') {
                $analyzer = new Gemini_Audio_Analyzer();
            } else {
                $analyzer = new OpenAI_Audio_Analyzer();
            }

            $result = $analyzer->analyze_audio_file($preview_path, [
                'title'       => $product->get_name(),
                'description' => wp_strip_all_tags($product->get_short_description() ?: $product->get_description()),
                'duration'    => (string) $product->get_meta('_aim_audio_duration', true),
            ]);

            if (is_wp_error($result)) {
                $this->fail_with_retry($product, $result->get_error_message());
                return;
            }

            $this->save_result($product, $result);

            $product->update_meta_data('_aim_retry_attempts', 0);
            $product->update_meta_data('_aim_last_retry_at', '');
            $product->save();

            Analysis_Logger::log($product_id, 'info', 'Analysis completed successfully', [
                'model' => $result['model'] ?? '',
            ]);

        } finally {
            if ($preview_path !== $tmp) {
                Temp_Audio_File_Manager::cleanup($preview_path);
            }
            Temp_Audio_File_Manager::cleanup($tmp);
        }
    }

    protected function save_result(\WC_Product $product, array $result): void {
        $product->update_meta_data('_aim_ai_moods', wp_json_encode($result['moods'] ?? []));
        $product->update_meta_data('_aim_ai_scene_tags', wp_json_encode($result['scene_tags'] ?? []));
        $product->update_meta_data('_aim_ai_instruments', wp_json_encode($result['instruments'] ?? []));
        $product->update_meta_data('_aim_ai_energy_label', $result['energy_label'] ?? 'medium');
        $product->update_meta_data('_aim_ai_tempo_label', $result['tempo_label'] ?? 'medium');
        $product->update_meta_data('_aim_ai_structure_tags', wp_json_encode($result['structure_tags'] ?? []));
        $product->update_meta_data('_aim_ai_summary', $result['summary'] ?? '');
        $product->update_meta_data('_aim_ai_confidence', (float) ($result['confidence'] ?? 0.7));

        $product->update_meta_data('_aim_analysis_status', 'done');
        $product->update_meta_data('_aim_analysis_provider', $result['provider'] ?? 'openai');
        $product->update_meta_data('_aim_analysis_model', $result['model'] ?? '');
        $product->update_meta_data('_aim_analysis_version', $result['version'] ?? Admin_Settings_Page::get_analysis_version());
        $product->update_meta_data('_aim_analysis_updated_at', gmdate('c'));
        $product->update_meta_data('_aim_analysis_error', '');

        $product->save();
    }

    protected function fail_with_retry(\WC_Product $product, string $message): void {
        $product_id = $product->get_id();
        $max_attempts = Admin_Settings_Page::get_retry_max_attempts();
        $delay_minutes = Admin_Settings_Page::get_retry_delay_minutes();

        $attempts = (int) $product->get_meta('_aim_retry_attempts', true);
        $attempts++;

        $product->update_meta_data('_aim_retry_attempts', $attempts);
        $product->update_meta_data('_aim_last_retry_at', gmdate('c'));
        $product->update_meta_data('_aim_analysis_error', sanitize_text_field($message));

        Analysis_Logger::log($product_id, 'error', 'Analysis failed', [
            'attempt' => $attempts,
            'max_attempts' => $max_attempts,
            'error' => $message,
        ]);

        if ($attempts < $max_attempts) {
            $product->update_meta_data('_aim_analysis_status', 'pending');
            $product->save();

            Audio_Analysis_Queue::enqueue_retry($product_id, $delay_minutes);

            Analysis_Logger::log($product_id, 'warning', 'Retry scheduled', [
                'attempt' => $attempts,
                'delay_minutes' => $delay_minutes,
            ]);
            return;
        }

        $product->update_meta_data('_aim_analysis_status', 'failed');
        $product->update_meta_data('_aim_analysis_updated_at', gmdate('c'));
        $product->save();

        Analysis_Logger::log($product_id, 'critical', 'Analysis marked as permanently failed', [
            'attempts' => $attempts,
        ]);
    }
}
