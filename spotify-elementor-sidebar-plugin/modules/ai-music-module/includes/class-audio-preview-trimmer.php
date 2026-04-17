<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Audio_Preview_Trimmer {

    public static function create_preview_clip(string $source_file, int $start_seconds = 0, int $duration_seconds = 30) {
        if (!file_exists($source_file)) {
            return new \WP_Error('aim_source_missing', 'Source audio file not found.');
        }

        $ext = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
        if (!in_array($ext, ['mp3', 'wav'], true)) {
            return new \WP_Error('aim_preview_invalid_ext', 'Only mp3 and wav are supported.');
        }

        $ffmpeg = self::find_ffmpeg_binary();
        if (!$ffmpeg) {
            return $source_file;
        }

        $output = wp_tempnam('aim-preview');
        if (!$output) {
            return new \WP_Error('aim_preview_tmp_failed', 'Could not create temp output file.');
        }

        $output_with_ext = $output . '.' . $ext;
        @rename($output, $output_with_ext);

        $cmd = sprintf(
            '%s -y -ss %d -i %s -t %d -ac 1 -b:a 96k %s 2>&1',
            escapeshellcmd($ffmpeg),
            (int) $start_seconds,
            escapeshellarg($source_file),
            (int) $duration_seconds,
            escapeshellarg($output_with_ext)
        );

        @exec($cmd, $lines, $return_var);

        if ($return_var !== 0 || !file_exists($output_with_ext) || filesize($output_with_ext) <= 0) {
            @unlink($output_with_ext);
            return new \WP_Error('aim_ffmpeg_failed', 'ffmpeg failed to create preview clip.', [
                'output' => implode("\n", $lines),
            ]);
        }

        return $output_with_ext;
    }

    protected static function find_ffmpeg_binary(): string {
        $candidates = ['ffmpeg', '/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg'];

        foreach ($candidates as $bin) {
            $cmd = sprintf('%s -version 2>&1', escapeshellcmd($bin));
            @exec($cmd, $out, $ret);
            if ($ret === 0) {
                return $bin;
            }
        }

        return '';
    }
}
