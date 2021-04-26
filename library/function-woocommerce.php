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