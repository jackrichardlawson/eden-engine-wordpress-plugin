<?php
/**
 * Eden Engine native WordPress template for the React-backed public pages.
 *
 * The page content owns the complete Eden shell. Keeping the theme template out
 * of this route prevents a hidden Astra masthead, duplicate navigation, and
 * duplicate landmark elements from remaining in the document.
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
<body <?php body_class( 'eden-engine-template eden-engine-public-template' ); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}

while ( have_posts() ) {
    the_post();
    the_content();
}

wp_footer();
?>
</body>
</html>
