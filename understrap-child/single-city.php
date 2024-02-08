<?php
/**
 * The template for displaying all single posts
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>
<div class="wrapper" id="single-wrapper">
	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">
		<div class="row">
			<main class="site-main w-100" id="main">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'loop-templates/content', 'single' );


					// Check if it's a city post
					if ( 'city' === get_post_type() ) {
						// Get the associated city ID
						$city_id = get_the_ID();

						// Query to get the last 10 real estate properties associated with the current city
						$args = array(
							'post_type'      => 'real_estate',
							'posts_per_page' => 10,
							'meta_query'     => array(
								array(
									'key'   => '_associated_city_id',
									'value' => $city_id,
								),
							),
							'orderby'        => 'date',
							'order'          => 'DESC',
						);

						$real_estate_query = new WP_Query( $args );

						if ( $real_estate_query->have_posts() ) { ?>
							<p class="h2"><?php _e( 'Last 10 Real Estate Properties in this City:', 'understrap-child' ); ?></p>
							<div class="row">
								<?php
								while ( $real_estate_query->have_posts() ) {
									$real_estate_query->the_post();
									get_template_part( 'loop-templates/content', 'real-estate' );
								}
								?>
							</div>
							<?php
						}
						// Restore original post data
						wp_reset_postdata();
					}
				}
				?>
			</main>
		</div><!-- .row -->
	</div><!-- #content -->
</div><!-- #single-wrapper -->
<?php
get_footer();
?>
