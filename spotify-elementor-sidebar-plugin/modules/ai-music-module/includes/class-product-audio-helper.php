<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Product_Audio_Helper {

    public static function get_audio_url(\WC_Product $product): string {
        if (!$product->is_downloadable()) {
            return '';
        }

        $downloads = $product->get_downloads();
        if (!empty($downloads)) {
            foreach ($downloads as $download) {
                $file = $download->get_file();
                if (self::is_supported_audio_url($file)) {
                    return $file;
                }
            }
        }

        $custom = (string) $product->get_meta('_aim_audio_source_url', true);
        if ($custom && self::is_supported_audio_url($custom)) {
            return $custom;
        }

        return '';
    }

    public static function is_supported_audio_url(string $url): bool {
        return (bool) preg_match('/\.(mp3|wav)(\?.*)?$/i', $url);
    }

    public static function detect_source_type(\WC_Product $product): string {
        $downloads = $product->get_downloads();
        if (!empty($downloads)) {
            return 'downloadable_file';
        }
        return 'custom';
    }

    public static function build_checksum(string $audio_url, string $version = '1.0.0'): string {
        return md5($audio_url . '|' . $version);
    }
}
