<?php

if (!defined('ABSPATH')) {
    exit;
}

class SEMS_Create_Playlist_Widget extends \Elementor\Widget_Base {
    public function get_name(): string {
        return 'sems_create_playlist';
    }

    public function get_title(): string {
        return esc_html__('Create Playlist', 'spotify-elementor-sidebar-menu');
    }

    public function get_icon(): string {
        return 'eicon-form-horizontal';
    }

    public function get_categories(): array {
        return ['general'];
    }

    public function get_style_depends(): array {
        return ['sems-playlist-widgets'];
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Create a Playlist', 'spotify-elementor-sidebar-menu'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Build your playlist from all published WooCommerce tracks.', 'spotify-elementor-sidebar-menu'),
            ]
        );

        $this->add_control(
            'submit_label',
            [
                'label' => esc_html__('Submit Button Label', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Save Playlist', 'spotify-elementor-sidebar-menu'),
            ]
        );

        $this->end_controls_section();

        $this->register_container_style_controls();
        $this->register_typography_style_controls();
        $this->register_form_style_controls();
        $this->register_button_style_controls();
    }

    private function register_container_style_controls(): void {
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__('Container', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_bg_color',
            [
                'label' => esc_html__('Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-create' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_color',
            [
                'label' => esc_html__('Border Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-create' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_border_width',
            [
                'label' => esc_html__('Border Width', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-create' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-create' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-create' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_typography_style_controls(): void {
        $this->start_controls_section(
            'section_style_typography',
            [
                'label' => esc_html__('Typography', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-create__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .sems-playlist-create__title',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-create__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .sems-playlist-create__description',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Label Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-form label, {{WRAPPER}} .sems-playlist-step-title, {{WRAPPER}} .sems-playlist-summary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .sems-playlist-form label, {{WRAPPER}} .sems-playlist-step-title',
            ]
        );

        $this->add_control(
            'steps_inactive_color',
            [
                'label' => esc_html__('Step Inactive Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-steps span' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'steps_active_color',
            [
                'label' => esc_html__('Step Active Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-steps span.is-active' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_form_style_controls(): void {
        $this->start_controls_section(
            'section_style_form',
            [
                'label' => esc_html__('Fields', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label' => esc_html__('Field Text Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-form input[type="text"], {{WRAPPER}} .sems-playlist-form input[type="file"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label' => esc_html__('Field Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-form input[type="text"], {{WRAPPER}} .sems-playlist-form input[type="file"]' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_border_color',
            [
                'label' => esc_html__('Field Border Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-form input[type="text"], {{WRAPPER}} .sems-playlist-form input[type="file"], {{WRAPPER}} .sems-playlist-products' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tracks_panel_max_height',
            [
                'label' => esc_html__('Tracks Panel Max Height', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 120, 'max' => 800]],
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-products' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto; overflow-x: hidden;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_button_style_controls(): void {
        $this->start_controls_section(
            'section_style_buttons',
            [
                'label' => esc_html__('Buttons', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Button Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-actions button, {{WRAPPER}} .sems-submit-playlist' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Button Text Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-actions button, {{WRAPPER}} .sems-submit-playlist' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => esc_html__('Button Border Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-actions button, {{WRAPPER}} .sems-submit-playlist' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_width',
            [
                'label' => esc_html__('Button Border Width', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-actions button, {{WRAPPER}} .sems-submit-playlist' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Button Border Radius', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-actions button, {{WRAPPER}} .sems-submit-playlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .sems-playlist-actions button, {{WRAPPER}} .sems-submit-playlist',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        if (!class_exists('WooCommerce')) {
            echo '<div class="sems-playlist-message sems-playlist-message--error">' . esc_html__('WooCommerce is required for this widget.', 'spotify-elementor-sidebar-menu') . '</div>';
            return;
        }

        if (!is_user_logged_in()) {
            echo '<div class="sems-playlist-message sems-playlist-message--error">' . esc_html__('Please log in to create a playlist.', 'spotify-elementor-sidebar-menu') . '</div>';
            return;
        }

        $settings = $this->get_settings_for_display();
        $products = SEMS_Playlists::get_all_published_product_ids();
        $tag_options = SEMS_Playlists::get_product_tag_options();

        if (empty($products)) {
            echo '<div class="sems-playlist-message sems-playlist-message--error">' . esc_html__('No published tracks are available yet.', 'spotify-elementor-sidebar-menu') . '</div>';
            return;
        }

        $status = isset($_GET['sems_playlist_status']) ? sanitize_key(wp_unslash($_GET['sems_playlist_status'])) : '';
        $message = isset($_GET['sems_playlist_message']) ? sanitize_text_field(rawurldecode(wp_unslash($_GET['sems_playlist_message']))) : '';

        if (in_array($status, ['success', 'error'], true) && '' !== $message) {
            $status_class = 'success' === $status ? 'sems-playlist-message--success' : 'sems-playlist-message--error';
            echo '<div class="sems-playlist-message ' . esc_attr($status_class) . '">' . esc_html($message) . '</div>';
        }

        $playlist_name_id = 'sems-playlist-name-' . esc_attr($this->get_id());

        echo '<div class="sems-playlist-create">';
        if (!empty($settings['title'])) {
            echo '<h3 class="sems-playlist-create__title">' . esc_html($settings['title']) . '</h3>';
        }

        if (!empty($settings['description'])) {
            echo '<p class="sems-playlist-create__description">' . esc_html($settings['description']) . '</p>';
        }

        echo '<form class="sems-playlist-form" method="post" action="' . esc_url(admin_url('admin-post.php')) . '" enctype="multipart/form-data">';
        echo '<input type="hidden" name="action" value="sems_create_playlist" />';
        echo '<input type="hidden" name="sems_return_url" value="' . esc_url(get_permalink()) . '" />';
        wp_nonce_field('sems_create_playlist', 'sems_create_playlist_nonce');

        echo '<div class="sems-playlist-steps">';
        echo '<span class="is-active">1. ' . esc_html__('Details', 'spotify-elementor-sidebar-menu') . '</span>';
        echo '<span>2. ' . esc_html__('Tracks', 'spotify-elementor-sidebar-menu') . '</span>';
        echo '<span>3. ' . esc_html__('Save', 'spotify-elementor-sidebar-menu') . '</span>';
        echo '</div>';

        echo '<div class="sems-playlist-step is-active" data-step="1">';
        echo '<label for="' . esc_attr($playlist_name_id) . '">' . esc_html__('Playlist Name', 'spotify-elementor-sidebar-menu') . '</label>';
        echo '<input id="' . esc_attr($playlist_name_id) . '" type="text" name="playlist_name" required maxlength="120" />';
        echo '<label>' . esc_html__('Cover Image (max 2MB)', 'spotify-elementor-sidebar-menu') . '</label>';
        echo '<input type="file" name="playlist_cover" accept="image/*" />';
        echo '<p class="sems-playlist-note">' . esc_html__('Accepted: image files up to 2MB.', 'spotify-elementor-sidebar-menu') . '</p>';
        echo '<div class="sems-playlist-actions">';
        echo '<button type="button" class="sems-next-step">' . esc_html__('Next', 'spotify-elementor-sidebar-menu') . '</button>';
        echo '</div>';
        echo '</div>';

        echo '<div class="sems-playlist-step" data-step="2">';
        echo '<p class="sems-playlist-step-title">' . esc_html__('Select tracks', 'spotify-elementor-sidebar-menu') . '</p>';

        if (!empty($tag_options)) {
            echo '<label class="sems-playlist-filter-label" for="sems-track-tag-filter-' . esc_attr($this->get_id()) . '">' . esc_html__('Filter by tag', 'spotify-elementor-sidebar-menu') . '</label>';
            echo '<select id="sems-track-tag-filter-' . esc_attr($this->get_id()) . '" class="sems-track-tag-filter">';
            echo '<option value="">' . esc_html__('All tags', 'spotify-elementor-sidebar-menu') . '</option>';

            foreach ($tag_options as $tag_option) {
                echo '<option value="' . esc_attr((string) $tag_option['id']) . '">' . esc_html($tag_option['name']) . '</option>';
            }

            echo '</select>';
        }

        echo '<div class="sems-playlist-products">';

        foreach ($products as $product_id) {
            $product = wc_get_product($product_id);
            if (!$product) {
                continue;
            }

            $term_ids = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'ids']);
            if (is_wp_error($term_ids) || !is_array($term_ids)) {
                $term_ids = [];
            }

            $term_ids = array_filter(array_map('intval', $term_ids));
            $tag_ids_attr = implode(',', $term_ids);

            echo '<label class="sems-playlist-product" data-tag-ids="' . esc_attr($tag_ids_attr) . '">';
            echo '<input type="checkbox" name="playlist_products[]" value="' . esc_attr((string) $product_id) . '" />';
            echo '<span>' . esc_html($product->get_name()) . '</span>';
            echo '</label>';
        }

        echo '</div>';
        echo '<div class="sems-playlist-actions">';
        echo '<button type="button" class="sems-prev-step">' . esc_html__('Back', 'spotify-elementor-sidebar-menu') . '</button>';
        echo '<button type="button" class="sems-next-step">' . esc_html__('Next', 'spotify-elementor-sidebar-menu') . '</button>';
        echo '</div>';
        echo '</div>';

        echo '<div class="sems-playlist-step" data-step="3">';
        echo '<p class="sems-playlist-step-title">' . esc_html__('Review and save your playlist', 'spotify-elementor-sidebar-menu') . '</p>';
        echo '<ul class="sems-playlist-summary">';
        echo '<li>' . esc_html__('Name and cover image are set in step 1.', 'spotify-elementor-sidebar-menu') . '</li>';
        echo '<li>' . esc_html__('Selected tracks are chosen in step 2.', 'spotify-elementor-sidebar-menu') . '</li>';
        echo '</ul>';
        echo '<div class="sems-playlist-actions">';
        echo '<button type="button" class="sems-prev-step">' . esc_html__('Back', 'spotify-elementor-sidebar-menu') . '</button>';
        echo '<button type="submit" class="sems-submit-playlist">' . esc_html($settings['submit_label'] ?: 'Save Playlist') . '</button>';
        echo '</div>';
        echo '</div>';

        echo '</form>';
        echo '</div>';

        $this->render_step_script();
    }

    private function render_step_script(): void {
        ?>
        <script>
            (function () {
                var root = document.currentScript ? document.currentScript.previousElementSibling : null;
                if (!root || !root.classList.contains('sems-playlist-create')) {
                    return;
                }

                var form = root.querySelector('.sems-playlist-form');
                if (!form) {
                    return;
                }

                var steps = Array.prototype.slice.call(form.querySelectorAll('.sems-playlist-step'));
                var indicators = Array.prototype.slice.call(form.querySelectorAll('.sems-playlist-steps span'));
                var stepIndex = 0;

                var setStep = function (index) {
                    stepIndex = Math.max(0, Math.min(index, steps.length - 1));

                    steps.forEach(function (step, idx) {
                        step.classList.toggle('is-active', idx === stepIndex);
                    });

                    indicators.forEach(function (indicator, idx) {
                        indicator.classList.toggle('is-active', idx === stepIndex);
                    });
                };

                form.querySelectorAll('.sems-next-step').forEach(function (button) {
                    button.addEventListener('click', function () {
                        setStep(stepIndex + 1);
                    });
                });

                form.querySelectorAll('.sems-prev-step').forEach(function (button) {
                    button.addEventListener('click', function () {
                        setStep(stepIndex - 1);
                    });
                });

                var tagFilter = form.querySelector('.sems-track-tag-filter');
                if (tagFilter) {
                    tagFilter.addEventListener('change', function () {
                        var selectedTag = tagFilter.value;
                        var trackItems = form.querySelectorAll('.sems-playlist-product');

                        trackItems.forEach(function (item) {
                            if (!selectedTag) {
                                item.style.display = '';
                                return;
                            }

                            var tagIds = item.getAttribute('data-tag-ids') || '';
                            var tags = tagIds.split(',').map(function (tag) {
                                return tag.trim();
                            }).filter(Boolean);

                            item.style.display = tags.indexOf(selectedTag) !== -1 ? '' : 'none';
                        });
                    });
                }

                form.addEventListener('submit', function (event) {
                    var hasProduct = form.querySelector('input[name="playlist_products[]"]:checked');
                    if (!hasProduct) {
                        event.preventDefault();
                        setStep(1);
                        window.alert('Please select at least one track.');
                    }
                });
            })();
        </script>
        <?php
    }
}
