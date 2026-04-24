<?php
/**
 * Public-facing Eden Engine shortcodes.
 *
 * @package EdenEngine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'eden_engine_shortcode_names' ) ) {
    function eden_engine_shortcode_names(): array {
        return array(
            'eden_engine_showcase',
            'eden_digital_twin',
            'eden_target_mapper',
            'eden_pathway_demo',
            'eden_reactor_status',
        );
    }
}

if ( ! function_exists( 'eden_engine_enqueue_assets' ) ) {
    function eden_engine_enqueue_assets(): void {
        $style_path  = EDEN_ENGINE_PLUGIN_PATH . 'assets/eden-engine.css';
        $script_path = EDEN_ENGINE_PLUGIN_PATH . 'assets/eden-engine.js';

        $style_version  = file_exists( $style_path ) ? (string) filemtime( $style_path ) : EDEN_ENGINE_VERSION;
        $script_version = file_exists( $script_path ) ? (string) filemtime( $script_path ) : EDEN_ENGINE_VERSION;

        wp_enqueue_style(
            'eden-engine',
            EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.css',
            array(),
            $style_version
        );

        wp_enqueue_script(
            'eden-engine',
            EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.js',
            array(),
            $script_version,
            true
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

if ( ! function_exists( 'eden_engine_maybe_enqueue_assets' ) ) {
    function eden_engine_maybe_enqueue_assets(): void {
        if ( eden_engine_page_has_shortcode() ) {
            eden_engine_enqueue_assets();
        }
    }
}

add_action( 'wp_enqueue_scripts', 'eden_engine_maybe_enqueue_assets' );

if ( ! function_exists( 'eden_engine_status_pill' ) ) {
    function eden_engine_status_pill( string $tone, string $label ): string {
        return sprintf(
            '<span class="eden-pill eden-pill--%1$s">%2$s</span>',
            esc_attr( $tone ),
            esc_html( $label )
        );
    }
}

if ( ! function_exists( 'eden_engine_section_header' ) ) {
    function eden_engine_section_header( string $eyebrow, string $title, string $copy = '' ): string {
        $html  = '<div class="eden-section-header">';
        $html .= sprintf( '<p class="eden-eyebrow">%s</p>', esc_html( $eyebrow ) );
        $html .= sprintf( '<h2>%s</h2>', esc_html( $title ) );

        if ( '' !== $copy ) {
            $html .= sprintf( '<p>%s</p>', esc_html( $copy ) );
        }

        $html .= '</div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_feature_cards_html' ) ) {
    function eden_engine_feature_cards_html(): string {
        $cards = array(
            array(
                'title' => 'Closed loop planning',
                'copy'  => 'Model resource flows, crop needs, operating targets, and system constraints in one public story.',
            ),
            array(
                'title' => 'Digital twin view',
                'copy'  => 'Show how inputs, conversion steps, growing systems, and outputs relate without exposing private controls.',
            ),
            array(
                'title' => 'Target mapping',
                'copy'  => 'Let visitors explore high-level tradeoffs such as efficiency, energy price, and pathway readiness.',
            ),
            array(
                'title' => 'Public safe status',
                'copy'  => 'Share milestones and demo state clearly while keeping internal research documents and APIs private.',
            ),
        );

        $html = '<div class="eden-feature-grid" aria-label="Eden Engine feature summary">';

        foreach ( $cards as $card ) {
            $html .= sprintf(
                '<article class="eden-card eden-feature-card"><span class="eden-card-rule"></span><h3>%1$s</h3><p>%2$s</p></article>',
                esc_html( $card['title'] ),
                esc_html( $card['copy'] )
            );
        }

        $html .= '</div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_digital_twin_html' ) ) {
    function eden_engine_digital_twin_html(): string {
        $modules = array(
            array(
                'stage'   => 'Input',
                'name'    => 'CO2 and resource streams',
                'summary' => 'Public diagrams treat carbon, water, energy, and nutrients as visible inputs to the production plan.',
                'metric'  => 'Resource flow',
                'tone'    => 'ready',
            ),
            array(
                'stage'   => 'System',
                'name'    => 'Farm operating layer',
                'summary' => 'The twin connects equipment state, crop planning, and pathway assumptions into one shared view.',
                'metric'  => 'Digital twin',
                'tone'    => 'ready',
            ),
            array(
                'stage'   => 'Planning',
                'name'    => 'Production pathway',
                'summary' => 'Visitors can see the intended pathway logic without access to private lab notes or control surfaces.',
                'metric'  => 'Public demo',
                'tone'    => 'watch',
            ),
            array(
                'stage'   => 'Output',
                'name'    => 'Fruit and food production',
                'summary' => 'The public copy frames output as a planning target, not a claim of deployed production capacity.',
                'metric'  => 'Resilience',
                'tone'    => 'planned',
            ),
        );

        $html  = '<section class="eden-section eden-digital-twin" data-eden-section>';
        $html .= eden_engine_section_header(
            'Digital Twin',
            'A public-safe map of the closed loop food system',
            'The digital twin explains how major system pieces relate while avoiding private documents, internal APIs, and operational controls.'
        );
        $html .= '<div class="eden-flow" aria-label="Eden Engine digital twin modules">';

        foreach ( $modules as $index => $module ) {
            $html .= sprintf(
                '<article class="eden-card eden-flow-card" data-eden-module="%1$d"><span class="eden-step">%2$s</span><h3>%3$s</h3><p>%4$s</p><div class="eden-card-footer">%5$s<strong>%6$s</strong></div></article>',
                absint( $index ),
                esc_html( $module['stage'] ),
                esc_html( $module['name'] ),
                esc_html( $module['summary'] ),
                eden_engine_status_pill( $module['tone'], $module['tone'] ),
                esc_html( $module['metric'] )
            );
        }

        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_target_mapper_html' ) ) {
    function eden_engine_target_mapper_html(): string {
        $html  = '<section class="eden-section eden-target-mapper" data-eden-section>';
        $html .= eden_engine_section_header(
            'Target Mapper',
            'Move the public planning levers',
            'This lightweight mapper shows how visible assumptions can shape a public estimate. It is an educational demo, not a forecast or guarantee.'
        );
        $html .= '<div class="eden-tool">';
        $html .= '<label><span>Faradaic efficiency <strong data-eden-efficiency-output>82%</strong></span><input data-eden-efficiency type="range" min="60" max="94" value="82" /></label>';
        $html .= '<label><span>Electricity <strong data-eden-electricity-output>$0.04/kWh</strong></span><input data-eden-electricity type="range" min="0.01" max="0.12" step="0.01" value="0.04" /></label>';
        $html .= '<div class="eden-metrics">';
        $html .= '<div><span>Modeled cost</span><strong data-eden-modeled-cost>$118/kg</strong></div>';
        $html .= '<div><span>Energy intensity</span><strong data-eden-energy-intensity>39.9 kWh/kg</strong></div>';
        $html .= '<div><span>Energy share</span><strong data-eden-energy-share>$1.60/kg</strong></div>';
        $html .= '<div><span>Output index</span><strong data-eden-output-index>1.00x</strong></div>';
        $html .= '</div>';
        $html .= '<p class="eden-note">Public demo only. Internal models, research documents, and private controls are not exposed.</p>';
        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_html' ) ) {
    function eden_engine_pathway_demo_html(): string {
        $routes = array(
            array(
                'name'       => 'Hybrid closed loop fruit system',
                'confidence' => '84',
                'signal'     => 'Best public demo candidate',
                'details'    => 'Balances resource recovery, crop planning, and system monitoring in a way visitors can understand.',
            ),
            array(
                'name'       => 'Stability-first greenhouse mode',
                'confidence' => '76',
                'signal'     => 'Operations track',
                'details'    => 'Prioritizes predictable operating conditions, maintenance visibility, and crop planning.',
            ),
            array(
                'name'       => 'Remote resilience mode',
                'confidence' => '69',
                'signal'     => 'Resilience planning',
                'details'    => 'Frames closed loop production for sites where land, water, logistics, or climate stability are constrained.',
            ),
        );

        $html  = '<section class="eden-section eden-pathway-demo" data-eden-section>';
        $html .= eden_engine_section_header(
            'Pathway Demo',
            'Compare production routes without hiding constraints',
            'A sanitized pathway view for education, partner conversations, and website storytelling.'
        );
        $html .= '<div class="eden-routes">';

        foreach ( $routes as $index => $route ) {
            $active = 0 === $index ? ' is-active' : '';
            $html  .= sprintf(
                '<button class="eden-route%1$s" type="button" data-eden-route="%2$d" data-confidence="%3$s" data-details="%4$s"><span>%5$s</span><strong>%6$s</strong><b>%3$s%%</b></button>',
                esc_attr( $active ),
                absint( $index ),
                esc_attr( $route['confidence'] ),
                esc_attr( $route['details'] ),
                esc_html( $route['signal'] ),
                esc_html( $route['name'] )
            );
        }

        $html .= '<div class="eden-route-detail"><div class="eden-ring" style="--eden-ring: 84"><span data-eden-route-confidence>84</span></div><p data-eden-route-details>Balances resource recovery, crop planning, and system monitoring in a way visitors can understand.</p></div>';
        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_html' ) ) {
    function eden_engine_reactor_status_html(): string {
        $items = array(
            array( 'label' => 'Public phase', 'value' => 'Investor-ready demo', 'tone' => 'ready' ),
            array( 'label' => 'Primary surface', 'value' => 'WordPress shortcodes', 'tone' => 'ready' ),
            array( 'label' => 'Next integration', 'value' => 'Deeper app-linked demos', 'tone' => 'watch' ),
            array( 'label' => 'Data exposure', 'value' => 'Sanitized public mode', 'tone' => 'planned' ),
        );

        $html  = '<section class="eden-section eden-reactor-status" data-eden-section>';
        $html .= eden_engine_section_header(
            'Reactor Status',
            'What visitors can safely see',
            'A restrained public status summary focused on milestones and demo readiness rather than private operations.'
        );
        $html .= '<div class="eden-status-grid">';

        foreach ( $items as $item ) {
            $html .= sprintf(
                '<article class="eden-card">%1$s<h3>%2$s</h3><p>%3$s</p></article>',
                eden_engine_status_pill( $item['tone'], $item['label'] ),
                esc_html( $item['value'] ),
                esc_html( 'Public website view' )
            );
        }

        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_cta_html' ) ) {
    function eden_engine_cta_html(): string {
        $html  = '<section class="eden-cta" data-eden-section>';
        $html .= '<div>';
        $html .= '<p class="eden-eyebrow">Collaborate</p>';
        $html .= '<h2>Bring Eden Engine into a partner conversation.</h2>';
        $html .= '<p>Use this public page as the front door for researchers, growers, investors, and infrastructure partners who need a clear view of the system without private data exposure.</p>';
        $html .= '</div>';
        $html .= '<a class="eden-button" href="mailto:jackrichardlawson@gmail.com">Start a conversation</a>';
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_showcase_shortcode' ) ) {
    function eden_engine_showcase_shortcode(): string {
        eden_engine_enqueue_assets();

        $html  = '<div class="eden-showcase">';
        $html .= '<section class="eden-hero" data-eden-section>';
        $html .= '<div class="eden-hero-copy">';
        $html .= '<p class="eden-eyebrow">Public-safe closed loop food system demo</p>';
        $html .= '<h1>A farm operating layer for resilient fruit and food production.</h1>';
        $html .= '<p>Eden Engine models the equipment, pathways, resource flows, and operating decisions behind closed loop growing systems. This WordPress section gives visitors a clear, credible public view without exposing private research controls.</p>';
        $html .= '<div class="eden-hero-actions">';
        $html .= '<a class="eden-button" href="#eden-digital-twin">Explore the system</a>';
        $html .= eden_engine_status_pill( 'planned', 'Public safe demo' );
        $html .= '</div></div>';
        $html .= '<div class="eden-hero-panel" aria-label="Eden Engine public system summary">';
        $html .= '<div class="eden-hero-grid"><span>Inputs</span><span>Water</span><span>Energy</span><span>Nutrients</span><span>Twin</span><span>Sensors</span><span>Crops</span><span>Storage</span><span>Output</span></div>';
        $html .= '<strong>Closed loop pathway aligned</strong><p>Resource flows, crop plans, and demo status are presented for visitors without private system controls.</p>';
        $html .= '</div></section>';
        $html .= '<section class="eden-section eden-overview" data-eden-section>';
        $html .= eden_engine_section_header(
            'Eden Engine overview',
            'A practical control story for closed loop growing',
            'The showcase brings together the public-safe WordPress sections into one polished homepage block.'
        );
        $html .= eden_engine_feature_cards_html();
        $html .= '</section>';
        $html .= '<div id="eden-digital-twin">' . eden_engine_digital_twin_html() . '</div>';
        $html .= eden_engine_target_mapper_html();
        $html .= eden_engine_pathway_demo_html();
        $html .= eden_engine_reactor_status_html();
        $html .= eden_engine_cta_html();
        $html .= '</div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_digital_twin_shortcode' ) ) {
    function eden_engine_digital_twin_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single">' . eden_engine_digital_twin_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_target_mapper_shortcode' ) ) {
    function eden_engine_target_mapper_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single">' . eden_engine_target_mapper_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_shortcode' ) ) {
    function eden_engine_pathway_demo_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single">' . eden_engine_pathway_demo_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_shortcode' ) ) {
    function eden_engine_reactor_status_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single">' . eden_engine_reactor_status_html() . '</div>';
    }
}

add_shortcode( 'eden_engine_showcase', 'eden_engine_showcase_shortcode' );
add_shortcode( 'eden_digital_twin', 'eden_engine_digital_twin_shortcode' );
add_shortcode( 'eden_target_mapper', 'eden_engine_target_mapper_shortcode' );
add_shortcode( 'eden_pathway_demo', 'eden_engine_pathway_demo_shortcode' );
add_shortcode( 'eden_reactor_status', 'eden_engine_reactor_status_shortcode' );
