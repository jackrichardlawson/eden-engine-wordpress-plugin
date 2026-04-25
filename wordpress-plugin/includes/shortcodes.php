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

if ( ! function_exists( 'eden_engine_logo_svg' ) ) {
    function eden_engine_logo_svg( string $class_name = 'eden-engine-logo' ): string {
        return '<svg class="' . esc_attr( $class_name ) . '" viewBox="0 0 96 96" aria-hidden="true" focusable="false"><circle cx="48" cy="48" r="42"></circle><path d="M48 70C48 58 48 45 48 24"></path><path d="M48 34C35 30 30 21 25 14"></path><path d="M49 35C64 31 68 22 73 15"></path><path d="M48 51C34 49 26 42 18 36"></path><path d="M48 52C63 50 72 43 80 36"></path><path d="M48 70C39 74 31 80 24 88"></path><path d="M48 70C56 75 64 80 72 88"></path><path d="M33 68C44 60 53 56 64 48"></path><path d="M63 68C52 60 43 56 32 48"></path><circle class="eden-engine-logo__node eden-engine-logo__node--green" cx="33" cy="68" r="3"></circle><circle class="eden-engine-logo__node eden-engine-logo__node--blue" cx="63" cy="68" r="3"></circle></svg>';
    }
}

if ( ! function_exists( 'eden_engine_tree_visual_svg' ) ) {
    function eden_engine_tree_visual_svg( string $class_name = 'eden-engine-tree-visual' ): string {
        return '<svg class="' . esc_attr( $class_name ) . '" viewBox="0 0 640 640" aria-hidden="true" focusable="false"><circle class="eden-engine-orbit eden-engine-orbit--blue" cx="320" cy="320" r="238"></circle><circle class="eden-engine-orbit eden-engine-orbit--green" cx="320" cy="320" r="182"></circle><ellipse class="eden-engine-orbit eden-engine-orbit--blue" cx="320" cy="320" rx="218" ry="82"></ellipse><ellipse class="eden-engine-orbit eden-engine-orbit--green" cx="320" cy="320" rx="218" ry="82" transform="rotate(64 280 230)"></ellipse><ellipse class="eden-engine-orbit eden-engine-orbit--blue" cx="320" cy="320" rx="218" ry="82" transform="rotate(-58 280 230)"></ellipse><path class="eden-engine-tree-visual__trunk" d="M314 500C314 420 316 342 318 218"></path><path class="eden-engine-tree-visual__branch eden-engine-tree-visual__branch--green" d="M316 274C250 248 215 196 188 132"></path><path class="eden-engine-tree-visual__branch eden-engine-tree-visual__branch--blue" d="M320 274C388 244 430 190 460 128"></path><path class="eden-engine-tree-visual__branch eden-engine-tree-visual__branch--green" d="M318 342C237 331 186 291 126 246"></path><path class="eden-engine-tree-visual__branch eden-engine-tree-visual__branch--blue" d="M320 346C407 333 465 289 524 238"></path><path class="eden-engine-tree-visual__root eden-engine-tree-visual__root--green" d="M316 500C262 526 202 566 154 612"></path><path class="eden-engine-tree-visual__root eden-engine-tree-visual__root--blue" d="M316 500C368 530 428 566 486 614"></path><path class="eden-engine-tree-visual__root eden-engine-tree-visual__root--green" d="M316 498C280 528 256 574 238 624"></path><path class="eden-engine-tree-visual__root eden-engine-tree-visual__root--blue" d="M318 498C362 527 391 575 408 624"></path><path class="eden-engine-dna eden-engine-dna--blue" d="M374 96C442 164 450 260 382 330C318 398 316 486 390 560"></path><path class="eden-engine-dna eden-engine-dna--green" d="M492 96C424 164 416 260 484 330C548 398 550 486 476 560"></path><g class="eden-engine-dna-rungs"><line x1="424" y1="120" x2="444" y2="140"></line><line x1="401" y1="166" x2="467" y2="186"></line><line x1="386" y1="212" x2="482" y2="232"></line><line x1="389" y1="258" x2="479" y2="278"></line><line x1="414" y1="304" x2="454" y2="324"></line><line x1="384" y1="350" x2="484" y2="370"></line><line x1="370" y1="396" x2="498" y2="416"></line><line x1="383" y1="442" x2="485" y2="462"></line><line x1="410" y1="488" x2="458" y2="508"></line></g><circle class="eden-engine-node eden-engine-node--green" cx="188" cy="132" r="6"></circle><circle class="eden-engine-node eden-engine-node--blue" cx="460" cy="128" r="6"></circle><circle class="eden-engine-node eden-engine-node--white" cx="318" cy="218" r="12"></circle></svg>';
    }
}

