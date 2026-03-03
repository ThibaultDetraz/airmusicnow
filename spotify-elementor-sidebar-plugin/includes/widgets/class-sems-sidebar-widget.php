<?php

if (!defined('ABSPATH')) {
    exit;
}

class SEMS_Sidebar_Widget extends \Elementor\Widget_Base {
    public function get_name(): string {
        return 'sems_spotify_sidebar';
    }

    public function get_title(): string {
        return esc_html__('Ultimate Index Addon-ons', 'spotify-elementor-sidebar-menu');
    }

    public function get_icon(): string {
        return 'eicon-nav-menu';
    }

    public function get_categories(): array {
        return ['general'];
    }

    public function get_style_depends(): array {
        return ['sems-sidebar-menu'];
    }

    protected function register_controls(): void {
        $this->register_brand_controls();
        $this->register_main_menu_controls();
        $this->register_shortcut_controls();
        $this->register_footer_controls();
        $this->register_language_controls();
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
            'brand_text',
            [
                'label' => esc_html__('Brand Text', 'spotify-elementor-sidebar-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Spotify',
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

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        ?>
        <aside class="sems-sidebar" aria-label="Sidebar menu">
            <div class="sems-sidebar__inner">
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
    }

    private function render_brand(array $settings): void {
        $brand_text = $settings['brand_text'] ?? 'Spotify';
        $logo_url = '';

        if (!empty($settings['brand_logo']['url'])) {
            $logo_url = $settings['brand_logo']['url'];
        }

        ?>
        <div class="sems-brand" role="img" aria-label="<?php echo esc_attr($brand_text); ?>">
            <div class="sems-brand__icon">
                <?php if (!empty($logo_url)) : ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($brand_text); ?>" />
                <?php else : ?>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="12" cy="12" r="12" fill="currentColor" />
                        <path d="M6.1 8.9c3.7-1.1 8-0.8 11.5 1 .4.2.6.7.4 1.1-.2.4-.7.6-1.1.4-3.1-1.6-7-1.9-10.4-.9-.4.1-.9-.1-1-.6-.1-.4.1-.9.6-1z" fill="#000"/>
                        <path d="M6.9 11.8c3-0.9 6.5-0.7 9.2.7.4.2.5.6.3 1-.2.4-.6.5-1 .3-2.4-1.2-5.4-1.4-8.1-.6-.4.1-.8-.1-.9-.5-.1-.4.1-.8.5-.9z" fill="#000"/>
                        <path d="M7.7 14.5c2.3-.7 4.8-.5 6.9.5.3.2.4.5.3.8-.2.3-.5.4-.8.3-1.8-.9-4-.9-5.9-.4-.3.1-.7-.1-.8-.4-.1-.3.1-.7.4-.8z" fill="#000"/>
                    </svg>
                <?php endif; ?>
            </div>
            <span class="sems-brand__text"><?php echo esc_html($brand_text); ?></span>
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

            $this->render_link_open($item['url'] ?? [], $classes, 'menu-item-' . $index);
            echo '<span class="sems-icon">';
            \Elementor\Icons_Manager::render_icon($item['icon'] ?? [], ['aria-hidden' => 'true']);
            echo '</span>';
            echo '<span>' . esc_html($label) . '</span>';
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

            $this->render_link_open($item['url'] ?? [], 'sems-shortcuts__item', 'shortcut-item-' . $index);
            echo '<span class="' . esc_attr($badge_classes) . '">';
            \Elementor\Icons_Manager::render_icon($item['icon'] ?? [], ['aria-hidden' => 'true']);
            echo '</span>';
            echo '<span>' . esc_html($label) . '</span>';
            echo '</a>';
        }
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
        echo '<span>' . esc_html($label) . '</span>';
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

    private function render_link_open(array $url_settings, string $class, string $key): void {
        $this->add_render_attribute($key, 'class', $class);

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

    private function render_globe_icon(): void {
        echo '<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M8 1a7 7 0 100 14A7 7 0 008 1zm4.9 6h-2.2a10.8 10.8 0 00-.8-3 5.04 5.04 0 013 3zM8 3c.5.6 1.1 2 1.4 4H6.6c.3-2 1-3.4 1.4-4zM3.1 9h2.2c.1 1.2.4 2.3.8 3a5.04 5.04 0 01-3-3zm2.2-2H3.1a5.04 5.04 0 013-3 10.8 10.8 0 00-.8 3zM8 13c-.5-.6-1.1-2-1.4-4h2.8c-.3 2-1 3.4-1.4 4zm1.7-1c.4-.7.7-1.8.8-3h2.2a5.04 5.04 0 01-3 3z"/></svg>';
    }
}
