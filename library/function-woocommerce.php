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
		// add_theme_support( 'wc-product-gallery-slider' );

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

// CREATE A TEAM TAXONOMY
add_action( 'init', 'custom_taxonomy_Team' );
function custom_taxonomy_Team()  {
$labels = array(
    'name'                       => 'Teams',
    'singular_name'              => 'Team',
    'menu_name'                  => 'Team',
    'all_items'                  => 'All Teams',
    'parent_item'                => 'Parent Team',
    'parent_item_colon'          => 'Parent Team:',
    'new_item_name'              => 'New Team Name',
    'add_new_item'               => 'Add New Team',
    'edit_item'                  => 'Edit Team',
    'update_item'                => 'Update Team',
    'separate_items_with_commas' => 'Separate Team with commas',
    'search_items'               => 'Search Teams',
    'add_or_remove_items'        => 'Add or remove Teams',
    'choose_from_most_used'      => 'Choose from the most used Teams',
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
	'query_var'					 => true,
);
register_taxonomy( 'team', 'product', $args );
register_taxonomy_for_object_type( 'team', 'product' );
}


add_action( 'woocommerce_after_add_to_cart_quantity', 'ts_quantity_plus_sign' );

function ts_quantity_plus_sign() {
echo '<button type="button" class="plus" >+</button>';
}

add_action( 'woocommerce_before_add_to_cart_quantity', 'ts_quantity_minus_sign' );

function ts_quantity_minus_sign() {
echo '<button type="button" class="minus" >-</button>';
}

add_action( 'wp_footer', 'ts_quantity_plus_minus' );

function ts_quantity_plus_minus() {
// To run this on the single product page
if ( ! is_product() ) return;
?>
<script type="text/javascript">

jQuery(document).ready(function($){

$('form.cart').on( 'click', 'button.plus, button.minus', function() {

// Get current quantity values
var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
var val = parseFloat(qty.val());
var max = parseFloat(qty.attr( 'max' ));
var min = parseFloat(qty.attr( 'min' ));
var step = parseFloat(qty.attr( 'step' ));

// Change the value if plus or minus
if ( $( this ).is( '.plus' ) ) {
if ( max && ( max <= val ) ) {
qty.val( max );
}
else {
qty.val( val + step );
}
}
else {
if ( min && ( min >= val ) ) {
qty.val( min );
}
else if ( val > 1 ) {
qty.val( val - step );
}
}

});

});

</script>
<?php
}

// get next and prev products
// Author: Georgy Bunin (bunin.co.il@gmail.com)
// forked from https://gist.github.com/2176823
function ShowLinkToProduct($post_id, $categories_as_array, $label, $is_prev) {
    // get post according post id
    $query_args = array( 'post__in' => array($post_id), 'posts_per_page' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $categories_as_array
        )));
    $r_single = new WP_Query($query_args);
    if ($r_single->have_posts()) {
        $r_single->the_post();
        global $product;
		$cardShortcode = get_field('card_shortcode' , $product->get_id());
    ?>
    <ul class="product_list_widget <?php echo $is_prev ? 'prev' : 'next'; ?>">
        <li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
			<?php if($is_prev): ?>
				<span class="arrow"><?php get_template_part( 'svg-template/svg', 'right-arrow' ) ?></span>
				<?php echo $label; ?>
			<?php endif; ?>            

            <?php if ( get_the_title() ) {
					echo '<span class="shortcode" >'.$cardShortcode.' </span>';
					echo '<span class="title">'. get_the_title().' </span>';
			}  else {
				the_ID(); }?>
			<?php if(!$is_prev): ?>
				<?php echo $label; ?>
				<span class="arrow"><?php get_template_part( 'svg-template/svg', 'right-arrow' ) ?></span>
			<?php endif; ?>
        </a></li>
    </ul>
    <?php
        wp_reset_query();
    }
}

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

/* OUT OF STOCK WORDING UPDATES */
add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability( $availability, $_product ) {

    if ( ! $_product->is_in_stock() ) {
        $availability['availability'] = __('BE BACK SOON', 'woocommerce');
    }
    return $availability;
}

// For Woocommerce version 3 and above only
add_filter( 'woocommerce_loop_add_to_cart_link', 'filter_loop_add_to_cart_link', 20, 3 );
function filter_loop_add_to_cart_link( $button, $product, $args = array() ) {
    if( $product->is_in_stock() ) return $button;

    // HERE set your button text (when product is not on stock)
    $button_text = __('BE BACK SOON', 'woocommerce');

    return sprintf( '<a class="button disabled" style="%s">%s</a>', '', $button_text );
}

/**
* Add custom action for sort by
*/
add_action( 'fd_sort_by', 'fdAddSorting' );
function fdAddSorting(){
	add_action( 'fd_sort_by', 'woocommerce_catalog_ordering', 30 );
}

