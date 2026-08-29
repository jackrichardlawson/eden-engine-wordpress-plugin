<?php
/**
 * Public-facing Eden Engine custom pages and shortcodes.
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
            'eden_mission',
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
                        'briefRequestNonce'   => wp_create_nonce( 'eden_engine_brief_request' ),
                        'partnerRequestNonce' => wp_create_nonce( 'eden_engine_partner_request' ),
                        'partnerNonceUrl'     => add_query_arg(
                            'action',
                            'eden_engine_partner_nonce',
                            admin_url( 'admin-ajax.php' )
                        ),
                        'fallbackEmail'       => sanitize_email(
                            (string) apply_filters( 'eden_engine_brief_request_recipient', get_option( 'admin_email' ) )
                        ),
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
                'title'   => 'Carbon In. Food Infrastructure Out.',
                'summary' => 'Eden Engine is developing a modular CO2-to-food-ingredients platform, beginning with bounded carbon validation, protein/biomass proof, and carbohydrate precursor R&D.',
                'cta'     => 'Partner on Phase 1',
                'url'     => home_url( '/partner/' ),
            ),
            'technology'      => array(
                'title'   => 'The Technology Behind CO2-to-Food Ingredients',
                'summary' => 'Technology explains the Phase 1 carbon-to-ingredient target, target architecture, technical risk, evidence metrics, and control loop.',
                'cta'     => 'View the Evidence Program',
                'url'     => home_url( '/evidence/' ),
            ),
            'evidence'        => array(
                'title'   => 'Evidence Before Scale',
                'summary' => 'The evidence program separates what is modeled, planned, measured, and independently reviewed while defining the next test that could reduce uncertainty.',
                'cta'     => 'Partner on Phase 1',
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
                'title'   => 'From First Carbon Proof to Future Food Systems',
                'summary' => 'The roadmap starts with Phase 1 carbon validation, then moves through evidence gates toward protein biomass proof, carbohydrate precursor breakthroughs, integrated pilots, and future food-system applications.',
                'cta'     => 'View the Evidence Program',
                'url'     => home_url( '/evidence/' ),
            ),
            'journal'         => array(
                'title'   => 'Field Notes From the Carbon Conversion Build',
                'summary' => 'A public proof ledger for what is modeled, what is being tested, and what still needs evidence.',
                'cta'     => 'Read Field Notes',
                'url'     => home_url( '/journal/' ),
            ),
            'company'         => array(
                'title'   => 'Building a Disciplined Carbon-to-Food-Ingredients Program',
                'summary' => 'Eden Engine Technologies is building a disciplined CO2-to-food-ingredients validation program with evidence before scale.',
                'cta'     => 'Partner on Phase 1',
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
                'cta'     => 'Partner on Phase 1',
                'url'     => home_url( '/partner/' ),
            ),
            'partner'         => array(
                'title'   => 'Partner on Phase 1',
                'summary' => 'Connect with Eden Engine about laboratory access, assays, bioprocess and engineering work, ingredient or industrial collaboration, strategic funding, or academic and media inquiries.',
                'cta'     => 'Read the Technical Brief',
                'url'     => home_url( '/technical-brief/' ),
            ),
            'contact'         => array(
                'title'   => 'Partner on Phase 1',
                'summary' => 'Use the partner inquiry to identify the capability you can contribute, why you are contacting Eden Engine, and the next step you want to explore.',
                'cta'     => 'Read the Technical Brief',
                'url'     => home_url( '/technical-brief/' ),
            ),
        );

        return $content[ $widget ] ?? $content['home'];
    }
}

if ( ! function_exists( 'eden_engine_fallback_html' ) ) {
    function eden_engine_fallback_html( string $widget ): string {
        $content = eden_engine_public_widget_content( $widget );

        $html  = '<section class="eden-engine-crawlable-fallback">';
        $html .= '<p class="eyebrow">Current status</p>';
        $html .= '<h1>' . esc_html( $content['title'] ) . '</h1>';
        $html .= '<p>' . esc_html( $content['summary'] ) . '</p>';
        $html .= '<ul>';
        $html .= '<li><strong>Stage:</strong> Phase 1: bench validation planning.</li>';
        $html .= '<li><strong>Objective:</strong> Build and instrument a bounded carbon-to-ingredient pathway, beginning with protein/biomass proof and preserving carbohydrate-relevant outputs as the breakthrough target.</li>';
        $html .= '<li><strong>Measured:</strong> No public measured performance data is claimed until dated bench evidence exists.</li>';
        $html .= '<li><strong>Not claimed:</strong> No commercial food, feed, nutrition, fuel, materials, life-support output, deployment, crop-improvement, or production-ready system claim.</li>';
        $html .= '</ul>';

        if ( 'partner' === $widget || 'contact' === $widget ) {
            $fallback_email = sanitize_email(
                (string) apply_filters( 'eden_engine_brief_request_recipient', get_option( 'admin_email' ) )
            );

            if ( is_email( $fallback_email ) ) {
                $html .= '<p><strong>Direct email:</strong> <a href="mailto:' . esc_attr( $fallback_email ) . '">' . esc_html( $fallback_email ) . '</a></p>';
            }
        }

        $html .= '<a class="button button--primary" href="' . esc_url( $content['url'] ) . '">' . esc_html( $content['cta'] ) . '</a>';
        $html .= '</section>';

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
        return is_home() || is_singular( 'post' ) || is_archive() || is_search();
    }
}

if ( ! function_exists( 'eden_engine_blog_template' ) ) {
    function eden_engine_blog_template( string $template ): string {
        if ( is_admin() ) {
            return $template;
        }

        if ( is_home() || is_archive() || is_search() ) {
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
        if ( is_front_page() ) {
            return 'home';
        }

        if ( is_page( 'mission' ) ) {
            return 'company';
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

        if ( is_page( 'system' ) || ( is_404() && 'system' === $request_path ) ) {
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

add_action( 'template_redirect', 'eden_engine_redirect_alias_pages', 10 );

if ( ! function_exists( 'eden_engine_brief_request_field' ) ) {
    function eden_engine_brief_request_field( string $key ): string {
        $value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        return sanitize_text_field( (string) $value );
    }
}

if ( ! function_exists( 'eden_engine_handle_brief_request' ) ) {
    function eden_engine_handle_brief_request(): void {
        $nonce = eden_engine_brief_request_field( 'nonce' );

        if ( ! wp_verify_nonce( $nonce, 'eden_engine_brief_request' ) ) {
            wp_send_json_error( array( 'message' => 'The request could not be verified. Please refresh and try again.' ), 403 );
        }

        if ( '' !== eden_engine_brief_request_field( 'website' ) ) {
            wp_send_json_error( array( 'message' => 'The request could not be sent.' ), 400 );
        }

        $name          = eden_engine_brief_request_field( 'name' );
        $email         = sanitize_email( eden_engine_brief_request_field( 'email' ) );
        $organization  = eden_engine_brief_request_field( 'organization' );
        $role          = eden_engine_brief_request_field( 'role' );
        $interest_type = eden_engine_brief_request_field( 'interestType' );
        $message       = sanitize_textarea_field(
            isset( $_POST['message'] ) ? (string) wp_unslash( $_POST['message'] ) : '' // phpcs:ignore WordPress.Security.NonceVerification.Missing
        );

        if ( '' === $name || '' === $email || '' === $message || ! is_email( $email ) ) {
            wp_send_json_error( array( 'message' => 'Please provide a valid name, email, and message.' ), 400 );
        }

        $recipient = sanitize_email(
            (string) apply_filters( 'eden_engine_brief_request_recipient', get_option( 'admin_email' ) )
        );

        if ( ! is_email( $recipient ) ) {
            wp_send_json_error( array( 'message' => 'The request inbox is not configured. Please use email instead.' ), 500 );
        }

        $subject = sprintf( 'Eden Engine Technical Brief Request - %s', $name );
        $body    = implode(
            "\n",
            array(
                'New Eden Engine technical brief request:',
                '',
                'Name: ' . $name,
                'Email: ' . $email,
                'Organization: ' . $organization,
                'Role: ' . $role,
                'Interest Type: ' . $interest_type,
                '',
                'Message:',
                $message,
            )
        );
        $headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

        if ( ! wp_mail( $recipient, $subject, $body, $headers ) ) {
            wp_send_json_error( array( 'message' => 'The request could not be sent. Please use email instead.' ), 500 );
        }

        wp_send_json_success( array( 'message' => 'Request sent. Eden Engine will follow up by email.' ) );
    }
}

add_action( 'wp_ajax_nopriv_eden_engine_brief_request', 'eden_engine_handle_brief_request' );
add_action( 'wp_ajax_eden_engine_brief_request', 'eden_engine_handle_brief_request' );

if ( ! function_exists( 'eden_engine_partner_request_textarea' ) ) {
    function eden_engine_partner_request_textarea( string $key ): string {
        $value = isset( $_POST[ $key ] ) ? (string) wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        return sanitize_textarea_field( $value );
    }
}

if ( ! function_exists( 'eden_engine_partner_inquiry_types' ) ) {
    function eden_engine_partner_inquiry_types(): array {
        return array(
            'Laboratory, analytical, or assay partner',
            'Bioprocess or engineering collaborator',
            'Ingredient, food, or industrial partner',
            'Grant, investment, or strategic funding',
            'Media, academic, or general inquiry',
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

        $recipient = sanitize_email(
            (string) apply_filters(
                'eden_engine_partner_request_recipient',
                apply_filters( 'eden_engine_brief_request_recipient', get_option( 'admin_email' ) )
            )
        );

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
            'technology'      => array( 'Technology', '[eden_technology]' ),
            'evidence'        => array( 'Evidence', '[eden_evidence]' ),
            'system'          => array( 'System', '[eden_system]' ),
            'applications'    => array( 'Applications', '[eden_applications]' ),
            'company'         => array( 'Company', '[eden_company]' ),
            'vision'          => array( 'Vision', '[eden_vision]' ),
            'partner'         => array( 'Partner', '[eden_partner]' ),
            'technical-brief' => array( 'Technical Brief', '[eden_technical_brief]' ),
            'contact'         => array( 'Contact', '[eden_contact]' ),
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
        do_action( 'litespeed_purge_url', home_url( '/technology/' ) );
        do_action( 'litespeed_purge_url', home_url( '/evidence/' ) );
        do_action( 'litespeed_purge_url', home_url( '/system/' ) );
        do_action( 'litespeed_purge_url', home_url( '/applications/' ) );
        do_action( 'litespeed_purge_url', home_url( '/roadmap/' ) );
        do_action( 'litespeed_purge_url', home_url( '/company/' ) );
        do_action( 'litespeed_purge_url', home_url( '/vision/' ) );
        do_action( 'litespeed_purge_url', home_url( '/partner/' ) );
        do_action( 'litespeed_purge_url', home_url( '/technical-brief/' ) );
        do_action( 'litespeed_purge_url', home_url( '/contact/' ) );
        do_action( 'litespeed_purge_url', home_url( '/journal/' ) );
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

if ( ! function_exists( 'eden_engine_document_title' ) ) {
    function eden_engine_document_title( array $parts ): array {
        $parts['tagline'] = eden_engine_public_tagline();

        return $parts;
    }
}

add_filter( 'document_title_parts', 'eden_engine_document_title' );

if ( ! function_exists( 'eden_engine_nav_html' ) ) {
    function eden_engine_nav_html(): string {
        $items = array(
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
        $html .= '<a class="button button--primary" href="' . esc_url( home_url( '/partner/' ) ) . '">Partner on Phase 1</a>';
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
        $html .= '<p>Eden Engine is developing a modular, evidence-gated carbon-to-food research platform. Phase 1 begins with bounded carbon validation and measurable protein or biomass proof before broader food-system claims.</p>';
        $html .= '</div>';
        $html .= '<div class="site-footer__links" aria-label="Footer navigation">';
        $html .= '<div><h2>Technology</h2><a href="' . esc_url( home_url( '/technology/' ) ) . '">Technology</a></div>';
        $html .= '<div><h2>Evidence</h2><a href="' . esc_url( home_url( '/evidence/' ) ) . '">Evidence Program</a></div>';
        $html .= '<div><h2>Roadmap</h2><a href="' . esc_url( home_url( '/roadmap/' ) ) . '">Roadmap</a></div>';
        $html .= '<div><h2>Applications</h2><a href="' . esc_url( home_url( '/applications/' ) ) . '">Applications</a></div>';
        $html .= '<div><h2>Journal</h2><a href="' . esc_url( home_url( '/journal/' ) ) . '">Journal</a></div>';
        $html .= '<div><h2>Company</h2><a href="' . esc_url( home_url( '/company/' ) ) . '">Company</a><a href="' . esc_url( home_url( '/partner/' ) ) . '">Partner on Phase 1</a><a href="' . esc_url( home_url( '/technical-brief/' ) ) . '">Technical Brief</a></div>';
        $html .= '</div>';
        $html .= '<p class="site-footer__disclaimer">Current status: Phase 1 bench-validation planning and early testing. No commercial food, feed, nutrition, life-support, deployment, or production-ready capability is claimed. Future applications depend on dated measured evidence, safety validation, independent review, and scale-up results.</p>';
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
