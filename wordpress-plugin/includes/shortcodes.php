<?php
/**
 * Public-facing Eden Engine custom pages and shortcodes.
 *
 * @package EdenEngine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'eden_engine_partner_recipient' ) ) {
    function eden_engine_partner_recipient(): string {
        return sanitize_email(
            (string) apply_filters(
                'eden_engine_partner_request_recipient',
                apply_filters( 'eden_engine_brief_request_recipient', get_option( 'admin_email' ) )
            )
        );
    }
}

if ( ! function_exists( 'eden_engine_partner_public_email' ) ) {
    function eden_engine_partner_public_email(): string {
        $configured_email = defined( 'EDEN_ENGINE_PARTNER_EMAIL' )
            ? (string) EDEN_ENGINE_PARTNER_EMAIL
            : (string) get_option( 'eden_engine_partner_public_email', 'partners@theedenengine.com' );

        return sanitize_email(
            (string) apply_filters( 'eden_engine_partner_public_email', $configured_email )
        );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_names' ) ) {
    function eden_engine_shortcode_names(): array {
        return array(
            'eden_engine_showcase',
            'eden_digital_twin',
            'eden_target_mapper',
            'eden_pathway_demo',
            'eden_reactor_status',
            'eden_mission',
            'eden_protein',
            'eden_technology',
            'eden_evidence',
            'eden_system',
            'eden_applications',
            'eden_roadmap',
            'eden_company',
            'eden_vision',
            'eden_partner',
            'eden_contact',
            'eden_technical_brief',
            'eden_whitepaper',
        );
    }
}

if ( ! function_exists( 'eden_engine_enqueue_assets' ) ) {
    function eden_engine_enqueue_assets( bool $include_script = true ): void {
        $style_path  = EDEN_ENGINE_PLUGIN_PATH . 'assets/eden-engine.css';
        $script_path = EDEN_ENGINE_PLUGIN_PATH . 'assets/eden-engine.js';

        $style_version  = file_exists( $style_path ) ? EDEN_ENGINE_VERSION . '-' . (string) filemtime( $style_path ) : EDEN_ENGINE_VERSION;
        $script_version = file_exists( $script_path ) ? EDEN_ENGINE_VERSION . '-' . (string) filemtime( $script_path ) : EDEN_ENGINE_VERSION;

        if ( file_exists( $style_path ) ) {
            wp_enqueue_style(
                'eden-engine',
                EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.css',
                array(),
                $style_version
            );
        }

        if ( $include_script && file_exists( $script_path ) ) {
            wp_enqueue_script(
                'eden-engine',
                EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.js',
                array(),
                $script_version,
                true
            );

            wp_add_inline_script(
                'eden-engine',
                'window.EdenEngineAssetsBase = ' . wp_json_encode( EDEN_ENGINE_PLUGIN_URL . 'assets/' ) . ';' .
                'window.EdenEngineConfig = ' . wp_json_encode(
                    array(
                        'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
                        'partnerRequestNonce' => wp_create_nonce( 'eden_engine_partner_request' ),
                        'partnerNonceUrl'     => add_query_arg(
                            'action',
                            'eden_engine_partner_nonce',
                            admin_url( 'admin-ajax.php' )
                        ),
                        'fallbackEmail'       => eden_engine_partner_public_email(),
                        'partnerUrl'          => home_url( '/partner/' ),
                        'technicalBriefUrl'   => home_url( '/technical-brief/' ),
                    )
                ) . ';',
                'before'
            );
        }
    }
}

if ( ! function_exists( 'eden_engine_enqueue_journal_nav' ) ) {
    function eden_engine_enqueue_journal_nav(): void {
        $script_path = EDEN_ENGINE_PLUGIN_PATH . 'assets/eden-engine-journal-nav.js';

        if ( ! file_exists( $script_path ) ) {
            return;
        }

        wp_enqueue_script(
            'eden-engine-journal-nav',
            EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine-journal-nav.js',
            array(),
            EDEN_ENGINE_VERSION . '-' . (string) filemtime( $script_path ),
            true
        );
    }
}

if ( ! function_exists( 'eden_engine_public_widget_content' ) ) {
    function eden_engine_public_widget_content( string $widget ): array {
        $content = array(
            'home'            => array(
                'title'   => 'Carbon-Fed Fermentation for Sustainable Protein',
                'summary' => 'Eden Engine is developing a modular gas-fermentation platform that uses conditioned CO₂ and electricity-derived hydrogen to cultivate protein-rich microbial biomass. Software and experiment controls are built; qualified biological validation is next.',
                'cta'     => 'View the Protein Program',
                'url'     => home_url( '/protein/' ),
            ),
            'protein'         => array(
                'title'   => 'The Phase 1 Protein Program',
                'summary' => 'The selected Phase 1 route uses conditioned CO₂, electricity-derived H₂, O₂, nutrients, and a hydrogen-oxidizing microorganism to produce a traceable, non-food research biomass lot for independent characterization.',
                'cta'     => 'Partner on Validation',
                'url'     => home_url( '/partner/' ),
            ),
            'technology'      => array(
                'title'   => 'One Platform, Two Carbon Interfaces',
                'summary' => 'Direct CO₂/H₂ fermentation is Eden’s selected Phase 1 protein pathway. A future acetate interface could expand the platform to selected fungi, yeast, and algae after the protein route is validated.',
                'cta'     => 'View the Evidence Program',
                'url'     => home_url( '/evidence/' ),
            ),
            'evidence'        => array(
                'title'   => 'Evidence Before Scale',
                'summary' => 'The evidence program keeps external precedent, Eden-modeled integration, planned experiments, measured results, independent review, and future qualification in separate claim states.',
                'cta'     => 'Partner on Validation',
                'url'     => home_url( '/partner/' ),
            ),
            'system'          => array(
                'title'   => 'Target System Architecture',
                'summary' => 'The system page describes an intended modular architecture: what is modeled now, what Phase 1 must measure, and which modules remain future work.',
                'cta'     => 'View Roadmap',
                'url'     => home_url( '/roadmap/' ),
            ),
            'applications'    => array(
                'title'   => 'Motivating Use Cases',
                'summary' => 'Applications are organized by research, industrial, resilience, remote, and frontier horizons. They are motivating targets, not claims of current deployment or commercial output.',
                'cta'     => 'View the Evidence Program',
                'url'     => home_url( '/evidence/' ),
            ),
            'roadmap'         => array(
                'title'   => 'From First Protein Evidence to Platform Expansion',
                'summary' => 'Phase 1 qualifies, executes, and independently characterizes the direct CO₂/H₂ protein pathway. Acetate and broader product diversification remain later platform-expansion work.',
                'cta'     => 'View the Evidence Program',
                'url'     => home_url( '/evidence/' ),
            ),
            'journal'         => array(
                'title'   => 'Research Journal / Build Log',
                'summary' => 'Artifact-backed notes on what is modeled, what is planned, what has been measured, and what still needs evidence.',
                'cta'     => 'Read Field Notes',
                'url'     => home_url( '/journal/' ),
            ),
            'company'         => array(
                'title'   => 'Building a Sustainable-Protein Program',
                'summary' => 'Eden Engine Technologies coordinates the software, science, partners, capital, measurement, and review needed to turn the selected CO₂/H₂ microbial-protein pathway into dated evidence and a partner-ready decision.',
                'cta'     => 'Partner on Validation',
                'url'     => home_url( '/partner/' ),
            ),
            'vision'          => array(
                'title'   => 'A Better Planet Starts With Better Infrastructure',
                'summary' => 'Vision is the long-term home for Eden Engine ambition: regenerative infrastructure, circular resources, and more resilient production systems.',
                'cta'     => 'View Roadmap',
                'url'     => home_url( '/roadmap/' ),
            ),
            'technical-brief' => array(
                'title'   => 'Eden Engine Technical Brief',
                'summary' => 'A focused technical overview of the Phase 1 hypothesis, bounded validation plan, evidence gates, system architecture, known risks, and next validation step.',
                'cta'     => 'Discuss the Technical Brief',
                'url'     => add_query_arg(
                    array(
                        'section' => 'partner-form',
                        'inquiry' => 'technical-brief',
                    ),
                    home_url( '/partner/' )
                ),
            ),
            'partner'         => array(
                'title'   => 'Partner on Validation',
                'summary' => 'Connect with Eden Engine about laboratory access, assays, bioprocess and engineering work, ingredient or industrial collaboration, strategic funding, or academic and media inquiries.',
                'cta'     => 'Read the Technical Brief',
                'url'     => home_url( '/technical-brief/' ),
            ),
            'contact'         => array(
                'title'   => 'Partner on Validation',
                'summary' => 'Use the partner inquiry to identify the capability you can contribute, why you are contacting Eden Engine, and the next step you want to explore.',
                'cta'     => 'Read the Technical Brief',
                'url'     => home_url( '/technical-brief/' ),
            ),
        );

        return $content[ $widget ] ?? $content['home'];
    }
}

if ( ! function_exists( 'eden_engine_public_widget_sections' ) ) {
    function eden_engine_public_widget_sections( string $widget ): array {
        $aliases = array(
            'system'  => 'technology',
            'vision'  => 'company',
            'contact' => 'partner',
        );
        $widget  = $aliases[ $widget ] ?? $widget;
        $sections = array(
            'home' => array(
                array(
                    'title' => 'Current stage',
                    'body'  => 'Software, pathway models, and experiment controls are built. Qualified biological validation is the next milestone.',
                    'items' => array(
                        'Built: software, models, experiment workflow, and evidence controls',
                        'Designed: selected direct CO₂/H₂ pathway, measurements, and decision gates',
                        'Next: qualified facility, safety approval, organism down-selection, controlled runs, and independent assays',
                    ),
                ),
                array(
                    'title' => 'One selected Phase 1 pathway',
                    'body'  => 'Eden’s current program uses conditioned CO₂ and electricity-derived hydrogen directly in microbial fermentation to pursue protein-rich biomass.',
                    'items' => array(
                        'Conditioned CO₂ + H₂ + O₂ + nutrients',
                        'Hydrogen-oxidizing microbial fermentation',
                        'Traceable research biomass lot',
                        'Independent characterization and a documented continue, rescope, or stop decision',
                    ),
                ),
                array(
                    'title' => 'What must be proven next',
                    'body'  => 'The immediate objective is a controlled biological campaign with known inputs, measurable outputs, qualified analytical methods, carbon and resource reconciliation, uncertainty, and an explicit decision.',
                    'items' => array(),
                ),
            ),
            'protein' => array(
                array(
                    'title' => 'The objective',
                    'body'  => 'Develop a repeatable, measurable CO₂/H₂ microbial-biomass process and establish whether the resulting biomass can advance toward a qualified protein ingredient.',
                    'items' => array(),
                ),
                array(
                    'title' => 'How the biology works',
                    'body'  => 'CO₂ supplies carbon. Electricity-derived hydrogen supplies biological energy. Oxygen, nitrogen, minerals, and water support growth. A hydrogen-oxidizing microorganism converts those inputs into protein-rich biomass.',
                    'items' => array(
                        'Qualify carbon source, organism, facility, gas safety, and analytical methods',
                        'Execute controlled fermentation runs with traceable inputs and lot records',
                        'Recover a non-food research biomass lot',
                        'Independently characterize composition and safety-relevant attributes',
                        'Reconcile carbon, mass, gas, water, energy, and uncertainty',
                    ),
                ),
                array(
                    'title' => 'What Phase 1 measures',
                    'body'  => 'The program is designed to produce a decision-ready evidence package, not a premature food or commercial-production claim.',
                    'items' => array(
                        'Biomass identity and repeatability',
                        'Protein and amino-acid composition',
                        'Moisture, ash, lipids, carbohydrates, and nucleic acids',
                        'Contaminants and other safety-relevant attributes',
                        'Carbon and mass reconciliation',
                        'Energy, gas, water, and recovery burden',
                        'Preliminary functionality and application fit',
                    ),
                ),
                array(
                    'title' => 'Partner on validation',
                    'body'  => 'Eden is seeking a qualified bioprocess facility, H₂/O₂ safety expertise, an analytical laboratory, food-science and regulatory guidance, and an ingredient-development partner.',
                    'items' => array(),
                ),
            ),
            'technology' => array(
                array(
                    'title' => 'Direct gas-fed protein — Phase 1',
                    'body'  => 'Eden’s current protein program uses conditioned CO₂ and electricity-derived H₂ directly in microbial fermentation. This is the selected Phase 1 route for protein-rich biomass.',
                    'items' => array(
                        'Declared carbon origin, contaminants, conditioning, electricity source, water, nutrients, and opportunity cost',
                        'Controlled fermentation, recovery, lot traceability, assays, and resource accounting',
                        'Digital-twin feedback that remains labeled as modeled until bench data exists',
                    ),
                ),
                array(
                    'title' => 'Liquid-carbon expansion — Phase 2+',
                    'body'  => 'A future electrochemical acetate pathway could provide a storable liquid feedstock for selected fungi, yeast, and algae, broadening Eden into texture, cultured ingredients, lipids, and specialty food components.',
                    'items' => array(),
                ),
                array(
                    'title' => 'Why the platform uses both',
                    'body'  => 'Direct hydrogen fermentation prioritizes protein production. Acetate prioritizes biological and product flexibility. The architecture assigns each interface a clear role without presenting them as interchangeable.',
                    'items' => array(
                        'Current: direct CO₂/H₂ protein validation',
                        'Later: acetate-enabled organism and product diversification',
                    ),
                ),
            ),
            'evidence' => array(
                array(
                    'title' => 'Evidence states',
                    'body'  => 'One optimistic readiness label cannot substitute for a traceable evidence register.',
                    'items' => array(
                        'Established external precedent: primary literature, standards, and regulatory or product precedent',
                        'Eden-modeled: route assumptions, digital models, and system integration',
                        'Planned: approved hypotheses, controls, methods, and HOLD rules',
                        'Measured: dated Eden observations with uncertainty and provenance',
                        'Independently reviewed: external review of an Eden result or evidence package',
                        'Future vision: motivating targets that are not current capability',
                    ),
                ),
                array(
                    'title' => 'Active claim status',
                    'body'  => 'Software and modeling capability are implemented. No Eden-specific bench performance, food-grade output, safety clearance, integrated pilot, or deployment qualification is currently published as measured and independently reviewed.',
                    'items' => array(),
                ),
                array(
                    'title' => 'Next evidence gate',
                    'body'  => 'Quantify feed, product, off-gas, liquid, solids, losses, and uncertainty for one bounded route with controls and a pre-written HOLD rule.',
                    'items' => array(),
                ),
            ),
            'roadmap' => array(
                array(
                    'title' => 'Phase 1 — selected protein pathway',
                    'body'  => 'The direct CO₂/H₂ program advances as one sequence. Later platform expansion remains dependent on measured evidence from this selected route.',
                    'items' => array(
                        'Qualify route, source, organism, facility, safety, methods, and acceptance criteria',
                        'Execute controlled runs and create a traceable non-food research lot',
                        'Independently characterize composition and safety-relevant attributes',
                        'Reconcile mass, carbon, gas, water, energy, and uncertainty',
                        'Make a documented continue, rescope, or stop decision',
                    ),
                ),
                array(
                    'title' => 'Phase 2+ — platform expansion',
                    'body'  => 'Only after the first protein route is characterized does Eden expand into acetate-fed fungi, yeast, algae, and broader ingredient applications.',
                    'items' => array(
                        'Acetate interface and biological diversification',
                        'Texture, cultured bases, lipids, and specialty components',
                        'Food-safety, regulatory, pilot, and application qualification',
                    ),
                ),
                array(
                    'title' => 'Failure is a roadmap result',
                    'body'  => 'If evidence does not close, the program narrows, repeats, changes method, selects a fallback route, or remains on HOLD. Calendar progress never overrides a failed gate.',
                    'items' => array(),
                ),
            ),
            'applications' => array(
                array(
                    'title' => 'Application horizons',
                    'body'  => 'Applications explain why the research matters; they do not imply current deployment.',
                    'items' => array(
                        'Near-term research: analytical services, route screening, and ingredient-relevant characterization',
                        'Industrial context: co-location and conditioned carbon-stream utilization after route validation',
                        'Resilience context: localized production concepts after safety, reliability, and integration evidence',
                        'Frontier context: remote or off-world systems only after mission-specific qualification',
                    ),
                ),
                array(
                    'title' => 'Maturity labels',
                    'body'  => 'Every application should be labeled as research precedent, modeled scenario, planned validation, measured evidence, or future vision.',
                    'items' => array(),
                ),
            ),
            'company' => array(
                array(
                    'title' => 'What the company is building',
                    'body'  => 'Eden coordinates pathway science, software, experiment design, measurement, evidence review, product qualification, partnerships, and capital around one selected direct CO₂/H₂ protein program.',
                    'items' => array(
                        'Public: program boundaries, claim states, evidence gates, safety constraints, and next tests',
                        'Protected: unpublished route parameters, detailed apparatus choices, partner data, calibration records, and confidential methods',
                        'Shared in stages: non-confidential capability fit before controlled technical exchange',
                    ),
                ),
                array(
                    'title' => 'Current needs',
                    'body'  => 'The company is seeking a qualified bioprocess facility, H₂/O₂ safety expertise, analytical methods, food-science and regulatory guidance, ingredient-development collaboration, independent review, and focused early funding.',
                    'items' => array(),
                ),
            ),
            'partner' => array(
                array(
                    'title' => 'Choose a focused inquiry',
                    'body'  => 'Useful inquiries identify the capability offered, the evidence it could improve, and a realistic next step.',
                    'items' => array(
                        'Laboratory or assay partner',
                        'Bioprocess engineering',
                        'Ingredient or industrial partner',
                        'Academic collaboration',
                        'Non-dilutive funding',
                        'Strategic investment',
                        'Media',
                        'Technical brief request',
                    ),
                ),
            ),
            'technical-brief' => array(
                array(
                    'title' => 'What the public brief contains',
                    'body'  => 'The web brief states the Phase 1 boundary, evidence classes, assumptions, risks, failure modes, safety constraints, and smallest useful next test.',
                    'items' => array(
                        'System boundary and source assumptions',
                        'Selected direct CO₂/H₂ protein hypothesis and later acetate expansion boundary',
                        'Evidence gates and active claim status',
                        'Mass, energy, carbon, safety, and product-qualification checks',
                        'Continue, revise, or HOLD decision logic',
                    ),
                ),
                array(
                    'title' => 'Current evidence boundary',
                    'body'  => 'No Eden-specific food-grade output, validated production performance, integrated pilot operation, or deployment is presented as measured and independently reviewed. Use Print / Save as PDF for the current public brief.',
                    'items' => array(),
                ),
            ),
        );

        return $sections[ $widget ] ?? $sections['home'];
    }
}

if ( ! function_exists( 'eden_engine_fallback_html' ) ) {
    function eden_engine_fallback_html( string $widget ): string {
        static $fallback_instance = 0;

        ++$fallback_instance;

        $content   = eden_engine_public_widget_content( $widget );
        $sections  = eden_engine_public_widget_sections( $widget );
        $full_page = function_exists( 'eden_engine_is_public_app_page' ) && eden_engine_is_public_app_page();
        $title_id  = 'eden-fallback-title-' . sanitize_html_class( $widget ) . '-' . $fallback_instance;

        if ( $full_page ) {
            $html  = '<div class="eden-site eden-server-fallback">';
            $html .= '<a class="sr-only" href="#eden-main-content">Skip to main content</a>';
            $html .= eden_engine_nav_html();
            $html .= '<main id="eden-main-content" tabindex="-1">';
            $html .= '<section class="eden-engine-crawlable-fallback" aria-labelledby="' . esc_attr( $title_id ) . '">';
        } else {
            $html = '<section class="eden-site eden-engine-crawlable-fallback eden-engine-crawlable-fallback--inline" aria-labelledby="' . esc_attr( $title_id ) . '">';
        }

        $html .= '<div class="eden-server-fallback__intro">';
        $html .= '<p class="eyebrow">Current program</p>';
        $html .= $full_page
            ? '<h1 id="' . esc_attr( $title_id ) . '">' . esc_html( $content['title'] ) . '</h1>'
            : '<h2 id="' . esc_attr( $title_id ) . '">' . esc_html( $content['title'] ) . '</h2>';
        $html .= '<p>' . esc_html( $content['summary'] ) . '</p>';
        $html .= '</div>';
        $html .= '<div class="eden-server-fallback__status" aria-label="Current program status">';
        $html .= '<article><span>Current program</span><strong>Direct CO₂/H₂ protein</strong><p>The selected Phase 1 route is a gas-fed microbial-biomass program.</p></article>';
        $html .= '<article><span>Current stage</span><strong>Designed and controlled</strong><p>Software, pathway models, experiment workflow, and evidence controls are built.</p></article>';
        $html .= '<article><span>Next milestone</span><strong>Qualified biological validation</strong><p>Facility, gas safety, organism down-selection, controlled runs, and independent assays come next.</p></article>';
        $html .= '</div>';

        foreach ( $sections as $section_index => $section ) {
            $heading_id = 'eden-fallback-section-' . sanitize_html_class( $widget ) . '-' . $fallback_instance . '-' . absint( $section_index );
            $html      .= '<section class="eden-server-fallback__section" aria-labelledby="' . esc_attr( $heading_id ) . '">';
            $html      .= '<p class="eyebrow">Program readout ' . esc_html( str_pad( (string) ( $section_index + 1 ), 2, '0', STR_PAD_LEFT ) ) . '</p>';
            $html      .= '<h2 id="' . esc_attr( $heading_id ) . '">' . esc_html( (string) $section['title'] ) . '</h2>';
            $html      .= '<p>' . esc_html( (string) $section['body'] ) . '</p>';

            if ( ! empty( $section['items'] ) && is_array( $section['items'] ) ) {
                $html .= '<ul>';

                foreach ( $section['items'] as $item ) {
                    $html .= '<li>' . esc_html( (string) $item ) . '</li>';
                }

                $html .= '</ul>';
            }

            $html .= '</section>';
        }

        if ( 'partner' === $widget || 'contact' === $widget ) {
            $fallback_email = eden_engine_partner_public_email();

            if ( is_email( $fallback_email ) ) {
                $html .= '<p class="eden-server-fallback__email"><strong>Direct email:</strong> <a href="mailto:' . esc_attr( $fallback_email ) . '">' . esc_html( $fallback_email ) . '</a></p>';
            }
        }

        $html .= '<div class="eden-server-fallback__action"><a class="button button--primary" href="' . esc_url( $content['url'] ) . '">' . esc_html( $content['cta'] ) . '</a></div>';

        if ( $full_page ) {
            $html .= '</section></main>';
            $html .= eden_engine_footer_html();
            $html .= '</div>';
        } else {
            $html .= '</section>';
        }

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_render' ) ) {
    function eden_engine_render( array $atts, string $widget ): string {
        eden_engine_enqueue_assets();

        $atts = shortcode_atts(
            array(
                'title'   => '',
                'compact' => 'false',
            ),
            $atts,
            'eden_engine_' . str_replace( '-', '_', $widget )
        );

        $title   = sanitize_text_field( (string) $atts['title'] );
        $compact = filter_var( $atts['compact'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';

        return sprintf(
            '<div class="eden-engine-embed-root" data-eden-engine-embed data-widget="%1$s" data-title="%2$s" data-compact="%3$s">%4$s</div>',
            esc_attr( $widget ),
            esc_attr( $title ),
            esc_attr( $compact ),
            eden_engine_fallback_html( $widget )
        );
    }
}

if ( ! function_exists( 'eden_engine_page_has_shortcode' ) ) {
    function eden_engine_page_has_shortcode(): bool {
        if ( is_admin() ) {
            return false;
        }

        $post = get_post();

        if ( ! $post || empty( $post->post_content ) ) {
            return false;
        }

        foreach ( eden_engine_shortcode_names() as $shortcode ) {
            if ( has_shortcode( $post->post_content, $shortcode ) ) {
                return true;
            }
        }

        return false;
    }
}

if ( ! function_exists( 'eden_engine_should_style_blog' ) ) {
    function eden_engine_should_style_blog(): bool {
        $post_search = is_search() && 'post' === get_query_var( 'post_type' );

        return is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() || $post_search;
    }
}

if ( ! function_exists( 'eden_engine_is_public_app_page' ) ) {
    function eden_engine_is_public_app_page(): bool {
        if ( is_admin() ) {
            return false;
        }

        if ( is_front_page() && is_page() && ! is_home() ) {
            return true;
        }

        return is_page(
            array(
                'protein',
                'technology',
                'evidence',
                'roadmap',
                'applications',
                'company',
                'partner',
                'technical-brief',
            )
        );
    }
}

if ( ! function_exists( 'eden_engine_blog_template' ) ) {
    function eden_engine_blog_template( string $template ): string {
        if ( is_admin() ) {
            return $template;
        }

        if ( eden_engine_is_public_app_page() ) {
            $public_template = EDEN_ENGINE_PLUGIN_PATH . 'templates/public-page.php';

            return file_exists( $public_template ) ? $public_template : $template;
        }

        if ( eden_engine_should_style_blog() && ! is_singular( 'post' ) ) {
            $journal_template = EDEN_ENGINE_PLUGIN_PATH . 'templates/journal-index.php';

            return file_exists( $journal_template ) ? $journal_template : $template;
        }

        if ( is_singular( 'post' ) ) {
            $single_template = EDEN_ENGINE_PLUGIN_PATH . 'templates/journal-single.php';

            return file_exists( $single_template ) ? $single_template : $template;
        }

        return $template;
    }
}

add_filter( 'template_include', 'eden_engine_blog_template', 99 );

if ( ! function_exists( 'eden_engine_post_image_url' ) ) {
    function eden_engine_post_image_url( ?int $post_id = null, string $size = 'large' ): string {
        $post_id = $post_id ?: get_the_ID();

        if ( has_post_thumbnail( $post_id ) ) {
            $image_url = get_the_post_thumbnail_url( $post_id, $size );

            if ( $image_url ) {
                return $image_url;
            }
        }

        $proof_images = array(
            'what-exp-bal-gas-must-measure-before-it-counts-as-eden-evidence' => 'pages/evidence/measurement-bench-v2.webp',
            'why-synthetic-data-can-test-edens-pipeline-but-cannot-become-measured-eden-evidence' => 'pages/technology/sensing-control-room-20260513.png',
            'how-edens-current-evidence-gated-decision-protocol-works' => 'pages/roadmap/evidence-gates-v2.webp',
        );
        $post_slug   = (string) get_post_field( 'post_name', $post_id );

        if ( ! empty( $proof_images[ $post_slug ] ) ) {
            return EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/' . $proof_images[ $post_slug ];
        }

        $fallbacks = array(
            EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/pages/home/phase-1-co2-to-sugar-20260513.png',
            EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/pages/home/pilot-system-hero.png',
            EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/pages/home/platform-pathways-20260513.png',
            EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/pages/home/roadmap-preview.jpg',
        );

        return $fallbacks[ absint( $post_id ) % count( $fallbacks ) ];
    }
}

if ( ! function_exists( 'eden_engine_post_image_alt' ) ) {
    function eden_engine_post_image_alt( ?int $post_id = null ): string {
        $post_id = $post_id ?: get_the_ID();

        if ( has_post_thumbnail( $post_id ) ) {
            $alt = get_post_meta( get_post_thumbnail_id( $post_id ), '_wp_attachment_image_alt', true );

            if ( $alt ) {
                return (string) $alt;
            }
        }

        return get_the_title( $post_id );
    }
}

if ( ! function_exists( 'eden_engine_post_kicker' ) ) {
    function eden_engine_post_kicker( ?int $post_id = null ): string {
        $post_id    = $post_id ?: get_the_ID();
        $categories = get_the_category( $post_id );

        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            return $categories[0]->name;
        }

        return 'Eden Engine Journal';
    }
}

if ( ! function_exists( 'eden_engine_post_read_time' ) ) {
    function eden_engine_post_read_time( ?int $post_id = null ): string {
        $post_id = $post_id ?: get_the_ID();
        $content = wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content', $post_id ) ) );
        $words   = str_word_count( $content );
        $minutes = max( 1, (int) ceil( $words / 220 ) );

        return sprintf(
            /* translators: %d is an estimated reading time in minutes. */
            _n( '%d min read', '%d min read', $minutes, 'eden-engine' ),
            $minutes
        );
    }
}

