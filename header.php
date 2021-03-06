<?php
/**
 * Main Site Header Template
 * 
 * @author   Andrea Musso
 * 
 * @package  Foundry
 * 
 */

?>

<?php 
// Social logic
$displaySocial = get_theme_mod('display-social');

?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--=== GMT head ===-->
	<?php  WPSeed_gtm('head') ?>
    <!--=== gmt end ===-->
	<?php wp_head(); ?>

	<!-- Hotjar Tracking Code for https://www.solvecollectibles.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2963008,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
</head>

<body <?php body_class(); ?>>
<!--=== GMT body ===-->
<?php WPSeed_gtm('body') ?>
<!--=== gmt end ===-->

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'foundry' ); ?></a>
	<?php get_template_part( 'components/header/banner' ) ?>
	<header class="site-header">
		<div class="site-header__inner">
			<div class="site-header__block site-header__block__navigation">
				<?php get_template_part( 'components/header/logo' ); ?>
				<div class="site-header__mobile">
					<?php get_product_search_form(); ?> 
					<div id="backParent" class="backToParent">Back</div>
					<?php get_template_part( 'components/navigation/primary' ); ?>
				</div>
			</div>
			<div class="site-header__block site-header__block__search">
				<?php get_product_search_form(); ?> 
			</div>
			<div class="site-header__block site-header__block__nav-shop">
				<?php get_template_part( 'components/navigation/shop' ); ?>
			</div>
		</div>
	</header><!-- .site-header -->


	<div id="content" class="site-content">
