<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Plugin {

    public static function init(): void {
        Product_Meta_Registry::init();
        Product_Save_Analyzer_Hook::init();
        Audio_Analysis_Queue::init();
        CLI_Commands::init();

        Admin_Settings_Page::init();
        Product_Admin_Metabox::init();
        Admin_Monitor_Page::init();

        Recommendation_REST_Controller::init();
        Widget_REST_Controller::init();
        Widget_Assets::init();
        Elementor_Widget_Registry::init();
        Widget_Shortcode::init();
    }

    public static function activate(): void {
        Analysis_Logger::create_table();
    }
}
