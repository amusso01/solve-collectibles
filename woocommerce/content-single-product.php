<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'fd-single-product__container', $product ); ?> >

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );
		?>
	</div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>
<?php
// get next and prev products
// Author: Georgy Bunin (bunin.co.il@gmail.com)
// forked from https://gist.github.com/2176823

if ( is_singular('product') ) {
    global $post;

    // get categories
    $terms = wp_get_post_terms( $post->ID, 'product_cat' );
    foreach ( $terms as $term ) $cats_array[] = $term->term_id;

    // get all posts in current categories
    $query_args = array('posts_per_page' => -1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $cats_array
        )));
    $r = new WP_Query($query_args);

    // show next and prev only if we have 3 or more
    if ($r->post_count > 2) {

        $prev_product_id = -1;
        $next_product_id = -1;

        $found_product = false;
        $i = 0;

        $current_product_index = $i;
        $current_product_id = get_the_ID();

        if ($r->have_posts()) {
            while ($r->have_posts()) {
                $r->the_post();
                $current_id = get_the_ID();

                if ($current_id == $current_product_id) {
                    $found_product = true;
                    $current_product_index = $i;
                }

                $is_first = ($current_product_index == 0);

                if ($is_first) {
                    $prev_product_id = get_the_ID(); // if product is first then 'prev' = last product
                } else {
                    if (!$found_product && $current_id != $current_product_id) {
                        $prev_product_id = get_the_ID();
                    }
                }

                if ($i == 0) { // if product is last then 'next' = first product
                    $next_product_id = get_the_ID();
                }

                if ($found_product && $i == $current_product_index + 1) {
                    $next_product_id = get_the_ID();
                }

                $i++;
            }?>
            <div class="fd-single-pagination">
            
     

<?php 
            if ($next_product_id != -1) { ShowLinkToProduct($next_product_id, $cats_array, "PREVIOUS ", true); }
            if ($prev_product_id != -1) { ShowLinkToProduct($prev_product_id, $cats_array, "NEXT ", false); }
        }
        ?>
               </div>

        <?php 

        wp_reset_query();
    }
}
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>


<!-- RELATED PRODUCT FROM THIS CATEGORY -->
<section class="fd-related-product-category">
    <h3>More from this collection.</h3>
    <?php
    // get all posts in current categories
    $query_args = array('posts_per_page' => 5, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $cats_array
        )));
    $thisCat = new WP_Query($query_args);
    woocommerce_product_loop_start();

	while ( $thisCat->have_posts() ) : $thisCat->the_post(); 
		/**
		 * Hook: woocommerce_shop_loop.
		 */
		do_action( 'woocommerce_shop_loop' );

		wc_get_template_part( 'content', 'product' );
	endwhile;

	woocommerce_product_loop_end();	
	wp_reset_query(); ?>
</section>
<aside class="fd-single-goBack">
    <a onclick="window.history.back()" href="#"><span><?php get_template_part( 'svg-template/svg', 'right-arrow' ) ?></span> GO BACK</a>
</aside>