# Eden Engine WordPress Plugin

Custom Eden Engine page layouts, evidence-program sections, partner intake, native Journal templates, and CO2-to-food-ingredients platform content for WordPress.

Current version: 0.4.0

This repository is intentionally plugin-only. It should contain only the WordPress plugin entry file, shortcode code, and static assets required for WordPress to install and activate the plugin directly from GitHub.

## Shortcodes

The plugin automatically renders custom layouts for:

- `/`
- `/technology/`
- `/evidence/`
- `/roadmap/`
- `/applications/`
- `/company/`
- `/partner/`
- `/technical-brief/`

`/journal/` remains the WordPress Posts Page and now uses Eden-owned native WordPress index and single post templates.

Legacy public routes redirect intentionally:

- `/system/` -> `/technology/?section=architecture`
- `/mission/` and `/vision/` -> `/company/`
- `/contact/` -> `/partner/`
- `/whitepaper/` -> `/technical-brief/`

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
[eden_evidence]
[eden_system]
[eden_applications]
[eden_roadmap]
[eden_company]
[eden_vision]
[eden_partner]
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

The native Journal header uses the same public navigation as the React site: Technology, Evidence, Roadmap, Applications, Journal, and Company, with one **Partner on Phase 1** action. A small Journal-only script provides the accessible mobile disclosure without loading the full React bundle. The shared footer is rendered by the plugin for both index and single-post templates.

Partner submissions refresh their nonce from a no-cache WordPress AJAX endpoint before posting. The server also validates the inquiry track, required context, field lengths, honeypot, and a short per-connection rate limit. If JavaScript is unavailable, the fallback exposes the configured direct email and Technical Brief link.

## Build Assets

The React source lives in the parent Eden Engine repository under `apps/web`. From `apps/web`, run:

```bash
npm run build:wp
```

That build type-checks the web app, clears `wordpress-plugin/assets/`, emits `eden-engine.js` and `eden-engine.css`, and copies the public image/model assets into this repository. Commit the generated assets here only after the source and PHP changes are complete.

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
    templates/
      journal-index.php
      journal-single.php
    assets/
      eden-engine.css
      eden-engine.js
      eden-engine-journal-nav.js
      images/
        eden-engine/
```

The root `eden-engine.php` file uses `plugin_dir_path( __FILE__ )` and `plugin_dir_url( __FILE__ )` so all plugin paths resolve from the WordPress plugin root.

## Changelog

### Version 0.4.0

- Added first-class Evidence, Applications, Partner, and distinct Technical Brief WordPress routes
- Added a dedicated Phase 1 partner inquiry nonce and AJAX mail handler
- Added fresh nonce retrieval, server-side inquiry allowlisting/limits, throttling, and a direct-email no-JavaScript fallback
- Consolidated native Journal navigation around Technology, Evidence, Roadmap, Applications, Journal, and Company
- Added an accessible Journal-only mobile menu without loading the full React bundle
- Added a shared native Journal footer and intentional redirects for legacy public routes
- Added automatic Evidence and Partner page creation plus cache-purge coverage
- Added optimized WebP homepage visuals generated for the new design

### Version 0.3.13

- Repositioned public website copy from sugar-only framing to a modular CO2-to-food-ingredients platform
- Added protein/biomass proof and carbohydrate precursor breakthrough language across the deploy bundle
- Bumped the plugin package version so cached WordPress pages refresh after deployment

### Version 0.3.12

- Restored the native Journal header actions and brand tagline so the top bar matches the public website shell
- Kept the consolidated five-link primary navigation on Journal pages
- Bumped the plugin package version so cached WordPress journal pages refresh after deployment

### Version 0.3.11

- Trimmed native Journal navigation to Home, Technology, Roadmap, Journal, and Company so it matches the consolidated public site header
- Bumped the plugin package version so cached WordPress journal pages refresh after deployment

### Version 0.3.10

- Reworked native Journal navigation and article sections to use the same full-width shell as the rest of the public site
- Stabilized single-post hero media to a consistent 16:9 frame for uploaded featured images
- Added a cache-busting plugin version bump for the WordPress deploy bundle

### Version 0.3.9

- Rebuilt WordPress deploy assets from the consolidated Eden Engine website
- Updated public navigation, page consolidation, reviewer-safe messaging, and new Home, Technology, and Roadmap visuals
- Kept old System, Applications, and Vision entry points routed into Technology and Roadmap

### Version 0.3.8

- Prepared the WordPress package for the consolidated Eden Engine public site

### Version 0.3.6

- Added plugin-versioned CSS and JavaScript URLs so browser and CDN caches pick up deployed bundle changes immediately

### Version 0.3.5

- Rebuilt the public site around Phase 1 credibility framing, current-status modules, tightened hero layouts, and a working technical brief intake path

### Version 0.3.4

- Matched the native Journal navigation to the main Eden Engine site header
- Tightened the Journal index hero so the first post appears higher on desktop and mobile

### Version 0.3.3

- Added native Eden Engine WordPress templates for the Journal index, archives, search, and single posts
- Matched native posts to the homepage visual system with signal-path, readout, featured-post, and article layouts
- Removed legacy child-theme Eden assets from native blog screens when the plugin renders journal templates

### Version 0.3.2

- Isolated Eden app heading, link, and form styles from Astra global theme rules
- Removed legacy child-theme Eden assets from custom Eden pages when the plugin renders the app

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

- Reworked homepage copy around Eden Engine's CO2-to-carbohydrate research path
- Added richer hero, molecule pathway visual, use cases, and stronger research preview messaging
- Added showcase page styling that can suppress the extra theme header on shortcode pages

### Version 0.1.1

- Improved homepage showcase
- Improved responsive styling
- Preserved all shortcodes
