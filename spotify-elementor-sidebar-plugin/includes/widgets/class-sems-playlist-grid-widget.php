<?php

if (!defined('ABSPATH')) {
    exit;
}

class SEMS_Playlist_Grid_Widget extends \Elementor\Widget_Base {
    public function get_name(): string {
        return 'sems_playlist_grid';
    }

    public function get_title(): string {
        return esc_html__('Playlist Grid', 'spotify-elementor-sidebar-menu');
    }

    public function get_icon(): string {
        return 'eicon-gallery-grid';
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
            'only_current_user',
            [
                'label' => esc_html__('Only Current User Playlists', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'spotify-elementor-sidebar-menu'),
                'label_off' => esc_html__('No', 'spotify-elementor-sidebar-menu'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'items_per_page',
            [
                'label' => esc_html__('Items', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50,
                'default' => 12,
            ]
        );

        $this->add_control(
            'products_limit',
            [
                'label' => esc_html__('Tracks per Playlist', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 200,
                'default' => 50,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Layout', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['custom'],
                'range' => [
                    'custom' => [
                        'min' => 1,
                        'max' => 6,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-grid' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => esc_html__('Card Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-card' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label' => esc_html__('Card Border Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Playlist Name Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-card__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .sems-playlist-card__title',
            ]
        );

        $this->add_control(
            'tracks_heading_color',
            [
                'label' => esc_html__('Tracks Label Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-card__tracks-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'track_link_color',
            [
                'label' => esc_html__('Track Link Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-card__products a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'track_link_hover_color',
            [
                'label' => esc_html__('Track Link Hover Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-playlist-card__products a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'track_typography',
                'selector' => '{{WRAPPER}} .sems-playlist-card__tracks-heading, {{WRAPPER}} .sems-playlist-card__products',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $items_per_page = isset($settings['items_per_page']) ? (int) $settings['items_per_page'] : 12;
        if ($items_per_page <= 0) {
            $items_per_page = 12;
        }

        $query_args = [
            'post_type' => SEMS_Playlists::get_post_type(),
            'post_status' => 'publish',
            'posts_per_page' => $items_per_page,
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $only_current_user = isset($settings['only_current_user']) && 'yes' === $settings['only_current_user'];
        if ($only_current_user) {
            if (!is_user_logged_in()) {
                echo '<div class="sems-playlist-message sems-playlist-message--error">' . esc_html__('Please log in to view your playlists.', 'spotify-elementor-sidebar-menu') . '</div>';
                return;
            }

            $query_args['author'] = get_current_user_id();
        }

        $playlists = new WP_Query($query_args);

        if (!$playlists->have_posts()) {
            echo '<div class="sems-playlist-message">' . esc_html__('No playlists found.', 'spotify-elementor-sidebar-menu') . '</div>';
            return;
        }

        $products_limit = isset($settings['products_limit']) ? (int) $settings['products_limit'] : 8;
        if ($products_limit <= 0) {
            $products_limit = 8;
        }

        echo '<div class="sems-playlist-grid">';
        while ($playlists->have_posts()) {
            $playlists->the_post();

            $playlist_id = get_the_ID();
            $cover_url = get_the_post_thumbnail_url($playlist_id, 'medium');
            $product_ids = get_post_meta($playlist_id, '_sems_playlist_products', true);
            if (!is_array($product_ids)) {
                $product_ids = [];
            }

            $product_ids = array_slice(array_map('intval', $product_ids), 0, $products_limit);

            echo '<article class="sems-playlist-card">';
            if (!empty($cover_url)) {
                echo '<img class="sems-playlist-card__cover" src="' . esc_url($cover_url) . '" alt="' . esc_attr(get_the_title()) . '" />';
            } else {
                echo '<div class="sems-playlist-card__cover sems-playlist-card__cover--empty"></div>';
            }

            echo '<h4 class="sems-playlist-card__title">' . esc_html(get_the_title()) . '</h4>';
            echo '<p class="sems-playlist-card__tracks-heading">' . esc_html__('Tracks', 'spotify-elementor-sidebar-menu') . '</p>';
            echo '<ul class="sems-playlist-card__products">';

            foreach ($product_ids as $product_id) {
                $product = wc_get_product($product_id);
                if (!$product) {
                    continue;
                }

                echo '<li><a href="' . esc_url(get_permalink($product_id)) . '">' . esc_html($product->get_name()) . '</a></li>';
            }

            echo '</ul>';
            echo '</article>';
        }
        echo '</div>';

        wp_reset_postdata();
    }
}
