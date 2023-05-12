<?php
/**
 * Template part for displaying post in post listing.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     Sinatra
 * @author      Sinatra Team <hello@sinatrawp.com>
 * @since       1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array('sinatra-article','fdhome') ); ?><?php sinatra_schema_markup( 'article' ); ?>>

	<div class="entry-content si-entry"<?php sinatra_schema_markup( 'text' ); ?>>
	<?php the_content(); ?>
        </div>

</article><!-- #post-<?php the_ID(); ?> -->
