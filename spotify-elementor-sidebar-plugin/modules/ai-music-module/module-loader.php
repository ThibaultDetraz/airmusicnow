<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

require_once __DIR__ . '/includes/class-autoloader.php';

class Module_Loader {

    public static function bootstrap(string $module_path, string $module_url, string $version = '1.0.0'): void {
        if (!defined('YOURPLUGIN_AIM_MODULE_PATH')) {
            define('YOURPLUGIN_AIM_MODULE_PATH', untrailingslashit($module_path) . '/');
        }
        if (!defined('YOURPLUGIN_AIM_MODULE_URL')) {
            define('YOURPLUGIN_AIM_MODULE_URL', untrailingslashit($module_url) . '/');
        }
        if (!defined('YOURPLUGIN_AIM_MODULE_VERSION')) {
            define('YOURPLUGIN_AIM_MODULE_VERSION', $version);
        }

        Autoloader::register();
        Plugin::init();
    }

    public static function activate(): void {
        if (!defined('YOURPLUGIN_AIM_MODULE_PATH')) {
            define('YOURPLUGIN_AIM_MODULE_PATH', __DIR__ . '/');
        }
        require_once __DIR__ . '/includes/class-autoloader.php';
        Autoloader::register();
        Plugin::activate();
    }
}
