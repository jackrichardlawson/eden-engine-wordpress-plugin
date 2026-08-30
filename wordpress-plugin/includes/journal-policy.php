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

if ( ! function_exists( 'eden_engine_reviewed_journal_policy' ) ) {
    function eden_engine_reviewed_journal_policy(): array {
        return array(
            'is-co2-derived-sugar-safe-to-eat' => array(
                'title'       => 'What Would Need to Be Proven Before CO₂-Derived Sugar Could Be Considered Safe?',
                'description' => 'A food-safety determination requires a defined product, process, impurity profile, intended use, specification, regulatory route, and Eden-specific evidence.',
                'claim_class' => 'Safety question / not yet demonstrated',
            ),
            'what-happens-to-global-food-security-when-sugar-becomes-unlimited' => array(
                'title'       => 'What Global Food Security Would Need From a Validated Carbon-to-Carbohydrate Pathway',
                'description' => 'A systems-level review of the evidence, infrastructure, safety, cost, and distribution conditions required before a carbon-to-carbohydrate pathway could affect food security.',
                'claim_class' => 'Future application / conditional scenario',
            ),
            'the-case-for-co2-to-sugar-cleaner-cheaper-local-and-limitless' => array(
                'title'       => 'What a CO₂-to-Carbohydrate Pathway Would Need to Prove',
                'description' => 'A claim-discipline checklist for evaluating purity, energy, cost, locality, carbon accounting, and scale in a proposed CO₂-to-carbohydrate pathway.',
                'claim_class' => 'Research hypothesis / unvalidated pathway',
            ),
            'how-co2-to-sugar-reduces-land-use-water-use-and-emissions' => array(
                'title'       => 'How to Evaluate Land, Water, and Emissions Claims for CO₂-to-Carbohydrate Pathways',
                'description' => 'A boundary-first framework for comparing land, water, energy, carbon source, and emissions against a named conventional ingredient pathway.',
                'claim_class' => 'Comparative model question / evidence required',
            ),
            'why-co%e2%82%82-is-the-most-abundant-untapped-food-resource-on-earth' => array(
                'title'       => 'Why Carbon Availability Is Only One Input to Food-System Conversion',
                'description' => 'Carbon availability alone does not establish a viable food pathway; source quality, energy, conversion, recovery, safety, cost, and validation remain decisive.',
                'claim_class' => 'Research framing / not a production claim',
            ),
            'how-co2-to-sugar-could-strengthen-disaster-relief-and-emergency-response' => array(
                'title'       => 'What a Validated Carbon-to-Carbohydrate Pathway Could Mean for Emergency Food Systems',
                'description' => 'A conditional resilience scenario describing the validation, logistics, safety, energy, and operating evidence needed before emergency-system use.',
                'claim_class' => 'Future application / conditional scenario',
            ),
            'what-abundant-clean-sugar-means-for-everyday-people' => array(
                'title'       => 'What a Validated Carbon-to-Carbohydrate Ingredient Could Mean for Consumers',
                'description' => 'A conditional consumer scenario that separates a future ingredient vision from current Eden capability, safety status, economics, and measured evidence.',
                'claim_class' => 'Future application / conditional scenario',
            ),
        );
    }
}

if ( ! function_exists( 'eden_engine_reviewed_journal_titles' ) ) {
    function eden_engine_reviewed_journal_titles(): array {
        $titles = array();

        foreach ( eden_engine_reviewed_journal_policy() as $slug => $policy ) {
            $titles[ $slug ] = (string) $policy['title'];
        }

        return $titles;
    }
}

