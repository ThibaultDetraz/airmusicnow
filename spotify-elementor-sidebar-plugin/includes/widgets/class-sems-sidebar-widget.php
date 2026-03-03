<?php

if (!defined('ABSPATH')) {
    exit;
}

class SEMS_Sidebar_Widget extends \Elementor\Widget_Base {
    public function get_name(): string {
        return 'sems_spotify_sidebar';
    }

    public function get_title(): string {
        return esc_html__('Spotify Sidebar Menu', 'spotify-elementor-sidebar-menu');
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

    protected function render(): void {
        ?>
        <aside class="sems-sidebar" aria-label="Spotify sidebar menu">
            <div class="sems-sidebar__inner">
                <div class="sems-brand" role="img" aria-label="Spotify">
                    <div class="sems-brand__icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="12" cy="12" r="12" fill="currentColor" />
                            <path d="M6.1 8.9c3.7-1.1 8-0.8 11.5 1 .4.2.6.7.4 1.1-.2.4-.7.6-1.1.4-3.1-1.6-7-1.9-10.4-.9-.4.1-.9-.1-1-.6-.1-.4.1-.9.6-1z" fill="#000"/>
                            <path d="M6.9 11.8c3-0.9 6.5-0.7 9.2.7.4.2.5.6.3 1-.2.4-.6.5-1 .3-2.4-1.2-5.4-1.4-8.1-.6-.4.1-.8-.1-.9-.5-.1-.4.1-.8.5-.9z" fill="#000"/>
                            <path d="M7.7 14.5c2.3-.7 4.8-.5 6.9.5.3.2.4.5.3.8-.2.3-.5.4-.8.3-1.8-.9-4-.9-5.9-.4-.3.1-.7-.1-.8-.4-.1-.3.1-.7.4-.8z" fill="#000"/>
                        </svg>
                    </div>
                    <span class="sems-brand__text">Spotify</span>
                </div>

                <nav class="sems-main-nav" aria-label="Main navigation">
                    <a class="sems-main-nav__item is-active" href="#">
                        <span class="sems-icon sems-icon--home"><?php $this->icon_home(); ?></span>
                        <span>Home</span>
                    </a>
                    <a class="sems-main-nav__item" href="#">
                        <span class="sems-icon sems-icon--search"><?php $this->icon_search(); ?></span>
                        <span>Search</span>
                    </a>
                    <a class="sems-main-nav__item" href="#">
                        <span class="sems-icon sems-icon--library"><?php $this->icon_library(); ?></span>
                        <span>Your Library</span>
                    </a>
                </nav>

                <div class="sems-shortcuts">
                    <a class="sems-shortcuts__item" href="#">
                        <span class="sems-square-icon sems-square-icon--plus"><?php $this->icon_plus(); ?></span>
                        <span>Create Playlist</span>
                    </a>
                    <a class="sems-shortcuts__item" href="#">
                        <span class="sems-square-icon sems-square-icon--liked"><?php $this->icon_heart(); ?></span>
                        <span>Liked Songs</span>
                    </a>
                </div>

                <div class="sems-footer-links" aria-label="Legal links">
                    <a href="#">Legal</a>
                    <a href="#">Privacy Center</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Cookies</a>
                    <a href="#">About Ads</a>
                    <a href="#">Cookies</a>
                </div>

                <button type="button" class="sems-language-btn">
                    <span class="sems-language-btn__icon"><?php $this->icon_globe(); ?></span>
                    <span>English</span>
                </button>
            </div>
        </aside>
        <?php
    }

    private function icon_home(): void {
        echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M12 3l9 7v11h-6v-7H9v7H3V10l9-7z"/></svg>';
    }

    private function icon_search(): void {
        echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M10.5 3a7.5 7.5 0 015.96 12.06l4.24 4.24-1.41 1.41-4.24-4.24A7.5 7.5 0 1110.5 3zm0 2a5.5 5.5 0 100 11 5.5 5.5 0 000-11z"/></svg>';
    }

    private function icon_library(): void {
        echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M3 4h2v16H3V4zm4 2h2v14H7V6zm4-2h2v16h-2V4zm4 4h2v12h-2V8zm4-2h2v14h-2V6z"/></svg>';
    }

    private function icon_plus(): void {
        echo '<svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M5 1h2v4h4v2H7v4H5V7H1V5h4V1z"/></svg>';
    }

    private function icon_heart(): void {
        echo '<svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M6 10.2L5.2 9.5C2.7 7.3 1 5.8 1 3.9A2.4 2.4 0 013.4 1.5c1 0 1.9.5 2.6 1.3.7-.8 1.6-1.3 2.6-1.3A2.4 2.4 0 0111 3.9c0 1.9-1.7 3.4-4.2 5.6l-.8.7z"/></svg>';
    }

    private function icon_globe(): void {
        echo '<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M8 1a7 7 0 100 14A7 7 0 008 1zm4.9 6h-2.2a10.8 10.8 0 00-.8-3 5.04 5.04 0 013 3zM8 3c.5.6 1.1 2 1.4 4H6.6c.3-2 1-3.4 1.4-4zM3.1 9h2.2c.1 1.2.4 2.3.8 3a5.04 5.04 0 01-3-3zm2.2-2H3.1a5.04 5.04 0 013-3 10.8 10.8 0 00-.8 3zM8 13c-.5-.6-1.1-2-1.4-4h2.8c-.3 2-1 3.4-1.4 4zm1.7-1c.4-.7.7-1.8.8-3h2.2a5.04 5.04 0 01-3 3z"/></svg>';
    }
}
