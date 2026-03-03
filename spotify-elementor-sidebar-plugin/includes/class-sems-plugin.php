<?php

if (!defined('ABSPATH')) {
    exit;
}

final class SEMS_Plugin {
    private static ?SEMS_Plugin $instance = null;

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
        if (!did_action('elementor/loaded')) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function register_assets(): void {
        wp_register_style(
            'sems-sidebar-menu',
            SEMS_PLUGIN_URL . 'assets/css/sidebar-menu.css',
            [],
            SEMS_VERSION
        );
    }

    public function register_widgets($widgets_manager): void {
        require_once SEMS_PLUGIN_PATH . 'includes/widgets/class-sems-sidebar-widget.php';

        $widgets_manager->register(new SEMS_Sidebar_Widget());
    }
}
