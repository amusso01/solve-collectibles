<?php
/**
 * Template Name: Cart page
 *
 * The template for displaying the cart page.
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

<header class="cart-template__header">
    <h1><?php echo get_the_title() ?></h1>
</header>

<main role="main" class="cart-main page-main">
    <?php the_content(); ?>
</main>

<?php get_footer(); ?>