if ( ! function_exists( 'eden_engine_twin_visual_svg' ) ) {
    function eden_engine_twin_visual_svg(): string {
        return '<svg class="eden-engine-twin-visual" viewBox="0 0 560 460" aria-hidden="true" focusable="false"><ellipse cx="280" cy="230" rx="206" ry="206"></ellipse><ellipse cx="280" cy="230" rx="160" ry="160"></ellipse><ellipse cx="280" cy="230" rx="218" ry="82"></ellipse><ellipse cx="280" cy="230" rx="218" ry="82" transform="rotate(64 280 230)"></ellipse><ellipse cx="280" cy="230" rx="218" ry="82" transform="rotate(-58 280 230)"></ellipse><path class="eden-engine-twin-visual__trunk" d="M280 332C280 290 280 245 280 174"></path><path class="eden-engine-twin-visual__green" d="M280 210C238 199 215 169 198 128"></path><path class="eden-engine-twin-visual__blue" d="M282 210C326 196 350 166 372 126"></path><path class="eden-engine-twin-visual__green" d="M280 332C244 350 206 382 178 420"></path><path class="eden-engine-twin-visual__blue" d="M280 332C320 354 358 384 396 420"></path><circle class="eden-engine-node eden-engine-node--green" cx="198" cy="128" r="5"></circle><circle class="eden-engine-node eden-engine-node--blue" cx="372" cy="126" r="5"></circle><circle class="eden-engine-node eden-engine-node--green" cx="178" cy="420" r="4"></circle><circle class="eden-engine-node eden-engine-node--blue" cx="396" cy="420" r="4"></circle></svg>';
    }
}

if ( ! function_exists( 'eden_engine_pathway_visual_svg' ) ) {
    function eden_engine_pathway_visual_svg(): string {
        return '<svg class="eden-engine-pathway-visual" viewBox="0 0 520 420" aria-hidden="true" focusable="false"><path class="eden-engine-pathway-visual__primary" d="M50 300C135 212 178 204 236 236C305 274 336 166 440 112"></path><path class="eden-engine-pathway-visual__secondary" d="M64 338C144 296 218 294 274 320C334 348 398 296 462 236"></path><path class="eden-engine-pathway-visual__ghost" d="M84 142C160 96 230 118 284 164C338 210 390 204 456 174"></path><circle cx="50" cy="300" r="15"></circle><circle cx="236" cy="236" r="12"></circle><circle cx="440" cy="112" r="18"></circle><circle cx="64" cy="338" r="9"></circle><circle cx="274" cy="320" r="11"></circle><circle cx="462" cy="236" r="13"></circle><circle cx="84" cy="142" r="10"></circle><circle cx="284" cy="164" r="10"></circle><circle cx="456" cy="174" r="10"></circle></svg>';
    }
}

if ( ! function_exists( 'eden_engine_chip' ) ) {
    function eden_engine_chip( string $label, string $tone = 'green' ): string {
        return sprintf(
            '<span class="eden-engine-chip eden-engine-chip--%1$s">%2$s</span>',
            esc_attr( $tone ),
            esc_html( $label )
        );
    }
}

