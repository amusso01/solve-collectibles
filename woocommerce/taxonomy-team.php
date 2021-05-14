<?php
/**
 * The Template for displaying team archives, 
 *
 */

defined( 'ABSPATH' ) || exit;
global $wp_query;
$FDcat = $wp_query->get_queried_object();

$image = get_field('thumbnail', $FDcat);
$teamImageUrl = wp_get_attachment_image( $image, 'medium' );

$slugIndividuals = $FDcat->slug;
$slugIndividualsArray = explode( '-', $slugIndividuals);
$slugIndividuals = $slugIndividualsArray[0] === 'individuals' ? $slugIndividuals : false ;

$term = get_queried_object();
$isParent = $term->parent === 0;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header fd-woo__archive-header">
	<!-- Header Image -->
    <div class="woo-products-header__image">
        <?php echo $teamImageUrl ?>
    </div>
    <!-- Header description -->
    <div class="woo-products-header__description">
        <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
        <?php
        /**
         * Hook: woocommerce_archive_description.
         *
         * @hooked woocommerce_taxonomy_archive_description - 10
         * @hooked woocommerce_product_archive_description - 10
         */
        do_action( 'woocommerce_archive_description' );
        ?>
    </div>
</header>
<?php
	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );
?>


<?php
// ARCHIVE NAV BAR ANCHOR LINK
wc_get_template_part( 'custom/custom', 'individuals-anchor-nav' ); 
?>


<!-- CARDS -->
<section  class="fd-woo__shop-grid fd-woo__shop-grid-individuals">
	<?php
		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}

		woocommerce_product_loop_end();

		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );

	?>
	</section> <!--  fd-woo__shop-grid-individuals  -->

<?php 
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );