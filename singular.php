<?php
/**
 * The template for displaying all pages, single posts and attachments.
 *
 * This is a new template file that WordPress introduced in
 * version 4.3.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package     Sinatra
 * @author      Sinatra Team <hello@sinatrawp.com>
 * @since       1.0.0
 */

?>

<?php get_header(); ?>

<?php
if (is_single()):
  $post_id = get_the_ID();
  $hero = get_field('superior', $post_id);
  if( $hero ): 
    if ( $hero['banner_superior']):
      $class = ' banner-active';
    endif;
  endif; 
endif;?>
<div class="si-container<?php echo (isset($class) ? $class : '') ?>">

	<div id="primary" class="content-area">
          
        <?php
        if (is_single()):
          if( $hero ): 
            if ( $hero['banner_superior']): ?>
            <div class="ad-banners center">
              <?php echo do_shortcode($hero['shortcode_banner']); ?>
            </div>
            <?php endif;
          endif; 
        endif;?>

		<?php do_action( 'sinatra_before_content' ); ?>

		<main id="content" class="site-content" role="main"<?php sinatra_schema_markup( 'main' ); ?>>

			<?php
			do_action( 'sinatra_before_singular' );

			do_action( 'sinatra_content_singular' );
                        
                        if (is_single()):
                          $post_id = get_the_ID();
                          $hero = get_field('inferior', $post_id);
                          if( $hero ): 
                            if ( $hero['banner_inferior']): ?>
                            <div class="ad-banners center">
                              <?php echo do_shortcode($hero['shortcode_banner']); ?>
                            </div>
                            <?php endif;
                          endif; 
                        endif;
                        
                        if ($post->post_type == "post"){
                          echo do_shortcode( '[ds8relatedposts]' );
                        }

			do_action( 'sinatra_after_singular' );
			?>

		</main><!-- #content .site-content -->

		<?php do_action( 'sinatra_after_content' ); ?>
                

	</div><!-- #primary .content-area -->

	<?php do_action( 'sinatra_sidebar' ); ?>

</div><!-- END .si-container -->

<?php
get_footer();
