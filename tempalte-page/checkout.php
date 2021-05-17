<?php
/**
 * Template Name: Checkout page
 *
 * The template for displaying the checkout page.
 * 
 * Template Post Type: page
 *
 * @package Strapped
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$teams = get_terms('team', array('hide_empty' => false));

get_header();
?>

<header class="checkout-template__header">
    <h1><?php echo get_the_title() ?></h1>
</header>

<main role="main" class="checkout-main page-main">
    <?php the_content(); ?>
</main>

<?php get_footer(); ?>