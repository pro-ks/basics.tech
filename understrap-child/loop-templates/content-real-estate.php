<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card m-1 p-0'); ?> style="max-width: 32%;min-width: 340px;">                    
    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>">
        	<?php the_post_thumbnail( 'homepage-understrap-child', array( 'class' => 'card-img-top' ) ); ?>
        </a>
    <?php endif; ?>                                
    <div class="card-body">
    	<p class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
        <!-- Вывод дополнительной информации об объекте недвижимости -->
        <p class="card-title">
        <?php display_associated_city( $post ); 

		    echo '<ul class="mt-3 pl-3">';
		    display_custom_fields( array(
		        '_address'      => __( 'Address:', 'understrap-child' ),
		        '_area'         => __( 'Area:', 'understrap-child' ),
		        '_living_area'  => __( 'Living Area:', 'understrap-child' ),
		        '_floor'        => __( 'Floor:', 'understrap-child' ),
		        '_price'        => __( 'Price:', 'understrap-child' ),
		    ) );
		    echo '</ul>';

	    ?>
        <a href="<?php the_permalink(); ?>" class="btn btn-primary w-100" role="button" ><?php _e( 'More detailed...', 'understrap-child' ); ?></a>
	</p>
    </div>
</article>