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

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => 'Submit Button',
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_alignment',
            [
                'label'     => 'Alignment',
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => 'Left',
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Center',
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => 'Right',
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'stretch' => [
                        'title' => 'Justify',
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .aim-widget-submit' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .aim-widget-submit',
            ]
        );

        $this->start_controls_tabs('button_style_tabs');

        $this->start_controls_tab(
            'button_style_normal',
            [
                'label' => 'Normal',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => 'Text Color',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aim-widget-submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label'     => 'Background Color',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aim-widget-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label'     => 'Border Color',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aim-widget-submit' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_style_hover',
            [
                'label' => 'Hover',
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label'     => 'Text Color',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aim-widget-submit:hover, {{WRAPPER}} .aim-widget-submit:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label'     => 'Background Color',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aim-widget-submit:hover, {{WRAPPER}} .aim-widget-submit:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => 'Border Color',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .aim-widget-submit:hover, {{WRAPPER}} .aim-widget-submit:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .aim-widget-submit',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => 'Border Radius',
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .aim-widget-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => 'Padding',
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .aim-widget-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label'      => 'Width',
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 600,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .aim-widget-submit' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
