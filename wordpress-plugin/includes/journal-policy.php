<?php
/**
 * Public editorial policy for legacy Eden Engine journal posts.
 *
 * Keeps historically useful articles available without allowing earlier vision
 * language to masquerade as current technical positioning.
 *
 * @package EdenEngine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'eden_engine_legacy_journal_redirects' ) ) {
    function eden_engine_legacy_journal_redirects(): array {
        return (array) apply_filters(
            'eden_engine_legacy_journal_redirects',
            array(
                'exploring-the-eden-engine-revolutionizing-food-production'   => 'what-is-the-eden-engine-and-why-the-world-needs-post-agricultural-food-systems',
                'introducing-the-eden-engine-revolutionizing-food-production' => 'what-is-the-eden-engine-and-why-the-world-needs-post-agricultural-food-systems',
                'how-closed-loop-food-systems-work-2'                         => 'how-closed-loop-food-systems-work',
            )
        );
    }
}

if ( ! function_exists( 'eden_engine_redirect_legacy_journal_posts' ) ) {
    function eden_engine_redirect_legacy_journal_posts(): void {
        if ( ! is_singular( 'post' ) ) {
            return;
        }

        $post_id   = get_queried_object_id();
        $post_slug = (string) get_post_field( 'post_name', $post_id );
        $redirects = eden_engine_legacy_journal_redirects();

        if ( empty( $redirects[ $post_slug ] ) ) {
            return;
        }

        wp_safe_redirect( home_url( '/' . sanitize_title( (string) $redirects[ $post_slug ] ) . '/' ), 301 );
        exit;
    }
}

add_action( 'template_redirect', 'eden_engine_redirect_legacy_journal_posts', 5 );

if ( ! function_exists( 'eden_engine_reviewed_journal_titles' ) ) {
    function eden_engine_reviewed_journal_titles(): array {
        return array(
            'is-co2-derived-sugar-safe-to-eat' => 'What Would Need to Be Proven Before CO2-Derived Sugar Could Be Considered Safe?',
            'what-happens-to-global-food-security-when-sugar-becomes-unlimited' => 'What Global Food Security Would Need From a Validated Carbon-to-Carbohydrate Pathway',
            'the-case-for-co2-to-sugar-cleaner-cheaper-local-and-limitless' => 'What a CO2-to-Carbohydrate Pathway Would Need to Prove',
            'how-co2-to-sugar-reduces-land-use-water-use-and-emissions' => 'How to Evaluate Land, Water, and Emissions Claims for CO2-to-Carbohydrate Pathways',
            'why-co%e2%82%82-is-the-most-abundant-untapped-food-resource-on-earth' => 'Why Carbon Availability Is Only One Input to Food-System Conversion',
            'how-co2-to-sugar-could-strengthen-disaster-relief-and-emergency-response' => 'What a Validated Carbon-to-Carbohydrate Pathway Could Mean for Emergency Food Systems',
            'what-abundant-clean-sugar-means-for-everyday-people' => 'What a Validated Carbon-to-Carbohydrate Ingredient Could Mean for Consumers',
        );
    }
}

if ( ! function_exists( 'eden_engine_filter_reviewed_journal_title' ) ) {
    function eden_engine_filter_reviewed_journal_title( string $title, int $post_id ): string {
        if ( 'post' !== get_post_type( $post_id ) ) {
            return $title;
        }

        $post_slug = (string) get_post_field( 'post_name', $post_id );
        $titles    = eden_engine_reviewed_journal_titles();

        return isset( $titles[ $post_slug ] ) ? (string) $titles[ $post_slug ] : $title;
    }
}

add_filter( 'the_title', 'eden_engine_filter_reviewed_journal_title', 20, 2 );

if ( ! function_exists( 'eden_engine_is_historical_journal_post' ) ) {
    function eden_engine_is_historical_journal_post( int $post_id ): bool {
        if ( 'post' !== get_post_type( $post_id ) ) {
            return false;
        }

        $published_date = (string) get_post_field( 'post_date', $post_id );
        $is_historical  = '' !== $published_date && $published_date < '2026-05-01 00:00:00';

        return (bool) apply_filters( 'eden_engine_is_historical_journal_post', $is_historical, $post_id );
    }
}

if ( ! function_exists( 'eden_engine_historical_journal_notice' ) ) {
    function eden_engine_historical_journal_notice(): string {
        return '<aside class="eden-journal-review-note" aria-labelledby="eden-journal-review-note-title">'
            . '<p class="eden-journal-eyebrow">Historical vision note</p>'
            . '<h2 id="eden-journal-review-note-title">Earlier concept / not current capability</h2>'
            . '<p>This article reflects an earlier Eden Engine concept. The current program begins with bounded carbon validation and microbial biomass proof. No production, safety, environmental-impact, or scale claim should be inferred from this article.</p>'
            . '<p><strong>Last technically reviewed:</strong> <time datetime="2026-08-29">August 29, 2026</time></p>'
            . '<a href="' . esc_url( home_url( '/evidence/' ) ) . '">View the current evidence program</a>'
            . '</aside>';
    }
}

if ( ! function_exists( 'eden_engine_sugar_safety_review_content' ) ) {
    function eden_engine_sugar_safety_review_content(): string {
        $proof_items = array(
            'Molecular identity of the intended output',
            'Source-gas origin, contaminants, and conditioning',
            'Process-derived impurities and removal steps',
            'Downstream purification and food-grade specifications',
            'Batch consistency and analytical method qualification',
            'Hazard analysis, toxicology, and exposure where applicable',
            'Intended use and expected consumption',
            'Shelf-life and stability logic',
            'Applicable regulatory pathway',
            'Eden-specific measured evidence and independent review',
        );
        $list_html = '';

        foreach ( $proof_items as $proof_item ) {
            $list_html .= '<li>' . esc_html( $proof_item ) . '</li>';
        }

        return '<section class="eden-journal-correction" aria-labelledby="eden-safety-review-title">'
            . '<p class="eden-journal-eyebrow">Current technical answer</p>'
            . '<h2 id="eden-safety-review-title">Safety cannot be answered categorically before the product and process are defined.</h2>'
            . '<p>Chemical identity matters, but it is not the whole safety or regulatory question. A specific output, manufacturing route, impurity profile, intended use, specification, and evidence package must be evaluated together.</p>'
            . '<h3>What would need to be demonstrated</h3>'
            . '<ol>' . $list_html . '</ol>'
            . '<p><strong>Current Eden status:</strong> no Eden output is publicly claimed to be food-grade, safe for consumption, or regulatorily cleared. The smallest useful next step remains a bounded, instrumented carbon-to-ingredient experiment.</p>'
            . '<a class="eden-journal-button" href="' . esc_url( home_url( '/evidence/' ) ) . '">View the evidence program</a>'
            . '</section>';
    }
}

if ( ! function_exists( 'eden_engine_apply_journal_review_policy' ) ) {
    function eden_engine_apply_journal_review_policy( string $content ): string {
        if ( is_admin() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
            return $content;
        }

        $post_id   = get_the_ID();
        $post_slug = (string) get_post_field( 'post_name', $post_id );

        if ( 'is-co2-derived-sugar-safe-to-eat' === $post_slug ) {
            return eden_engine_historical_journal_notice() . eden_engine_sugar_safety_review_content();
        }

        if ( eden_engine_is_historical_journal_post( $post_id ) ) {
            return eden_engine_historical_journal_notice() . $content;
        }

        return $content;
    }
}

add_filter( 'the_content', 'eden_engine_apply_journal_review_policy', 30 );

if ( ! function_exists( 'eden_engine_reviewed_journal_excerpt' ) ) {
    function eden_engine_reviewed_journal_excerpt( string $excerpt, $post ): string {
        if ( ! $post instanceof WP_Post || 'is-co2-derived-sugar-safe-to-eat' !== $post->post_name ) {
            return $excerpt;
        }

        return 'A safety determination requires a defined product, process, impurity profile, intended use, food-grade specification, and Eden-specific evidence.';
    }
}

add_filter( 'get_the_excerpt', 'eden_engine_reviewed_journal_excerpt', 20, 2 );
