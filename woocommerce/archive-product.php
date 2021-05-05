<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
global $wp_query;
$cat = $wp_query->get_queried_object();
$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
$secondaryTitle = get_field('title_description', $cat);

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
    <div class="woo-products-header__image">
        <img src="<?php echo $image ?>" alt="<?php echo  $cat->name ?>">
    </div>
    <div class="woo-products-header__description">
        <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
		<p class="subTitle"><?php echo $secondaryTitle ?></p>
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
if ( woocommerce_product_loop() ) {

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
if(is_product_category()):
wc_get_template_part( 'custom/custom', 'archive-anchor-nav' ); 
endif;
// MOST POPULAR ACF 
$term = get_queried_object();
$mostPopular = get_field('most_popular_products', $term);

// CHECK IF IS PARENT CATEGORY
if ( $term->parent === 0  ) : ?>

<section class="fd-woo__most-popular">
	<h3>Most popular</h3>
	<div class="fd-woo__most-popular-grid">
	<?php foreach($mostPopular as $popular) :
		$productObject = wc_get_product( $popular->ID );
		?>
	
		<div class="fd-woo__most-popular-item">
			<img src="<?php echo get_the_post_thumbnail_url( $popular->ID , 'most_popular') ?>" alt="<?php echo $popular->post_title ?>">
			<div class="fd-woo__most-popular-description">
				<h2><?php echo $popular->post_title ?></h2>
				<p class="cat-title" ><?php woocommerce_page_title(); ?></p>
				<p class="price"><?php echo wc_price($productObject->get_price()); ?></p>
				<?php echo do_shortcode( '[add_to_cart id="'.$popular->ID.'" show_price="false" class="btn-woo" style=""]' ) ?>
			</div>
		</div>

	<?php endforeach;  ?>


	</div>
</section>
<?php endif; ?>

<section class="fd-woo__shop-grid">
	<h3>Packs</h3>
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
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}
?>

</section> <!--  fd-woo__shop-grid  -->

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );