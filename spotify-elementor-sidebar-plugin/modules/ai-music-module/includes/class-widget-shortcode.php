<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Widget_Shortcode {

    public static function init(): void {
        add_shortcode('aim_music_finder', [__CLASS__, 'render']);
    }

    public static function render($atts = []): string {
        $atts = shortcode_atts([
            'per_page'   => 6,
            'show_price' => '1',
        ], $atts, 'aim_music_finder');

        ob_start();
        ?>
        <div class="aim-widget" data-per-page="<?php echo esc_attr((int) $atts['per_page']); ?>" data-show-price="<?php echo esc_attr($atts['show_price'] === '1' ? '1' : '0'); ?>">
            <div class="aim-widget-form">
                <textarea class="aim-widget-prompt" placeholder="Describe your video context, mood, pacing, and scene..."></textarea>
                <button type="button" class="aim-widget-submit">Find Matching Music</button>
            </div>
            <div class="aim-widget-status"></div>
            <div class="aim-widget-results"></div>
            <div class="aim-widget-pagination"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}
