<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Elementor_Widget_Registry {

    public static function init(): void {
        add_action('elementor/widgets/register', [__CLASS__, 'register_widgets']);
    }

    public static function register_widgets($widgets_manager): void {
        if (!did_action('elementor/loaded')) {
            return;
        }

        $widgets_manager->register(new Elementor_Music_Finder_Widget());
    }
}