/**
* Change checkout wording
*/
function woocommerce_button_proceed_to_checkout() { ?>
	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
	<?php esc_html_e( 'PROCEED TO CHECKOUT', 'woocommerce' ); ?>
	</a>
	<?php
   }


/**
* Change sort by wording
*/
add_filter('woocommerce_catalog_orderby', 'wc_customize_product_sorting');

function wc_customize_product_sorting($sorting_options){
    $sorting_options = array(
        'menu_order' => __( 'Sort', 'woocommerce' ),
        'popularity' => __( 'Popularity', 'woocommerce' ),
        'rating'     => __( 'Average rating', 'woocommerce' ),
        'date'       => __( 'Latest', 'woocommerce' ),
        'price'      => __( 'Price: low to high', 'woocommerce' ),
        'price-desc' => __( 'Price: high to low', 'woocommerce' ),
    );

    return $sorting_options;
}

/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = -1;
  return $cols;
}

/**
 * @snippet       Rename "Returning Customer?" @ Checkout Page - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=21719
 * @author        Rodolfo Melogli
 * @compatible    WC 3.5.4
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_filter( 'woocommerce_checkout_login_message', 'bbloomer_return_customer_message' );
 
function bbloomer_return_customer_message() {
return 'Already have an account?';
}

/**
* Modify checkout field
*/
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
	// Billing
     $fields['billing']['billing_first_name']['placeholder'] = 'First Name';
     $fields['billing']['billing_last_name']['placeholder'] = 'Last Name';
     $fields['billing']['billing_company']['placeholder'] = 'Company Name';
     $fields['billing']['billing_city']['placeholder'] = 'Town / City';
     $fields['billing']['billing_postcode']['placeholder'] = 'Postcode';
     $fields['billing']['billing_state']['placeholder'] = 'County';
     $fields['billing']['billing_phone']['placeholder'] = 'Phone';
     $fields['billing']['billing_email']['placeholder'] = 'Email';
	 unset($fields['billing']['billing_first_name']['label']);
	 unset($fields['billing']['billing_last_name']['label']);
	 unset($fields['billing']['billing_company']['label']);
	 unset($fields['billing']['billing_city']['label']);
	 unset($fields['billing']['billing_postcode']['label']);
	 unset($fields['billing']['billing_state']['label']);
	 unset($fields['billing']['billing_phone']['label']);
	 unset($fields['billing']['billing_email']['label']);
	 unset($fields['billing']['billing_address_1']['label']);
	 unset($fields['billing']['billing_country']['label']);
	// Shipping
		$fields['shipping']['shipping_first_name']['placeholder'] = 'First Name';
		$fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name';
		$fields['shipping']['shipping_company']['placeholder'] = 'Company Name';
		$fields['shipping']['shipping_city']['placeholder'] = 'Town / City';
		$fields['shipping']['shipping_postcode']['placeholder'] = 'Postcode';
		$fields['shipping']['shipping_state']['placeholder'] = 'County';
		$fields['shipping']['shipping_phone']['placeholder'] = 'Phone';
		$fields['shipping']['shipping_email']['placeholder'] = 'Email';
		unset($fields['shipping']['shippipng_first_name']['label']);
		unset($fields['shipping']['shippipng_last_name']['label']);
		unset($fields['shipping']['shippipng_company']['label']);
		unset($fields['shipping']['shippipng_city']['label']);
		unset($fields['shipping']['shippipng_postcode']['label']);
		unset($fields['shipping']['shippipng_state']['label']);
		unset($fields['shipping']['shippipng_phone']['label']);
		unset($fields['shipping']['shippipng_email']['label']);
		unset($fields['shipping']['shippipng_address_1']['label']);
		unset($fields['shipping']['shippipng_country']['label']);
	//  Order Details
     $fields['order']['order_comments']['placeholder'] = 'Additional note';
	 unset($fields['order']['order_comments']['label']);
     return $fields;
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

/**
* Remove related products
*/
add_action('woocommerce_after_single_product_summary', 'remove_related_product' );
function remove_related_product(){
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}

/**
* Remove upsell
*/
add_action('woocommerce_after_single_product_summary', 'remove_upsell_display' );
function remove_upsell_display(){
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
}

/**
* Remove product_data_tabs
*/
add_filter( 'woocommerce_product_tabs', '__return_empty_array', 98 );

/**
* Remove single excerpt
*/
add_action('woocommerce_single_product_summary', 'remove_single_excerpt' );
function remove_single_excerpt(){
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
}


// WOOCOMMERCE ADD HTML

/**
* Add single products descritpion
*/
add_action('woocommerce_after_single_product', 'fd_add_product_description' );
function fd_add_product_description(){
	echo	'<section class="fd-single-description">';
	echo	' <h3>About this card</h3>';
	get_template_part( 'svg-template/svg', 'right-arrow' );
	the_content();
	echo	'</section>';

}

