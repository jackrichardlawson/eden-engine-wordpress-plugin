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

        wp_enqueue_style( 'eden-engine', EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.css', array(), $style_version );
        wp_enqueue_script( 'eden-engine', EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.js', array(), $script_version, true );
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

if ( ! function_exists( 'eden_engine_pill' ) ) {
    function eden_engine_pill( string $tone, string $label ): string {
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
                'kicker' => 'Carbon conversion',
                'title'  => 'CO2 is treated as feedstock',
                'copy'   => 'The core research path converts captured carbon dioxide into formate, then explores enzymatic routes toward glucose.',
            ),
            array(
                'kicker' => 'Digital twin',
                'title'  => 'The model enforces physical limits',
                'copy'   => 'Eden Engine v2 couples efficiency, current density, CO2 supply, voltage, pH, and temperature so the demo cannot hand-wave the hard parts.',
            ),
            array(
                'kicker' => 'Target mapper',
                'title'  => 'Engineering goals become levers',
                'copy'   => 'Instead of vague promises, the public mapper asks what has to improve to approach a cost or production target.',
            ),
            array(
                'kicker' => 'Food systems',
                'title'  => 'Glucose is the bridge',
                'copy'   => 'The near-term story is not magic food from nowhere. It is a controlled pathway toward sugar feedstocks for resilient food production.',
            ),
        );

        $html = '<div class="eden-feature-grid">';

        foreach ( $cards as $card ) {
            $html .= sprintf(
                '<article class="eden-card eden-feature-card"><span class="eden-card-rule"></span><p class="eden-card-kicker">%1$s</p><h3>%2$s</h3><p>%3$s</p></article>',
                esc_html( $card['kicker'] ),
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
                'stage'   => '01',
                'name'    => 'CO2 input gate',
                'summary' => 'The model limits production when the available carbon stream is not sufficient.',
                'metric'  => 'Hard gate',
                'tone'    => 'ready',
            ),
            array(
                'stage'   => '02',
                'name'    => 'Electrolyzer model',
                'summary' => 'Current density, voltage, and Faradaic efficiency are coupled instead of treated as independent knobs.',
                'metric'  => 'Physics',
                'tone'    => 'ready',
            ),
            array(
                'stage'   => '03',
                'name'    => 'Enzyme pathway',
                'summary' => 'The public route frames formate-to-glucose as a research integration path, not a completed commercial system.',
                'metric'  => 'Research',
                'tone'    => 'watch',
            ),
            array(
                'stage'   => '04',
                'name'    => 'Food feedstock',
                'summary' => 'Glucose is presented as a downstream feedstock for fermentation, nutrition, and resilient food system design.',
                'metric'  => 'Target',
                'tone'    => 'planned',
            ),
        );

        $html  = '<section class="eden-section eden-digital-twin" id="eden-digital-twin" data-eden-section>';
        $html .= eden_engine_section_header(
            'Digital Twin',
            'A physics-constrained model of the CO2-to-sugar pathway',
            'The public twin explains the system without exposing private notebooks, internal APIs, or operational controls. It is built to show constraints clearly, not to make the hard problems disappear.'
        );
        $html .= '<div class="eden-system-map">';
        $html .= '<div class="eden-system-rail" aria-hidden="true"><span>CO2</span><span>HCOO-</span><span>C6H12O6</span><span>Food systems</span></div>';
        $html .= '<div class="eden-flow">';

        foreach ( $modules as $index => $module ) {
            $html .= sprintf(
                '<article class="eden-card eden-flow-card" data-eden-module="%1$d"><span class="eden-step">%2$s</span><h3>%3$s</h3><p>%4$s</p><div class="eden-card-footer">%5$s<strong>%6$s</strong></div></article>',
                absint( $index ),
                esc_html( $module['stage'] ),
                esc_html( $module['name'] ),
                esc_html( $module['summary'] ),
                eden_engine_pill( $module['tone'], $module['tone'] ),
                esc_html( $module['metric'] )
            );
        }

        $html .= '</div></div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_target_mapper_html' ) ) {
    function eden_engine_target_mapper_html(): string {
        $html  = '<section class="eden-section eden-target-mapper" id="eden-target-mapper" data-eden-section>';
        $html .= eden_engine_section_header(
            'Target Mapper',
            'Move the engineering levers and watch the public model respond',
            'This is a simplified public approximation for education and partner conversations. It shows directionality, not a deployment forecast.'
        );
        $html .= '<div class="eden-tool-layout">';
        $html .= '<div class="eden-tool">';
        $html .= '<label><span>Faradaic efficiency <strong data-eden-efficiency-output>82%</strong></span><input data-eden-efficiency type="range" min="60" max="94" value="82" /></label>';
        $html .= '<label><span>Electricity price <strong data-eden-electricity-output>$0.04/kWh</strong></span><input data-eden-electricity type="range" min="0.01" max="0.12" step="0.01" value="0.04" /></label>';
        $html .= '<div class="eden-metrics">';
        $html .= '<div><span>Modeled cost</span><strong data-eden-modeled-cost>$118/kg</strong></div>';
        $html .= '<div><span>Energy intensity</span><strong data-eden-energy-intensity>39.9 kWh/kg</strong></div>';
        $html .= '<div><span>Energy share</span><strong data-eden-energy-share>$1.60/kg</strong></div>';
        $html .= '<div><span>Output index</span><strong data-eden-output-index>1.00x</strong></div>';
        $html .= '</div>';
        $html .= '<p class="eden-note">Public demo only. Internal models, research documents, and private controls are not exposed.</p>';
        $html .= '</div>';
        $html .= '<aside class="eden-target-aside"><p class="eden-eyebrow">What this answers</p><h3>What has to improve to make the pathway more viable?</h3><p>The mapper turns abstract targets into concrete engineering pressure: better catalysts, lower electricity cost, improved enzyme lifetime, and tighter system integration.</p></aside>';
        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_html' ) ) {
    function eden_engine_pathway_demo_html(): string {
        $routes = array(
            array(
                'name'       => 'CO2 to formate foundation',
                'confidence' => '86',
                'signal'     => 'Current technical focus',
                'details'    => 'Start with the most controllable reduction step: convert captured CO2 into formate while tracking efficiency, voltage, and carbon supply.',
            ),
            array(
                'name'       => 'Formate to glucose integration',
                'confidence' => '72',
                'signal'     => 'Next research bridge',
                'details'    => 'Connect formate output to enzymatic pathways that can assemble sugar molecules under controlled operating conditions.',
            ),
            array(
                'name'       => 'Glucose to food systems',
                'confidence' => '64',
                'signal'     => 'Application layer',
                'details'    => 'Use glucose as a feedstock for fermentation, nutrition products, and resilient food production where land or climate conditions are constrained.',
            ),
        );

        $html  = '<section class="eden-section eden-pathway-demo" id="eden-pathway-demo" data-eden-section>';
        $html .= eden_engine_section_header(
            'Pathway Demo',
            'A staged route from captured carbon to useful food feedstocks',
            'The pathway view separates what is modeled now, what is being integrated next, and what belongs in later food-system applications.'
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

        $html .= '<div class="eden-route-detail"><div class="eden-ring" style="--eden-ring: 86"><span data-eden-route-confidence>86</span></div><p data-eden-route-details>Start with the most controllable reduction step: convert captured CO2 into formate while tracking efficiency, voltage, and carbon supply.</p></div>';
        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_html' ) ) {
    function eden_engine_reactor_status_html(): string {
        $items = array(
            array( 'label' => 'Public phase', 'value' => 'Research and model showcase', 'tone' => 'ready' ),
            array( 'label' => 'Primary milestone', 'value' => 'CO2 to formate foundation', 'tone' => 'ready' ),
            array( 'label' => 'Next integration', 'value' => 'Formate to glucose pathway', 'tone' => 'watch' ),
            array( 'label' => 'Data exposure', 'value' => 'Sanitized public demo', 'tone' => 'planned' ),
        );

        $html  = '<section class="eden-section eden-reactor-status" id="eden-reactor-status" data-eden-section>';
        $html .= eden_engine_section_header(
            'Reactor Status',
            'A clear public status without pretending the system is already commercial',
            'The status surface keeps the story honest: what is modeled, what is current, what is next, and what remains private.'
        );
        $html .= '<div class="eden-status-grid">';

        foreach ( $items as $item ) {
            $html .= sprintf(
                '<article class="eden-card">%1$s<h3>%2$s</h3><p>%3$s</p></article>',
                eden_engine_pill( $item['tone'], $item['label'] ),
                esc_html( $item['value'] ),
                esc_html( 'Public website view' )
            );
        }

        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_use_cases_html' ) ) {
    function eden_engine_use_cases_html(): string {
        $items = array(
            'Climate-resilient calorie production',
            'Remote and constrained food infrastructure',
            'Fermentation and cellular agriculture feedstocks',
            'Long-duration closed-loop habitat planning',
        );

        $html  = '<section class="eden-section eden-use-cases" data-eden-section>';
        $html .= eden_engine_section_header(
            'Why it matters',
            'Food as infrastructure, not only acreage',
            'Eden Engine is about planning a future where some essential food inputs can be manufactured with measured flows, renewable energy, and captured carbon.'
        );
        $html .= '<div class="eden-use-grid">';

        foreach ( $items as $item ) {
            $html .= sprintf( '<article class="eden-card"><span class="eden-card-rule"></span><h3>%s</h3></article>', esc_html( $item ) );
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
        $html .= '<h2>Help turn the model into hardware, data, and validated pathways.</h2>';
        $html .= '<p>Eden Engine is looking for serious collaborators across electrochemistry, enzyme engineering, bioprocess design, controlled environment agriculture, and climate-resilient food systems.</p>';
        $html .= '</div>';
        $html .= '<a class="eden-button eden-button--light" href="mailto:jackrichardlawson@gmail.com">Start a conversation</a>';
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_showcase_shortcode' ) ) {
    function eden_engine_showcase_shortcode(): string {
        eden_engine_enqueue_assets();

        $html  = '<div class="eden-showcase" data-eden-showcase>';
        $html .= '<nav class="eden-site-nav" aria-label="Eden Engine sections"><a href="#eden-digital-twin">Digital Twin</a><a href="#eden-target-mapper">Target Mapper</a><a href="#eden-pathway-demo">Pathway</a><a href="#eden-reactor-status">Status</a></nav>';
        $html .= '<section class="eden-hero" data-eden-section>';
        $html .= '<div class="eden-hero-copy">';
        $html .= '<p class="eden-eyebrow">Carbon capture to food production</p>';
        $html .= '<h1>Turning captured CO2 into the building blocks of food.</h1>';
        $html .= '<p>Eden Engine is a research and modeling platform for a modular CO2-to-sugar pathway: electrochemical conversion to formate, enzymatic assembly toward glucose, and a digital twin that keeps the physics honest.</p>';
        $html .= '<div class="eden-hero-actions">';
        $html .= '<a class="eden-button" href="#eden-target-mapper">Explore the model</a>';
        $html .= eden_engine_pill( 'ready', 'Public safe demo' );
        $html .= eden_engine_pill( 'watch', 'Research stage' );
        $html .= '</div></div>';
        $html .= '<div class="eden-hero-visual" aria-label="CO2 to glucose pathway">';
        $html .= '<div class="eden-molecule-row"><span>CO2</span><b>+</b><span>e-</span><b>+</b><span>H2O</span></div>';
        $html .= '<div class="eden-reaction-arrow">electrochemistry</div>';
        $html .= '<div class="eden-molecule-row"><span>Formate</span><b>to</b><span>Glucose</span></div>';
        $html .= '<div class="eden-equation">12 CO2 + 24 e- + 12 H2O -> C6H12O6 + O2 pathway outputs</div>';
        $html .= '<div class="eden-hero-metrics"><div><strong>3-stage</strong><span>capture, convert, build</span></div><div><strong>v2 twin</strong><span>coupled physics model</span></div><div><strong>public</strong><span>sanitized demo layer</span></div></div>';
        $html .= '</div></section>';
        $html .= '<section class="eden-section eden-overview" data-eden-section>';
        $html .= eden_engine_section_header(
            'Eden Engine overview',
            'A serious public surface for a hard technical pathway',
            'The website should make the system legible: what Eden Engine is attempting, what the model can show, and where the work still needs validation.'
        );
        $html .= eden_engine_feature_cards_html();
        $html .= '</section>';
        $html .= eden_engine_digital_twin_html();
        $html .= eden_engine_target_mapper_html();
        $html .= eden_engine_pathway_demo_html();
        $html .= eden_engine_reactor_status_html();
        $html .= eden_engine_use_cases_html();
        $html .= eden_engine_cta_html();
        $html .= '</div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_digital_twin_shortcode' ) ) {
    function eden_engine_digital_twin_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single" data-eden-showcase>' . eden_engine_digital_twin_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_target_mapper_shortcode' ) ) {
    function eden_engine_target_mapper_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single" data-eden-showcase>' . eden_engine_target_mapper_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_shortcode' ) ) {
    function eden_engine_pathway_demo_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single" data-eden-showcase>' . eden_engine_pathway_demo_html() . '</div>';
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_shortcode' ) ) {
    function eden_engine_reactor_status_shortcode(): string {
        eden_engine_enqueue_assets();

        return '<div class="eden-showcase eden-showcase--single" data-eden-showcase>' . eden_engine_reactor_status_html() . '</div>';
    }
}

add_shortcode( 'eden_engine_showcase', 'eden_engine_showcase_shortcode' );
add_shortcode( 'eden_digital_twin', 'eden_engine_digital_twin_shortcode' );
add_shortcode( 'eden_target_mapper', 'eden_engine_target_mapper_shortcode' );
add_shortcode( 'eden_pathway_demo', 'eden_engine_pathway_demo_shortcode' );
add_shortcode( 'eden_reactor_status', 'eden_engine_reactor_status_shortcode' );
