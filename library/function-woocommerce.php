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
  $new_min = '';
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




/**
* Qty Selector
*/
add_action( 'woocommerce_after_add_to_cart_quantity', 'ts_quantity_plus_sign' );

function ts_quantity_plus_sign() {
echo '<button type="button" class="plus" title="Maximum limit of 3" >+</button>';
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

var inputQty = $('form.cart input.qty');
var btnPlus = $('form.cart button.plus');
var btnMinus = $('form.cart button.minus');


if(inputQty.attr('type') === 'hidden'){
	console.log( btnMinus);
	btnMinus.css({display: "none"});
	btnPlus.css({display: "none"});
}

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
    ?>
    <ul class="product_list_widget <?php echo $is_prev ? 'prev' : 'next'; ?>">
        <li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
			<?php if($is_prev): ?>
				<span class="arrow"><?php get_template_part( 'svg-template/svg', 'right-arrow' ) ?></span>
				<?php echo $label; ?>
			<?php endif; ?>            

            <?php if ( get_the_title() ) {
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
  $cols = 56;
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
return 'Already have an account? </br> <span> CLICK HERE </span>';
}



/**
*  WC IMAGE MODIFY single image
*/
// remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
// add_action( 'woocommerce_before_shop_loop_item_title', 'custom_loop_product_thumbnail', 10 );
// function custom_loop_product_thumbnail() {
//     global $product;
//     $size = 'woocommerce_thumbnail';
// 	$image_id  = $product->get_image_id();
// 	$image_url = wp_get_attachment_image_url( $image_id, $size );

// 	echo '<img src="https://www.foundrydigital.co.uk/wp-content/themes/foundry/img/Spinner.gif" class="lozad"  data-src="'.$image_url.'" >';
// }


/**
*  WC IMAGE MODIFY single image
*/
function fd_wc_get_gallery_image_html( $attachment_id, $main_image = false ) {
	$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
	$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
	$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
	$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
	$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
	$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
	$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
	$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
	$image             = wp_get_attachment_image(
	  $attachment_id,
	  $image_size,
	  false,
	  apply_filters(
		'woocommerce_gallery_image_html_attachment_image_params',
		array(
		  'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
		  'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
		  'data-src'                => esc_url( $full_src[0] ),
		  'data-large_image'        => esc_url( $full_src[0] ),
		  'data-large_image_width'  => esc_attr( $full_src[1] ),
		  'data-large_image_height' => esc_attr( $full_src[2] ),
		  'class'                   => esc_attr( $main_image ? 'wp-post-image lozad' : 'lozad' ),
		),
		$attachment_id,
		$image_size,
		$main_image
	  )
	);
  
	return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>';
  }


/**
*  Redirect shop to home
*/
function custom_shop_page_redirect() {
    if( is_shop() && !is_search()){
        wp_redirect( site_url( '/' ) );
        exit();
    }
}
add_action( 'template_redirect', 'custom_shop_page_redirect' );


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



/**
* Decimal displaying / THIS ISUUE HAS BEEN FIXED REMOVING CURRENCY PLUGIN
*/

// add_filter( 'formatted_woocommerce_price', 'ts_woo_decimal_price', 10, 5 );
// function ts_woo_decimal_price( $formatted_price, $price, $decimal_places, $decimal_separator, $thousand_separator ) {
// 	$unit = number_format( intval( $price ), 0, $decimal_separator, $thousand_separator );
// 	$decimal = sprintf( '%02d', ( $price - intval( $price ) ) * 100 );
// 	return $unit . '<span>' . $decimal_separator. $decimal . '</span>';
// }


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

// Redirect user not login for rewards page
add_action( 'template_redirect', 'redirect_if_user_not_logged_in' );

function redirect_if_user_not_logged_in() {

	if ( is_product_category('rewards')&& ! is_user_logged_in() ) { //example can be is_page(23) where 23 is page ID

		wp_redirect( site_url('/') ); 
 
     exit;// never forget this exit since its very important for the wp_redirect() to have the exit / die
   
   }
   
}

// Pulling shortcode in the loop title
remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );
function abChangeProductsTitle() {

    echo '<h2 class="woocommerce-loop-product__title">'. get_the_title() . '</h2>';
}


// WOOCOMMERCE ADD HTML

/**
* Add single products descritpion
*/
add_action('woocommerce_after_single_product', 'fd_add_product_description' );
function fd_add_product_description(){
	echo	'<section class="fd-single-description">';
	echo	' <h3>About this product</h3>';
	the_content();
	echo	'</section>';

}


// Alphabetical order woocommerce products
// add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );

// function custom_woocommerce_get_catalog_ordering_args( $args ) {
//     $orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

//     if ( 'alphabetical' == $orderby_value ) {
//         $args['orderby'] = 'title';
//         $args['order'] = 'ASC';
//     }

//     return $args;
// }

// add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
// add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );

// function custom_woocommerce_catalog_orderby( $sortby ) {
//     $sortby['alphabetical'] = __( 'Alphabetical' );
//     return $sortby;
// }


// MOVE OUT OF STOCK AT THE END OF THE PAGE
// From https://stackoverflow.com/questions/25113581/show-out-of-stock-products-at-the-end-in-woocommerce

