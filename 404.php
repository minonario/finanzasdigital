<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package     Sinatra
 * @author      Sinatra Team <hello@sinatrawp.com>
 * @since       1.0.0
 */

?>

<?php get_header(); ?>

<div class="si-container">

	<div id="primary" class="content-area">

		<?php do_action( 'sinatra_before_content' ); ?>

		<main id="content" class="site-content" role="main"<?php sinatra_schema_markup( 'main' ); ?>>
                  
                  <div class="home-top-visitas"> 
                        <section id="topVisitas">
                            <div class="title-section"> <span class="act">Lo más leído </span> </div>
                            <?php 
                              // args
                              $args = array(
                                  'post_type'        => 'post',
                                  'post_status'      => 'publish',
                                  'posts_per_page'   => 5,
                                  'category__not_in' => 314,
                                  'meta_key'         => 'post_views_count',
                                  'orderby'          => 'meta_value_num',
                                  'date_query' => array(
                                                        array(
                                                          'after' => '1 week ago'
                                                        )
                                                      ),
                                  'order'            => 'DESC'
                              );
                              // query
                              $the_query = new WP_Query( $args );

                              if( $the_query->have_posts() ): ?>
                                <div class="list-items">
                                  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                    <article> 
                                      <header>  
                                        <h3 class="post-title"> 
                                          <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                          </a>
                                        </h3> 
                                      </header> 
                                    </article>
                              <?php endwhile; ?>
                                </div>
                              <?php endif; ?>
                        </section>
                    </div>

			<?php do_action( 'sinatra_content_404' ); ?>

		</main><!-- #content .site-content -->

		<?php do_action( 'sinatra_after_content' ); ?>

	</div><!-- #primary .content-area -->

</div><!-- END .si-container -->

<?php
get_footer();
