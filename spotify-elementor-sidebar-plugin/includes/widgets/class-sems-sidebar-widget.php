<?php

if (!defined('ABSPATH')) {
    exit;
}

class SEMS_Sidebar_Widget extends \Elementor\Widget_Base {
    public function get_name(): string {
        return 'sems_spotify_sidebar';
    }

    public function get_title(): string {
        return esc_html__('Air Music Now Sidebar', 'spotify-elementor-sidebar-menu');
    }

    public function get_icon(): string {
        return 'eicon-nav-menu';
    }

    public function get_categories(): array {
        return ['general'];
    }

    public function get_style_depends(): array {
        return ['sems-sidebar-menu', 'elementor-icons'];
    }

    protected function register_controls(): void {
        $this->register_brand_controls();
        $this->register_main_menu_controls();
        $this->register_shortcut_controls();
        $this->register_mobile_floating_controls();
        $this->register_footer_controls();
        $this->register_language_controls();
        $this->register_toggle_controls();
        $this->register_style_controls();
    }

    private function register_brand_controls(): void {
        $this->start_controls_section(
            'section_brand',
            [
                'label' => esc_html__('Brand', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'brand_logo',
            [
                'label' => esc_html__('Logo Image', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'brand_logo_collapsed',
            [
                'label' => esc_html__('Collapsed Logo Image', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'description' => esc_html__('Used when the sidebar is collapsed.', 'spotify-elementor-sidebar-menu'),
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_main_menu_controls(): void {
        $this->start_controls_section(
            'section_main_menu',
            [
                'label' => esc_html__('Main Menu', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'label',
            [
                'label' => esc_html__('Label', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Menu Item', 'spotify-elementor-sidebar-menu'),
            ]
        );

        $repeater->add_control(
            'url',
            [
                'label' => esc_html__('Link', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'eicon-angle-right',
                    'library' => 'eicons',
                ],
            ]
        );

        $repeater->add_control(
            'is_active',
            [
                'label' => esc_html__('Active Item', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'spotify-elementor-sidebar-menu'),
                'label_off' => esc_html__('No', 'spotify-elementor-sidebar-menu'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'main_menu_items',
            [
                'label' => esc_html__('Main Menu Items', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'label' => 'Home',
                        'url' => ['url' => '#'],
                        'icon' => ['value' => 'eicon-home', 'library' => 'eicons'],
                        'is_active' => 'yes',
                    ],
                    [
                        'label' => 'Search',
                        'url' => ['url' => '#'],
                        'icon' => ['value' => 'eicon-search', 'library' => 'eicons'],
                    ],
                    [
                        'label' => 'Your Library',
                        'url' => ['url' => '#'],
                        'icon' => ['value' => 'eicon-slider-push', 'library' => 'eicons'],
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function register_shortcut_controls(): void {
        $this->start_controls_section(
            'section_shortcuts',
            [
                'label' => esc_html__('Shortcut Menu', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'label',
            [
                'label' => esc_html__('Label', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Shortcut Item', 'spotify-elementor-sidebar-menu'),
            ]
        );

        $repeater->add_control(
            'url',
            [
                'label' => esc_html__('Link', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'eicon-plus',
                    'library' => 'eicons',
                ],
            ]
        );

        $repeater->add_control(
            'badge_style',
            [
                'label' => esc_html__('Icon Badge Style', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'plus',
                'options' => [
                    'plus' => esc_html__('Gray', 'spotify-elementor-sidebar-menu'),
                    'liked' => esc_html__('Liked Gradient', 'spotify-elementor-sidebar-menu'),
                    'custom' => esc_html__('No Background', 'spotify-elementor-sidebar-menu'),
                ],
            ]
        );

        $this->add_control(
            'shortcut_items',
            [
                'label' => esc_html__('Shortcut Items', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'label' => 'Create Playlist',
                        'url' => ['url' => '#'],
                        'icon' => ['value' => 'eicon-plus', 'library' => 'eicons'],
                        'badge_style' => 'plus',
                    ],
                    [
                        'label' => 'Liked Songs',
                        'url' => ['url' => '#'],
                        'icon' => ['value' => 'eicon-heart', 'library' => 'eicons'],
                        'badge_style' => 'liked',
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function register_footer_controls(): void {
        $this->start_controls_section(
            'section_footer',
            [
                'label' => esc_html__('Footer Links', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'footer_links',
            [
                'label' => esc_html__('Footer Links (one per line)', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => "Legal\nPrivacy Center\nPrivacy Policy\nCookies\nAbout Ads\nCookies",
                'rows' => 6,
            ]
        );

        $this->end_controls_section();
    }

    private function register_mobile_floating_controls(): void {
        $this->start_controls_section(
            'section_mobile_floating',
            [
                'label' => esc_html__('Mobile Floating Buttons', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_mobile_floating_buttons',
            [
                'label' => esc_html__('Enable Mobile Floating Buttons', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'spotify-elementor-sidebar-menu'),
                'label_off' => esc_html__('No', 'spotify-elementor-sidebar-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mobile_floating_home_url',
            [
                'label' => esc_html__('Home Link', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => ['url' => '#'],
                'condition' => ['enable_mobile_floating_buttons' => 'yes'],
            ]
        );

        $this->add_control(
            'mobile_floating_library_url',
            [
                'label' => esc_html__('Your Library Link', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => ['url' => '#'],
                'condition' => ['enable_mobile_floating_buttons' => 'yes'],
            ]
        );

        $this->add_control(
            'mobile_floating_create_url',
            [
                'label' => esc_html__('Create Playlists Link', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => ['url' => '#'],
                'condition' => ['enable_mobile_floating_buttons' => 'yes'],
            ]
        );

        $this->add_control(
            'mobile_floating_favorite_url',
            [
                'label' => esc_html__('Favorite Link', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => ['url' => '#'],
                'condition' => ['enable_mobile_floating_buttons' => 'yes'],
            ]
        );

        $this->end_controls_section();
    }

    private function register_language_controls(): void {
        $this->start_controls_section(
            'section_language',
            [
                'label' => esc_html__('Language Button', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'use_translatepress',
            [
                'label' => esc_html__('Use TranslatePress Switcher', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'spotify-elementor-sidebar-menu'),
                'label_off' => esc_html__('No', 'spotify-elementor-sidebar-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'language_button_label',
            [
                'label' => esc_html__('Fallback Label', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'English',
                'condition' => [
                    'use_translatepress!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'language_button_url',
            [
                'label' => esc_html__('Fallback Link', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'use_translatepress!' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_toggle_controls(): void {
        $this->start_controls_section(
            'section_toggle',
            [
                'label' => esc_html__('Menu Toggle', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_menu_toggle',
            [
                'label' => esc_html__('Enable Toggle', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'spotify-elementor-sidebar-menu'),
                'label_off' => esc_html__('No', 'spotify-elementor-sidebar-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'collapsed_default',
            [
                'label' => esc_html__('Collapsed by Default', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'spotify-elementor-sidebar-menu'),
                'label_off' => esc_html__('No', 'spotify-elementor-sidebar-menu'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'enable_menu_toggle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'auto_close_mobile',
            [
                'label' => esc_html__('Auto Close on Mobile', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'spotify-elementor-sidebar-menu'),
                'label_off' => esc_html__('No', 'spotify-elementor-sidebar-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__('Keeps menu collapsed on mobile and auto-collapses after tapping a menu item.', 'spotify-elementor-sidebar-menu'),
                'condition' => [
                    'enable_menu_toggle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'toggle_close_icon',
            [
                'label' => esc_html__('Close Icon', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'eicon-close',
                    'library' => 'eicons',
                ],
                'condition' => [
                    'enable_menu_toggle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'toggle_open_icon',
            [
                'label' => esc_html__('Open Icon', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'eicon-menu-bar',
                    'library' => 'eicons',
                ],
                'condition' => [
                    'enable_menu_toggle' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls(): void {
        $this->register_style_sidebar_controls();
        $this->register_style_brand_controls();
        $this->register_style_menu_controls();
        $this->register_style_shortcuts_controls();
        $this->register_style_footer_controls();
        $this->register_style_language_controls();
        $this->register_style_toggle_controls();
        $this->register_style_mobile_floating_controls();
    }

    private function register_style_sidebar_controls(): void {
        $this->start_controls_section(
            'style_sidebar',
            [
                'label' => esc_html__('Sidebar', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sidebar_background_color',
            [
                'label' => esc_html__('Background Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sidebar_width',
            [
                'label' => esc_html__('Open Width', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 180, 'max' => 420]],
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sidebar_closed_width',
            [
                'label' => esc_html__('Closed Width', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 56, 'max' => 220]],
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar.is-collapsed' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sidebar_padding',
            [
                'label' => esc_html__('Inner Padding', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_brand_controls(): void {
        $this->start_controls_section(
            'style_brand',
            [
                'label' => esc_html__('Logo', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'brand_logo_color',
            [
                'label' => esc_html__('Logo Color (SVG)', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-brand__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'brand_logo_max_height',
            [
                'label' => esc_html__('Logo Max Height', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 20, 'max' => 140]],
                'selectors' => [
                    '{{WRAPPER}} .sems-brand__icon' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_menu_controls(): void {
        $this->start_controls_section(
            'style_main_menu',
            [
                'label' => esc_html__('Main Menu', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'menu_item_color',
            [
                'label' => esc_html__('Item Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-main-nav__item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_item_hover_color',
            [
                'label' => esc_html__('Hover Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-main-nav__item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_item_active_color',
            [
                'label' => esc_html__('Active Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-main-nav__item.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'selector' => '{{WRAPPER}} .sems-main-nav__item',
            ]
        );

        $this->add_responsive_control(
            'menu_icon_size',
            [
                'label' => esc_html__('Icon Size', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 10, 'max' => 40]],
                'selectors' => [
                    '{{WRAPPER}} .sems-main-nav .sems-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .sems-main-nav .sems-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_items_padding',
            [
                'label' => esc_html__('Items Padding', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .sems-main-nav__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .sems-shortcuts__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'description' => esc_html__('Use the responsive device tabs to set mobile-specific padding.', 'spotify-elementor-sidebar-menu'),
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_shortcuts_controls(): void {
        $this->start_controls_section(
            'style_shortcuts',
            [
                'label' => esc_html__('Shortcut Menu', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'shortcut_text_color',
            [
                'label' => esc_html__('Text Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-shortcuts__item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'shortcut_hover_color',
            [
                'label' => esc_html__('Hover Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-shortcuts__item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'shortcut_typography',
                'selector' => '{{WRAPPER}} .sems-shortcuts__item',
            ]
        );

        $this->add_control(
            'shortcut_badge_plus_bg',
            [
                'label' => esc_html__('Plus Badge Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-square-icon--plus' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'shortcut_badge_plus_color',
            [
                'label' => esc_html__('Plus Badge Icon Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-square-icon--plus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'shortcut_badge_liked_color',
            [
                'label' => esc_html__('Liked Badge Icon Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-square-icon--liked' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_footer_controls(): void {
        $this->start_controls_section(
            'style_footer',
            [
                'label' => esc_html__('Footer Links', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'footer_color',
            [
                'label' => esc_html__('Text Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-footer-links a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_hover_color',
            [
                'label' => esc_html__('Hover Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-footer-links a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'footer_typography',
                'selector' => '{{WRAPPER}} .sems-footer-links a',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_language_controls(): void {
        $this->start_controls_section(
            'style_language',
            [
                'label' => esc_html__('Language Button', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'language_color',
            [
                'label' => esc_html__('Text Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-language-btn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .sems-language-switcher .trp-ls-shortcode-current-language > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'language_border_color',
            [
                'label' => esc_html__('Border Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-language-btn' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .sems-language-switcher .trp-ls-shortcode-current-language > a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'language_typography',
                'selector' => '{{WRAPPER}} .sems-language-btn, {{WRAPPER}} .sems-language-switcher .trp-ls-shortcode-current-language > a',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_toggle_controls(): void {
        $this->start_controls_section(
            'style_toggle',
            [
                'label' => esc_html__('Toggle Button', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_menu_toggle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'toggle_icon_color',
            [
                'label' => esc_html__('Icon Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar__toggle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_bg_color',
            [
                'label' => esc_html__('Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar__toggle' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_icon_size',
            [
                'label' => esc_html__('Icon Size', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 10, 'max' => 30]],
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar__toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .sems-sidebar__toggle i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_position_top',
            [
                'label' => esc_html__('Top Offset', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 120]],
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar__toggle' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_position_right',
            [
                'label' => esc_html__('Right Offset', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 120]],
                'selectors' => [
                    '{{WRAPPER}} .sems-sidebar__toggle' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_mobile_floating_controls(): void {
        $this->start_controls_section(
            'style_mobile_floating_buttons',
            [
                'label' => esc_html__('Mobile Floating Buttons', 'spotify-elementor-sidebar-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_mobile_floating_buttons' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mobile_floating_bg_color',
            [
                'label' => esc_html__('Container Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_floating_border_color',
            [
                'label' => esc_html__('Container Border Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_floating_blur',
            [
                'label' => esc_html__('Glass Blur', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav' => '-webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}) saturate(170%); backdrop-filter: blur({{SIZE}}{{UNIT}}) saturate(170%);',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_floating_radius',
            [
                'label' => esc_html__('Container Radius', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_floating_item_color',
            [
                'label' => esc_html__('Button Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav__item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_floating_item_hover_color',
            [
                'label' => esc_html__('Button Hover Color', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav__item:hover, {{WRAPPER}} .sems-mobile-floating-nav__item:focus-visible' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_floating_item_hover_bg',
            [
                'label' => esc_html__('Button Hover Background', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav__item:hover, {{WRAPPER}} .sems-mobile-floating-nav__item:focus-visible' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mobile_floating_label_typography',
                'selector' => '{{WRAPPER}} .sems-mobile-floating-nav__label',
            ]
        );

        $this->add_responsive_control(
            'mobile_floating_icon_size',
            [
                'label' => esc_html__('Icon Size', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 10, 'max' => 40]],
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .sems-mobile-floating-nav__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_floating_item_padding',
            [
                'label' => esc_html__('Button Padding', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .sems-mobile-floating-nav__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        $collapsed = isset($settings['collapsed_default']) && 'yes' === $settings['collapsed_default'];
        $enable_toggle = isset($settings['enable_menu_toggle']) && 'yes' === $settings['enable_menu_toggle'];

        $sidebar_classes = 'sems-sidebar';
        if ($collapsed) {
            $sidebar_classes .= ' is-collapsed';
        }

        $sidebar_id = 'sems-sidebar-' . esc_attr($this->get_id());

        ?>
        <aside id="<?php echo esc_attr($sidebar_id); ?>" class="<?php echo esc_attr($sidebar_classes); ?>" aria-label="Sidebar menu">
            <div class="sems-sidebar__inner">
                <?php if ($enable_toggle) : ?>
                    <?php $this->render_toggle_button($collapsed, $settings); ?>
                <?php endif; ?>

                <?php $this->render_brand($settings); ?>

                <nav class="sems-main-nav" aria-label="Main navigation">
                    <?php $this->render_main_menu_items($settings['main_menu_items'] ?? []); ?>
                </nav>

                <div class="sems-shortcuts">
                    <?php $this->render_shortcut_items($settings['shortcut_items'] ?? []); ?>
                </div>

                <?php $this->render_footer_links($settings['footer_links'] ?? ''); ?>

                <?php $this->render_language_switcher($settings); ?>
            </div>
        </aside>
        <?php

        $this->render_mobile_floating_nav($settings);

        $this->render_toggle_script($settings, $sidebar_id);
    }

    private function render_toggle_button(bool $collapsed, array $settings): void {
        $expanded = !$collapsed;
        $close_icon = $settings['toggle_close_icon'] ?? ['value' => 'eicon-close', 'library' => 'eicons'];
        $open_icon = $settings['toggle_open_icon'] ?? ['value' => 'eicon-menu-bar', 'library' => 'eicons'];

        echo '<button type="button" class="sems-sidebar__toggle" aria-expanded="' . ($expanded ? 'true' : 'false') . '">';
        echo '<span class="sems-sidebar__toggle-close" aria-hidden="true">';
        \Elementor\Icons_Manager::render_icon($close_icon, ['aria-hidden' => 'true']);
        echo '</span>';
        echo '<span class="sems-sidebar__toggle-open" aria-hidden="true">';
        \Elementor\Icons_Manager::render_icon($open_icon, ['aria-hidden' => 'true']);
        echo '</span>';
        echo '<span class="screen-reader-text">' . esc_html__('Toggle sidebar menu', 'spotify-elementor-sidebar-menu') . '</span>';
        echo '</button>';
    }

    private function render_mobile_launcher(array $settings, string $sidebar_id): void {
        $launcher_logo = '';
        if (!empty($settings['brand_logo']['url'])) {
            $launcher_logo = $settings['brand_logo']['url'];
        } elseif (!empty($settings['brand_logo_collapsed']['url'])) {
            $launcher_logo = $settings['brand_logo_collapsed']['url'];
        }

        $open_icon = $settings['toggle_open_icon'] ?? ['value' => 'eicon-menu-bar', 'library' => 'eicons'];

        echo '<button type="button" class="sems-mobile-launcher" data-target="' . esc_attr($sidebar_id) . '" aria-expanded="false" aria-label="' . esc_attr__('Open sidebar menu', 'spotify-elementor-sidebar-menu') . '">';
        echo '<span class="sems-mobile-launcher__logo" aria-hidden="true">';

        if (!empty($launcher_logo)) {
            echo '<img src="' . esc_url($launcher_logo) . '" alt="Logo" />';
        } else {
            echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="12" fill="currentColor" /><path d="M6.1 8.9c3.7-1.1 8-0.8 11.5 1 .4.2.6.7.4 1.1-.2.4-.7.6-1.1.4-3.1-1.6-7-1.9-10.4-.9-.4.1-.9-.1-1-.6-.1-.4.1-.9.6-1z" fill="#000"/><path d="M6.9 11.8c3-0.9 6.5-0.7 9.2.7.4.2.5.6.3 1-.2.4-.6.5-1 .3-2.4-1.2-5.4-1.4-8.1-.6-.4.1-.8-.1-.9-.5-.1-.4.1-.8.5-.9z" fill="#000"/><path d="M7.7 14.5c2.3-.7 4.8-.5 6.9.5.3.2.4.5.3.8-.2.3-.5.4-.8.3-1.8-.9-4-.9-5.9-.4-.3.1-.7-.1-.8-.4-.1-.3.1-.7.4-.8z" fill="#000"/></svg>';
        }

        echo '</span>';
        echo '<span class="sems-mobile-launcher__icon" aria-hidden="true">';
        \Elementor\Icons_Manager::render_icon($open_icon, ['aria-hidden' => 'true']);
        echo '</span>';
        echo '</button>';
    }

    private function render_mobile_floating_nav(array $settings): void {
        if (!isset($settings['enable_mobile_floating_buttons']) || 'yes' !== $settings['enable_mobile_floating_buttons']) {
            return;
        }

        $items = [
            [
                'label' => esc_html__('Home', 'spotify-elementor-sidebar-menu'),
                'url' => $settings['mobile_floating_home_url'] ?? ['url' => '#'],
                'icon' => $this->get_main_menu_fallback_icon(0),
            ],
            [
                'label' => esc_html__('Your Library', 'spotify-elementor-sidebar-menu'),
                'url' => $settings['mobile_floating_library_url'] ?? ['url' => '#'],
                'icon' => $this->get_main_menu_fallback_icon(2),
            ],
            [
                'label' => esc_html__('Create Playlists', 'spotify-elementor-sidebar-menu'),
                'url' => $settings['mobile_floating_create_url'] ?? ['url' => '#'],
                'icon' => $this->get_shortcut_fallback_icon('plus'),
            ],
            [
                'label' => esc_html__('Favorite', 'spotify-elementor-sidebar-menu'),
                'url' => $settings['mobile_floating_favorite_url'] ?? ['url' => '#'],
                'icon' => $this->get_shortcut_fallback_icon('liked'),
            ],
        ];

        echo '<nav class="sems-mobile-floating-nav" aria-label="' . esc_attr__('Mobile quick actions', 'spotify-elementor-sidebar-menu') . '">';
        foreach ($items as $item) {
            $this->render_mobile_floating_nav_item($item['label'], $item['url'], $item['icon']);
        }
        echo '</nav>';
    }

    private function render_mobile_floating_nav_item(string $label, array $url_settings, string $icon_markup): void {
        $url = '#';
        if (!empty($url_settings['url'])) {
            $url = $url_settings['url'];
        }

        echo '<a class="sems-mobile-floating-nav__item" href="' . esc_url($url) . '"';

        if (!empty($url_settings['is_external'])) {
            echo ' target="_blank"';
        }

        if (!empty($url_settings['nofollow'])) {
            echo ' rel="nofollow"';
        }

        echo '>';
        echo '<span class="sems-mobile-floating-nav__icon" aria-hidden="true">' . $icon_markup . '</span>';
        echo '<span class="sems-mobile-floating-nav__label">' . esc_html($label) . '</span>';
        echo '</a>';
    }

    private function render_brand(array $settings): void {
        $logo_url = '';
        $collapsed_logo_url = '';

        if (!empty($settings['brand_logo']['url'])) {
            $logo_url = $settings['brand_logo']['url'];
        }

        if (!empty($settings['brand_logo_collapsed']['url'])) {
            $collapsed_logo_url = $settings['brand_logo_collapsed']['url'];
        }

        $brand_class = 'sems-brand';
        if (!empty($collapsed_logo_url)) {
            $brand_class .= ' has-collapsed-logo';
        }

        ?>
        <div class="<?php echo esc_attr($brand_class); ?>" role="img" aria-label="Logo">
            <div class="sems-brand__icon">
                <?php if (!empty($logo_url)) : ?>
                    <img class="sems-brand__img sems-brand__img--expanded" src="<?php echo esc_url($logo_url); ?>" alt="Logo" />
                <?php else : ?>
                    <svg class="sems-brand__img sems-brand__img--expanded" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="12" cy="12" r="12" fill="currentColor" />
                        <path d="M6.1 8.9c3.7-1.1 8-0.8 11.5 1 .4.2.6.7.4 1.1-.2.4-.7.6-1.1.4-3.1-1.6-7-1.9-10.4-.9-.4.1-.9-.1-1-.6-.1-.4.1-.9.6-1z" fill="#000"/>
                        <path d="M6.9 11.8c3-0.9 6.5-0.7 9.2.7.4.2.5.6.3 1-.2.4-.6.5-1 .3-2.4-1.2-5.4-1.4-8.1-.6-.4.1-.8-.1-.9-.5-.1-.4.1-.8.5-.9z" fill="#000"/>
                        <path d="M7.7 14.5c2.3-.7 4.8-.5 6.9.5.3.2.4.5.3.8-.2.3-.5.4-.8.3-1.8-.9-4-.9-5.9-.4-.3.1-.7-.1-.8-.4-.1-.3.1-.7.4-.8z" fill="#000"/>
                    </svg>
                <?php endif; ?>

                <?php if (!empty($collapsed_logo_url)) : ?>
                    <img class="sems-brand__img sems-brand__img--collapsed" src="<?php echo esc_url($collapsed_logo_url); ?>" alt="Collapsed Logo" />
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function render_main_menu_items(array $items): void {
        if (empty($items)) {
            return;
        }

        foreach ($items as $index => $item) {
            $label = $item['label'] ?? '';
            if ('' === trim($label)) {
                continue;
            }

            $is_active = !empty($item['is_active']) && 'yes' === $item['is_active'];
            $classes = 'sems-main-nav__item';
            if ($is_active) {
                $classes .= ' is-active';
            }

            $this->render_link_open($item['url'] ?? [], $classes, 'menu-item-' . $index, $label);
            echo '<span class="sems-icon">';
            echo $this->get_icon_markup($item['icon'] ?? [], $this->get_main_menu_fallback_icon($index));
            echo '</span>';
            echo '<span class="sems-item-label">' . esc_html($label) . '</span>';
            echo '</a>';
        }
    }

    private function render_shortcut_items(array $items): void {
        if (empty($items)) {
            return;
        }

        foreach ($items as $index => $item) {
            $label = $item['label'] ?? '';
            if ('' === trim($label)) {
                continue;
            }

            $badge_style = $item['badge_style'] ?? 'plus';
            $badge_classes = 'sems-square-icon sems-square-icon--' . sanitize_html_class($badge_style);

            $this->render_link_open($item['url'] ?? [], 'sems-shortcuts__item', 'shortcut-item-' . $index, $label);
            echo '<span class="' . esc_attr($badge_classes) . '">';
            echo $this->get_icon_markup($item['icon'] ?? [], $this->get_shortcut_fallback_icon($badge_style));
            echo '</span>';
            echo '<span class="sems-item-label">' . esc_html($label) . '</span>';
            echo '</a>';
        }
    }

    private function get_icon_markup(array $icon_settings, string $fallback_svg): string {
        if (!empty($icon_settings['value'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($icon_settings, ['aria-hidden' => 'true']);
            $icon_markup = trim((string) ob_get_clean());
            if ('' !== $icon_markup) {
                return $icon_markup;
            }
        }

        return $fallback_svg;
    }

    private function get_main_menu_fallback_icon(int $index): string {
        if (0 === $index) {
            return '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M12 3l9 7v11h-6v-7H9v7H3V10l9-7z"/></svg>';
        }

        if (1 === $index) {
            return '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M10.5 3a7.5 7.5 0 015.96 12.06l4.24 4.24-1.41 1.41-4.24-4.24A7.5 7.5 0 1110.5 3zm0 2a5.5 5.5 0 100 11 5.5 5.5 0 000-11z"/></svg>';
        }

        if (2 === $index) {
            return '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M3 4h2v16H3V4zm4 2h2v14H7V6zm4-2h2v16h-2V4zm4 4h2v12h-2V8zm4-2h2v14h-2V6z"/></svg>';
        }

        return '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="8" fill="currentColor"/></svg>';
    }

    private function get_shortcut_fallback_icon(string $badge_style): string {
        if ('liked' === $badge_style) {
            return '<svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M6 10.2L5.2 9.5C2.7 7.3 1 5.8 1 3.9A2.4 2.4 0 013.4 1.5c1 0 1.9.5 2.6 1.3.7-.8 1.6-1.3 2.6-1.3A2.4 2.4 0 0111 3.9c0 1.9-1.7 3.4-4.2 5.6l-.8.7z"/></svg>';
        }

        return '<svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M5 1h2v4h4v2H7v4H5V7H1V5h4V1z"/></svg>';
    }

    private function render_footer_links(string $links): void {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $links)));

        if (empty($lines)) {
            return;
        }

        echo '<div class="sems-footer-links" aria-label="Legal links">';
        foreach ($lines as $line) {
            echo '<a href="#">' . esc_html($line) . '</a>';
        }
        echo '</div>';
    }

    private function render_language_switcher(array $settings): void {
        $use_translatepress = isset($settings['use_translatepress']) && 'yes' === $settings['use_translatepress'];

        if ($use_translatepress && $this->render_translatepress_switcher()) {
            return;
        }

        $url = '#';
        if (!empty($settings['language_button_url']['url'])) {
            $url = $settings['language_button_url']['url'];
        }

        $label = $settings['language_button_label'] ?? 'English';

        $attributes = [
            'class' => 'sems-language-btn',
            'href' => esc_url($url),
        ];

        if (!empty($settings['language_button_url']['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($settings['language_button_url']['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }

        echo '<a';
        foreach ($attributes as $key => $value) {
            echo ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }
        echo '>';
        echo '<span class="sems-language-btn__icon">';
        $this->render_globe_icon();
        echo '</span>';
        echo '<span class="sems-item-label">' . esc_html($label) . '</span>';
        echo '</a>';
    }

    private function render_translatepress_switcher(): bool {
        if (!shortcode_exists('language-switcher') && !function_exists('trp_the_language_switcher')) {
            return false;
        }

        $output = '';

        if (shortcode_exists('language-switcher')) {
            $output = do_shortcode('[language-switcher]');
        } elseif (function_exists('trp_the_language_switcher')) {
            ob_start();
            trp_the_language_switcher();
            $output = (string) ob_get_clean();
        }

        if ('' === trim($output)) {
            return false;
        }

        echo '<div class="sems-language-switcher">';
        echo wp_kses_post($output);
        echo '</div>';

        return true;
    }

    private function render_link_open(array $url_settings, string $class, string $key, string $tooltip = ''): void {
        $this->add_render_attribute($key, 'class', $class);

        if ('' !== trim($tooltip)) {
            $this->add_render_attribute($key, 'data-tooltip', $tooltip);
            $this->add_render_attribute($key, 'title', $tooltip);
        }

        $url = '#';
        if (!empty($url_settings['url'])) {
            $url = $url_settings['url'];
        }

        $this->add_render_attribute($key, 'href', esc_url($url));

        if (!empty($url_settings['is_external'])) {
            $this->add_render_attribute($key, 'target', '_blank');
        }

        if (!empty($url_settings['nofollow'])) {
            $this->add_render_attribute($key, 'rel', 'nofollow');
        }

        echo '<a ' . $this->get_render_attribute_string($key) . '>';
    }

    private function render_toggle_script(array $settings, string $sidebar_id): void {
        $auto_close_mobile = isset($settings['auto_close_mobile']) && 'yes' === $settings['auto_close_mobile'];
        $enable_toggle = isset($settings['enable_menu_toggle']) && 'yes' === $settings['enable_menu_toggle'];
        ?>
        <script>
            (function () {
                var sidebar = document.getElementById('<?php echo esc_js($sidebar_id); ?>');
                if (!sidebar || !sidebar.classList.contains('sems-sidebar')) {
                    return;
                }
                var inner = sidebar.querySelector('.sems-sidebar__inner');
                var button = sidebar.querySelector('.sems-sidebar__toggle');

                var autoCloseMobile = <?php echo $auto_close_mobile ? 'true' : 'false'; ?>;
                var hasToggle = <?php echo $enable_toggle ? 'true' : 'false'; ?>;
                var mobileQuery = window.matchMedia('(max-width: 767px)');

                var syncSidebarHeight = function () {
                    var viewportHeight = window.innerHeight || document.documentElement.clientHeight || 0;

                    if (viewportHeight > 0) {
                        sidebar.style.height = viewportHeight + 'px';
                        sidebar.style.minHeight = viewportHeight + 'px';
                        if (inner) {
                            inner.style.minHeight = viewportHeight + 'px';
                        }
                    }
                };

                var tooltip = document.getElementById('sems-floating-tooltip');
                if (!tooltip) {
                    tooltip = document.createElement('div');
                    tooltip.id = 'sems-floating-tooltip';
                    tooltip.className = 'sems-floating-tooltip';
                    document.body.appendChild(tooltip);
                }

                var tooltipTargets = sidebar.querySelectorAll('.sems-main-nav__item[data-tooltip], .sems-shortcuts__item[data-tooltip]');

                var positionTooltip = function (target) {
                    var rect = target.getBoundingClientRect();
                    tooltip.style.left = (rect.right + 10) + 'px';
                    tooltip.style.top = (rect.top + (rect.height / 2)) + 'px';
                };

                var showTooltip = function (target) {
                    if (!sidebar.classList.contains('is-collapsed')) {
                        return;
                    }

                    var text = target.getAttribute('data-tooltip');
                    if (!text) {
                        return;
                    }

                    tooltip.textContent = text;
                    positionTooltip(target);
                    tooltip.classList.add('is-visible');
                };

                var hideTooltip = function () {
                    tooltip.classList.remove('is-visible');
                };

                var updateToggleState = function () {
                    if (!button) {
                        return;
                    }

                    var expanded = !sidebar.classList.contains('is-collapsed');
                    button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                };

                var collapseSidebar = function () {
                    sidebar.classList.add('is-collapsed');
                    hideTooltip();
                    updateToggleState();
                };

                var applyMobileAutoCloseState = function () {
                    if (!hasToggle) {
                        return;
                    }

                    if (autoCloseMobile && mobileQuery.matches) {
                        collapseSidebar();
                    }
                };

                tooltipTargets.forEach(function (target) {
                    target.addEventListener('mouseenter', function () {
                        showTooltip(target);
                    });
                    target.addEventListener('mousemove', function () {
                        if (tooltip.classList.contains('is-visible')) {
                            positionTooltip(target);
                        }
                    });
                    target.addEventListener('mouseleave', hideTooltip);
                    target.addEventListener('focus', function () {
                        showTooltip(target);
                    });
                    target.addEventListener('blur', hideTooltip);

                    target.addEventListener('click', function () {
                        if (autoCloseMobile && mobileQuery.matches) {
                            collapseSidebar();
                        }
                    });
                });

                var extraCloseTargets = sidebar.querySelectorAll('.sems-language-btn, .sems-language-switcher a');
                extraCloseTargets.forEach(function (target) {
                    target.addEventListener('click', function () {
                        if (autoCloseMobile && mobileQuery.matches) {
                            collapseSidebar();
                        }
                    });
                });

                if (button) {
                    button.addEventListener('click', function () {
                        sidebar.classList.toggle('is-collapsed');
                        hideTooltip();
                        updateToggleState();
                    });
                }

                syncSidebarHeight();
                applyMobileAutoCloseState();
                updateToggleState();

                if (window.requestAnimationFrame) {
                    window.requestAnimationFrame(syncSidebarHeight);
                }
                window.setTimeout(syncSidebarHeight, 250);
                window.addEventListener('load', syncSidebarHeight);
                window.addEventListener('resize', function () {
                    syncSidebarHeight();
                    updateToggleState();
                });

                if (mobileQuery.addEventListener) {
                    mobileQuery.addEventListener('change', function () {
                        applyMobileAutoCloseState();
                        syncSidebarHeight();
                    });
                } else if (mobileQuery.addListener) {
                    mobileQuery.addListener(function () {
                        applyMobileAutoCloseState();
                        syncSidebarHeight();
                    });
                }
            })();
        </script>
        <?php
    }

    private function render_globe_icon(): void {
        echo '<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M8 1a7 7 0 100 14A7 7 0 008 1zm4.9 6h-2.2a10.8 10.8 0 00-.8-3 5.04 5.04 0 013 3zM8 3c.5.6 1.1 2 1.4 4H6.6c.3-2 1-3.4 1.4-4zM3.1 9h2.2c.1 1.2.4 2.3.8 3a5.04 5.04 0 01-3-3zm2.2-2H3.1a5.04 5.04 0 013-3 10.8 10.8 0 00-.8 3zM8 13c-.5-.6-1.1-2-1.4-4h2.8c-.3 2-1 3.4-1.4 4zm1.7-1c.4-.7.7-1.8.8-3h2.2a5.04 5.04 0 01-3 3z"/></svg>';
    }
}
