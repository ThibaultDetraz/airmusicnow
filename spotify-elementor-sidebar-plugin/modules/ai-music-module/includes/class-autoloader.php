<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Autoloader {

    public static function register(): void {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload(string $class): void {
        $prefix = __NAMESPACE__ . '\\';

        if (strpos($class, $prefix) !== 0) {
            return;
        }

        $relative = substr($class, strlen($prefix));
        $relative = strtolower(str_replace('_', '-', $relative));
        $file = YOURPLUGIN_AIM_MODULE_PATH . 'includes/class-' . $relative . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}