if ( ! function_exists( 'eden_engine_post_excerpt' ) ) {
    function eden_engine_post_excerpt( ?int $post_id = null, int $words = 26 ): string {
        $post_id = $post_id ?: get_the_ID();
        $excerpt = get_the_excerpt( $post_id );

        if ( '' === trim( $excerpt ) ) {
            $excerpt = (string) get_post_field( 'post_content', $post_id );
        }

        return wp_trim_words( wp_strip_all_tags( $excerpt ), $words, '...' );
    }
}

if ( ! function_exists( 'eden_engine_render_journal_card' ) ) {
    function eden_engine_render_journal_card( string $variant = 'card' ): void {
        $post_id     = get_the_ID();
        $is_featured = 'featured' === $variant;
        $heading_tag = $is_featured ? 'h2' : 'h3';
        $class_name  = $is_featured ? 'eden-journal-card eden-journal-card--featured' : 'eden-journal-card';
        $image_size  = $is_featured ? 'large' : 'medium_large';
        $contract    = function_exists( 'eden_engine_journal_publication_contract_for_post' )
            ? eden_engine_journal_publication_contract_for_post( $post_id )
            : array();
        $badge_html  = function_exists( 'eden_engine_journal_artifact_badge_html' )
            ? eden_engine_journal_artifact_badge_html( $post_id, $contract )
            : '';
        ?>
        <article class="<?php echo esc_attr( $class_name ); ?>">
            <a class="eden-journal-card__media" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
                <img
                    src="<?php echo esc_url( eden_engine_post_image_url( $post_id, $image_size ) ); ?>"
                    alt="<?php echo esc_attr( eden_engine_post_image_alt( $post_id ) ); ?>"
                    <?php echo $is_featured ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                />
            </a>
            <div class="eden-journal-card__body">
                <div class="eden-journal-card__meta" aria-label="Post metadata">
                    <span><?php echo esc_html( eden_engine_post_kicker( $post_id ) ); ?></span>
                    <span><?php echo esc_html( get_the_date( '', $post_id ) ); ?></span>
                    <span><?php echo esc_html( eden_engine_post_read_time( $post_id ) ); ?></span>
                    <?php if ( '' !== $badge_html ) : ?>
                        <?php echo $badge_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated and escaped by the contract helper. ?>
                        <?php if ( ! empty( $contract['claim_state_label'] ) ) : ?>
                            <span><?php echo esc_html( (string) $contract['claim_state_label'] ); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <<?php echo esc_attr( $heading_tag ); ?> class="eden-journal-card__title">
                    <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a>
                </<?php echo esc_attr( $heading_tag ); ?>>
                <p class="eden-journal-card__excerpt"><?php echo esc_html( eden_engine_post_excerpt( $post_id, $is_featured ? 34 : 24 ) ); ?></p>
                <a class="eden-journal-card__link" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">Read field note</a>
            </div>
        </article>
        <?php
    }
}

