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

if ( ! function_exists( 'eden_engine_asset_url' ) ) {
    function eden_engine_asset_url( string $filename ): string {
        return EDEN_ENGINE_PLUGIN_URL . 'assets/images/' . ltrim( $filename, '/' );
    }
}

if ( ! function_exists( 'eden_engine_media_frame' ) ) {
    function eden_engine_media_frame( string $filename, string $alt, string $class = '' ): string {
        $classes = trim( 'eden-media ' . $class );

        return sprintf(
            '<figure class="%1$s"><img src="%2$s" alt="%3$s" loading="lazy" decoding="async" /></figure>',
            esc_attr( $classes ),
            esc_url( eden_engine_asset_url( $filename ) ),
            esc_attr( $alt )
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

if ( ! function_exists( 'eden_engine_pillars_html' ) ) {
    function eden_engine_pillars_html(): string {
        $cards = array(
            array(
                'title' => 'Digital Twin',
                'copy'  => 'Model the living system before it is built, with inputs, constraints, signals, flows, and production goals in one view.',
                'image' => 'eden-digital-twin.png',
            ),
            array(
                'title' => 'Target Mapper',
                'copy'  => 'Turn production goals into visible pathways by mapping crop targets, site conditions, tradeoffs, and resource limits.',
                'image' => 'eden-target-map.png',
            ),
            array(
                'title' => 'Closed Loop Reactor',
                'copy'  => 'See water, nutrients, energy, biomass, labor, and outputs as connected operating flows rather than isolated metrics.',
                'image' => 'eden-reactor-system.png',
            ),
        );

        $html  = '<section class="eden-section eden-pillars" data-eden-section>';
        $html .= eden_engine_section_header(
            'System pillars',
            'Three views of one biological operating system.',
            'Eden Engine is presented as a serious deep tech product: visual, specific, and grounded in system design.'
        );
        $html .= '<div class="eden-pillar-grid">';

        foreach ( $cards as $index => $card ) {
            $html .= '<article class="eden-card eden-pillar-card">';
            $html .= sprintf( '<span class="eden-card-index">0%d</span>', absint( $index + 1 ) );
            $html .= eden_engine_media_frame( $card['image'], $card['title'] . ' graphic', 'eden-card-media' );
            $html .= sprintf( '<h3>%s</h3>', esc_html( $card['title'] ) );
            $html .= sprintf( '<p>%s</p>', esc_html( $card['copy'] ) );
            $html .= '</article>';
        }

        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_digital_twin_html' ) ) {
    function eden_engine_digital_twin_html(): string {
        $items = array( 'Inputs', 'Constraints', 'Environmental signals', 'Resource flows', 'Production goals' );

        $html  = '<section class="eden-section eden-visual-section eden-digital-twin" id="eden-digital-twin" data-eden-section>';
        $html .= '<div class="eden-copy-column">';
        $html .= eden_engine_section_header(
            'Digital Twin',
            'Model the living system before you build it.',
            'Eden Engine maps the operating context around a food production system: inputs, constraints, environmental signals, resource flows, and production goals.'
        );
        $html .= '<div class="eden-signal-list">';

        foreach ( $items as $item ) {
            $html .= sprintf( '<span>%s</span>', esc_html( $item ) );
        }

        $html .= '</div></div>';
        $html .= eden_engine_media_frame( 'eden-digital-twin.png', 'Eden Engine digital twin visualization', 'eden-section-media' );
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_target_mapper_html' ) ) {
    function eden_engine_target_mapper_html(): string {
        $items = array( 'Planning', 'Tradeoffs', 'Crop targets', 'Site conditions', 'Resource constraints' );

        $html  = '<section class="eden-section eden-visual-section eden-target-mapper" id="eden-target-mapper" data-eden-section>';
        $html .= eden_engine_media_frame( 'eden-target-map.png', 'Eden Engine target mapping ecosystem network', 'eden-section-media' );
        $html .= '<div class="eden-copy-column">';
        $html .= eden_engine_section_header(
            'Target Mapper',
            'Turn production goals into visible pathways.',
            'The target mapper frames outcomes against the biological and operational constraints that shape them.'
        );
        $html .= '<div class="eden-signal-list">';

        foreach ( $items as $item ) {
            $html .= sprintf( '<span>%s</span>', esc_html( $item ) );
        }

        $html .= '</div></div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_html' ) ) {
    function eden_engine_pathway_demo_html(): string {
        $steps = array(
            array( 'label' => '01', 'title' => 'Resource intake', 'copy' => 'Map water, energy, nutrients, labor, and site limitations.' ),
            array( 'label' => '02', 'title' => 'Biological pathway', 'copy' => 'Connect crop targets and growth conditions to system-level planning.' ),
            array( 'label' => '03', 'title' => 'Operating loop', 'copy' => 'Translate pathway logic into visible readiness and production flow.' ),
        );

        $html  = '<section class="eden-section eden-pathway-demo" id="eden-pathway-demo" data-eden-section>';
        $html .= eden_engine_section_header(
            'Pathway Demo',
            'Production logic without hiding the constraints.',
            'The pathway view shows how Eden Engine can organize resources, biological conditions, and operating state into a readable production model.'
        );
        $html .= '<div class="eden-pathway-grid">';

        foreach ( $steps as $step ) {
            $html .= '<article class="eden-card eden-pathway-card">';
            $html .= sprintf( '<span class="eden-step">%s</span>', esc_html( $step['label'] ) );
            $html .= sprintf( '<h3>%s</h3>', esc_html( $step['title'] ) );
            $html .= sprintf( '<p>%s</p>', esc_html( $step['copy'] ) );
            $html .= '</article>';
        }

        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_html' ) ) {
    function eden_engine_reactor_status_html(): string {
        $flows = array( 'Water', 'Nutrients', 'Energy', 'Biomass', 'Labor', 'Outputs' );

        $html  = '<section class="eden-section eden-visual-section eden-reactor-status" id="eden-reactor-status" data-eden-section>';
        $html .= '<div class="eden-copy-column">';
        $html .= eden_engine_section_header(
            'Reactor Status',
            'See the system as an operating loop.',
            'The reactor view presents resource and production state as connected flows: water, nutrients, energy, biomass, labor, and outputs.'
        );
        $html .= '<div class="eden-flow-tags">';

        foreach ( $flows as $flow ) {
            $html .= sprintf( '<span>%s</span>', esc_html( $flow ) );
        }

        $html .= '</div></div>';
        $html .= eden_engine_media_frame( 'eden-reactor-system.png', 'Eden Engine closed loop reactor system', 'eden-section-media' );
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_public_demo_html' ) ) {
    function eden_engine_public_demo_html(): string {
        $modules = array(
            array( 'name' => 'System Map', 'state' => 'Visible' ),
            array( 'name' => 'Pathway Builder', 'state' => 'Public mode' ),
            array( 'name' => 'Resource Flow', 'state' => 'Sanitized' ),
            array( 'name' => 'Readiness State', 'state' => 'Demo view' ),
            array( 'name' => 'Growth Forecast', 'state' => 'Illustrative' ),
        );

        $html  = '<section class="eden-section eden-demo-panel-section" id="eden-public-demo" data-eden-section>';
        $html .= eden_engine_section_header(
            'Public safe demo',
            'A product interface layer, without private controls.',
            'These modules show the kind of views Eden Engine can expose publicly while keeping internal research, documents, and operations protected.'
        );
        $html .= '<div class="eden-interface-panel">';
        $html .= '<div class="eden-interface-top"><span></span><span></span><span></span><strong>Public Demo Surface</strong></div>';
        $html .= '<div class="eden-module-grid">';

        foreach ( $modules as $module ) {
            $html .= '<article class="eden-module-tile">';
            $html .= sprintf( '<h3>%s</h3>', esc_html( $module['name'] ) );
            $html .= sprintf( '<p>%s</p>', esc_html( $module['state'] ) );
            $html .= '<i aria-hidden="true"></i>';
            $html .= '</article>';
        }

        $html .= '</div></div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_cta_html' ) ) {
    function eden_engine_cta_html(): string {
        $html  = '<section class="eden-cta" data-eden-section>';
        $html .= '<div class="eden-cta-glow" aria-hidden="true"></div>';
        $html .= '<div>';
        $html .= '<p class="eden-eyebrow">Eden Engine</p>';
        $html .= '<h2>Build the future of resilient food production.</h2>';
        $html .= '<p>Eden Engine is the public front door for a more intelligent farm operating layer: biological, computational, and built for closed loop food systems.</p>';
        $html .= '</div>';
        $html .= '<a class="eden-button eden-button--light" href="#eden-digital-twin">Enter Eden Engine</a>';
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_showcase_shortcode' ) ) {
    function eden_engine_showcase_shortcode(): string {
        eden_engine_enqueue_assets();

        $html  = '<div class="eden-showcase" data-eden-showcase>';
        $html .= '<nav class="eden-site-nav" aria-label="Eden Engine sections">';
        $html .= '<a href="#eden-digital-twin">Digital Twin</a><a href="#eden-target-mapper">Target Mapper</a><a href="#eden-pathway-demo">Pathway Demo</a><a href="#eden-reactor-status">Reactor Status</a>';
        $html .= '</nav>';
        $html .= '<section class="eden-hero" data-eden-section>';
        $html .= '<div class="eden-hero-bg" aria-hidden="true"></div>';
        $html .= '<div class="eden-hero-copy">';
        $html .= sprintf( '<img class="eden-logo" src="%s" alt="Eden Engine" loading="eager" decoding="async" />', esc_url( eden_engine_asset_url( 'eden-logo.png' ) ) );
        $html .= '<p class="eden-eyebrow">Bio-intelligent production systems</p>';
        $html .= '<h1>The operating layer for closed loop food systems.</h1>';
        $html .= '<p>Eden Engine models, maps, and manages the biological pathways behind resilient fruit and food production.</p>';
        $html .= '<div class="eden-hero-actions"><a class="eden-button" href="#eden-digital-twin">Explore the System</a><a class="eden-button eden-button--ghost" href="#eden-public-demo">View Public Demo</a></div>';
        $html .= '</div>';
        $html .= '<div class="eden-hero-art">';
        $html .= eden_engine_media_frame( 'eden-hero-tree-dna.png', 'Eden Engine tree canopy roots and DNA hero graphic', 'eden-hero-media' );
        $html .= '<div class="eden-hero-readout"><span>Living system model</span><strong>Rooted intelligence online</strong></div>';
        $html .= '</div></section>';
        $html .= eden_engine_pillars_html();
        $html .= eden_engine_digital_twin_html();
        $html .= eden_engine_target_mapper_html();
        $html .= eden_engine_reactor_status_html();
        $html .= eden_engine_public_demo_html();
        $html .= eden_engine_pathway_demo_html();
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
