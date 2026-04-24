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
            '<span class="eden-engine-pill eden-engine-pill--%1$s">%2$s</span>',
            esc_attr( $tone ),
            esc_html( $label )
        );
    }
}

if ( ! function_exists( 'eden_engine_section_header' ) ) {
    function eden_engine_section_header( string $eyebrow, string $title, string $copy = '' ): string {
        $html  = '<div class="eden-engine-section__header">';
        $html .= sprintf( '<p class="eden-engine-eyebrow">%s</p>', esc_html( $eyebrow ) );
        $html .= sprintf( '<h2>%s</h2>', esc_html( $title ) );

        if ( '' !== $copy ) {
            $html .= sprintf( '<p>%s</p>', esc_html( $copy ) );
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
                'name'    => 'CO2 capture',
                'summary' => 'Captured carbon dioxide is routed into a public model of the conversion chain.',
                'metric'  => 'Feedstock',
                'tone'    => 'ready',
            ),
            array(
                'stage'   => 'Conversion',
                'name'    => 'Electrolyzer stack',
                'summary' => 'Renewable power converts CO2 toward formate in a simplified public simulation.',
                'metric'  => 'Modeled',
                'tone'    => 'ready',
            ),
            array(
                'stage'   => 'Assembly',
                'name'    => 'Enzyme cascade',
                'summary' => 'A visible, non-sensitive pathway view shows how C1 inputs can become sugar precursors.',
                'metric'  => 'Research track',
                'tone'    => 'watch',
            ),
            array(
                'stage'   => 'Output',
                'name'    => 'Glucose stream',
                'summary' => 'The demo frames glucose as a familiar food-system molecule without making production claims.',
                'metric'  => 'Target',
                'tone'    => 'planned',
            ),
        );

        $html  = '<section class="eden-engine-section eden-engine-digital-twin" data-eden-engine-section>';
        $html .= eden_engine_section_header(
            'Digital twin',
            'A public-safe map of the CO2-to-food system',
            'The twin shows relationships between modules without exposing private data, internal controls, or unreleased research.'
        );
        $html .= '<div class="eden-engine-flow" aria-label="Eden Engine digital twin modules">';

        foreach ( $modules as $index => $module ) {
            $html .= sprintf(
                '<article class="eden-engine-card eden-engine-flow__card" data-eden-engine-module="%1$d"><span>%2$s</span><h3>%3$s</h3><p>%4$s</p><div class="eden-engine-card__footer">%5$s<strong>%6$s</strong></div></article>',
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
        $html  = '<section class="eden-engine-section eden-engine-target-mapper" data-eden-engine-section>';
        $html .= eden_engine_section_header(
            'Target mapper',
            'Simple levers for a visitor-facing model',
            'These controls illustrate how efficiency and energy price change a public estimate. They are not a forecast or a guarantee.'
        );
        $html .= '<div class="eden-engine-tool">';
        $html .= '<label><span>Faradaic efficiency <strong data-eden-efficiency-output>82%</strong></span><input data-eden-efficiency type="range" min="60" max="94" value="82" /></label>';
        $html .= '<label><span>Electricity <strong data-eden-electricity-output>$0.04/kWh</strong></span><input data-eden-electricity type="range" min="0.01" max="0.12" step="0.01" value="0.04" /></label>';
        $html .= '<div class="eden-engine-metrics">';
        $html .= '<div><span>Modeled cost</span><strong data-eden-modeled-cost>$118/kg</strong></div>';
        $html .= '<div><span>Energy intensity</span><strong data-eden-energy-intensity>39.9 kWh/kg</strong></div>';
        $html .= '<div><span>Energy share</span><strong data-eden-energy-share>$1.60/kg</strong></div>';
        $html .= '<div><span>Output index</span><strong data-eden-output-index>1.00x</strong></div>';
        $html .= '</div></div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_html' ) ) {
    function eden_engine_pathway_demo_html(): string {
        $routes = array(
            array(
                'name'       => 'Hybrid formate-to-hexose',
                'confidence' => '86',
                'signal'     => 'Best public demo candidate',
                'details'    => 'Balances electrochemical efficiency with a glucose-oriented enzyme cascade.',
            ),
            array(
                'name'       => 'Stability-first cascade',
                'confidence' => '74',
                'signal'     => 'Pilot robustness track',
                'details'    => 'Prioritizes operating window and enzyme lifetime over maximum throughput.',
            ),
            array(
                'name'       => 'Closed-loop habitat mode',
                'confidence' => '68',
                'signal'     => 'Remote systems framing',
                'details'    => 'Explains how captured CO2 could support resilient food-system design conversations.',
            ),
        );

        $html  = '<section class="eden-engine-section eden-engine-pathway-demo" data-eden-engine-section>';
        $html .= eden_engine_section_header(
            'Pathway demo',
            'Ranked public routes to glucose',
            'A sanitized pathway view for education, partner conversations, and website storytelling.'
        );
        $html .= '<div class="eden-engine-routes">';

        foreach ( $routes as $index => $route ) {
            $active = 0 === $index ? ' is-active' : '';
            $html  .= sprintf(
                '<button class="eden-engine-route%1$s" type="button" data-eden-route="%2$d" data-confidence="%3$s" data-details="%4$s"><span>%5$s</span><strong>%6$s</strong><b>%3$s%%</b></button>',
                esc_attr( $active ),
                absint( $index ),
                esc_attr( $route['confidence'] ),
                esc_attr( $route['details'] ),
                esc_html( $route['signal'] ),
                esc_html( $route['name'] )
            );
        }

        $html .= '<div class="eden-engine-route-detail"><div class="eden-engine-ring" style="--eden-ring: 86"><span data-eden-route-confidence>86</span></div><p data-eden-route-details>Balances electrochemical efficiency with a glucose-oriented enzyme cascade.</p></div>';
        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_html' ) ) {
    function eden_engine_reactor_status_html(): string {
        $items = array(
            array( 'label' => 'Current public phase', 'value' => 'Bench foundation', 'tone' => 'ready' ),
            array( 'label' => 'Primary milestone', 'value' => 'CO2 to formate', 'tone' => 'ready' ),
            array( 'label' => 'Next integration', 'value' => 'Formate to glucose', 'tone' => 'watch' ),
            array( 'label' => 'Public data mode', 'value' => 'Sanitized demo', 'tone' => 'planned' ),
        );

        $html  = '<section class="eden-engine-section eden-engine-reactor-status" data-eden-engine-section>';
        $html .= eden_engine_section_header(
            'Reactor status',
            'What visitors can safely see',
            'A restrained status summary for the website, focused on public milestones rather than private operations.'
        );
        $html .= '<div class="eden-engine-status-grid">';

        foreach ( $items as $item ) {
            $html .= sprintf(
                '<article class="eden-engine-card">%1$s<h3>%2$s</h3><p>%3$s</p></article>',
                eden_engine_status_pill( $item['tone'], $item['label'] ),
                esc_html( $item['value'] ),
                esc_html( 'Public website view' )
            );
        }

        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_showcase_shortcode' ) ) {
    function eden_engine_showcase_shortcode(): string {
        eden_engine_enqueue_assets();

        $html  = '<div class="eden-engine-showcase">';
        $html .= '<section class="eden-engine-hero" data-eden-engine-section>';
        $html .= '<p class="eden-engine-eyebrow">Eden Engine</p>';
        $html .= '<h1>Public-safe CO2-to-food system demo</h1>';
        $html .= '<p>Eden Engine presents a clear website-facing view of digital twins, pathway mapping, and milestone status for carbon-to-food research.</p>';
        $html .= '<div class="eden-engine-hero__actions">';
        $html .= eden_engine_status_pill( 'ready', 'WordPress ready' );
        $html .= eden_engine_status_pill( 'planned', 'No private data' );
        $html .= '</div></section>';
        $html .= eden_engine_digital_twin_html();
        $html .= eden_engine_target_mapper_html();
        $html .= eden_engine_pathway_demo_html();
        $html .= eden_engine_reactor_status_html();
        $html .= '</div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_digital_twin_shortcode' ) ) {
    function eden_engine_digital_twin_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-engine-showcase eden-engine-showcase--single">' . eden_engine_digital_twin_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_target_mapper_shortcode' ) ) {
    function eden_engine_target_mapper_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-engine-showcase eden-engine-showcase--single">' . eden_engine_target_mapper_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_shortcode' ) ) {
    function eden_engine_pathway_demo_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-engine-showcase eden-engine-showcase--single">' . eden_engine_pathway_demo_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_shortcode' ) ) {
    function eden_engine_reactor_status_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-engine-showcase eden-engine-showcase--single">' . eden_engine_reactor_status_html() . '</div>';
    }
}

add_shortcode( 'eden_engine_showcase', 'eden_engine_showcase_shortcode' );
add_shortcode( 'eden_digital_twin', 'eden_engine_digital_twin_shortcode' );
add_shortcode( 'eden_target_mapper', 'eden_engine_target_mapper_shortcode' );
add_shortcode( 'eden_pathway_demo', 'eden_engine_pathway_demo_shortcode' );
add_shortcode( 'eden_reactor_status', 'eden_engine_reactor_status_shortcode' );

