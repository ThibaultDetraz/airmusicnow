<?php
/**
 * Paste the relevant parts of this into spotify-elementor-sidebar-menu.php
 */

require_once plugin_dir_path(__FILE__) . 'modules/ai-music-module/module-loader.php';

add_action('plugins_loaded', function () {
    \YourPlugin\AI_Music\Module_Loader::bootstrap(
        plugin_dir_path(__FILE__) . 'modules/ai-music-module/',
        plugin_dir_url(__FILE__) . 'modules/ai-music-module/',
        '1.0.0'
    );
});

register_activation_hook(__FILE__, function () {
    require_once plugin_dir_path(__FILE__) . 'modules/ai-music-module/module-loader.php';
    \YourPlugin\AI_Music\Module_Loader::activate();
});