if ( ! function_exists( 'eden_engine_reviewed_journal_policy_for_post' ) ) {
    function eden_engine_reviewed_journal_policy_for_post( ?int $post_id = null ): array {
        $post_id = $post_id ?: get_queried_object_id();

        if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
            return array();
        }

        $post_slug = (string) get_post_field( 'post_name', $post_id );
        $policy    = eden_engine_reviewed_journal_policy();

        return isset( $policy[ $post_slug ] ) ? (array) $policy[ $post_slug ] : array();
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

if ( ! function_exists( 'eden_engine_filter_reviewed_document_title' ) ) {
    function eden_engine_filter_reviewed_document_title( array $parts ): array {
        if ( ! is_singular( 'post' ) ) {
            return $parts;
        }

        $policy = eden_engine_reviewed_journal_policy_for_post();

        if ( ! empty( $policy['title'] ) ) {
            $parts['title'] = (string) $policy['title'];
        }

        return $parts;
    }
}

add_filter( 'document_title_parts', 'eden_engine_filter_reviewed_document_title', 40 );

if ( ! function_exists( 'eden_engine_filter_reviewed_seo_title' ) ) {
    function eden_engine_filter_reviewed_seo_title( string $title ): string {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        return ! empty( $policy['title'] ) ? (string) $policy['title'] . ' | ' . get_bloginfo( 'name' ) : $title;
    }
}

if ( ! function_exists( 'eden_engine_filter_reviewed_seo_description' ) ) {
    function eden_engine_filter_reviewed_seo_description( string $description ): string {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        return ! empty( $policy['description'] ) ? (string) $policy['description'] : $description;
    }
}

add_filter( 'wpseo_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'wpseo_opengraph_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'wpseo_twitter_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'rank_math/frontend/title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'rank_math/opengraph/facebook/og_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'rank_math/opengraph/twitter/twitter_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'wpseo_metadesc', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'wpseo_opengraph_desc', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'wpseo_twitter_description', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'rank_math/frontend/description', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'rank_math/opengraph/facebook/og_description', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'rank_math/opengraph/twitter/twitter_description', 'eden_engine_filter_reviewed_seo_description', 40 );

if ( ! function_exists( 'eden_engine_filter_reviewed_yoast_article_schema' ) ) {
    function eden_engine_filter_reviewed_yoast_article_schema( array $data ): array {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        if ( ! empty( $policy['title'] ) ) {
            $data['headline'] = (string) $policy['title'];
        }

        if ( ! empty( $policy['description'] ) ) {
            $data['description'] = (string) $policy['description'];
        }

        return $data;
    }
}

add_filter( 'wpseo_schema_article', 'eden_engine_filter_reviewed_yoast_article_schema', 40 );

if ( ! function_exists( 'eden_engine_filter_reviewed_rank_math_schema' ) ) {
    function eden_engine_filter_reviewed_rank_math_schema( array $data ): array {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        if ( empty( $policy ) ) {
            return $data;
        }

        foreach ( $data as &$schema_piece ) {
            if ( ! is_array( $schema_piece ) || empty( $schema_piece['@type'] ) ) {
                continue;
            }

            $schema_types = is_array( $schema_piece['@type'] )
                ? $schema_piece['@type']
                : array( $schema_piece['@type'] );
            $is_article   = (bool) array_intersect(
                array( 'Article', 'BlogPosting', 'NewsArticle' ),
                array_map( 'strval', $schema_types )
            );

            if ( ! $is_article ) {
                continue;
            }

            $schema_piece['headline']    = (string) $policy['title'];
            $schema_piece['description'] = (string) $policy['description'];
        }

        unset( $schema_piece );

        return $data;
    }
}

add_filter( 'rank_math/json_ld', 'eden_engine_filter_reviewed_rank_math_schema', 40 );

if ( ! function_exists( 'eden_engine_reviewed_journal_head_metadata' ) ) {
    function eden_engine_reviewed_journal_head_metadata(): void {
        if ( ! is_singular( 'post' ) || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
            return;
        }

        $post_id = get_queried_object_id();
        $policy  = eden_engine_reviewed_journal_policy_for_post( $post_id );

        if ( empty( $policy ) ) {
            return;
        }

        $title       = (string) $policy['title'];
        $description = (string) $policy['description'];
        $permalink   = get_permalink( $post_id );
        $schema      = array(
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => $title,
            'description'      => $description,
            'datePublished'    => get_the_date( DATE_W3C, $post_id ),
            'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
            'mainEntityOfPage' => $permalink,
            'publisher'        => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'url'   => home_url( '/' ),
            ),
        );

        echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}

add_action( 'wp_head', 'eden_engine_reviewed_journal_head_metadata', 5 );

if ( ! function_exists( 'eden_engine_is_historical_journal_post' ) ) {
    function eden_engine_is_historical_journal_post( int $post_id ): bool {
        if ( 'post' !== get_post_type( $post_id ) ) {
            return false;
        }

        $published_date    = (string) get_post_field( 'post_date', $post_id );
        $post_slug         = (string) get_post_field( 'post_name', $post_id );
        $historical_slugs  = array(
            'what-carbon-negative-food-actually-means',
            'why-co2-to-sugar-is-the-first-step-toward-post-agricultural-humanity',
        );
        $is_historical     = '' !== $published_date && $published_date < '2026-05-01 00:00:00';
        $is_historical     = $is_historical || in_array( $post_slug, $historical_slugs, true );

        return (bool) apply_filters( 'eden_engine_is_historical_journal_post', $is_historical, $post_id );
    }
}

if ( ! function_exists( 'eden_engine_historical_journal_notice' ) ) {
    function eden_engine_historical_journal_notice(): string {
        return '<aside class="eden-journal-review-note" aria-labelledby="eden-journal-review-note-title">'
            . '<p class="eden-journal-eyebrow">Historical vision note</p>'
            . '<h2 id="eden-journal-review-note-title">Earlier concept / not current capability</h2>'
            . '<p><strong>Claim class:</strong> historical vision / future research target.</p>'
            . '<p>This article reflects an earlier Eden Engine concept. The current program separates Phase 1A microbial-biomass validation from a parallel, higher-risk Phase 1B carbon-to-carbohydrate program. No production, safety, environmental-impact, or scale claim should be inferred from this article.</p>'
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
        if ( ! $post instanceof WP_Post ) {
            return $excerpt;
        }

        $policy = eden_engine_reviewed_journal_policy_for_post( (int) $post->ID );

        return ! empty( $policy['description'] ) ? (string) $policy['description'] : $excerpt;
    }
}

add_filter( 'get_the_excerpt', 'eden_engine_reviewed_journal_excerpt', 20, 2 );
