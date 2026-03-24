<?php

if (!defined('ABSPATH')) {
    exit;
}

final class SEMS_Plugin {
    private static ?SEMS_Plugin $instance = null;
    private bool $widget_registered = false;

    public static function instance(): SEMS_Plugin {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init(): void {
        if (!$this->is_compatible()) {
            return;
        }

        require_once SEMS_PLUGIN_PATH . 'includes/class-sems-playlists.php';
        SEMS_Playlists::init();

        add_action('elementor/frontend/after_register_styles', [$this, 'register_assets']);
        add_action('elementor/editor/before_enqueue_styles', [$this, 'register_assets']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets_legacy']);
    }

    private function is_compatible(): bool {
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        if (version_compare(PHP_VERSION, SEMS_MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, SEMS_MINIMUM_ELEMENTOR_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        return true;
    }

    public function register_assets(): void {
        wp_register_style(
            'sems-sidebar-menu',
            SEMS_PLUGIN_URL . 'assets/css/sidebar-menu.css',
            [],
            SEMS_VERSION
        );

        wp_register_style(
            'sems-playlist-widgets',
            SEMS_PLUGIN_URL . 'assets/css/playlist-widgets.css',
            [],
            SEMS_VERSION
        );
    }

    public function register_widgets($widgets_manager): void {
        if ($this->widget_registered) {
            return;
        }

        require_once SEMS_PLUGIN_PATH . 'includes/widgets/class-sems-sidebar-widget.php';
        require_once SEMS_PLUGIN_PATH . 'includes/widgets/class-sems-create-playlist-widget.php';
        require_once SEMS_PLUGIN_PATH . 'includes/widgets/class-sems-playlist-grid-widget.php';

        $widgets_manager->register(new SEMS_Sidebar_Widget());
        $widgets_manager->register(new SEMS_Create_Playlist_Widget());
        $widgets_manager->register(new SEMS_Playlist_Grid_Widget());
        $this->widget_registered = true;
    }

    public function register_widgets_legacy(): void {
        if ($this->widget_registered || !class_exists('\\Elementor\\Plugin')) {
            return;
        }

        require_once SEMS_PLUGIN_PATH . 'includes/widgets/class-sems-sidebar-widget.php';
        require_once SEMS_PLUGIN_PATH . 'includes/widgets/class-sems-create-playlist-widget.php';
        require_once SEMS_PLUGIN_PATH . 'includes/widgets/class-sems-playlist-grid-widget.php';

        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

        if (method_exists($widgets_manager, 'register')) {
            $widgets_manager->register(new SEMS_Sidebar_Widget());
            $widgets_manager->register(new SEMS_Create_Playlist_Widget());
            $widgets_manager->register(new SEMS_Playlist_Grid_Widget());
        } elseif (method_exists($widgets_manager, 'register_widget_type')) {
            $widgets_manager->register_widget_type(new SEMS_Sidebar_Widget());
            $widgets_manager->register_widget_type(new SEMS_Create_Playlist_Widget());
            $widgets_manager->register_widget_type(new SEMS_Playlist_Grid_Widget());
        }

        $this->widget_registered = true;
    }

    public function admin_notice_missing_main_plugin(): void {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        printf(
            '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
            esc_html__('Ultimate Index Addon-ons requires Elementor to be installed and activated.', 'spotify-elementor-sidebar-menu')
        );
    }

    public function admin_notice_minimum_php_version(): void {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        printf(
            '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
            esc_html(sprintf('Ultimate Index Addon-ons requires PHP version %s or greater.', SEMS_MINIMUM_PHP_VERSION))
        );
    }

    public function admin_notice_minimum_elementor_version(): void {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        printf(
            '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
            esc_html(sprintf('Ultimate Index Addon-ons requires Elementor version %s or greater.', SEMS_MINIMUM_ELEMENTOR_VERSION))
        );
    }
}
