<?php
/**
 * Template Name: Guest Checkout
 *
 * The template for displaying the account page.
 * 
 * Template Post Type: page
 *
 * @package Strapped
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<header class="account-template__header">
    <h1>Checkout</h1>
</header>

<main role="main" class="guest-chekout-main page-main" style="max-width:870px; margin:120px auto 120px;">
    <div class="guest-chekout" style="text-align:center; margin-bottom:20px;">
        <h3>Please Create an account or login before checkout</h3>
        <h3>So you won't lose our best offers</h3>
        <a href="<?php echo site_url('/my-account?redirect_to='.site_url().'/checkout') ?>" class="btn"
            style="margin:30px 0; padding:0 25px; display:inline-flex;"
        >Login / Create an account</a>
    </div>

    <div class="guest-text guest-chekout" style="text-align:center;">
        <h4 style="margin-bottom:30px">Are you in a hurry?</h4>
        <a 
        style="padding:0 25px; display:inline-flex;"
        class="btn" onclick="window.location.href = '<?php echo site_url('/checkout') ?>?guest=true'" href="javascript:void(0); ">Checkout as guest</a>
    </div>
  
    
</main>

<?php get_footer(); ?>