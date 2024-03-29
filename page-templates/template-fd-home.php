<?php
/**
 * Template Name: FD Fullwidth
 *
 * 100% wide page template without vertical spacing.
 *
 * @package     Sinatra
 * @author      Sinatra Team <hello@sinatrawp.com>
 * @since       1.0.0
 */

get_header(); ?>

<div class="si-container">
	<h1 class="hidden"><?php the_title()?></h1>
        <?php
        // Check value exists.
        if( have_rows('inicio') ):
            ?>
        <div class="grid nl">
            <?php
            // Loop through rows.
            while ( have_rows('inicio') ) : the_row();

                // Case: Paragraph layout.
                if( get_row_layout() == 'destacado' ):
                    $categoria = get_sub_field('categoria');
                    $cantidad = get_sub_field('cantidad');

                    // args
                    $args = array(
                        'cat' => $categoria,
                        'posts_per_page'  => $cantidad,
                        'post_type'       => 'post'
                    );


                    // query
                    $the_query = new WP_Query( $args );
                    $count = 1;

                    if( $the_query->have_posts() ): ?>
                        <div class="home-recomendaciones-del-editor"> 
                            <section id="recomendacionesDelEditor">
                                <div class="carousel-x" id="scroller">
                                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                  <article id="recomendaciones-del-editor-<?php echo $count ?>">
                                    <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                                    <?php //the_post_thumbnail('full', array('class' => 'post-thumbnail')); ?>
                                      
                                    <?php if ( has_post_thumbnail() ) : ?>
				    <?php the_post_thumbnail('feature-mobile')?>
                                    <?php endif; ?>

                                    </a>
                                    <header>
                                        <h2 class="post-title">
                                          <a href="<?php the_permalink(); ?>">
                                              <?php the_title(); ?>
                                          </a>
                                        </h2>
                                    </header>
                                  </article>
                                <?php $count++;
                                      endwhile; ?>
                                </div>
				<div class="navigationx">
                                  <span id="prev-btn" class="btnx prev-btn"></span>
                                  <span id="next-btn" class="btnx next-btn"></span>
                                </div>
                                <?php /* $count = 1; $the_query->rewind_posts(); ?>
                                <div class="carousel-x-btns">
                                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                 <a href="#recomendaciones-del-editor-<?php echo $count ?>" <?php echo($count == 1 ? 'class="activex"' : '')?>><?php echo $count ?></a>
                                <?php $count++;
                                      endwhile; */?>
                              <?php /*
                                </div> */?>
                            </section>
                            <?php
                              $hero = get_field('destacado','option');
                              if( $hero ): 
                                if ( $hero['destacado_banner']): ?>
                                <div class="desta-banner">
                                  <?php echo do_shortcode($hero['shortcode_banner']); ?>
                                </div>
                                <?php endif;
                              endif; ?>
                        </div>
                    <?php endif; wp_reset_query();?>
                <?php
                elseif( get_row_layout() == 'sidebar_dinamico' ):
                      if ( is_active_sidebar( 'fd-sidebar' ) ) :
                          dynamic_sidebar( 'fd-sidebar' );
                      endif;
                      
                elseif( get_row_layout() == 'cuadro_lista' ):
                    ?>
                    
                        <div class="home-top-visitas"> 
                            <section id="topVisitas">
                                <div class="title-section"> <span class="act"><?php echo get_sub_field('titulo') ?></span> </div>
                                <?php 
                                  // args
                                  $args = array(
                                      'post_type'        => 'post',
                                      'post_status'      => 'publish',
                                      'posts_per_page'   => get_sub_field('cantidad'),
                                      'category__not_in' => get_sub_field('excluir_categorias'),
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
                    <?php wp_reset_query();   // Restore global post data stomped by the_post().
                endif;

            // End loop.
            endwhile; ?>

        </div>
        <?php 
        // No value.
        else :
            // Do something...
        endif;
        ?>
	<div id="primary" class="content-area">
          
          
        <?php
        wp_reset_query(); 
        // Check value exists.
        if( have_rows('contenido') ):

            // Loop through rows.
            while ( have_rows('contenido') ) : the_row();
                if( get_row_layout() == 'noticias' ):
                    $args = array(
                        'post_type'        => 'post',
                        'post_status'      => 'publish',
                        'category__not_in' => array(19),
                        'posts_per_page'   => 18
                    );
                    $query = new WP_Query( $args );

                    if( $query->have_posts() ):
                ?>
                      <div class="main si-container">
                          <div class="grid">
                              <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                  <article id="post-<?php the_ID(); ?>" <?php post_class( 'sinatra-article' ); ?><?php sinatra_schema_markup( 'article' ); ?>>

                                  <?php
                                    get_template_part( 'template-parts/entry/entry', 'thumbnail' );
                                    //get_template_part( 'template-parts/entry/entry', 'header' );
                                    $sinatra_title_icon = apply_filters( 'sinatra_post_title_icon', '' );
                                    $sinatra_title_string = '%2$s%1$s';
                                    if ( 'link' === get_post_format() ) {
                                            $sinatra_title_string = '<a href="%3$s" class="titulo" title="%3$s" rel="bookmark">%2$s%1$s</a>';
                                    } elseif ( ! is_single( get_the_ID() ) ) {
                                            $sinatra_title_string = '<a href="%3$s" class="titulo" title="%4$s" rel="bookmark">%2$s%1$s</a>';
                                    }
                                    $sinatra_title_icon = sinatra()->icons->get_svg( $sinatra_title_icon );

                                    echo sinatra_entry_meta_date(array('before' => '<span class="posted-on clearfix">'));
                                    echo sprintf(
                                            wp_kses_post( $sinatra_title_string ),
                                            wp_kses_post( get_the_title() ),
                                            wp_kses_post( $sinatra_title_icon ),
                                            esc_url( sinatra_entry_get_permalink() ),
                                            the_title_attribute( array( 'echo' => false ) )
                                    );

                                    //get_template_part( 'template-parts/entry/entry', 'summary' );
                                    //get_template_part( 'template-parts/entry/entry', 'footer' );
                                    //get_template_part( 'template-parts/entry/entry-summary-footer' );
                                  ?>

                                  </article><!-- #post-<?php the_ID(); ?> -->
                              <?php endwhile; ?>
                              <?php
                                $hero = get_field('grupo_uno','option');
                                if( $hero ): 
                                  if ( $hero['banner_uno']): ?>
                                    <div class="desta-banner-1"><?php echo do_shortcode($hero['shortcode_banner']); ?></div>
                                  <?php endif;
                                endif;
                              ?>
                              <?php
                                $hero = get_field('grupo_dos','option');
                                if( $hero ): 
                                  if ( $hero['banner_dos']): ?>
                                    <div class="desta-banner-2"><?php echo do_shortcode($hero['shortcode_banner']); ?></div>
                                  <?php endif;
                                endif;
                              ?>
                              <?php
                                $hero = get_field('grupo_tres','option');
                                if( $hero ): 
                                  if ( $hero['banner_tres']): ?>
                                    <div class="desta-banner-3"><?php echo do_shortcode($hero['shortcode_banner']); ?></div>
                                  <?php endif;
                                endif;
                              ?>
                          </div>
                        </div>
                <?php
                    endif;
                    wp_reset_query();
                elseif( get_row_layout() == 'otros_titulares' ): 
                    $args = array(
                        'post_type'        => 'post',
                        'post_status'      => 'publish',
                        'category__not_in' => array(19),
                        'offset'           => 18,
                        'posts_per_page'   => 15
                    );
                    $query = new WP_Query( $args );

                    if( $query->have_posts() ):
                ?>  
                  <div class="home-top-visitas">
                    <h4 class="title-otros">Otros titulares</h4>
                    <ul class="otros_titulares">
                      <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                      <li>
                        <?php 
                          $sinatra_title_string = '%2$s%1$s';
                          $sinatra_title_string = '<a href="%3$s" class="titulo" title="%4$s" rel="bookmark">%2$s%1$s</a>';
                          echo sprintf(
                                      wp_kses_post( $sinatra_title_string ),
                                      wp_kses_post( get_the_title() ),
                                      wp_kses_post( $sinatra_title_icon ),
                                      esc_url( sinatra_entry_get_permalink() ),
                                      the_title_attribute( array( 'echo' => false ) )
                              );
                        ?>
                      </li>
                      <?php endwhile; ?>
                    </ul>
                  </div>
                <?php
                    endif;
                endif;

            // End loop.
            endwhile;

        // No value.
        elseif (get_row_layout() == 'otros_titulares' ) :
          
          
        ?>
        <?php
        endif;
        if ( have_posts() ) :
                while ( have_posts() ) :
                        the_post();

                        get_template_part( 'template-parts/content/content', 'home' );
                endwhile;
        endif;
        ?>
        </div><!-- #primary .content-area -->

	<?php do_action( 'sinatra_sidebar' ); ?>

</div><!-- END .si-container -->           
<?php
get_footer();
