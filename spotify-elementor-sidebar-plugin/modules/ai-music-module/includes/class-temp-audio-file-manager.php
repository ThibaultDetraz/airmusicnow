<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Temp_Audio_File_Manager {

    public static function download_to_temp(string $url) {
        $tmp = download_url($url, 120);
        if (is_wp_error($tmp)) {
            return $tmp;
        }

        $validated = self::ensure_supported_extension($tmp, $url);
        if (is_wp_error($validated)) {
            @unlink($tmp);
            return $validated;
        }

        return $validated;
    }

    protected static function ensure_supported_extension(string $tmp_path, string $source_url) {
        $ext = strtolower(pathinfo(parse_url($source_url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));

        if (!in_array($ext, ['mp3', 'wav'], true)) {
            return new \WP_Error('aim_invalid_audio_ext', 'Only mp3 and wav are supported.');
        }

        $new_path = $tmp_path . '.' . $ext;
        if (!@rename($tmp_path, $new_path)) {
            return new \WP_Error('aim_temp_rename_failed', 'Failed to rename temp audio file.');
        }

        return $new_path;
    }

    public static function cleanup(string $path): void {
        if ($path && file_exists($path)) {
            @unlink($path);
        }
    }
}
