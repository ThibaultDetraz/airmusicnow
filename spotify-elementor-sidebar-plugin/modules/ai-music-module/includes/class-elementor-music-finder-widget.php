<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Elementor_Music_Finder_Widget extends \Elementor\Widget_Base {

    public function get_name(): string {
        return 'aim_music_finder';
    }

    public function get_title(): string {
        return 'AI Music Finder';
    }

    public function get_icon(): string {
        return 'eicon-playlist-audio';
    }

    public function get_categories(): array {
        return ['general'];
    }

    public function get_script_depends(): array {
        return ['aim-widget-frontend'];
    }

    public function get_style_depends(): array {
        return ['aim-widget-frontend'];
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Content',
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label'   => 'Prompt Placeholder',
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'Describe your video context, mood, pacing, and scene...',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => 'Button Text',
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'Find Matching Music',
            ]
        );

        $this->add_control(
            'per_page',
            [
                'label'   => 'Products Per Page',
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'max'     => 24,
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label'        => 'Show Price',
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => 'Yes',
                'label_off'    => 'No',
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $widget_id = 'aim-widget-' . $this->get_id();
        $per_page  = max(1, (int) ($settings['per_page'] ?? 6));
        $show_price = (($settings['show_price'] ?? 'yes') === 'yes') ? '1' : '0';
        ?>
        <div
            id="<?php echo esc_attr($widget_id); ?>"
            class="aim-widget"
            data-per-page="<?php echo esc_attr($per_page); ?>"
            data-show-price="<?php echo esc_attr($show_price); ?>"
        >
            <div class="aim-widget-form">
                <textarea
                    class="aim-widget-prompt"
                    placeholder="<?php echo esc_attr($settings['placeholder'] ?? 'Describe your video context...'); ?>"
                ></textarea>

                <button type="button" class="aim-widget-submit">
                    <?php echo esc_html($settings['button_text'] ?? 'Find Matching Music'); ?>
                </button>
            </div>

            <div class="aim-widget-status"></div>
            <div class="aim-widget-results"></div>
            <div class="aim-widget-pagination"></div>
        </div>
        <?php
    }
}
