<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card p-0 mt-3 m-1 '); ?> style="max-width: 580px;">
    <div class="row g-0">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="col-md-4">
            	<a href="<?php the_permalink(); ?>">
                	<?php the_post_thumbnail( 'homepage-city-understrap-child', array( 'class' => 'img-fluid rounded-start' ) ); ?>
                </a>
            </div>
        <?php endif; ?>
	    <div class="col-md-8">
	      <div class="card-body">
	        <p class="card-title h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
	        <p class="card-text"><?php the_excerpt(); ?></p>
	      </div>
	    </div>
    </div>
</article>