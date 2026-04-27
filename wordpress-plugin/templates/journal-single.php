<?php
/**
 * Eden Engine native WordPress single post template.
 *
 * @package EdenEngine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
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
<main class="eden-site eden-post-shell">
    <div class="technical-backdrop" aria-hidden="true">
        <img src="<?php echo esc_url( EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/shared/backgrounds/dark-grid.jpg' ); ?>" alt="" />
    </div>
    <?php echo eden_engine_nav_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <?php
    while ( have_posts() ) :
        the_post();
        $post_id = get_the_ID();
        ?>
        <article <?php post_class( 'eden-post' ); ?>>
            <header class="eden-post-hero">
                <div class="eden-post-hero__copy">
                    <a class="eden-post-back" href="<?php echo esc_url( home_url( '/journal/' ) ); ?>">Journal</a>
                    <p class="eden-journal-eyebrow"><?php echo esc_html( eden_engine_post_kicker( $post_id ) ); ?></p>
                    <h1><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
                    <p class="eden-post-dek"><?php echo esc_html( eden_engine_post_excerpt( $post_id, 34 ) ); ?></p>
                    <div class="eden-post-hero__meta" aria-label="Post metadata">
                        <span><?php echo esc_html( get_the_date( '', $post_id ) ); ?></span>
                        <span><?php echo esc_html( eden_engine_post_read_time( $post_id ) ); ?></span>
                        <span><?php echo esc_html( get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ) ); ?></span>
                    </div>
                </div>
                <figure class="eden-post-hero__media">
                    <img
                        src="<?php echo esc_url( eden_engine_post_image_url( $post_id, 'large' ) ); ?>"
                        alt="<?php echo esc_attr( eden_engine_post_image_alt( $post_id ) ); ?>"
                        loading="eager"
                        fetchpriority="high"
                    />
                </figure>
            </header>

            <div class="eden-post-layout">
                <aside class="eden-post-readout" aria-label="Article control readout">
                    <p class="eden-journal-eyebrow">Control Readout</p>
                    <dl>
                        <div>
                            <dt>Status</dt>
                            <dd>Public note</dd>
                        </div>
                        <div>
                            <dt>Signal</dt>
                            <dd><?php echo esc_html( eden_engine_post_kicker( $post_id ) ); ?></dd>
                        </div>
                        <div>
                            <dt>Runtime</dt>
                            <dd><?php echo esc_html( eden_engine_post_read_time( $post_id ) ); ?></dd>
                        </div>
                    </dl>
                    <a class="eden-journal-button" href="<?php echo esc_url( home_url( '/technical-brief/' ) ); ?>">Request Technical Brief</a>
                </aside>

                <div class="eden-post-content">
                    <?php
                    the_content();
                    wp_link_pages(
                        array(
                            'before' => '<nav class="eden-post-page-links" aria-label="Post pages">',
                            'after'  => '</nav>',
                        )
                    );
                    ?>
                </div>
            </div>

            <nav class="eden-post-navigation" aria-label="Adjacent journal posts">
                <div>
                    <?php previous_post_link( '<span>Previous</span>%link' ); ?>
                </div>
                <div>
                    <?php next_post_link( '<span>Next</span>%link' ); ?>
                </div>
            </nav>
        </article>
    <?php endwhile; ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
