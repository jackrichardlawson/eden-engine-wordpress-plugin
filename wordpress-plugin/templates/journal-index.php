<?php
/**
 * Eden Engine native WordPress journal index template.
 *
 * @package EdenEngine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_search = is_search();
$is_archive = is_archive();

if ( $is_search ) {
    $journal_title = sprintf(
        /* translators: %s is the current search query. */
        __( 'Search Results for "%s"', 'eden-engine' ),
        get_search_query()
    );
    $journal_intro = 'Native WordPress posts filtered through the Eden Engine journal.';
} elseif ( $is_archive ) {
    $journal_title = wp_strip_all_tags( get_the_archive_title() );
    $journal_intro = wp_strip_all_tags( get_the_archive_description() );
    $journal_intro = $journal_intro ?: 'A focused collection from the Eden Engine build log.';
} else {
    $journal_title = 'Journal';
    $journal_intro = 'Research notes, public constraints, and build updates from the path toward a controlled carbon conversion platform.';
}

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'eden-engine-template' ); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>
<div class="eden-site eden-journal-shell">
    <a class="sr-only" href="#eden-main-content">Skip to main content</a>
    <div class="technical-backdrop" aria-hidden="true">
        <img src="<?php echo esc_url( EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/shared/backgrounds/dark-grid.jpg' ); ?>" alt="" />
    </div>
    <?php echo eden_engine_nav_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <main id="eden-main-content">

    <section class="eden-journal-hero" aria-labelledby="eden-journal-title">
        <div class="eden-journal-hero__copy">
            <p class="eden-journal-eyebrow">Research Journal / Build Log</p>
            <h1 id="eden-journal-title"><?php echo esc_html( $journal_title ); ?></h1>
            <p><?php echo esc_html( $journal_intro ); ?></p>
        </div>
        <div class="eden-journal-hero__readout" aria-label="Journal focus areas">
            <span>Build Log</span>
            <strong>CO₂ to ingredients, bounded validation, evidence before scale.</strong>
            <ul>
                <li>Research notes</li>
                <li>Regulatory context</li>
                <li>Artifact-backed only after the full public evidence contract</li>
            </ul>
        </div>
    </section>

    <section class="eden-journal-flow" aria-label="Journal signal path">
        <span>Question</span>
        <i aria-hidden="true"></i>
        <span>Model</span>
        <i aria-hidden="true"></i>
        <span>Experiment</span>
        <i aria-hidden="true"></i>
        <span>Public note</span>
    </section>

    <section class="eden-journal-publication-guide" aria-labelledby="eden-journal-contract-title">
        <div class="eden-journal-publication-guide__intro">
            <p class="eden-journal-eyebrow">Fixed publication contract</p>
            <h2 id="eden-journal-contract-title">Proof is a classification, not a tone of voice.</h2>
            <p>An ordinary article remains a public note. The Artifact-backed label appears only when the complete evidence contract is present and the linked artifact receipt is published.</p>
        </div>
        <dl class="eden-journal-publication-guide__grid">
            <?php
            $publication_contract_fields = array(
                array( 'Artifact', 'A repository document, experiment ID, model version, or dated output.' ),
                array( 'Claim state', 'External precedent, modeled, planned, synthetic, measured, reviewed, or future.' ),
                array( 'What changed', 'The concrete technical or program decision represented by the update.' ),
                array( 'Supports', 'The smallest defensible claim the reviewed artifact permits.' ),
                array( 'Excludes', 'Production, safety, nutrition, scale, economics, deployment, or other unsupported inferences.' ),
                array( 'Evidence', 'Reviewed Eden artifacts or sources, with Eden’s interpretation kept separate.' ),
                array( 'Next gate', 'The next observation or decision, plus the date the public claim was reviewed.' ),
            );

            foreach ( $publication_contract_fields as $field_index => $publication_contract_field ) :
                ?>
                <div>
                    <dt><span><?php echo esc_html( str_pad( (string) ( $field_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><?php echo esc_html( $publication_contract_field[0] ); ?></dt>
                    <dd><?php echo esc_html( $publication_contract_field[1] ); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
        <p class="eden-journal-publication-guide__source-note"><strong>Reviewed source layer:</strong> each cited source records what it establishes, what Eden infers, what it does not establish, and whether it concerns pathway science, food precedent, Eden integration, or deployment.</p>
    </section>

    <section class="eden-journal-posts" aria-label="Journal posts">
        <?php if ( have_posts() ) : ?>
            <?php
            $post_index    = 0;
            $show_featured = ! is_paged() && ! $is_search;

            while ( have_posts() ) :
                the_post();
                ++$post_index;

                if ( 1 === $post_index && $show_featured ) {
                    eden_engine_render_journal_card( 'featured' );
                    echo '<div class="eden-journal-grid">';
                    continue;
                }

                if ( 1 === $post_index && ! $show_featured ) {
                    echo '<div class="eden-journal-grid eden-journal-grid--full">';
                }

                eden_engine_render_journal_card();
            endwhile;

            if ( $show_featured || $post_index > 0 ) {
                echo '</div>';
            }
            ?>

            <?php
            the_posts_pagination(
                array(
                    'mid_size'           => 1,
                    'prev_text'          => 'Previous',
                    'next_text'          => 'Next',
                    'screen_reader_text' => 'Journal pagination',
                    'class'              => 'eden-journal-pagination',
                )
            );
            ?>
        <?php else : ?>
            <div class="eden-journal-empty">
                <p class="eden-journal-eyebrow">No Signal Found</p>
                <h2>No journal posts matched this request.</h2>
                <p>Return to the journal index or try a different search term.</p>
                <a class="eden-journal-button" href="<?php echo esc_url( home_url( '/journal/' ) ); ?>">Back to Journal</a>
            </div>
        <?php endif; ?>
    </section>
    </main>
    <?php echo eden_engine_footer_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
