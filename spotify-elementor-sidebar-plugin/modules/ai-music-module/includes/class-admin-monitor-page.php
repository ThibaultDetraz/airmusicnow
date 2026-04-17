<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Admin_Monitor_Page {

    public static function init(): void {
        add_action('admin_menu', [__CLASS__, 'register_menu']);
        add_action('admin_init', [__CLASS__, 'handle_bulk_actions']);
    }

    public static function register_menu(): void {
        add_submenu_page(
            'woocommerce',
            'AI Music Monitor',
            'AI Music Monitor',
            'manage_woocommerce',
            'aim-music-monitor',
            [__CLASS__, 'render_page']
        );
    }

    public static function handle_bulk_actions(): void {
        if (
            !isset($_GET['page']) ||
            $_GET['page'] !== 'aim-music-monitor' ||
            empty($_POST['action']) ||
            empty($_POST['product_ids'])
        ) {
            return;
        }

        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        check_admin_referer('bulk-' . 'ai_music_products');

        $action = sanitize_text_field(wp_unslash($_POST['action']));
        $product_ids = array_map('absint', (array) $_POST['product_ids']);

        foreach ($product_ids as $product_id) {
            if ($action === 'queue_analyze') {
                Audio_Analysis_Queue::enqueue_product($product_id, false);
            } elseif ($action === 'force_reanalyze') {
                Audio_Analysis_Queue::enqueue_product($product_id, true);
            }
        }
    }

    public static function render_page(): void {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        $table = new Admin_List_Table();
        $table->prepare_items();

        $view_logs = isset($_GET['view_logs']) ? absint($_GET['view_logs']) : 0;
        ?>
        <div class="wrap">
            <h1>AI Music Monitor</h1>

            <form method="get">
                <input type="hidden" name="page" value="aim-music-monitor" />
                <?php $table->search_box('Search products', 'aim-product-search'); ?>
            </form>

            <form method="post">
                <?php
                $table->display();
                ?>
            </form>

            <?php if ($view_logs): ?>
                <hr>
                <h2>Recent logs for product #<?php echo esc_html($view_logs); ?></h2>
                <?php
                $logs = Analysis_Logger::get_recent_logs($view_logs, 30);
                if (empty($logs)) {
                    echo '<p>No logs found.</p>';
                } else {
                    echo '<table class="widefat striped"><thead><tr><th>Time</th><th>Level</th><th>Message</th><th>Context</th></tr></thead><tbody>';
                    foreach ($logs as $log) {
                        echo '<tr>';
                        echo '<td>' . esc_html($log['created_at']) . '</td>';
                        echo '<td>' . esc_html($log['level']) . '</td>';
                        echo '<td>' . esc_html($log['message']) . '</td>';
                        echo '<td><code style="white-space:pre-wrap;">' . esc_html($log['context']) . '</code></td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                }
                ?>
            <?php endif; ?>
        </div>
        <?php
    }
}