// add_filter('posts_clauses', 'order_by_stock_status');
// function order_by_stock_status($posts_clauses) {
//     global $wpdb;
//     // only change query on WooCommerce loops
//     if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())) {
//         $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
//         $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
//         $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
//     }
//     return $posts_clauses;
// }

/**
 * @snippet       Sort Products By Stock Status - WooCommerce Shop
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_filter( 'woocommerce_get_catalog_ordering_args', 'bbloomer_first_sort_by_stock_amount', 9999 );
 
function bbloomer_first_sort_by_stock_amount( $args ) {
   $args['orderby'] = 'meta_value';
   $args['meta_key'] = '_stock_status';
   return $args;
}

// Alter number of search posts per page. This is to fix 500 Server problem when try to search for too many post
function pd_search_posts_per_page($query) {
	if ( $query->is_search && !is_admin()) {
			$query->set( 'posts_per_page', '5' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'ASC' );
			$query->set( 'meta_key', '_stock_status' );

			// REMOVE OUT OF STOCK FROM SEARCH
			// $query->set( 'meta_query', array(array(
			// 	'key'       => '_stock_status',
			// 	'value'     => 'outofstock',
			// 	'compare'   => 'NOT IN'
			// 	)));
	}
	return $query;
}
add_filter( 'pre_get_posts','pd_search_posts_per_page' );


add_action( 'woocommerce_before_add_to_cart_button', 'mish_before_add_to_cart_btn' );

function mish_before_add_to_cart_btn(){
	echo '<div class="fd-selector-wrapper">';
}

add_action( 'woocommerce_after_add_to_cart_quantity', 'mish_after_add_to_cart_btn' );

function mish_after_add_to_cart_btn(){
	echo '</div>';
}


// REDIRECT BEFORE CHECKOUT
// add_action('template_redirect','check_if_logged_in');
// function check_if_logged_in()
// {
// 	$pageid = get_option( 'woocommerce_checkout_page_id' );
// 	$guest = $_GET['guest'];
// 	if(!is_user_logged_in() && is_page($pageid) && $guest!= 'true')
// 	{
// 			wp_redirect(site_url('/guest-checkout/') );
// 			exit;
// 	}

// 	if(is_user_logged_in())
// 	{
// 		if(is_page(get_option( 'woocommerce_myaccount_page_id' )))
// 		{	
// 				$redirect = $_GET['redirect_to'];
// 				if (isset($redirect)) {
// 				echo '<script>window.location.href = "'.esc_url($redirect).'";</script>';
// 				}
// 		}
// 	}
// }


// ADD OUT OF STOCK ON VARIATIONS
/**
 * Disable out-of-stock variations
 *
 * Make sure you check "Manage Stock" on each variation.
 * Set the stock level to zero and in the front-end drop-down variations list
 * the variation will be greyed-out on the product drop-down
 *
 * @author Wil Brown zeropointdevelopment.com
 * @param $active
 * @param $variation
 *
 * @return boolean
 */
// function zpd_variation_is_active( $active, $variation ) {
// 	if( ! $variation->is_in_stock() ) {
// 		return false;
// 	}
// 	return $active;
// }
// add_filter( 'woocommerce_variation_is_active', 'zpd_variation_is_active', 10, 2 );

/**
 * Adds " - sold out" to the drop-down list for out-of-stock variatons.
 *
 * Make sure you check "Manage Stock" on each variation.
 * Set the stock level to zero and in the front-end drop-down variations list
 *
 * @param $option
 * @param $_
 * @param $attribute
 * @param $product
 *
 * @return mixed|string
 */
function zpd_add_sold_out_label_to_wc_product_dropdown( $option, $_, $attribute, $product ){
  if( is_product() ){
	$sold_out_text = ' - sold out';
	$variations    = $product->get_children();
	$attributes = $product->get_attributes();
	$attribute_slug = zpd_wc_get_att_slug_by_title( $attribute, $attributes );
	
	if( empty( $attribute_slug ) ) return $option;

	

	foreach ( $variations as $variation ) {
		$variation_product = wc_get_product( $variation );
		$variation_attributes = $variation_product->get_attributes(); 
		//print_r($option);
		if ( $variation_attributes['pyt-pyp'] == $option &&  !$variation_product->is_in_stock() ) {
			$option .= $sold_out_text.$variation_product->is_in_stock(); 
		}
		
	}
  }
  return $option; 
}
add_filter( 'woocommerce_variation_option_name', 'zpd_add_sold_out_label_to_wc_product_dropdown', 1, 4 );

/**
 * Returns the slug of the WooCommerce attribute taxonomy
 *
 * @param $attribute_title
 * @param $attributes
 *
 * @return int|string
 */
function zpd_wc_get_att_slug_by_title( $attribute_title, $attributes ){
	if ( empty( $attribute_title ) || empty( $attributes )) __return_empty_string();
	$att_slug = '';

	foreach( $attributes as $tax => $tax_obj ){

		if( is_object($tax_obj) ){
			
			if( $tax_obj[ 'name'] === $attribute_title ){
				$att_slug = $tax;
			}
		}
		
	}

	return $att_slug;
}