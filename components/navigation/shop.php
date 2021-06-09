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
    <?php if(is_user_logged_in()) : ?>
    <a href="<?php echo site_url('/product-category/rewards') ?>" title="Rewards"><?php get_template_part( 'svg-template/svg', 'rewards' ) ?></a>
    <?php endif; ?>
    <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"  title="Account"><?php get_template_part( 'svg-template/svg', 'account' ) ?></a>
    <a href="<?php echo wc_get_cart_url() ?>" title="Cart"><?php get_template_part( 'svg-template/svg', 'bag' ) ?></a>
    <a href="<?php echo site_url('/wish-list') ?>" title="Wish List"><?php get_template_part( 'svg-template/svg', 'wish' ) ?></a>
    <a href="#" class="site-header__hamburger-block" >
        <button class="site-header__item site-header__hamburger hamburger hamburger--collapse " id="hamburger" type="button">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button><!-- hamburger  -->
    </a>
</nav>