<?php
/**
 * Plugin Name: Ultimate Index Addon-ons
 * Description: Adds a Spotify-style sidebar menu widget for Elementor.
 * Version: 1.2.0
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
define('SEMS_VERSION', '1.2.0');
define('SEMS_MINIMUM_ELEMENTOR_VERSION', '3.5.0');
define('SEMS_MINIMUM_PHP_VERSION', '7.4');

require_once SEMS_PLUGIN_PATH . 'includes/class-sems-plugin.php';

SEMS_Plugin::instance();
