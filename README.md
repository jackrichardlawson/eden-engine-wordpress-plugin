# Eden Engine WordPress Plugin

Custom Eden Engine page layouts, carbon conversion platform sections, and technical brief intake components for WordPress.

Current version: 0.3.1

This repository is intentionally plugin-only. It should contain only the WordPress plugin entry file, shortcode code, and static assets required for WordPress to install and activate the plugin directly from GitHub.

## Shortcodes

The plugin automatically renders custom layouts for:

- `/`
- `/technology/`
- `/system/`
- `/applications/`
- `/roadmap/`
- `/company/`
- `/vision/`
- `/technical-brief/`
- `/contact/`

`/journal/` remains the WordPress Posts Page and receives Eden visual styling only.

Paste this shortcode into a WordPress page for the full homepage layout if you need manual placement:

```text
[eden_engine_showcase]
```

Individual sections are also available:

```text
[eden_digital_twin]
[eden_target_mapper]
[eden_pathway_demo]
[eden_reactor_status]
[eden_mission]
[eden_technology]
[eden_system]
[eden_applications]
[eden_roadmap]
[eden_company]
[eden_vision]
[eden_contact]
[eden_technical_brief]
[eden_whitepaper]
```

## WordPress Deployer for Git

The repository must be public for the free Deployer for Git plugin to download it.

Enter this in WordPress:

```text
Provider Type: GitHub
Repository URL: https://github.com/jackrichardlawson/eden-engine-wordpress-plugin
Branch: main
```

After installation, activate **Eden Engine** from the WordPress Plugins screen. Keep Home as the Front Page and Journal as the Posts Page.

## Manual ZIP Install

1. Download or ZIP this repository.
2. In WordPress, go to **Plugins > Add New > Upload Plugin**.
3. Upload the ZIP.
4. Activate **Eden Engine**.
5. Keep Home as the Front Page and Journal as the Posts Page.

## Structure

```text
eden-engine-wordpress-plugin/
  eden-engine.php
  README.md
  wordpress-plugin/
    includes/
      shortcodes.php
    assets/
      eden-engine.css
      eden-engine.js
      images/
        eden-engine/
```

The root `eden-engine.php` file uses `plugin_dir_path( __FILE__ )` and `plugin_dir_url( __FILE__ )` so all plugin paths resolve from the WordPress plugin root.

## Changelog

### Version 0.3.1

- Rebuilt homepage deploy assets with the latest System Loop and Digital Twin polish
- Bumped the plugin package version so WordPress deploy tooling can recognize the update

### Version 0.2.0

- Rebuilt the Eden Engine landing page to match the Figma design direction
- Added custom Home, Mission, Technology, and Whitepaper page rendering
- Added Eden visual styling for the native WordPress Journal and post templates
- Added dark cinematic biotech styling, green/cyan glow effects, glass panels, and inline system visuals
- Preserved the existing showcase and section shortcodes

### Version 0.1.2

- Reworked homepage copy around Eden Engine's CO2-to-sugar research path
- Added richer hero, molecule pathway visual, use cases, and stronger research preview messaging
- Added showcase page styling that can suppress the extra theme header on shortcode pages

### Version 0.1.1

- Improved homepage showcase
- Improved responsive styling
- Preserved all shortcodes
