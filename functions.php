<?php
/**
 * Theme functions and definitions.
 *
 * @package Sinatra
 * @author  Sinatra Team <hello@sinatrawp.com>
 * @since   1.0.0
 */

/**
 * Main Sinatra class.
 *
 * @since 1.0.0
 */
final class Sinatra {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Theme version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version = '1.2.1';

	/**
	 * Main Sinatra Instance.
	 *
	 * Insures that only one instance of Sinatra exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0.0
	 * @return Sinatra
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Sinatra ) ) {
			self::$instance = new Sinatra();

			self::$instance->constants();
			self::$instance->includes();
			self::$instance->objects();

			// Hook now that all of the Sinatra stuff is loaded.
			do_action( 'sinatra_loaded' );
		}
		return self::$instance;
	}

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Setup constants.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function constants() {

		if ( ! defined( 'SINATRA_THEME_VERSION' ) ) {
			define( 'SINATRA_THEME_VERSION', $this->version );
		}

		if ( ! defined( 'SINATRA_THEME_URI' ) ) {
			define( 'SINATRA_THEME_URI', get_parent_theme_file_uri() );
		}

		if ( ! defined( 'SINATRA_THEME_PATH' ) ) {
			define( 'SINATRA_THEME_PATH', get_parent_theme_file_path() );
		}
	}

	/**
	 * Include files.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function includes() {

		require_once SINATRA_THEME_PATH . '/inc/common.php';
		require_once SINATRA_THEME_PATH . '/inc/deprecated.php';
		require_once SINATRA_THEME_PATH . '/inc/helpers.php';
		require_once SINATRA_THEME_PATH . '/inc/widgets.php';
		require_once SINATRA_THEME_PATH . '/inc/template-tags.php';
		require_once SINATRA_THEME_PATH . '/inc/template-parts.php';
		require_once SINATRA_THEME_PATH . '/inc/icon-functions.php';
		require_once SINATRA_THEME_PATH . '/inc/breadcrumbs.php';
		require_once SINATRA_THEME_PATH . '/inc/class-sinatra-dynamic-styles.php';

		// Core.
		require_once SINATRA_THEME_PATH . '/inc/core/class-sinatra-options.php';
		require_once SINATRA_THEME_PATH . '/inc/core/class-sinatra-enqueue-scripts.php';
		require_once SINATRA_THEME_PATH . '/inc/core/class-sinatra-fonts.php';
		require_once SINATRA_THEME_PATH . '/inc/core/class-sinatra-theme-setup.php';
		require_once SINATRA_THEME_PATH . '/inc/core/class-sinatra-db-updater.php';

		// Compatibility.
		require_once SINATRA_THEME_PATH . '/inc/compatibility/woocommerce/class-sinatra-woocommerce.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/socialsnap/class-sinatra-socialsnap.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/class-sinatra-wpforms.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/class-sinatra-jetpack.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/class-sinatra-endurance.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/class-sinatra-beaver-themer.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/class-sinatra-elementor.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/class-sinatra-elementor-pro.php';
		require_once SINATRA_THEME_PATH . '/inc/compatibility/class-sinatra-hfe.php';

		if ( is_admin() ) {
			require_once SINATRA_THEME_PATH . '/inc/utilities/class-sinatra-plugin-utilities.php';
			require_once SINATRA_THEME_PATH . '/inc/admin/class-sinatra-admin.php';
		}

		// Customizer.
		require_once SINATRA_THEME_PATH . '/inc/customizer/class-sinatra-customizer.php';
                
                //require_once SINATRA_THEME_PATH . '/inc/fdcalendar.php'; // JLMA
	}

	/**
	 * Setup objects to be used throughout the theme.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function objects() {

		sinatra()->options    = new Sinatra_Options();
		sinatra()->fonts      = new Sinatra_Fonts();
		sinatra()->icons      = new Sinatra_Icons();
		sinatra()->customizer = new Sinatra_Customizer();

		if ( is_admin() ) {
			sinatra()->admin = new Sinatra_Admin();
		}
	}
}

/**
 * The function which returns the one Sinatra instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $sinatra = sinatra(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function sinatra() {
	return Sinatra::instance();
}

sinatra();


/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}


add_filter( 'body_class', 'custom_body_class' );

function custom_body_class( array $classes ) {
	
        $classes[] = 'si-blog-layout-1';

	return $classes;
}

/*
* Set post views count using post meta
*/
function setPostViews($postID=null) {
     if(!is_single() || 'post' !== get_post_type() || current_user_can('administrator')) return;
     $postID = !empty($postID) ? $postID : get_the_ID();
     $countKey = 'post_views_count';
     $count = get_post_meta($postID, $countKey, true);
     update_post_meta($postID, $countKey, ((int)$count)+1);
}
add_action('template_redirect', 'setPostViews');

add_filter('comment_form_default_fields', 'unset_url_field');
function unset_url_field($fields){
    if(isset($fields['url']))
       unset($fields['url']);
       return $fields;
}

add_filter( 'preprocess_comment', 'ds8_preprocess_comment' );
 
function ds8_preprocess_comment($comment) {
    if ( strlen( $comment['comment_content'] ) > 300 ) {
        wp_die('El comentario es demasiado largo. Por favor mantenga su comentario por debajo de los 300 caracteres..');
    }
    return $comment;
}


// DEPRECATED - cambio va por yoast admin
//add_filter( 'wpseo_title', 'wpdocs_hack_wp_title_for_home');
/**
 * Customize the title for the home page, if one is not set.
 *
 * @param string $title The original title.
 * @return string The title to use.
 */
function wpdocs_hack_wp_title_for_home( $title )
{
  if ( empty( $title ) && ( is_home() || is_front_page() ) ) {
    $title = __( 'Home', 'textdomain' ) . ' | ' . get_bloginfo( 'description' );
  }
  return $title;
}

remove_filter( 'posts_search_orderby', 'relevanssi_light_posts_search_orderby', 10 );
add_filter( 'posts_search_orderby', 'rl_posts_search_orderby', 10, 2 );
function rl_posts_search_orderby( $orderby, $query ) {
	if ( isset( $query->query['s'] ) ) {
		$orderby = 'post_date DESC';
	}
	return $orderby;
}

//add_filter( "wpseo_sitemap_page_content", "add_sitemap_items" );

function add_sitemap_items( $sitemap_added_items ) {
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/seguros/seguros/tabla/primasnetascobradas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/seguros/seguros/tabla/sinistrostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/seguros/seguros/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/seguros/seguros/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/seguros/seguros/tabla/sinistroscobradosprimasnetascobradas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/seguros/seguros/tabla/sinistrosincurridosprimasdevengadas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/seguros/seguros/tabla/indicecoberturareservas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/portafoliosinversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/pasivosfinancierosdirectos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/honorarios</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/resultadooperacionfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/actimprpatrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/valores/valores/tabla/suficienciapatrimonial</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/operacionescredito</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/otroscreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/depositosinterbancarios</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/tb/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/operacionescredito</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/otroscreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/depositosinterbancarios</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/br/financieras/bm/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/ins/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/ins/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/ins/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/ins/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/ins/tabla/ingresosoperacionales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/ins/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/bn/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/bn/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/bn/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/bn/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/bn/tabla/ingresosoperacionales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/bn/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/be/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/be/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/be/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/be/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/be/tabla/ingresosoperacionales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/financieras/be/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/sg/sgc/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/sg/sgc/tabla/totalpasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/sg/sgc/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/sg/sgc/tabla/resultadointegral</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/sg/sgc/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/sg/sgc/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/vei/agv/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/vei/agv/tabla/totalpasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/vei/agv/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/vei/agv/tabla/resultadoporintermediacion</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/vei/agv/tabla/resultadodelejercicio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/vei/agv/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ch/vei/agv/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/instrumentofinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ins/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/instrumentofinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/bn/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/instrumentofinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cf/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/instrumentofinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/co/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/instrumentofinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/cr/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/instrumentofinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ecredito/ie/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/creditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/endeudamiento</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/cac/co/tabla/activosproductivosactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpo/fpo/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpo/fpo/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpo/fpo/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpo/fpo/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpo/fpo/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ces/ces/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ces/ces/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ces/ces/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ces/ces/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/ces/ces/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpv/fpv/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpv/fpv/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpv/fpv/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpv/fpv/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpv/fpv/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fpv/fpv/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fra/fra/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fra/fra/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fra/fra/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fra/fra/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fra/fra/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fra/fra/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/fra/fra/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/scv/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/scv/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/scv/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/scv/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/scv/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/scv/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/cb/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/cb/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/cb/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/cb/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/cb/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/cb/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/sai/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/sai/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/sai/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/sai/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/sai/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/sai/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/oin/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/oin/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/oin/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/oin/tabla/ingresosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/oin/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/co/itv/oin/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/carteracreditosdia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/inversionestitulosvalores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/activosimproductivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/pasivoscosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/patrimonioajustado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/inginterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/gtosinterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/gtosadministracion</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/actinterpascosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ins/tabla/utilidadopergtosadmin</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/carteracreditosdia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/inversionestitulosvalores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/activosimproductivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/pasivoscosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/patrimonioajustado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/inginterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/gtosinterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/gtosadministracion</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/actinterpascosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/bp/tabla/utilidadopergtosadmin</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/carteracreditosdia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/inversionestitulosvalores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/activosimproductivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/pasivoscosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/patrimonioajustado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/inginterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/gtosinterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/gtosadministracion</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/actinterpascosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ef/tabla/utilidadopergtosadmin</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/carteracreditosdia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/inversionestitulosvalores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/activosimproductivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/pasivoscosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/patrimonioajustado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/inginterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/gtosinterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/gtosadministracion</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/actinterpascosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/ec/tabla/utilidadopergtosadmin</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/carteracreditosdia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/inversionestitulosvalores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/activosimproductivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/pasivoscosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/patrimonioajustado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/inginterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/gtosinterfinanciera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/gtosadministracion</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/actinterpascosto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/cr/ifin/sfn/tabla/utilidadopergtosadmin</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/an/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/an/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/an/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/an/tabla/primas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/an/tabla/siniestros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/an/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/ins/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/ins/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/ins/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/ins/tabla/primas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/ins/tabla/siniestros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/seg/ins/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/ganancias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coop/cac/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/coopt/cact/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/ins/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/ins/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/ins/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/ins/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/tb/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/tb/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/tb/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ec/ifin/tb/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ins/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ins/tabla/inversionestemporarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ins/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ins/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/bm/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/bm/tabla/inversionestemporarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/bm/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/bm/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/co/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/co/tabla/inversionestemporarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/co/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/co/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ifd/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ifd/tabla/inversionestemporarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ifd/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/financieras/ifd/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/emi/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ag/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/com/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/con/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ega/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/ind/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/inm/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/min/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/or/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/osf/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/pet/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/resultadooperativo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/bo/emisores/tt/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/ins/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/ins/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/ins/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/ins/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/ins/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/tb/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/tb/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/tb/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/tb/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/tb/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bc/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bc/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bc/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bc/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bc/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/be/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/be/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/be/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/be/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/be/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/sa/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/sa/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/sa/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/sa/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/sa/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bd/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bd/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bd/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bd/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ifin/bd/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/seg/seg/tabla/inversionesfinancieras</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/seg/seg/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/seg/seg/tabla/reservastecnicas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/seg/seg/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/seg/seg/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/seg/seg/tabla/siniestralidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/afp/afp/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/afp/afp/tabla/pasivocorriente</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/afp/afp/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/afp/afp/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/afp/afp/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/afp/afp/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/fond/fon/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/fond/fon/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/fond/fon/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/fond/fon/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/fond/fon/tabla/egresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/fond/fon/tabla/resultadoejercicio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/val/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/val/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/val/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/val/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/val/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/val/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/val/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/alm/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/alm/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/alm/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/alm/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/alm/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/alm/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/alm/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/bv/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/bv/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/bv/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/bv/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/bv/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/bv/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/bv/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cd/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cd/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cd/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cd/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cd/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cd/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cd/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cb/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cb/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cb/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cb/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cb/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cb/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/cb/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/tit/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/tit/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/tit/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/tit/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/tit/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/tit/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/val/tit/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ent/ent/tabla/inversionesfinancieras</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ent/ent/tabla/recursos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ent/ent/tabla/obligacionesterceros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ent/ent/tabla/obligacionespropias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/sa/ent/ent/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/prestamosyanticipos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/totalpasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/totalpatrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ins/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/prestamosyanticipos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/totalpasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/totalpatrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/tb/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/prestamosyanticipos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/totalpasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/totalpatrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/ca/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/prestamosyanticipos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/totalpasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/totalpatrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/es/ifin/cc/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/aa/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/aa/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/aa/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/aa/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/aa/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/aa/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bc/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bc/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bc/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bc/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bc/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bc/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bf/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bf/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bf/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bf/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bf/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bf/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/be/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/be/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/be/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/be/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/be/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/be/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/nm/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/nm/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/nm/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/nm/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/nm/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/nm/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bn/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bn/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bn/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bn/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bn/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/bn/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/ca/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/ca/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/ca/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/ca/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/ca/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/ca/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/sf/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/sf/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/sf/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/sf/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/sf/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ee/ifin/sf/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/creditosobtenidos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/margen</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/ganancia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/alm/ins/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/bol/ins/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/bol/ins/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/bol/ins/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/bol/ins/tabla/capitalcontables</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/bol/ins/tabla/ganancianeta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/bol/ins/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/bol/ins/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/fz/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/fz/tabla/primascobrar</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/fz/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/fz/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/fz/tabla/totalprimas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/fz/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/sg/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/sg/tabla/primascobrar</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/sg/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/sg/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/sg/tabla/totalprimas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/sg/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/ins/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/ins/tabla/primascobrar</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/ins/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/ins/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/ins/tabla/totalprimas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/seg/ins/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/disponibilidades</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/carteradecreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/obligacionesdepositarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/margenoperacionalneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/utilidadbruta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/cobertura</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ins/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/disponibilidades</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/carteradecreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/obligacionesdepositarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/margenoperacionalneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/utilidadbruta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/cobertura</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/tb/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/disponibilidades</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/carteradecreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/obligacionesdepositarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/margenoperacionalneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/utilidadbruta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/cobertura</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/os/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/disponibilidades</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/carteradecreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/obligacionesdepositarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/margenoperacionalneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/utilidadbruta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/cobertura</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ee/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/disponibilidades</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/carteradecreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/obligacionesdepositarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/margenoperacionalneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/utilidadbruta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/cobertura</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/ef/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/disponibilidades</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/carteradecreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/obligacionesdepositarias</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/margenoperacionalneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/utilidadbruta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/cobertura</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/gu/ifin/oi/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/alm/alm/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/alm/alm/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/alm/alm/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/alm/alm/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/alm/alm/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/alm/alm/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/alm/alm/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/cdc/cdc/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/cdc/cdc/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/cdc/cdc/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/cdc/cdc/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/cdc/cdc/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/cdc/cdc/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/cdc/cdc/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/egresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/primas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/siniestros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/seg/seg/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/prstamosdescuentosnegociaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/apalancamiento</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/ins/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/prstamosdescuentosnegociaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/apalancamiento</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/bc/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/prstamosdescuentosnegociaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/utilidadneta</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/apalancamiento</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/ifin/sf/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/opdfs/opdf/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/otdc/ptdc/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ho/srdd/rem/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/captaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/ins/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/captaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bd/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/captaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/capital</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/gastos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/liquidez</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/ifin/bm/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/afores/afo/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/afores/afo/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/afores/afo/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/afores/afo/tabla/resultadointegral</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/afores/afo/tabla/solvencia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/afores/afo/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/afores/afo/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofipos/sof/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofipos/sof/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofipos/sof/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofipos/sof/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofipos/sof/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofipos/sof/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/alm/alm/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/alm/alm/tabla/totalcarteradecreditoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/alm/alm/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/alm/alm/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/alm/alm/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/cc/cc/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/cc/cc/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/cc/cc/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/cc/cc/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofomes/sof/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofomes/sof/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofomes/sof/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/sofomes/sof/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/socaps/soc/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/socaps/soc/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/socaps/soc/tabla/capitalcontable</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/socaps/soc/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/socaps/soc/tabla/imor</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/mx/socaps/soc/tabla/niveldecapitalizacion</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/margenfinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/ins/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/margenfinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/bc/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/obligaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/margenfinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ifin/fi/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/al/al/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/al/al/tabla/activomaterial</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/al/al/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/al/al/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/al/al/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/al/al/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/al/al/tabla/mercanciasalmacenadas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/seg/seg/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/seg/seg/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/seg/seg/tabla/reservas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/seg/seg/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/seg/seg/tabla/primasnetas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/seg/seg/tabla/margenneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/val/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/val/tabla/inversionescostoamortizado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/val/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/val/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/val/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/val/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/val/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/af/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/af/tabla/inversionescostoamortizado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/af/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/af/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/af/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/af/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/af/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/bv/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/bv/tabla/inversionescostoamortizado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/bv/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/bv/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/bv/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/bv/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/bv/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/cv/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/cv/tabla/inversionescostoamortizado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/cv/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/cv/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/cv/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/cv/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/cv/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/pb/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/pb/tabla/inversionescostoamortizado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/pb/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/pb/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/pb/tabla/resultado</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/pb/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ni/ival/pb/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/carteracrediticia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/prestamoslocales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/inversionesvalores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/ingintereses</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/egrgenerales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/actliquidosdepositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/patrimonioactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/ingintereses</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/egrgenerales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/actliquidosdepositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/cbi/tabla/patrimonioactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/carteracrediticia</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/prestamoslocales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/inversionesvalores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/ingintereses</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/egrgenerales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/actliquidosdepositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/patrimonioactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/ingintereses</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/egrgenerales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/resultadoneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/actliquidosdepositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pa/snc/sbn/tabla/patrimonioactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/colocacionesprestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/ingresosfinancieros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/margenfinancierobruto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/gastosadministrativos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/resultadodistribuir</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/sf/tabla/patrimonioactivocontigentes</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/colocacionesprestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/ingresosfinancieros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/margenfinancierobruto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/gastosadministrativos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/resultadodistribuir</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/if/tabla/patrimonioactivocontigentes</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/activototal</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/colocacionesprestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/inversiones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/ingresosfinancieros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/margenfinancierobruto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/gastosadministrativos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/resultadodistribuir</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/if/tb/tabla/patrimonioactivocontigentes</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/seg/seg/tabla/totalactivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/seg/seg/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/seg/seg/tabla/resultadodelejercicio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/seg/seg/tabla/patrimonioneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/seg/seg/tabla/totalingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/seg/seg/tabla/totalegresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/cc/cc/tabla/totalactivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/cc/cc/tabla/totalpasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/cc/cc/tabla/patrimonioneto</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/cc/cc/tabla/margenfinanciero</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/cc/cc/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/py/cc/cc/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/sf/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/sf/tabla/creditosnetos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/sf/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/sf/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/sf/tabla/depositosahorro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/sf/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/bm/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/bm/tabla/creditosnetos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/bm/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/bm/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/bm/tabla/depositosahorro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/bm/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cm/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cm/tabla/creditosnetos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cm/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cm/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cm/tabla/depositosahorro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cm/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cr/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cr/tabla/creditosnetos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cr/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cr/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cr/tabla/depositosahorro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/cr/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ef/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ef/tabla/creditosnetos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ef/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ef/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ef/tabla/depositosahorro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ef/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ee/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ee/tabla/creditosnetos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ee/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ee/tabla/depositosvista</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ee/tabla/depositosahorro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/if/ee/tabla/depositosplazo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/activo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/pasivo</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/primas</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/siniestros</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/utilidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/pe/seg/seg/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/margen</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/disponibilidadescaptaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/sf/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/margen</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/disponibilidadescaptaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ag/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/margen</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/disponibilidadescaptaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/ap/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/margen</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/disponibilidadescaptaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bm/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/margen</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/disponibilidadescaptaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/bac/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/activostotales</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/carteracreditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/obligacionespublico</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/margen</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/disponibilidadescaptaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/rd/if/cc/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/resultadosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/sf/tabla/deterioro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/resultadosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/ac/tabla/deterioro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/resultadosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bo/tabla/deterioro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/cartera</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/totalpasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/resultadosdeoperaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/roe</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ur/if/bp/tabla/deterioro</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/instituciones/tabla/captaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/instituciones/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/instituciones/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/instituciones/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/instituciones/tabla/provision</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bc/tabla/creditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bc/tabla/valores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bc/tabla/captaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bc/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bc/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bc/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bc/tabla/provision</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bm/tabla/creditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bm/tabla/valores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bm/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bm/tabla/captaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bm/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bm/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bm/tabla/provision</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bu/tabla/creditos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bu/tabla/valores</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bu/tabla/captaciones</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bu/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bu/tabla/morosidad</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bu/tabla/provision</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/bu/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/egresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ban/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/activos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/prestamos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/depositos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/pasivos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/patrimonio</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/ingresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/egresos</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/resultados</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ar/financieras/ins/tabla/roa</loc>
        <lastmod>2023-03-05T19:29:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/instituciones/tabla/creditos</loc>
        <lastmod>2023-03-03T08:35:00+00:00</lastmod>
      </url>
        ";
    $sitemap_added_items .= "
      <url>
        <loc>https://finanzasdigital.com/estadisticas/ve/financieras/instituciones/tabla/valores</loc>
        <lastmod>2023-03-03T08:35:00+00:00</lastmod>
      </url>
        ";	
    return $sitemap_added_items;
}


