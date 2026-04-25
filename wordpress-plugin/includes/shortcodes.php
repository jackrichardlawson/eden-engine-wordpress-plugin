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

if ( ! function_exists( 'eden_engine_shortcode_whitepaper' ) ) {
    function eden_engine_shortcode_whitepaper( array $atts ): string {
        return eden_engine_render( $atts, 'whitepaper' );
    }
}

add_shortcode( 'eden_engine_showcase', 'eden_engine_shortcode_showcase' );
add_shortcode( 'eden_digital_twin', 'eden_engine_shortcode_digital_twin' );
add_shortcode( 'eden_target_mapper', 'eden_engine_shortcode_target_mapper' );
add_shortcode( 'eden_pathway_demo', 'eden_engine_shortcode_pathway_demo' );
add_shortcode( 'eden_reactor_status', 'eden_engine_shortcode_reactor_status' );
add_shortcode( 'eden_mission', 'eden_engine_shortcode_mission' );
add_shortcode( 'eden_technology', 'eden_engine_shortcode_technology' );
add_shortcode( 'eden_whitepaper', 'eden_engine_shortcode_whitepaper' );

if ( ! function_exists( 'eden_engine_current_page_widget' ) ) {
    function eden_engine_current_page_widget(): string {
        if ( is_front_page() ) {
            return 'home';
        }

        if ( is_page( 'mission' ) ) {
            return 'mission';
        }

        if ( is_page( 'technology' ) ) {
            return 'technology';
        }

        if ( is_page( 'whitepaper' ) ) {
            return 'whitepaper';
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

if ( ! function_exists( 'eden_engine_nav_html' ) ) {
    function eden_engine_nav_html(): string {
        $items = array(
            array( 'Home', home_url( '/' ) ),
            array( 'Mission', home_url( '/mission/' ) ),
            array( 'Technology', home_url( '/technology/' ) ),
            array( 'Whitepaper', home_url( '/whitepaper/' ) ),
            array( 'Journal', home_url( '/journal/' ) ),
            array( 'Collaborate', home_url( '/#collaborate' ) ),
        );

        $html  = '<div class="eden-wp-nav-wrap"><header class="eden-nav eden-wp-nav" aria-label="Eden Engine navigation">';
        $html .= '<a class="eden-brand" href="' . esc_url( home_url( '/' ) ) . '" aria-label="Eden Engine home"><span class="eden-brand-mark" aria-hidden="true">EE</span><span>Eden Engine</span></a>';
        $html .= '<nav class="eden-nav-links" aria-label="Primary navigation">';

        foreach ( $items as $item ) {
            $html .= '<a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a>';
        }

        $html .= '</nav><a class="eden-nav-action" href="' . esc_url( home_url( '/#collaborate' ) ) . '">Collaborate</a>';
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
