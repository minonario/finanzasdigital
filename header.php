<?php
/**
 * The header for our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     Sinatra
 * @author      Sinatra Team <hello@sinatrawp.com>
 * @since       1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?><?php sinatra_schema_markup( 'html' ); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  
<div id="CookieBanner" data-version="1.1.0" data-template="custom-template" data-tone="neutral" name="CookieBanner" style="display: none;" class="is-visible-cookie-banner">
 <div id="CookieBannerOverlay"></div>
 <div id="CookieBannerNotice" lang="en" dir="ltr" role="dialog" aria-labelledby="CookieBannerTitle" aria-describedby="CookieBannerDescription" ng-non-bindable="">
    <div class="cookiebanner__main">
       <div class="cookiebanner__main__inner">
          <div class="cookiebanner__main__content">
             <p id="CookieBannerDescription" class="cookiebanner__main__description">Utilizamos cookies para ofrecerte la mejor experiencia en nuestra web.  <a href="https://finanzasdigital.com/politica-de-cookies/" title="Ver Política de Cookies">Ver nuestra Política de Cookies</a>. </p>
          </div>
          <div class="cookiebanner__buttons">
             <ul>
                <li><button class="cookiebanner__buttons__accept" id="CybotCookiebotDialogBodyLevelButtonLevelOptinAllowAll" onclick="acceptCookieConsent();">Aceptar</button></li>
                <li><button class="cookiebanner__buttons__reject" id="CustomCookiebotReject" onclick="acceptCookieConsent();">Rechazar</button></li>
             </ul>
          </div>
       </div>
    </div>
 </div>
</div>

<?php wp_body_open(); ?>

<?php do_action( 'sinatra_before_page_wrapper' ); ?>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sinatra' ); ?></a>

	<?php do_action( 'sinatra_before_masthead' ); ?>

	<header id="masthead" class="site-header" role="banner"<?php sinatra_masthead_atts(); ?><?php sinatra_schema_markup( 'header' ); ?>>
		<?php do_action( 'sinatra_header' ); ?>
		<?php do_action( 'sinatra_page_header' ); ?>
	</header><!-- #masthead .site-header -->

	<?php do_action( 'sinatra_after_masthead' ); ?>

	<?php do_action( 'sinatra_before_main' ); ?>
	<div id="main" class="site-main">

		<?php do_action( 'sinatra_main_start' ); ?>
