<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Admin_Monitor_Service {

    public static function get_products_status_data(array $args = []): array {
        global $wpdb;

        $defaults = [
            'paged'         => 1,
            'per_page'      => 20,
            'search'        => '',
            'status_filter' => '',
            'orderby'       => 'updated_at',
            'order'         => 'DESC',
        ];
        $args = wp_parse_args($args, $defaults);

        $paged    = max(1, (int) $args['paged']);
        $per_page = max(1, (int) $args['per_page']);
        $offset   = ($paged - 1) * $per_page;

        $posts = $wpdb->posts;
        $pm    = $wpdb->postmeta;

        $allowed_orderby = [
            'product'    => 'p.post_title',
            'status'     => 'status_meta.meta_value',
            'updated_at' => 'updated_meta.meta_value',
            'retries'    => 'retry_meta.meta_value',
        ];
        $orderby_sql = $allowed_orderby[$args['orderby']] ?? 'updated_meta.meta_value';
        $order_sql = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';

        $where = " WHERE p.post_type = 'product' AND p.post_status IN ('publish','draft','private') ";
        $params = [];

        if ($args['search'] !== '') {
            $where .= " AND p.post_title LIKE %s ";
            $params[] = '%' . $wpdb->esc_like($args['search']) . '%';
        }

        if ($args['status_filter'] !== '') {
            $where .= " AND status_meta.meta_value = %s ";
            $params[] = $args['status_filter'];
        }

        $base_sql = "
            FROM {$posts} p
            LEFT JOIN {$pm} status_meta
                ON status_meta.post_id = p.ID AND status_meta.meta_key = '_aim_analysis_status'
            LEFT JOIN {$pm} model_meta
                ON model_meta.post_id = p.ID AND model_meta.meta_key = '_aim_analysis_model'
            LEFT JOIN {$pm} version_meta
                ON version_meta.post_id = p.ID AND version_meta.meta_key = '_aim_analysis_version'
            LEFT JOIN {$pm} updated_meta
                ON updated_meta.post_id = p.ID AND updated_meta.meta_key = '_aim_analysis_updated_at'
            LEFT JOIN {$pm} retry_meta
                ON retry_meta.post_id = p.ID AND retry_meta.meta_key = '_aim_retry_attempts'
            LEFT JOIN {$pm} error_meta
                ON error_meta.post_id = p.ID AND error_meta.meta_key = '_aim_analysis_error'
        ";

        $count_sql = "SELECT COUNT(DISTINCT p.ID) " . $base_sql . $where;
        $prepared_count_sql = !empty($params) ? $wpdb->prepare($count_sql, $params) : $count_sql;
        $total_items = (int) $wpdb->get_var($prepared_count_sql);

        $data_sql = "
            SELECT DISTINCT
                p.ID as id,
                p.post_title as product,
                status_meta.meta_value as status,
                model_meta.meta_value as model,
                version_meta.meta_value as version,
                updated_meta.meta_value as updated_at,
                retry_meta.meta_value as retries,
                error_meta.meta_value as error
            " . $base_sql . $where . "
            ORDER BY {$orderby_sql} {$order_sql}
            LIMIT %d OFFSET %d
        ";

        $data_params = $params;
        $data_params[] = $per_page;
        $data_params[] = $offset;

        $prepared_data_sql = $wpdb->prepare($data_sql, $data_params);
        $items = $wpdb->get_results($prepared_data_sql, ARRAY_A) ?: [];

        foreach ($items as &$item) {
            $item['retries'] = (int) ($item['retries'] ?? 0);
        }

        return [
            'items'       => $items,
            'total_items' => $total_items,
        ];
    }
}
