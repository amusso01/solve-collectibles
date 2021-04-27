<?php
/**
 * shop Nav Nav
 * 
 * @author Andrea Musso
 * 
 * @package Foundry
 */

?>

<nav class="site-header__nav-shop">
    <a href="#"><?php get_template_part( 'svg-template/svg', 'rewards' ) ?></a>
    <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php get_template_part( 'svg-template/svg', 'account' ) ?></a>
    <a href="<?php echo wc_get_cart_url() ?>"><?php get_template_part( 'svg-template/svg', 'bag' ) ?></a>
    <a href="#"><?php get_template_part( 'svg-template/svg', 'wish' ) ?></a>
    <a href="#" class="site-header__hamburger-block" >
        <button class="site-header__item site-header__hamburger hamburger hamburger--collapse " id="hamburger" type="button">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button><!-- hamburger  -->
    </a>
</nav>