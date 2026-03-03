# Spotify Sidebar Menu for Elementor

Custom WordPress plugin that adds one Elementor widget matching the Spotify sidebar menu design from Figma.

## Widget
- Name in Elementor: **Spotify Sidebar Menu**
- Category: **General**
- Fixed design width: `241px`

## Install
1. Zip the folder `spotify-elementor-sidebar-plugin`.
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
3. Upload the zip and activate it.
4. Make sure Elementor is installed and active.

## Use in Elementor
1. Edit a page with Elementor.
2. Search for **Spotify Sidebar Menu** in the widgets panel.
3. Drag it to your layout.

## Files
- `spotify-elementor-sidebar-menu.php` – plugin bootstrap
- `includes/class-sems-plugin.php` – Elementor integration and asset registration
- `includes/widgets/class-sems-sidebar-widget.php` – widget markup and SVG icons
- `assets/css/sidebar-menu.css` – Spotify-style visual design
