# Eden Engine WordPress Plugin

Custom Eden Engine page layouts, evidence-program sections, partner intake, native Journal templates, and CO2-to-food-ingredients platform content for WordPress.

Current version: 0.7.1

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
- `/evidence-2/` -> `/evidence/`

Paste this shortcode into a WordPress page for the full homepage layout if you need manual placement:

```text
[eden_engine_showcase]
```

Route shortcodes are also available. Each mounts one complete Eden route; use no more than one Eden route shortcode on a page:

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

The public partner address defaults to `partners@theedenengine.com`. Define `EDEN_ENGINE_PARTNER_EMAIL` in `wp-config.php`, or set the `eden_engine_partner_public_email` option, to change the address shown on the site. The mailbox must be provisioned separately. Form submissions continue to use the WordPress administrator email unless the `eden_engine_partner_request_recipient` filter is configured.

## Artifact-Backed Journal Contract

Published Journal posts can declare a versioned public evidence contract through registered post meta. The contract is intentionally stricter than a normal editorial label: a post is computed as `artifact_backed: true` only when every required field below is present and valid **and** the linked artifact status is `published`. A partial, draft, under-review, superseded, or withdrawn contract never receives the Artifact-backed badge.

The stable post meta keys are:

- `_eden_journal_artifact_identifier` — stable public artifact ID
- `_eden_journal_artifact_url` — valid public HTTP(S) artifact link that does not resolve to the Journal post itself
- `_eden_journal_artifact_type` — `dataset`, `experiment-record`, `model-or-simulation`, `analysis`, `protocol-or-method`, `software-release`, `technical-note`, `review`, or `other`
- `_eden_journal_artifact_status` — `draft`, `under-review`, `published`, `superseded`, or `withdrawn`
- `_eden_journal_claim_state` — `external-precedent`, `eden-modeled`, `planned-validation`, `synthetic`, `implemented-governance`, `measured-unreviewed`, `measured-reviewed`, `qualified`, or `historical-vision`
- `_eden_journal_what_changed` — concrete change represented by the publication
- `_eden_journal_narrow_support` — smallest defensible interpretation supported by the artifact
- `_eden_journal_excluded_inferences` — non-empty array of claims readers must not infer
- `_eden_journal_evidence_references` — non-empty array of `{ "label": "...", "url": "https://...", "type": "..." }` public references
- `_eden_journal_next_gate` — smallest next validation step that would reduce uncertainty
- `_eden_journal_review_date` — valid `YYYY-MM-DD` technical review date
- `_eden_journal_eden_interpretation` — Eden-specific interpretation, kept separate from the underlying evidence
- `_eden_journal_unknowns` — non-empty array of remaining unknowns, risks, or unresolved assumptions

All keys are single post meta values, sanitized on write, registered with `show_in_rest`, and editable only by a user who can edit the post. The raw values are available through the standard REST `meta` object. A read-only `eden_journal_contract` field is also added to every `/wp/v2/posts` response; it is `null` when no contract is declared and otherwise includes declared normalized fields, human-readable labels, `missing_required_fields`, `contract_status`, and the authoritative `artifact_backed` boolean. Missing fields are omitted from that computed response rather than represented by schema-invalid empty values. This computed field remains available even if the site disables the post type's generic `custom-fields` REST support.

PHP integrations and migrations should call `eden_engine_journal_publication_contract_for_post( $post_id )` for the normalized contract and `eden_engine_is_artifact_backed_post( $post_id )` for the fixed completeness decision. Integrations may extend controlled choice labels through `eden_engine_journal_artifact_types`, `eden_engine_journal_artifact_statuses`, and `eden_engine_journal_claim_states`; supply an existing editorial source through `eden_engine_journal_contract_fields`; or add a namespaced REST payload under `extensions` through `eden_engine_journal_contract_rest_extensions`. These filters do not provide a separate way to set the authoritative Artifact-backed flag.

The native Journal index shows the Artifact-backed badge only when the helper returns true. The single-post template conditionally renders the linked artifact, claim state, review date, narrow support, excluded inferences, references, Eden interpretation, unknowns, and next evidence gate. A partial contract is rendered as incomplete and explicitly not artifact-backed.

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
      artifact-publications.php
      journal-policy.php
      shortcodes.php
    public-artifacts/
      2026-08-29-journal-proof-manifest.json
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

### Version 0.7.1

- Added the missing canonical URL to the native Journal archive fallback metadata
- Bumped the release version so the deployment purges LiteSpeed/CDN output for the corrected Journal head

### Version 0.7.0

- Published three artifact-backed Build Log entries with explicit claim states, excluded inferences, unknowns, review dates, and next evidence gates
- Added a strict fixed Journal publication contract with registered REST metadata and fail-closed Artifact-backed labels
- Published a machine-readable artifact receipt manifest with dated source locators and SHA-256 receipts for protected repository artifacts
- Added social images and complete Open Graph/Twitter metadata for the main public routes and reviewed Journal entries
- Added non-PII partner-form outcome telemetry and expanded cache purges to cover the new artifact-backed posts

### Version 0.6.1

- Reframed the remaining sugar-first Journal card titles around the parallel Phase 1A and Phase 1B program structure
- Added reviewed browser, social, schema, description, and claim-class metadata for the carbon-negative article and the two legacy sugar-first roadmap posts
- Extended the historical-context notice to the old Phase 1 CO₂-to-sugar roadmap article

### Version 0.6.0

- Added an Eden-owned public page template so Astra does not emit a hidden duplicate masthead or legacy menu on the main website routes
- Expanded server-rendered fallback content into page-specific, crawlable summaries with explicit current capability, evidence state, Phase 1A, and parallel Phase 1B boundaries
- Added a permanent redirect from `/evidence-2/` to the canonical Evidence page and included the duplicate route in cache purges
- Added reviewed Journal titles, descriptions, claim classes, social metadata, and Article structured data for riskier legacy posts
- Renamed the Journal framing to Research Journal / Build Log and removed synthesized fallback posts from the React feed
- Expanded the partner intake to eight focused inquiry tracks, added a configurable branded public email, and connected the Technical Brief directly to its preselected inquiry path
- Added a shared Evidence gate ledger to the Roadmap and made active claim status and independent-review state explicit

### Version 0.5.0

- Refined the public experience with a full-bleed Phase 1 hero, editorial evidence layouts, site progress telemetry, and a more useful status-console footer
- Expanded evidence gates, recovery and QA architecture, roadmap sequencing, and partner-track routing while keeping claims inside the Phase 1 bench-validation boundary
- Added canonical redirects and review metadata for duplicate or historical Journal posts
- Replaced the historical sugar-safety article output with a proof-focused safety checklist and current-status notice
- Removed the obsolete brief-request AJAX endpoint and aligned the visible partner fallback address with the configured mail recipient
- Improved semantic page structure, skip-link behavior, image loading, reduced-motion support, and native Journal template ownership

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
