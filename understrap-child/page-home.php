<?php
/**
  * The template for displaying the homepage
* Template Name: Home page
 * Template Post Type: page
 * @package Understrap
 */

// Выход, если доступ осуществлен напрямую.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );

?>

<div class="wrapper" id="page-wrapper">
    <div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">
        <div class="row">
            <main class="site-main w-100" id="main">
                <section class="latest-real-estate">
                    <h2><?php _e( 'Latest Real Estate Properties', 'understrap-child' ); ?></h2>
                    <div class="row">
                    <?php
                    // Запрос для получения последних 10 объектов недвижимости
                    $args = array(
                        'post_type'      => 'real_estate',
                        'posts_per_page' => 10,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );

                    $real_estate_query = new WP_Query( $args );

                    if ( $real_estate_query->have_posts() ) {
                        while ( $real_estate_query->have_posts() ) {
                            $real_estate_query->the_post();
                            get_template_part( 'loop-templates/content', 'real-estate' );
                        }
                    }

                    // Восстановление исходных данных поста
                    wp_reset_postdata();
                    ?>
	                </div>
                </section>
				<section class="latest-cities mt-5">
				    <h2><?php _e( 'Latest Cities', 'understrap-child' ); ?></h2>
				    <div class="row p-0">
				        <?php
				        // Запрос для получения последних 10 городов
				        $args = array(
				            'post_type'      => 'city',
				            'posts_per_page' => -1,
				            'orderby'        => 'title',
				            'order'          => 'ASC',
				        );
				        $city_query = new WP_Query( $args );
				        if ( $city_query->have_posts() ) {
				            while ( $city_query->have_posts() ) {
				                $city_query->the_post();
				                get_template_part( 'loop-templates/content', 'city' );
				            }
				        }

				        // Восстановление исходных данных поста
				        wp_reset_postdata();
				        ?>
				    </div><!-- .row -->
				</section>
				<section class="latest-form mt-5">
					<div class="row">
						<div class="col-12"><?php the_content(); ?></div>
					</div>
				</section>
            </main>
        </div><!-- .row -->
    </div><!-- #content -->
</div><!-- #page-wrapper -->

<?php
get_footer();