if ( ! function_exists( 'eden_engine_maybe_enqueue_assets' ) ) {
    function eden_engine_maybe_enqueue_assets(): void {
        if ( eden_engine_page_has_shortcode() || '' !== eden_engine_current_page_widget() ) {
            eden_engine_enqueue_assets();
        } elseif ( eden_engine_should_style_blog() ) {
            eden_engine_enqueue_assets( false );
            eden_engine_enqueue_journal_nav();
        }
    }
}

add_action( 'wp_enqueue_scripts', 'eden_engine_maybe_enqueue_assets' );

if ( ! function_exists( 'eden_engine_dequeue_legacy_theme_assets' ) ) {
    function eden_engine_dequeue_legacy_theme_assets(): void {
        if ( '' === eden_engine_current_page_widget() && ! eden_engine_page_has_shortcode() && ! eden_engine_should_style_blog() ) {
            return;
        }

        wp_dequeue_style( 'eden-engine-style' );
        wp_deregister_style( 'eden-engine-style' );
        wp_dequeue_script( 'eden-engine-script' );
        wp_deregister_script( 'eden-engine-script' );
    }
}

add_action( 'wp_enqueue_scripts', 'eden_engine_dequeue_legacy_theme_assets', 100 );

if ( ! function_exists( 'eden_engine_shortcode_showcase' ) ) {
    function eden_engine_shortcode_showcase( array $atts ): string {
        return eden_engine_render( $atts, 'home' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_digital_twin' ) ) {
    function eden_engine_shortcode_digital_twin( array $atts ): string {
        return eden_engine_render( $atts, 'technology' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_target_mapper' ) ) {
    function eden_engine_shortcode_target_mapper( array $atts ): string {
        return eden_engine_render( $atts, 'technology' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_pathway_demo' ) ) {
    function eden_engine_shortcode_pathway_demo( array $atts ): string {
        return eden_engine_render( $atts, 'technology' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_reactor_status' ) ) {
    function eden_engine_shortcode_reactor_status( array $atts ): string {
        return eden_engine_render( $atts, 'home' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_mission' ) ) {
    function eden_engine_shortcode_mission( array $atts ): string {
        return eden_engine_render( $atts, 'company' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_protein' ) ) {
    function eden_engine_shortcode_protein( array $atts ): string {
        return eden_engine_render( $atts, 'protein' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_technology' ) ) {
    function eden_engine_shortcode_technology( array $atts ): string {
        return eden_engine_render( $atts, 'technology' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_evidence' ) ) {
    function eden_engine_shortcode_evidence( array $atts ): string {
        return eden_engine_render( $atts, 'evidence' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_system' ) ) {
    function eden_engine_shortcode_system( array $atts ): string {
        return eden_engine_render( $atts, 'system' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_applications' ) ) {
    function eden_engine_shortcode_applications( array $atts ): string {
        return eden_engine_render( $atts, 'applications' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_roadmap' ) ) {
    function eden_engine_shortcode_roadmap( array $atts ): string {
        return eden_engine_render( $atts, 'roadmap' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_company' ) ) {
    function eden_engine_shortcode_company( array $atts ): string {
        return eden_engine_render( $atts, 'company' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_vision' ) ) {
    function eden_engine_shortcode_vision( array $atts ): string {
        return eden_engine_render( $atts, 'vision' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_partner' ) ) {
    function eden_engine_shortcode_partner( array $atts ): string {
        return eden_engine_render( $atts, 'partner' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_contact' ) ) {
    function eden_engine_shortcode_contact( array $atts ): string {
        return eden_engine_render( $atts, 'contact' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_technical_brief' ) ) {
    function eden_engine_shortcode_technical_brief( array $atts ): string {
        return eden_engine_render( $atts, 'technical-brief' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_whitepaper' ) ) {
    function eden_engine_shortcode_whitepaper( array $atts ): string {
        return eden_engine_render( $atts, 'technical-brief' );
    }
}

add_shortcode( 'eden_engine_showcase', 'eden_engine_shortcode_showcase' );
add_shortcode( 'eden_digital_twin', 'eden_engine_shortcode_digital_twin' );
add_shortcode( 'eden_target_mapper', 'eden_engine_shortcode_target_mapper' );
add_shortcode( 'eden_pathway_demo', 'eden_engine_shortcode_pathway_demo' );
add_shortcode( 'eden_reactor_status', 'eden_engine_shortcode_reactor_status' );
add_shortcode( 'eden_mission', 'eden_engine_shortcode_mission' );
add_shortcode( 'eden_protein', 'eden_engine_shortcode_protein' );
add_shortcode( 'eden_technology', 'eden_engine_shortcode_technology' );
add_shortcode( 'eden_evidence', 'eden_engine_shortcode_evidence' );
add_shortcode( 'eden_system', 'eden_engine_shortcode_system' );
add_shortcode( 'eden_applications', 'eden_engine_shortcode_applications' );
add_shortcode( 'eden_roadmap', 'eden_engine_shortcode_roadmap' );
add_shortcode( 'eden_company', 'eden_engine_shortcode_company' );
add_shortcode( 'eden_vision', 'eden_engine_shortcode_vision' );
add_shortcode( 'eden_partner', 'eden_engine_shortcode_partner' );
add_shortcode( 'eden_contact', 'eden_engine_shortcode_contact' );
add_shortcode( 'eden_technical_brief', 'eden_engine_shortcode_technical_brief' );
add_shortcode( 'eden_whitepaper', 'eden_engine_shortcode_whitepaper' );

if ( ! function_exists( 'eden_engine_current_page_widget' ) ) {
    function eden_engine_current_page_widget(): string {
        if ( is_front_page() && is_page() && ! is_home() ) {
            return 'home';
        }

        if ( is_page( 'mission' ) ) {
            return 'company';
        }

        if ( is_page( 'protein' ) ) {
            return 'protein';
        }

        if ( is_page( 'technology' ) ) {
            return 'technology';
        }

        if ( is_page( 'evidence' ) ) {
            return 'evidence';
        }

        if ( is_page( 'system' ) ) {
            return 'system';
        }

        if ( is_page( 'applications' ) ) {
            return 'applications';
        }

        if ( is_page( 'roadmap' ) ) {
            return 'roadmap';
        }

        if ( is_page( 'company' ) ) {
            return 'company';
        }

        if ( is_page( 'vision' ) ) {
            return 'vision';
        }

        if ( is_page( 'partner' ) || is_page( 'contact' ) ) {
            return 'partner';
        }

        if ( is_page( 'technical-brief' ) || is_page( 'whitepaper' ) ) {
            return 'technical-brief';
        }

        return '';
    }
}

if ( ! function_exists( 'eden_engine_auto_custom_pages' ) ) {
    function eden_engine_auto_custom_pages( string $content ): string {
        if ( is_admin() || ! is_main_query() || ! in_the_loop() ) {
            return $content;
        }

        if (
            has_shortcode( $content, 'eden_engine_showcase' ) ||
            has_shortcode( $content, 'eden_mission' ) ||
            has_shortcode( $content, 'eden_protein' ) ||
            has_shortcode( $content, 'eden_technology' ) ||
            has_shortcode( $content, 'eden_evidence' ) ||
            has_shortcode( $content, 'eden_system' ) ||
            has_shortcode( $content, 'eden_applications' ) ||
            has_shortcode( $content, 'eden_roadmap' ) ||
            has_shortcode( $content, 'eden_company' ) ||
            has_shortcode( $content, 'eden_vision' ) ||
            has_shortcode( $content, 'eden_partner' ) ||
            has_shortcode( $content, 'eden_contact' ) ||
            has_shortcode( $content, 'eden_technical_brief' ) ||
            has_shortcode( $content, 'eden_whitepaper' ) ||
            str_contains( $content, 'data-eden-engine-embed' )
        ) {
            return $content;
        }

        $widget = eden_engine_current_page_widget();

        if ( '' === $widget ) {
            return $content;
        }

        return eden_engine_render( array(), $widget );
    }
}

add_filter( 'the_content', 'eden_engine_auto_custom_pages', 5 );

if ( ! function_exists( 'eden_engine_redirect_alias_pages' ) ) {
    function eden_engine_redirect_alias_pages(): void {
        if ( is_admin() ) {
            return;
        }

        $destination  = '';
        $request_path = '';

        if ( isset( $GLOBALS['wp'] ) && isset( $GLOBALS['wp']->request ) ) {
            $request_path = trim( (string) $GLOBALS['wp']->request, '/' );
        }

        if ( is_page( 'evidence-2' ) || ( is_404() && 'evidence-2' === $request_path ) ) {
            $destination = home_url( '/evidence/' );
        } elseif ( is_page( 'system' ) || ( is_404() && 'system' === $request_path ) ) {
            $destination = add_query_arg( 'section', 'architecture', home_url( '/technology/' ) );
        } elseif (
            is_page( 'mission' ) ||
            is_page( 'vision' ) ||
            ( is_404() && in_array( $request_path, array( 'mission', 'vision' ), true ) )
        ) {
            $destination = home_url( '/company/' );
        } elseif ( is_page( 'contact' ) || ( is_404() && 'contact' === $request_path ) ) {
            $destination = home_url( '/partner/' );
        } elseif ( is_page( 'whitepaper' ) || ( is_404() && 'whitepaper' === $request_path ) ) {
            $destination = home_url( '/technical-brief/' );
        }

        if ( '' === $destination ) {
            return;
        }

        wp_safe_redirect( $destination, 301 );
        exit;
    }
}

add_action( 'template_redirect', 'eden_engine_redirect_alias_pages', 1 );

if ( ! function_exists( 'eden_engine_legacy_alias_page_slugs' ) ) {
    function eden_engine_legacy_alias_page_slugs(): array {
        return array( 'evidence-2', 'system', 'mission', 'vision', 'contact', 'whitepaper' );
    }
}

if ( ! function_exists( 'eden_engine_legacy_alias_page_ids' ) ) {
    function eden_engine_legacy_alias_page_ids(): array {
        $post_ids = array();

        foreach ( eden_engine_legacy_alias_page_slugs() as $slug ) {
            $page = get_page_by_path( $slug, OBJECT, 'page' );
            if ( $page instanceof WP_Post ) {
                $post_ids[] = (int) $page->ID;
            }
        }

        return array_values( array_unique( array_filter( $post_ids ) ) );
    }
}

if ( ! function_exists( 'eden_engine_exclude_aliases_from_core_sitemap' ) ) {
    function eden_engine_exclude_aliases_from_core_sitemap( array $query_args, string $post_type ): array {
        if ( 'page' !== $post_type ) {
            return $query_args;
        }

        $query_args['post__not_in'] = array_values(
            array_unique(
                array_merge(
                    (array) ( $query_args['post__not_in'] ?? array() ),
                    eden_engine_legacy_alias_page_ids()
                )
            )
        );

        return $query_args;
    }
}

add_filter( 'wp_sitemaps_posts_query_args', 'eden_engine_exclude_aliases_from_core_sitemap', 10, 2 );

if ( ! function_exists( 'eden_engine_exclude_aliases_from_yoast_sitemap' ) ) {
    function eden_engine_exclude_aliases_from_yoast_sitemap( array $post_ids ): array {
        return array_values( array_unique( array_merge( $post_ids, eden_engine_legacy_alias_page_ids() ) ) );
    }
}

add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', 'eden_engine_exclude_aliases_from_yoast_sitemap' );

if ( ! function_exists( 'eden_engine_exclude_aliases_from_rank_math_sitemap' ) ) {
    function eden_engine_exclude_aliases_from_rank_math_sitemap( $url, string $type, $object ) {
        if ( 'post' !== $type || ! $object instanceof WP_Post || 'page' !== $object->post_type ) {
            return $url;
        }

        return in_array( (int) $object->ID, eden_engine_legacy_alias_page_ids(), true ) ? false : $url;
    }
}

add_filter( 'rank_math/sitemap/entry', 'eden_engine_exclude_aliases_from_rank_math_sitemap', 10, 3 );

if ( ! function_exists( 'eden_engine_brief_request_field' ) ) {
    function eden_engine_brief_request_field( string $key ): string {
        $value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        return sanitize_text_field( (string) $value );
    }
}

if ( ! function_exists( 'eden_engine_partner_request_textarea' ) ) {
    function eden_engine_partner_request_textarea( string $key ): string {
        $value = isset( $_POST[ $key ] ) ? (string) wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        return sanitize_textarea_field( $value );
    }
}

if ( ! function_exists( 'eden_engine_partner_inquiry_types' ) ) {
    function eden_engine_partner_inquiry_types(): array {
        return array(
            'Laboratory or assay partner',
            'Bioprocess engineering',
            'Ingredient or industrial partner',
            'Academic collaboration',
            'Non-dilutive funding',
            'Strategic investment',
            'Media',
            'Technical brief request',
        );
    }
}

if ( ! function_exists( 'eden_engine_partner_nonce' ) ) {
    function eden_engine_partner_nonce(): void {
        nocache_headers();
        wp_send_json_success( array( 'nonce' => wp_create_nonce( 'eden_engine_partner_request' ) ) );
    }
}

add_action( 'wp_ajax_nopriv_eden_engine_partner_nonce', 'eden_engine_partner_nonce' );
add_action( 'wp_ajax_eden_engine_partner_nonce', 'eden_engine_partner_nonce' );

if ( ! function_exists( 'eden_engine_partner_rate_limit_key' ) ) {
    function eden_engine_partner_rate_limit_key(): string {
        $remote_address = isset( $_SERVER['REMOTE_ADDR'] )
            ? sanitize_text_field( (string) wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
            : 'unknown';

        return 'eden_partner_' . substr( hash_hmac( 'sha256', $remote_address, wp_salt( 'nonce' ) ), 0, 32 );
    }
}

if ( ! function_exists( 'eden_engine_handle_partner_request' ) ) {
    function eden_engine_handle_partner_request(): void {
        $nonce = eden_engine_brief_request_field( 'nonce' );

        if ( ! wp_verify_nonce( $nonce, 'eden_engine_partner_request' ) ) {
            wp_send_json_error( array( 'message' => 'The partner inquiry could not be verified. Please refresh and try again.' ), 403 );
        }

        if ( '' !== eden_engine_brief_request_field( 'website' ) ) {
            wp_send_json_error( array( 'message' => 'The partner inquiry could not be sent.' ), 400 );
        }

        $name                = eden_engine_brief_request_field( 'name' );
        $email               = sanitize_email( eden_engine_brief_request_field( 'email' ) );
        $organization        = eden_engine_brief_request_field( 'organization' );
        $role                = eden_engine_brief_request_field( 'role' );
        $inquiry_type        = eden_engine_brief_request_field( 'inquiryType' );
        $relevant_capability = eden_engine_partner_request_textarea( 'relevantCapability' );
        $reason_for_contact  = eden_engine_partner_request_textarea( 'reasonForContact' );
        $desired_next_step   = eden_engine_partner_request_textarea( 'desiredNextStep' );
        $attachment_url      = esc_url_raw( eden_engine_brief_request_field( 'attachmentUrl' ) );

        if ( '' === $inquiry_type ) {
            $inquiry_type = eden_engine_brief_request_field( 'interestType' );
        }

        if ( '' === $reason_for_contact ) {
            $reason_for_contact = eden_engine_partner_request_textarea( 'message' );
        }

        if (
            '' === $name ||
            '' === $email ||
            ! is_email( $email ) ||
            '' === $organization ||
            '' === $role ||
            '' === $inquiry_type ||
            '' === $relevant_capability ||
            '' === $reason_for_contact ||
            '' === $desired_next_step
        ) {
            wp_send_json_error(
                array( 'message' => 'Please complete every required partner inquiry field with a valid email address.' ),
                400
            );
        }

        if ( ! in_array( $inquiry_type, eden_engine_partner_inquiry_types(), true ) ) {
            wp_send_json_error( array( 'message' => 'Please select a recognized inquiry type.' ), 400 );
        }

        $length_limits = array(
            array( $name, 120 ),
            array( $email, 254 ),
            array( $organization, 180 ),
            array( $role, 140 ),
            array( $inquiry_type, 180 ),
            array( $relevant_capability, 2000 ),
            array( $reason_for_contact, 3000 ),
            array( $desired_next_step, 2000 ),
            array( $attachment_url, 2048 ),
        );

        foreach ( $length_limits as $length_limit ) {
            if ( strlen( $length_limit[0] ) > $length_limit[1] ) {
                wp_send_json_error( array( 'message' => 'One or more inquiry fields exceed the allowed length.' ), 400 );
            }
        }

        $attachment_input = eden_engine_brief_request_field( 'attachmentUrl' );
        if ( '' !== $attachment_input && '' === $attachment_url ) {
            wp_send_json_error( array( 'message' => 'Please provide a valid optional reference URL.' ), 400 );
        }

        $recipient = eden_engine_partner_recipient();

        if ( ! is_email( $recipient ) ) {
            wp_send_json_error( array( 'message' => 'The partner inbox is not configured. Please use the direct email link instead.' ), 500 );
        }

        $rate_limit_key = eden_engine_partner_rate_limit_key();
        $request_count  = (int) get_transient( $rate_limit_key );

        if ( $request_count >= 5 ) {
            wp_send_json_error(
                array( 'message' => 'Too many partner inquiries were submitted from this connection. Please wait and try again or use the direct email link.' ),
                429
            );
        }

        set_transient( $rate_limit_key, $request_count + 1, 10 * MINUTE_IN_SECONDS );

        $subject = sprintf( 'Eden Engine Partner Inquiry - %s - %s', $inquiry_type, $name );
        $body    = implode(
            "\n",
            array(
                'New Eden Engine Phase 1 partner inquiry',
                '',
                'Contact',
                'Name: ' . $name,
                'Email: ' . $email,
                'Organization: ' . ( $organization ?: 'Not provided' ),
                'Role: ' . ( $role ?: 'Not provided' ),
                '',
                'Inquiry',
                'Inquiry type: ' . $inquiry_type,
                'Relevant capability: ' . ( $relevant_capability ?: 'Not provided' ),
                '',
                'Reason for contacting Eden Engine:',
                $reason_for_contact,
                '',
                'Desired next step:',
                $desired_next_step,
                '',
                'Optional attachment or reference link: ' . ( $attachment_url ?: 'Not provided' ),
            )
        );
        $headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

        if ( ! wp_mail( $recipient, $subject, $body, $headers ) ) {
            wp_send_json_error( array( 'message' => 'The partner inquiry could not be sent. Please use the direct email link instead.' ), 500 );
        }

        wp_send_json_success( array( 'message' => 'Partner inquiry sent. Eden Engine will follow up by email.' ) );
    }
}

add_action( 'wp_ajax_nopriv_eden_engine_partner_request', 'eden_engine_handle_partner_request' );
add_action( 'wp_ajax_eden_engine_partner_request', 'eden_engine_handle_partner_request' );

if ( ! function_exists( 'eden_engine_body_class' ) ) {
    function eden_engine_body_class( array $classes ): array {
        if ( '' !== eden_engine_current_page_widget() ) {
            $classes[] = 'eden-engine-custom-page';
        }

        if ( eden_engine_should_style_blog() ) {
            $classes[] = 'eden-engine-custom-page';
            $classes[] = 'eden-engine-journal-page';
        }

        return $classes;
    }
}

add_filter( 'body_class', 'eden_engine_body_class' );

if ( ! function_exists( 'eden_engine_ensure_public_pages' ) ) {
    function eden_engine_ensure_public_pages(): void {
        if ( get_option( 'eden_engine_pages_created_version' ) === EDEN_ENGINE_VERSION ) {
            return;
        }

        if ( ! get_page_by_path( 'roadmap' ) ) {
            wp_insert_post(
                array(
                    'post_title'   => 'Roadmap',
                    'post_name'    => 'roadmap',
                    'post_content' => '[eden_roadmap]',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                )
            );
        }

        $pages = array(
            'protein'         => array( 'Protein', '[eden_protein]' ),
            'technology'      => array( 'Technology', '[eden_technology]' ),
            'evidence'        => array( 'Evidence', '[eden_evidence]' ),
            'applications'    => array( 'Applications', '[eden_applications]' ),
            'company'         => array( 'Company', '[eden_company]' ),
            'partner'         => array( 'Partner', '[eden_partner]' ),
            'technical-brief' => array( 'Technical Brief', '[eden_technical_brief]' ),
        );

        foreach ( $pages as $slug => $page ) {
            if ( get_page_by_path( $slug ) ) {
                continue;
            }

            wp_insert_post(
                array(
                    'post_title'   => $page[0],
                    'post_name'    => $slug,
                    'post_content' => $page[1],
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                )
            );
        }

        update_option( 'eden_engine_pages_created_version', EDEN_ENGINE_VERSION, false );
    }
}

add_action( 'init', 'eden_engine_ensure_public_pages', 20 );

if ( ! function_exists( 'eden_engine_purge_cache_after_update' ) ) {
    function eden_engine_purge_cache_after_update(): void {
        if ( is_admin() ) {
            return;
        }

        $option_name = 'eden_engine_cache_purged_version';

        if ( get_option( $option_name ) === EDEN_ENGINE_VERSION ) {
            return;
        }

        do_action( 'litespeed_purge_url', home_url( '/' ) );
        do_action( 'litespeed_purge_url', home_url( '/protein/' ) );
        do_action( 'litespeed_purge_url', home_url( '/technology/' ) );
        do_action( 'litespeed_purge_url', home_url( '/evidence/' ) );
        do_action( 'litespeed_purge_url', home_url( '/evidence-2/' ) );
        do_action( 'litespeed_purge_url', home_url( '/system/' ) );
        do_action( 'litespeed_purge_url', home_url( '/applications/' ) );
        do_action( 'litespeed_purge_url', home_url( '/roadmap/' ) );
        do_action( 'litespeed_purge_url', home_url( '/company/' ) );
        do_action( 'litespeed_purge_url', home_url( '/vision/' ) );
        do_action( 'litespeed_purge_url', home_url( '/partner/' ) );
        do_action( 'litespeed_purge_url', home_url( '/technical-brief/' ) );
        do_action( 'litespeed_purge_url', home_url( '/contact/' ) );
        do_action( 'litespeed_purge_url', home_url( '/journal/' ) );

        if ( function_exists( 'eden_engine_seeded_artifact_publications' ) ) {
            foreach ( array_keys( eden_engine_seeded_artifact_publications() ) as $publication_slug ) {
                do_action( 'litespeed_purge_url', home_url( '/' . sanitize_title( (string) $publication_slug ) . '/' ) );
            }
        }

        do_action( 'litespeed_purge_all' );

        if ( ! headers_sent() ) {
            header( 'X-LiteSpeed-Purge: *', false );
        }

        update_option( $option_name, EDEN_ENGINE_VERSION, false );
    }
}

add_action( 'wp', 'eden_engine_purge_cache_after_update', 20 );

if ( ! function_exists( 'eden_engine_public_tagline' ) ) {
    function eden_engine_public_tagline(): string {
        return 'Carbon In. Food Infrastructure Out.';
    }
}

add_filter( 'pre_option_blogdescription', 'eden_engine_public_tagline' );

if ( ! function_exists( 'eden_engine_public_route_metadata' ) ) {
    function eden_engine_public_route_metadata(): array {
        $widget = eden_engine_current_page_widget();

        if ( '' === $widget ) {
            return array();
        }

        $titles = array(
            'home'            => 'Carbon-Fed Fermentation for Sustainable Protein',
            'protein'         => 'Phase 1 Sustainable Protein Program',
            'technology'      => 'One Platform, Two Carbon Interfaces',
            'evidence'        => 'Evidence Program and Active Claim Status',
            'roadmap'         => 'Evidence-Gated Research Roadmap',
            'applications'    => 'Potential Applications and Evidence Boundaries',
            'company'         => 'Company, Mission, and Information Boundary',
            'partner'         => 'Partner on Validation',
            'technical-brief' => 'Phase 1 Technical Brief',
        );
        $content = eden_engine_public_widget_content( $widget );

        return array(
            'title'       => $titles[ $widget ] ?? (string) $content['title'],
            'description' => (string) $content['summary'],
        );
    }
}

if ( ! function_exists( 'eden_engine_document_title' ) ) {
    function eden_engine_document_title( array $parts ): array {
        $metadata = eden_engine_public_route_metadata();

        if ( ! empty( $metadata['title'] ) ) {
            $parts['title'] = (string) $metadata['title'];
        }

        $parts['tagline'] = eden_engine_public_tagline();

        return $parts;
    }
}

add_filter( 'document_title_parts', 'eden_engine_document_title' );

if ( ! function_exists( 'eden_engine_filter_public_page_seo_title' ) ) {
    function eden_engine_filter_public_page_seo_title( string $title ): string {
        $metadata = eden_engine_public_route_metadata();

        return ! empty( $metadata['title'] ) ? (string) $metadata['title'] . ' | ' . get_bloginfo( 'name' ) : $title;
    }
}

if ( ! function_exists( 'eden_engine_filter_public_page_seo_description' ) ) {
    function eden_engine_filter_public_page_seo_description( string $description ): string {
        $metadata = eden_engine_public_route_metadata();

        return ! empty( $metadata['description'] ) ? (string) $metadata['description'] : $description;
    }
}

add_filter( 'wpseo_title', 'eden_engine_filter_public_page_seo_title', 30 );
add_filter( 'wpseo_opengraph_title', 'eden_engine_filter_public_page_seo_title', 30 );
add_filter( 'wpseo_twitter_title', 'eden_engine_filter_public_page_seo_title', 30 );
add_filter( 'rank_math/frontend/title', 'eden_engine_filter_public_page_seo_title', 30 );
add_filter( 'rank_math/opengraph/facebook/og_title', 'eden_engine_filter_public_page_seo_title', 30 );
add_filter( 'rank_math/opengraph/twitter/twitter_title', 'eden_engine_filter_public_page_seo_title', 30 );
add_filter( 'wpseo_metadesc', 'eden_engine_filter_public_page_seo_description', 30 );
add_filter( 'wpseo_opengraph_desc', 'eden_engine_filter_public_page_seo_description', 30 );
add_filter( 'wpseo_twitter_description', 'eden_engine_filter_public_page_seo_description', 30 );
add_filter( 'rank_math/frontend/description', 'eden_engine_filter_public_page_seo_description', 30 );
add_filter( 'rank_math/opengraph/facebook/og_description', 'eden_engine_filter_public_page_seo_description', 30 );
add_filter( 'rank_math/opengraph/twitter/twitter_description', 'eden_engine_filter_public_page_seo_description', 30 );

if ( ! function_exists( 'eden_engine_public_page_social_image' ) ) {
    function eden_engine_public_page_social_image(): string {
        $images = array(
            'home'            => 'pages/home/pilot-system-hero.png',
            'protein'         => 'pages/technology/bench-platform-hero-v2.webp',
            'technology'      => 'pages/technology/bench-platform-hero-v2.webp',
            'evidence'        => 'pages/evidence/measurement-bench-v2.webp',
            'roadmap'         => 'pages/roadmap/evidence-gates-v2.webp',
            'applications'    => 'pages/applications/hero-global-mission-map.jpg',
            'company'         => 'pages/company/hero-founder-reactor.jpg',
            'partner'         => 'pages/partner/technical-review-v2.webp',
            'technical-brief' => 'pages/technical-brief/dossier-v2.webp',
        );
        $widget = eden_engine_current_page_widget();

        if ( empty( $images[ $widget ] ) ) {
            return '';
        }

        return EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/' . $images[ $widget ];
    }
}

if ( ! function_exists( 'eden_engine_filter_public_page_social_image' ) ) {
    function eden_engine_filter_public_page_social_image( string $image ): string {
        $public_image = eden_engine_public_page_social_image();

        return '' !== $public_image ? $public_image : $image;
    }
}

add_filter( 'wpseo_opengraph_image', 'eden_engine_filter_public_page_social_image', 30 );
add_filter( 'wpseo_twitter_image', 'eden_engine_filter_public_page_social_image', 30 );
add_filter( 'rank_math/opengraph/facebook/og_image', 'eden_engine_filter_public_page_social_image', 30 );
add_filter( 'rank_math/opengraph/twitter/twitter_image', 'eden_engine_filter_public_page_social_image', 30 );

if ( ! function_exists( 'eden_engine_public_page_head_metadata' ) ) {
    function eden_engine_public_page_head_metadata(): void {
        if ( ! eden_engine_is_public_app_page() || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
            return;
        }

        $metadata = eden_engine_public_route_metadata();

        if ( empty( $metadata ) ) {
            return;
        }

        $title       = (string) $metadata['title'] . ' | ' . get_bloginfo( 'name' );
        $description = (string) $metadata['description'];
        $url         = get_permalink( get_queried_object_id() ) ?: home_url( '/' );
        $image_url   = eden_engine_public_page_social_image();
        $schema      = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'WebPage',
            'name'        => $title,
            'description' => $description,
            'url'         => $url,
            'isPartOf'    => array(
                '@type' => 'WebSite',
                'name'  => get_bloginfo( 'name' ),
                'url'   => home_url( '/' ),
            ),
        );

        if ( '' !== $image_url ) {
            $schema['image'] = $image_url;
        }

        echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
        if ( '' !== $image_url ) {
            echo '<meta property="og:image" content="' . esc_url( $image_url ) . '" />' . "\n";
            echo '<meta property="og:image:alt" content="' . esc_attr( $title ) . '" />' . "\n";
        }
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
        if ( '' !== $image_url ) {
            echo '<meta name="twitter:image" content="' . esc_url( $image_url ) . '" />' . "\n";
        }
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}

add_action( 'wp_head', 'eden_engine_public_page_head_metadata', 5 );

if ( ! function_exists( 'eden_engine_nav_html' ) ) {
    function eden_engine_nav_html(): string {
        $items = array(
            array( 'protein', 'Protein', home_url( '/protein/' ) ),
            array( 'technology', 'Technology', home_url( '/technology/' ) ),
            array( 'evidence', 'Evidence', home_url( '/evidence/' ) ),
            array( 'roadmap', 'Roadmap', home_url( '/roadmap/' ) ),
            array( 'applications', 'Applications', home_url( '/applications/' ) ),
            array( 'journal', 'Journal', home_url( '/journal/' ) ),
            array( 'company', 'Company', home_url( '/company/' ) ),
        );

        $html  = '<div class="eden-wp-nav-wrap"><header class="site-header site-header--interactive eden-wp-nav" aria-label="Eden Engine site header" data-eden-wp-nav>';
        $html .= '<a class="site-brand" href="' . esc_url( home_url( '/' ) ) . '" aria-label="Eden Engine home">';
        $html .= '<span class="site-brand__mark site-brand__mark--image" aria-hidden="true"><img src="' . esc_url( EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/brand/legacy-tree-logo.png' ) . '" alt="" /></span>';
        $html .= '<span><strong>Eden Engine</strong><small>Carbon In. Food Infrastructure Out.</small></span></a>';
        $html .= '<button class="site-nav-toggle" type="button" aria-controls="eden-wp-primary-menu" aria-expanded="false" aria-label="Open primary navigation" data-eden-wp-nav-toggle>';
        $html .= '<span data-eden-wp-nav-label>Menu</span><span class="site-nav-toggle__glyph" aria-hidden="true"><i></i><i></i></span></button>';
        $html .= '<div class="site-header__panel" id="eden-wp-primary-menu" data-eden-wp-nav-panel>';
        $html .= '<nav class="site-nav eden-wp-site-nav" aria-label="Primary navigation">';

        foreach ( $items as $item ) {
            $is_current = ( 'journal' === $item[0] && eden_engine_should_style_blog() ) || is_page( $item[0] );
            $current    = $is_current ? ' aria-current="page"' : '';
            $html      .= '<a class="site-nav__link" href="' . esc_url( $item[2] ) . '"' . $current . '>' . esc_html( $item[1] ) . '</a>';
        }

        $html .= '</nav><div class="site-header__actions">';
        $html .= '<a class="button button--primary" href="' . esc_url( home_url( '/partner/' ) ) . '">Partner on Validation</a>';
        $html .= '</div></div>';
        $html .= '</header></div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_footer_html' ) ) {
    function eden_engine_footer_html(): string {
        $logo_url = EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/brand/legacy-tree-logo.png';

        $html  = '<footer class="site-footer eden-wp-footer">';
        $html .= '<div class="site-footer__brand">';
        $html .= '<a class="site-brand" href="' . esc_url( home_url( '/' ) ) . '" aria-label="Eden Engine home">';
        $html .= '<span class="site-brand__mark site-brand__mark--image" aria-hidden="true"><img src="' . esc_url( $logo_url ) . '" alt="" /></span>';
        $html .= '<span><strong>Eden Engine</strong><small>Carbon In. Food Infrastructure Out.</small></span></a>';
        $html .= '<p>Eden Engine is developing a modular carbon-fed fermentation platform, beginning with a selected direct CO₂/H₂ pathway to protein-rich microbial biomass. Acetate remains a later platform-expansion interface.</p>';
        $html .= '</div>';
        $html .= '<div class="site-footer__links" aria-label="Footer navigation">';
        $html .= '<div><h2>Protein</h2><a href="' . esc_url( home_url( '/protein/' ) ) . '">Protein Program</a></div>';
        $html .= '<div><h2>Technology</h2><a href="' . esc_url( home_url( '/technology/' ) ) . '">Technology</a></div>';
        $html .= '<div><h2>Evidence</h2><a href="' . esc_url( home_url( '/evidence/' ) ) . '">Evidence Program</a></div>';
        $html .= '<div><h2>Roadmap</h2><a href="' . esc_url( home_url( '/roadmap/' ) ) . '">Roadmap</a></div>';
        $html .= '<div><h2>Applications</h2><a href="' . esc_url( home_url( '/applications/' ) ) . '">Applications</a></div>';
        $html .= '<div><h2>Journal</h2><a href="' . esc_url( home_url( '/journal/' ) ) . '">Journal</a></div>';
        $html .= '<div><h2>Company</h2><a href="' . esc_url( home_url( '/company/' ) ) . '">Company</a><a href="' . esc_url( home_url( '/partner/' ) ) . '">Partner on Validation</a><a href="' . esc_url( home_url( '/technical-brief/' ) ) . '">Technical Brief</a></div>';
        $html .= '</div>';
        $html .= '<p class="site-footer__disclaimer">Current stage: software, pathway models, and experiment controls built; qualified biological validation next. No Eden-specific food-grade output or commercial production claim is being made.</p>';
        $html .= '</footer>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_blog_nav' ) ) {
    function eden_engine_blog_nav(): void {
        if ( eden_engine_should_style_blog() ) {
            echo eden_engine_nav_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}
