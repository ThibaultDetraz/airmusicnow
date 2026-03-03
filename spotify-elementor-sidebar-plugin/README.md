# Ultimate Index Addon-ons

Custom WordPress plugin that adds one Elementor widget matching the Spotify sidebar menu design from Figma.

Current plugin version: **1.1.0**

## Widget
- Name in Elementor: **Ultimate Index Addon-ons**
- Category: **General**
- Fixed design width: `241px`
- Registration: supports both modern and legacy Elementor widget registration hooks
- Controls: logo image/text, main menu repeater (label/link/icon/active), shortcut repeater (label/link/icon/style), footer links
- Language area: integrates with TranslatePress switcher automatically when plugin is active

## Install
1. Zip the folder `spotify-elementor-sidebar-plugin`.
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
3. Upload the zip and activate it.
4. Make sure Elementor is installed and active.

## Use in Elementor
1. Edit a page with Elementor.
2. Search for **Ultimate Index Addon-ons** in the widgets panel.
3. Drag it to your layout.

## Files
- `spotify-elementor-sidebar-menu.php` – plugin bootstrap
- `includes/class-sems-plugin.php` – compatibility checks, Elementor registration, and asset registration
- `includes/widgets/class-sems-sidebar-widget.php` – widget markup and SVG icons
- `assets/css/sidebar-menu.css` – Spotify-style visual design
