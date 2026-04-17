<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Admin_Settings_Page {

    const OPTION_GROUP = 'aim_settings_group';
    const OPTION_KEY_API = 'aim_openai_api_key';
    const OPTION_KEY_MODEL = 'aim_openai_model';
    const OPTION_KEY_VERSION = 'aim_analysis_version';
    const OPTION_KEY_RETRY_MAX = 'aim_retry_max_attempts';
    const OPTION_KEY_RETRY_DELAY = 'aim_retry_delay_minutes';
    const OPTION_KEY_BACKFILL_LIMIT = 'aim_backfill_default_limit';
    const OPTION_KEY_PROVIDER = 'aim_provider';
    const OPTION_KEY_GEMINI_API = 'aim_gemini_api_key';
    const OPTION_KEY_GEMINI_MODEL_PROMPT = 'aim_gemini_model_prompt';
    const OPTION_KEY_GEMINI_MODEL_AUDIO = 'aim_gemini_model_audio';

    public static function init(): void {
        add_action('admin_menu', [__CLASS__, 'register_menu']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function register_menu(): void {
        add_submenu_page(
            'woocommerce',
            'AI Music Settings',
            'AI Music Settings',
            'manage_woocommerce',
            'aim-music-settings',
            [__CLASS__, 'render_page']
        );
    }

    public static function register_settings(): void {
        register_setting(self::OPTION_GROUP, self::OPTION_KEY_API);
        register_setting(self::OPTION_GROUP, self::OPTION_KEY_MODEL, [
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        register_setting(self::OPTION_GROUP, self::OPTION_KEY_VERSION, [
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        register_setting(self::OPTION_GROUP, self::OPTION_KEY_RETRY_MAX, [
            'sanitize_callback' => 'absint',
        ]);
        register_setting(self::OPTION_GROUP, self::OPTION_KEY_RETRY_DELAY, [
            'sanitize_callback' => 'absint',
        ]);
        register_setting(self::OPTION_GROUP, self::OPTION_KEY_BACKFILL_LIMIT, [
            'sanitize_callback' => 'absint',
        ]);

        register_setting(self::OPTION_GROUP, self::OPTION_KEY_PROVIDER, [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        register_setting(self::OPTION_GROUP, self::OPTION_KEY_GEMINI_API);

        register_setting(self::OPTION_GROUP, self::OPTION_KEY_GEMINI_MODEL_PROMPT, [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        register_setting(self::OPTION_GROUP, self::OPTION_KEY_GEMINI_MODEL_AUDIO, [
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        add_settings_section(
            'aim_main_section',
            'AI Music Analyzer Settings',
            '__return_false',
            'aim-music-settings'
        );

        self::add_field(self::OPTION_KEY_API, 'OpenAI API Key', 'password');
        self::add_field(self::OPTION_KEY_MODEL, 'OpenAI Model', 'text', 'gpt-4.1');
        self::add_field(self::OPTION_KEY_VERSION, 'Analysis Version', 'text', '1.0.0');
        self::add_field(self::OPTION_KEY_RETRY_MAX, 'Retry Max Attempts', 'number', 3);
        self::add_field(self::OPTION_KEY_RETRY_DELAY, 'Retry Delay (minutes)', 'number', 10);
        self::add_field(self::OPTION_KEY_BACKFILL_LIMIT, 'Backfill Default Limit', 'number', 500);
        self::add_field(self::OPTION_KEY_PROVIDER, 'AI Provider', 'text', 'gemini');
        self::add_field(self::OPTION_KEY_GEMINI_API, 'Gemini API Key', 'password');
        self::add_field(self::OPTION_KEY_GEMINI_MODEL_PROMPT, 'Gemini Prompt Model', 'text', 'gemini-3.1-flash-lite-preview');
        self::add_field(self::OPTION_KEY_GEMINI_MODEL_AUDIO, 'Gemini Audio Model', 'text', 'gemini-3.1-flash-lite-preview');
    }

    protected static function add_field(string $key, string $label, string $type = 'text', $default = ''): void {
        add_settings_field(
            $key,
            $label,
            function() use ($key, $type, $default) {
                $value = get_option($key, $default);
                printf(
                    '<input type="%1$s" name="%2$s" value="%3$s" class="regular-text" %4$s />',
                    esc_attr($type),
                    esc_attr($key),
                    esc_attr((string) $value),
                    $type === 'number' ? 'min="0"' : ''
                );
            },
            'aim-music-settings',
            'aim_main_section'
        );
    }

    public static function render_page(): void {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        if (
            isset($_POST['aim_run_backfill']) &&
            check_admin_referer('aim_run_backfill_action', 'aim_run_backfill_nonce')
        ) {
            $limit = (int) get_option(self::OPTION_KEY_BACKFILL_LIMIT, 500);
            $count = Audio_Backfill_Manager::enqueue_all([
                'limit'   => $limit,
                'version' => self::get_analysis_version(),
                'force'   => false,
            ]);

            echo '<div class="notice notice-success"><p>Enqueued ' . esc_html($count) . ' products for analysis.</p></div>';
        }

        ?>
        <div class="wrap">
            <h1>AI Music Settings</h1>

            <form method="post" action="options.php">
                <?php
                settings_fields(self::OPTION_GROUP);
                do_settings_sections('aim-music-settings');
                submit_button();
                ?>
            </form>

            <hr>

            <h2>Backfill Existing Products</h2>
            <form method="post">
                <?php wp_nonce_field('aim_run_backfill_action', 'aim_run_backfill_nonce'); ?>
                <p>Queue analysis jobs for existing downloadable WooCommerce music products.</p>
                <p>
                    <button type="submit" name="aim_run_backfill" class="button button-primary">
                        Run Backfill Queue
                    </button>
                </p>
            </form>
        </div>
        <?php
    }

    public static function get_api_key(): string {
        return (string) get_option(self::OPTION_KEY_API, '');
    }

    public static function get_model(): string {
        return (string) get_option(self::OPTION_KEY_MODEL, 'gpt-4.1');
    }

    public static function get_analysis_version(): string {
        return (string) get_option(self::OPTION_KEY_VERSION, '1.0.0');
    }

    public static function get_retry_max_attempts(): int {
        return max(0, (int) get_option(self::OPTION_KEY_RETRY_MAX, 3));
    }

    public static function get_retry_delay_minutes(): int {
        return max(1, (int) get_option(self::OPTION_KEY_RETRY_DELAY, 10));
    }
    public static function get_provider(): string {
        return (string) get_option(self::OPTION_KEY_PROVIDER, 'gemini');
    }

    public static function get_gemini_api_key(): string {
        return (string) get_option(self::OPTION_KEY_GEMINI_API, '');
    }

    public static function get_gemini_model_prompt(): string {
        return (string) get_option(self::OPTION_KEY_GEMINI_MODEL_PROMPT, 'gemini-3.1-flash-lite-preview');
    }

    public static function get_gemini_model_audio(): string {
        return (string) get_option(self::OPTION_KEY_GEMINI_MODEL_AUDIO, 'gemini-3.1-flash-lite-preview');
    }
}
