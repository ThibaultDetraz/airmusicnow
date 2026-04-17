<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

if (!class_exists('\WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Admin_List_Table extends \WP_List_Table {

    public function __construct() {
        parent::__construct([
            'singular' => 'ai_music_product',
            'plural'   => 'ai_music_products',
            'ajax'     => false,
        ]);
    }

    public function get_columns() {
        return [
            'cb'         => '<input type="checkbox" />',
            'product'    => 'Product',
            'status'     => 'Status',
            'model'      => 'Model',
            'version'    => 'Version',
            'updated_at' => 'Updated',
            'retries'    => 'Retries',
            'error'      => 'Last Error',
            'actions'    => 'Actions',
        ];
    }

    protected function get_sortable_columns() {
        return [
            'product'    => ['product', false],
            'status'     => ['status', false],
            'updated_at' => ['updated_at', true],
            'retries'    => ['retries', false],
        ];
    }

    protected function get_bulk_actions() {
        return [
            'queue_analyze' => 'Queue Analyze',
            'force_reanalyze' => 'Force Reanalyze',
        ];
    }

    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="product_ids[]" value="%d" />', (int) $item['id']);
    }

    public function column_product($item) {
        $edit_link = get_edit_post_link($item['id']);
        return sprintf('<a href="%s"><strong>%s</strong></a>', esc_url($edit_link), esc_html($item['product']));
    }

    public function column_status($item) {
        $status = $item['status'] ?: 'not_analyzed';
        $colors = [
            'done'       => '#1a7f37',
            'processing' => '#0969da',
            'pending'    => '#9a6700',
            'failed'     => '#cf222e',
        ];
        $color = $colors[$status] ?? '#666';
        return '<span style="font-weight:600;color:' . esc_attr($color) . '">' . esc_html($status) . '</span>';
    }

    public function column_model($item) {
        return esc_html($item['model'] ?: '-');
    }

    public function column_version($item) {
        return esc_html($item['version'] ?: '-');
    }

    public function column_updated_at($item) {
        return esc_html($item['updated_at'] ?: '-');
    }

    public function column_retries($item) {
        return (int) $item['retries'];
    }

    public function column_error($item) {
        $error = $item['error'] ?: '-';
        return '<div style="max-width:320px;white-space:normal;">' . esc_html($error) . '</div>';
    }

    public function column_actions($item) {
        $queue_url = wp_nonce_url(
            admin_url('admin-post.php?action=aim_manual_analyze_product&product_id=' . $item['id']),
            'aim_manual_analyze_' . $item['id']
        );

        $force_url = wp_nonce_url(
            admin_url('admin-post.php?action=aim_manual_analyze_product&product_id=' . $item['id'] . '&force=1'),
            'aim_manual_analyze_' . $item['id']
        );

        $logs_url = admin_url('admin.php?page=aim-music-monitor&view_logs=' . $item['id']);

        return sprintf(
            '<a class="button button-small" href="%1$s">Analyze</a> <a class="button button-small" href="%2$s">Force</a> <a class="button button-small" href="%3$s">Logs</a>',
            esc_url($queue_url),
            esc_url($force_url),
            esc_url($logs_url)
        );
    }

    public function column_default($item, $column_name) {
        return esc_html($item[$column_name] ?? '');
    }

    public function prepare_items() {
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $search = isset($_REQUEST['s']) ? sanitize_text_field(wp_unslash($_REQUEST['s'])) : '';
        $status_filter = isset($_REQUEST['status_filter']) ? sanitize_text_field(wp_unslash($_REQUEST['status_filter'])) : '';
        $orderby = isset($_REQUEST['orderby']) ? sanitize_text_field(wp_unslash($_REQUEST['orderby'])) : 'updated_at';
        $order = isset($_REQUEST['order']) ? sanitize_text_field(wp_unslash($_REQUEST['order'])) : 'DESC';

        $data = Admin_Monitor_Service::get_products_status_data([
            'paged'         => $current_page,
            'per_page'      => $per_page,
            'search'        => $search,
            'status_filter' => $status_filter,
            'orderby'       => $orderby,
            'order'         => $order,
        ]);

        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];
        $this->items = $data['items'];

        $this->set_pagination_args([
            'total_items' => $data['total_items'],
            'per_page'    => $per_page,
            'total_pages' => ceil($data['total_items'] / $per_page),
        ]);
    }
}
