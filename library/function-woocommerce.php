<?php
/**
 * Add WooCommerce support
 *
 * @package foundry
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'foundry_woocommerce_support' );
if ( ! function_exists( 'foundry_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function foundry_woocommerce_support() {
		add_theme_support( 'woocommerce' );

		// Add Product Gallery support.
		// add_theme_support( 'wc-product-gallery-lightbox' );
		// add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		}
}

// Disable all stylesheets Woocomemrce
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// MIN MAX ORDER QTY
function wc_qty_input_args( $args, $product ) {
	
	$product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	
	$product_min = wc_get_product_min_limit( $product_id );
	$product_max = wc_get_product_max_limit( $product_id );	

	if ( ! empty( $product_min ) ) {
		// min is empty
		if ( false !== $product_min ) {
			$args['min_value'] = $product_min;
		}
	}

	if ( ! empty( $product_max ) ) {
		// max is empty
		if ( false !== $product_max ) {
			$args['max_value'] = $product_max;
		}
	}

	if ( $product->managing_stock() && ! $product->backorders_allowed() ) {
		$stock = $product->get_stock_quantity();

		$args['max_value'] = min( $stock, $args['max_value'] );	
	}

	return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'wc_qty_input_args', 10, 2 );

function wc_get_product_max_limit( $product_id ) {
	$qty = get_post_meta( $product_id, '_wc_max_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}
	return $limit;
}

function wc_get_product_min_limit( $product_id ) {
	$qty = get_post_meta( $product_id, '_wc_min_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}
	return $limit;
}
// SHOW QTY MIN MAX ON PRODUCT
function wc_qty_add_product_field() {

	echo '<div class="options_group">';
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_wc_min_qty_product', 
			'label'       => __( 'Minimum Quantity', 'woocommerce-max-quantity' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a minimum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity' ) 
		)
	);
	echo '</div>';

	echo '<div class="options_group">';
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_wc_max_qty_product', 
			'label'       => __( 'Maximum Quantity', 'woocommerce-max-quantity' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a maximum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity' ) 
		)
	);
	echo '</div>';	
}
add_action( 'woocommerce_product_options_inventory_product_data', 'wc_qty_add_product_field' );

// SAVE MIN MAX QTY
/*
* This function will save the value set to Minimum Quantity and Maximum Quantity options
* into _wc_min_qty_product and _wc_max_qty_product meta keys respectively
*/

function wc_qty_save_product_field( $post_id ) {
	$val_min = trim( get_post_meta( $post_id, '_wc_min_qty_product', true ) );
	$new_min = sanitize_text_field( $_POST['_wc_min_qty_product'] );

	$val_max = trim( get_post_meta( $post_id, '_wc_max_qty_product', true ) );
	$new_max = sanitize_text_field( $_POST['_wc_max_qty_product'] );
	
	if ( $val_min != $new_min ) {
		update_post_meta( $post_id, '_wc_min_qty_product', $new_min );
	}

	if ( $val_max != $new_max ) {
		update_post_meta( $post_id, '_wc_max_qty_product', $new_max );
	}
}
add_action( 'woocommerce_process_product_meta', 'wc_qty_save_product_field' );

/*
* Validating the quantity on add to cart action with the quantity of the same product available in the cart. 
*/
function wc_qty_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {

	$product_min = wc_get_product_min_limit( $product_id );
	$product_max = wc_get_product_max_limit( $product_id );

	if ( ! empty( $product_min ) ) {
		// min is empty
		if ( false !== $product_min ) {
			$new_min = $product_min;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}

	if ( ! empty( $product_max ) ) {
		// min is empty
		if ( false !== $product_max ) {
			$new_max = $product_max;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}

	$already_in_cart 	= wc_qty_get_cart_qty( $product_id );
	$product 			= wc_get_product( $product_id );
	$product_title 		= $product->get_title();
	
	if ( !is_null( $new_max ) && !empty( $already_in_cart ) ) {
		
		if ( ( $already_in_cart + $quantity ) > $new_max ) {
			// oops. too much.
			$passed = false;			

			wc_add_notice( apply_filters( 'isa_wc_max_qty_error_message_already_had', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.', 'woocommerce-max-quantity' ), 
						$new_max,
						$product_title,
						'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>',
						$already_in_cart ),
					$new_max,
					$already_in_cart ),
			'error' );

		}
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'wc_qty_add_to_cart_validation', 1, 5 );

/*
* Get the total quantity of the product available in the cart.
*/ 
function wc_qty_get_cart_qty( $product_id ) {
	global $woocommerce;
	$running_qty = 0; // iniializing quantity to 0

	// search the cart for the product in and calculate quantity.
	foreach($woocommerce->cart->get_cart() as $other_cart_item_keys => $values ) {
		if ( $product_id == $values['product_id'] ) {				
			$running_qty += (int) $values['quantity'];
		}
	}

	return $running_qty;
}


/**
* Show only parent category in Archive
*/
function exclude_product_cat_children($wp_query) {
	if ( isset ( $wp_query->query_vars['product_cat'] ) && $wp_query->is_main_query()) {
		$wp_query->set('tax_query', array(
				array (
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $wp_query->query_vars['product_cat'],
					'include_children' => false
				)
			)
		);
	}
}
add_filter('pre_get_posts', 'exclude_product_cat_children');



// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'ADD TO BAG', 'woocommerce' ); 
}

// To change add to cart text on product archives(Collection) page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
function woocommerce_custom_product_add_to_cart_text() {
    return __( 'ADD TO BAG', 'woocommerce' );
}



// WOOCOMMERCE ORDER MANIPULATE

/**
* Remove the breadcrumbs 
*/
add_action( 'init', 'woo_remove_wc_breadcrumbs' );
function woo_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

/**
* Remove result count
*/
add_action('woocommerce_before_shop_loop', 'remove_result_count' );
function remove_result_count(){
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
}

/**
* Remove catalogue ordering
*/
add_action('woocommerce_before_shop_loop', 'remove_catalog_ordering' );
function remove_catalog_ordering(){
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
}



// WOOCOMMERCE ADD HTML

