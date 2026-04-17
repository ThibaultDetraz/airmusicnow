<?php
/**
 * Plugin Name: Ultimate Index Addon-ons
 * Description: Adds a Spotify-style sidebar menu widget for Elementor.
 * Version: 1.2.6
 * Author: Copilot
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: spotify-elementor-sidebar-menu
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SEMS_PLUGIN_FILE', __FILE__);
define('SEMS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEMS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SEMS_VERSION', '1.2.3');
define('SEMS_MINIMUM_ELEMENTOR_VERSION', '3.5.0');
define('SEMS_MINIMUM_PHP_VERSION', '7.4');

require_once SEMS_PLUGIN_PATH . 'includes/class-sems-plugin.php';

SEMS_Plugin::instance();

require_once plugin_dir_path(__FILE__) . 'modules/ai-music-module/module-loader.php';

add_action('plugins_loaded', function () {
    \YourPlugin\AI_Music\Module_Loader::bootstrap(
        plugin_dir_path(__FILE__) . 'modules/ai-music-module/',
        plugin_dir_url(__FILE__) . 'modules/ai-music-module/',
        '1.0.1'
    );
});

register_activation_hook(__FILE__, function () {
    require_once plugin_dir_path(__FILE__) . 'modules/ai-music-module/module-loader.php';
    \YourPlugin\AI_Music\Module_Loader::activate();
});