<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Analysis_Logger {

    const TABLE_SUFFIX = 'aim_analysis_logs';

    public static function create_table(): void {
        global $wpdb;

        $table_name = self::get_table_name();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id BIGINT UNSIGNED NOT NULL,
            level VARCHAR(20) NOT NULL,
            message TEXT NOT NULL,
            context LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY product_id (product_id),
            KEY level (level),
            KEY created_at (created_at)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public static function get_table_name(): string {
        global $wpdb;
        return $wpdb->prefix . self::TABLE_SUFFIX;
    }

    public static function log(int $product_id, string $level, string $message, array $context = []): void {
        global $wpdb;

        $wpdb->insert(
            self::get_table_name(),
            [
                'product_id' => $product_id,
                'level'      => sanitize_text_field($level),
                'message'    => sanitize_textarea_field($message),
                'context'    => !empty($context) ? wp_json_encode($context) : null,
                'created_at' => current_time('mysql', true),
            ],
            ['%d','%s','%s','%s','%s']
        );
    }

    public static function get_recent_logs(int $product_id, int $limit = 10): array {
        global $wpdb;

        $table = self::get_table_name();
        $sql = $wpdb->prepare(
            "SELECT * FROM {$table} WHERE product_id = %d ORDER BY id DESC LIMIT %d",
            $product_id,
            $limit
        );

        return $wpdb->get_results($sql, ARRAY_A) ?: [];
    }
}