if ( ! function_exists( 'eden_engine_section_header' ) ) {
    function eden_engine_section_header( string $eyebrow, string $title, string $copy = '', string $align = 'left' ): string {
        $html  = '<div class="eden-engine-section__header eden-engine-section__header--' . esc_attr( $align ) . '">';
        $html .= sprintf( '<p class="eden-engine-eyebrow">%s</p>', esc_html( $eyebrow ) );
        $html .= sprintf( '<h2>%s</h2>', esc_html( $title ) );

        if ( '' !== $copy ) {
            $html .= sprintf( '<p class="eden-engine-section__copy">%s</p>', esc_html( $copy ) );
        }

        $html .= '</div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_mini_signal_panel' ) ) {
    function eden_engine_mini_signal_panel( string $title, string $tone = 'green' ): string {
        $bars = array( 22, 41, 60, 25, 44, 63 );

        $html  = '<div class="eden-engine-signal-panel eden-engine-signal-panel--' . esc_attr( $tone ) . '">';
        $html .= sprintf( '<span>%s</span>', esc_html( $title ) );
        $html .= '<div class="eden-engine-signal-panel__grid" aria-hidden="true">';

        foreach ( $bars as $height ) {
            $html .= '<i style="--eden-bar:' . absint( $height ) . 'px"></i>';
        }

        $html .= '</div></div>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_hero_html' ) ) {
    function eden_engine_hero_html(): string {
        $html  = '<section class="eden-engine-hero" data-eden-engine-section>';
        $html .= '<div class="eden-engine-hero__nav" aria-label="Eden Engine landing navigation">';
        $html .= '<div class="eden-engine-brand">' . eden_engine_logo_svg() . '<span>Eden Engine</span></div>';
        $html .= '<div class="eden-engine-nav-links"><a href="#eden-system">System</a><a href="#eden-twin">Twin</a><a href="#eden-mapper">Mapper</a><a href="#eden-demo">Demo</a></div>';
        $html .= '<a class="eden-engine-button eden-engine-button--small" href="#eden-contact">Request access</a>';
        $html .= '</div>';
        $html .= '<div class="eden-engine-hero__content">';
        $html .= '<div class="eden-engine-hero__copy">';
        $html .= eden_engine_chip( 'Biological intelligence platform', 'green' );
        $html .= '<h1>The operating layer for closed-loop food systems.</h1>';
        $html .= '<p>Eden Engine connects digital twins, target pathways, and feedback reactors into one living model for fruit and food production systems.</p>';
        $html .= '<div class="eden-engine-hero__actions"><a class="eden-engine-button" href="#eden-system">Explore the system</a><a class="eden-engine-button eden-engine-button--ghost" href="#eden-demo">View safe demo</a></div>';
        $html .= '<div class="eden-engine-loop-rail"><article><strong>Digital Twin</strong><span>Living model layer</span></article><i></i><article><strong>Target Mapper</strong><span>Visible pathway layer</span></article><i></i><article><strong>Closed Loop Reactor</strong><span>Feedback control layer</span></article></div>';
        $html .= '</div>';
        $html .= '<div class="eden-engine-hero__visual"><div class="eden-engine-visual-card eden-engine-visual-card--hero"><span class="eden-engine-floating-label eden-engine-floating-label--top">Tree, roots, DNA</span>' . eden_engine_tree_visual_svg() . '<span class="eden-engine-floating-label eden-engine-floating-label--bottom">Animation-ready system layers</span></div></div>';
        $html .= '</div>';
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_system_pillars_html' ) ) {
    function eden_engine_system_pillars_html(): string {
        $pillars = array(
            array(
                'title'   => 'Digital Twin',
                'line'    => 'Model the living system before you build it.',
                'body'    => 'Multi-layer biological, climate, and operational representation.',
                'tone'    => 'green',
            ),
            array(
                'title'   => 'Target Mapper',
                'line'    => 'Turn production goals into visible pathways.',
                'body'    => 'Goal-to-constraint mapping with transparent tradeoff surfaces.',
                'tone'    => 'blue',
            ),
            array(
                'title'   => 'Closed Loop Reactor',
                'line'    => 'See the system as an operating loop.',
                'body'    => 'Feedback-ready system view for resilient production design.',
                'tone'    => 'white',
            ),
        );

        $html  = '<section id="eden-system" class="eden-engine-section eden-engine-pillars" data-eden-engine-section>';
        $html .= eden_engine_section_header(
            'System pillars',
            'See the system as an operating loop.',
            'Each layer is designed as a visible part of the production loop, from model to pathway to reactor feedback.'
        );
        $html .= '<div class="eden-engine-pillar-grid">';

        foreach ( $pillars as $pillar ) {
            $html .= '<article class="eden-engine-pillar-card eden-engine-pillar-card--' . esc_attr( $pillar['tone'] ) . '">';
            $html .= '<div class="eden-engine-pillar-card__diagram" aria-hidden="true"><span></span><i></i><b></b><b></b><b></b><b></b></div>';
            $html .= sprintf( '<h3>%s</h3>', esc_html( $pillar['title'] ) );
            $html .= sprintf( '<p class="eden-engine-card-line">%s</p>', esc_html( $pillar['line'] ) );
            $html .= sprintf( '<p>%s</p>', esc_html( $pillar['body'] ) );
            $html .= '</article>';
        }

        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_digital_twin_html' ) ) {
    function eden_engine_digital_twin_html(): string {
        $chips = array( 'Canopy layer', 'Root-zone layer', 'Climate envelope', 'Crop plan' );

        $html  = '<section id="eden-twin" class="eden-engine-section eden-engine-feature eden-engine-feature--twin" data-eden-engine-section>';
        $html .= '<div class="eden-engine-feature__visual"><div class="eden-engine-visual-card eden-engine-visual-card--twin">' . eden_engine_twin_visual_svg() . '<div class="eden-engine-panel-row">' . eden_engine_mini_signal_panel( 'Living state layers', 'green' ) . eden_engine_mini_signal_panel( 'Inspectable digital twin model', 'blue' ) . '</div></div></div>';
        $html .= '<div class="eden-engine-feature__copy">';
        $html .= eden_engine_section_header(
            'Digital Twin',
            'Model the living system before you build it.',
            'Represent crops, canopy, root zone, climate envelope, and operational intent as a single model that can be inspected before deployment.'
        );
        $html .= '<div class="eden-engine-chip-grid">';

        foreach ( $chips as $index => $chip ) {
            $html .= eden_engine_chip( $chip, 0 === $index % 2 ? 'green' : 'blue' );
        }

        $html .= '</div></div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_target_mapper_html' ) ) {
    function eden_engine_target_mapper_html(): string {
        $steps = array( 'Goal', 'Constraints', 'Pathway', 'Feedback' );

        $html  = '<section id="eden-mapper" class="eden-engine-section eden-engine-feature eden-engine-feature--mapper" data-eden-engine-section>';
        $html .= '<div class="eden-engine-feature__copy">';
        $html .= eden_engine_section_header(
            'Target Mapper',
            'Turn production goals into visible pathways.',
            'Map a desired production outcome against biological limits, resource constraints, and operational options. The result is a pathway designers can inspect.'
        );
        $html .= '<div class="eden-engine-step-list">';

        foreach ( $steps as $step ) {
            $html .= sprintf( '<span>%s</span>', esc_html( $step ) );
        }

        $html .= '</div></div>';
        $html .= '<div class="eden-engine-feature__visual"><div class="eden-engine-visual-card eden-engine-visual-card--pathway"><div class="eden-engine-field-lines" aria-hidden="true"></div>' . eden_engine_pathway_visual_svg() . '<div class="eden-engine-pathway-labels"><span>production intent</span><i></i><span>visible pathway</span></div></div></div>';
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_pathway_demo_html' ) ) {
    function eden_engine_pathway_demo_html(): string {
        $questions = array(
            array(
                'label'   => 'Orchard model',
                'prompt'  => 'How would a closed-loop orchard model expose dependencies before construction?',
                'details' => 'Educational model explanation, pathway diagrams, and non-operational design context.',
            ),
            array(
                'label'   => 'Greenhouse model',
                'prompt'  => 'Where do climate, root-zone, and crop-plan assumptions interact?',
                'details' => 'A public view of system relationships without live control parameters.',
            ),
            array(
                'label'   => 'Food system review',
                'prompt'  => 'Which parts of the operating loop should stay visible to partners?',
                'details' => 'Design-review surfaces separated from private operational controls.',
            ),
        );

        $html  = '<section id="eden-demo" class="eden-engine-section eden-engine-demo" data-eden-engine-section>';
        $html .= '<div class="eden-engine-feature__copy">';
        $html .= eden_engine_section_header(
            'Public Safe Demo',
            'A safe interface for exploring the model.',
            'Expose educational and design-review views while keeping operational control, live parameters, and sensitive production instructions outside the public surface.'
        );
        $html .= '<div class="eden-engine-demo-tabs" role="tablist" aria-label="Public safe demo scenarios">';

        foreach ( $questions as $index => $question ) {
            $html .= sprintf(
                '<button class="eden-engine-demo-tab%1$s" type="button" data-eden-demo-tab data-eden-prompt="%2$s" data-eden-details="%3$s"><span>%4$s</span></button>',
                0 === $index ? ' is-active' : '',
                esc_attr( $question['prompt'] ),
                esc_attr( $question['details'] ),
                esc_html( $question['label'] )
            );
        }

        $html .= '</div></div>';
        $html .= '<div class="eden-engine-demo-shell">';
        $html .= '<div class="eden-engine-browser-bar"><i></i><i></i><strong>Eden public sandbox</strong></div>';
        $html .= '<div class="eden-engine-prompt-panel"><span>Scenario question</span><p data-eden-demo-prompt>' . esc_html( $questions[0]['prompt'] ) . '</p></div>';
        $html .= '<div class="eden-engine-demo-card-grid"><article><h3>Allowed view</h3><p data-eden-demo-details>' . esc_html( $questions[0]['details'] ) . '</p></article><article><h3>Restricted surface</h3><p>Live control changes, private operational data, and implementation-sensitive instructions stay sealed.</p></article></div>';
        $html .= '<div class="eden-engine-output-panel"><h3>Model response preview</h3><i></i><i></i><i></i><i></i></div>';
        $html .= '</div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_reactor_status_html' ) ) {
    function eden_engine_reactor_status_html(): string {
        $rows = array(
            array( 'label' => 'Nutrient loop', 'state' => 'review surface' ),
            array( 'label' => 'Canopy response', 'state' => 'model layer' ),
            array( 'label' => 'Climate envelope', 'state' => 'constraint view' ),
            array( 'label' => 'Actuator policy', 'state' => 'public safe' ),
        );

        $html  = '<section id="eden-reactor" class="eden-engine-section eden-engine-feature eden-engine-feature--reactor" data-eden-engine-section>';
        $html .= '<div class="eden-engine-feature__copy">';
        $html .= eden_engine_section_header(
            'Reactor Status',
            'See the system as an operating loop.',
            'A dashboard-style operating view for feedback relationships, safe review states, and loop-level visibility without pretending to be live telemetry.'
        );
        $html .= '</div>';
        $html .= '<div class="eden-engine-dashboard">';
        $html .= '<div class="eden-engine-dashboard__header"><h3>Loop overview</h3><span>public review mode</span></div>';
        $html .= '<div class="eden-engine-dashboard__body"><div class="eden-engine-loop-core" aria-hidden="true"><span>closed loop</span></div>' . eden_engine_mini_signal_panel( 'Signal field', 'green' ) . '</div>';
        $html .= '<div class="eden-engine-dashboard__rows">';

        foreach ( $rows as $row ) {
            $html .= sprintf( '<div><strong>%1$s</strong><span>%2$s</span></div>', esc_html( $row['label'] ), esc_html( $row['state'] ) );
        }

        $html .= '</div></div></section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_final_cta_html' ) ) {
    function eden_engine_final_cta_html(): string {
        $html  = '<section id="eden-contact" class="eden-engine-final-cta" data-eden-engine-section>';
        $html .= '<div class="eden-engine-final-cta__bloom" aria-hidden="true"></div>';
        $html .= eden_engine_logo_svg( 'eden-engine-logo eden-engine-logo--large' );
        $html .= '<h2>Build the operating layer for resilient food production.</h2>';
        $html .= '<p>Design closed-loop food systems with living models, visible pathways, and feedback-ready interfaces.</p>';
        $html .= '<a class="eden-engine-button" href="#eden-contact">Start a conversation</a>';
        $html .= '<small>Eden Engine - biological intelligence for closed-loop fruit and food production</small>';
        $html .= '</section>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_showcase_shortcode' ) ) {
    function eden_engine_showcase_shortcode(): string {
        eden_engine_enqueue_assets();

        $html  = '<div class="eden-engine-showcase">';
        $html .= '<div class="eden-engine-bg-grid" aria-hidden="true"></div>';
        $html .= eden_engine_hero_html();
        $html .= eden_engine_system_pillars_html();
        $html .= eden_engine_digital_twin_html();
        $html .= eden_engine_target_mapper_html();
        $html .= eden_engine_reactor_status_html();
        $html .= eden_engine_pathway_demo_html();
        $html .= eden_engine_final_cta_html();
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
