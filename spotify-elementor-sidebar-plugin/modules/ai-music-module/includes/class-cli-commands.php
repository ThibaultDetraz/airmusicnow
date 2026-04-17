<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class CLI_Commands {

    public static function init(): void {
        if (defined('WP_CLI') && \WP_CLI) {
            \WP_CLI::add_command('aim backfill-audio', [__CLASS__, 'backfill_audio']);
        }
    }

    public static function backfill_audio($args, $assoc_args): void {
        $limit = isset($assoc_args['limit']) ? (int) $assoc_args['limit'] : 500;
        $force = !empty($assoc_args['force']);

        $count = Audio_Backfill_Manager::enqueue_all([
            'limit'   => $limit,
            'version' => Admin_Settings_Page::get_analysis_version(),
            'force'   => $force,
        ]);

        \WP_CLI::success("Enqueued {$count} products for audio analysis.");
    }
}
