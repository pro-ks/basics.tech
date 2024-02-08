<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <div class="entry-meta">
            <?php understrap_posted_on(); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <?php
    // Display the featured image
    echo get_the_post_thumbnail( $post->ID, 'large' );

    // Display custom fields
    echo '<div class="entry-content">';
    echo '<ul class="mt-3 pl-3">';
    display_custom_fields( array(
        '_address'      => __( 'Address:', 'understrap-child' ),
        '_area'         => __( 'Area:', 'understrap-child' ),
        '_living_area'  => __( 'Living Area:', 'understrap-child' ),
        '_floor'        => __( 'Floor:', 'understrap-child' ),
        '_price'        => __( 'Price:', 'understrap-child' ),
    ) );
    echo '</ul>';

    // Display taxonomy terms
    display_taxonomy_terms( $post );

    // Display associated city
    display_associated_city( $post );

    // Display post content and pagination
    the_content();
    understrap_link_pages();

    echo '</div><!-- .entry-content -->';

    ?>
    <footer class="entry-footer">
        <?php understrap_entry_footer(); ?>
    </footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->