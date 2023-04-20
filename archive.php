<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sinatra
 * @author  Sinatra Team <hello@sinatrawp.com>
 * @since   1.0.0
 */

?>

<?php get_header(); ?>

<?php
if (is_archive()):
  $queried_object = get_queried_object();
  $term_id = $queried_object->term_id;
  $hero = get_field('superior', 'category_'.$term_id);
  if( $hero ): 
    if ( $hero['banner_superior']):
      $class = ' banner-active';
    endif;
  endif;  
endif;?>
<div class="si-container<?php echo (isset($class) ? $class : '') ?>">
	<div id="primary" class="content-area">
          <?php
          if (is_archive()):
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

			<?php do_action( 'sinatra_content_archive' ); ?>

		</main><!-- #content .site-content -->

		<?php do_action( 'sinatra_after_content' ); ?>
          <?php
          if (is_archive()):
            $queried_object = get_queried_object();
            $term_id = $queried_object->term_id;
            $hero = get_field('inferior', 'category_'.$term_id);
            if( $hero ): 
              if ( $hero['banner_inferior']): ?>
              <div class="ad-banners-inferior center">
                <?php echo do_shortcode($hero['shortcode_banner']); ?>
              </div>
              <?php endif;
            endif; 
          endif;?>

	</div><!-- #primary .content-area -->

	<?php do_action( 'sinatra_sidebar' ); ?>

</div><!-- END .si-container -->

<?php
get_footer();
