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
            'eden_system',
            'eden_applications',
            'eden_roadmap',
            'eden_company',
            'eden_vision',
            'eden_contact',
            'eden_technical_brief',
            'eden_whitepaper',
        );
    }
}

if ( ! function_exists( 'eden_engine_enqueue_assets' ) ) {
    function eden_engine_enqueue_assets(): void {
        $style_path  = EDEN_ENGINE_PLUGIN_PATH . 'assets/eden-engine.css';
        $script_path = EDEN_ENGINE_PLUGIN_PATH . 'assets/eden-engine.js';

        $style_version  = file_exists( $style_path ) ? (string) filemtime( $style_path ) : EDEN_ENGINE_VERSION;
        $script_version = file_exists( $script_path ) ? (string) filemtime( $script_path ) : EDEN_ENGINE_VERSION;

        if ( file_exists( $style_path ) ) {
            wp_enqueue_style(
                'eden-engine',
                EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.css',
                array(),
                $style_version
            );
        }

        if ( file_exists( $script_path ) ) {
            wp_enqueue_script(
                'eden-engine',
                EDEN_ENGINE_PLUGIN_URL . 'assets/eden-engine.js',
                array(),
                $script_version,
                true
            );

            wp_add_inline_script(
                'eden-engine',
                'window.EdenEngineAssetsBase = ' . wp_json_encode( EDEN_ENGINE_PLUGIN_URL . 'assets/' ) . ';',
                'before'
            );
        }
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
            '<div class="eden-engine-embed-root" data-eden-engine-embed data-widget="%1$s" data-title="%2$s" data-compact="%3$s"></div>',
            esc_attr( $widget ),
            esc_attr( $title ),
            esc_attr( $compact )
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

if ( ! function_exists( 'eden_engine_maybe_enqueue_assets' ) ) {
    function eden_engine_maybe_enqueue_assets(): void {
        if ( eden_engine_page_has_shortcode() || eden_engine_should_style_blog() || '' !== eden_engine_current_page_widget() ) {
            eden_engine_enqueue_assets();
        }
    }
}

add_action( 'wp_enqueue_scripts', 'eden_engine_maybe_enqueue_assets' );

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
        return eden_engine_render( $atts, 'mission' );
    }
}

if ( ! function_exists( 'eden_engine_shortcode_technology' ) ) {
    function eden_engine_shortcode_technology( array $atts ): string {
        return eden_engine_render( $atts, 'technology' );
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
add_shortcode( 'eden_system', 'eden_engine_shortcode_system' );
add_shortcode( 'eden_applications', 'eden_engine_shortcode_applications' );
add_shortcode( 'eden_roadmap', 'eden_engine_shortcode_roadmap' );
add_shortcode( 'eden_company', 'eden_engine_shortcode_company' );
add_shortcode( 'eden_vision', 'eden_engine_shortcode_vision' );
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

        if ( is_page( 'technical-brief' ) || is_page( 'contact' ) || is_page( 'whitepaper' ) ) {
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
            has_shortcode( $content, 'eden_system' ) ||
            has_shortcode( $content, 'eden_applications' ) ||
            has_shortcode( $content, 'eden_roadmap' ) ||
            has_shortcode( $content, 'eden_company' ) ||
            has_shortcode( $content, 'eden_vision' ) ||
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

if ( ! function_exists( 'eden_engine_body_class' ) ) {
    function eden_engine_body_class( array $classes ): array {
        if ( '' !== eden_engine_current_page_widget() ) {
            $classes[] = 'eden-engine-custom-page';
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
            'system'          => array( 'System', '[eden_system]' ),
            'applications'    => array( 'Applications', '[eden_applications]' ),
            'company'         => array( 'Company', '[eden_company]' ),
            'vision'          => array( 'Vision', '[eden_vision]' ),
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
        do_action( 'litespeed_purge_url', home_url( '/system/' ) );
        do_action( 'litespeed_purge_url', home_url( '/applications/' ) );
        do_action( 'litespeed_purge_url', home_url( '/roadmap/' ) );
        do_action( 'litespeed_purge_url', home_url( '/company/' ) );
        do_action( 'litespeed_purge_url', home_url( '/vision/' ) );
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
        return 'Carbon In. Civilization Out.';
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
            array( 'Home', home_url( '/' ) ),
            array( 'Technology', home_url( '/technology/' ) ),
            array( 'System', home_url( '/system/' ) ),
            array( 'Applications', home_url( '/applications/' ) ),
            array( 'Roadmap', home_url( '/roadmap/' ) ),
            array( 'Company', home_url( '/company/' ) ),
            array( 'Vision', home_url( '/vision/' ) ),
        );

        $html  = '<div class="eden-wp-nav-wrap"><header class="eden-nav eden-wp-nav" aria-label="Eden Engine navigation">';
        $html .= '<a class="eden-brand" href="' . esc_url( home_url( '/' ) ) . '" aria-label="Eden Engine home"><span class="eden-brand-mark" aria-hidden="true">EE</span><span>Eden Engine</span></a>';
        $html .= '<nav class="eden-nav-links" aria-label="Primary navigation">';

        foreach ( $items as $item ) {
            $html .= '<a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a>';
        }

        $html .= '</nav><a class="eden-nav-action" href="' . esc_url( home_url( '/technical-brief/' ) ) . '">Request Technical Brief</a>';
        $html .= '</header></div>';

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

add_action( 'wp_body_open', 'eden_engine_blog_nav' );
