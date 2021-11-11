<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>

<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>
    <?php 
    $cat = get_the_terms( $product->get_id(), 'product_cat' );
    $parentInt = $cat[0]->parent;
    // If the product has a parent category
    if($parentInt !== 0 ) :
    ?>

    <div class="fd-meta-category">
        <h4>Collection</h4>
        <?php 

        foreach ($cat as $categoria) {

        $parent = get_ancestors( $categoria->term_id, 'product_cat' );
        foreach($parent as $parentcat){
            $parentName = get_the_category_by_ID( $parentcat);
            }
        
        }?>
        <a href="<?php echo get_term_link( $parent[0]) ?>"><?php echo $parentName ;   ?></a>
       

    </div>

    <div class="fd-meta-team">
        <h4>Team</h4>
        <?php  $team = get_the_terms( $product->get_id(), 'team' )[0];?>
        <?php if($team) : ?>
            <a href="<?php echo get_term_link($team->term_id ) ?>"><?php echo $team->name ?></a>
        <?php endif; ?>
    </div>
   
    <?php 
    // Else if is a main parent category
    else: ?>

    <div class="fd-meta-single fd-meta-category">

        <h4>Collection</h4>
        <a href="<?php echo get_term_link( $cat[0] ) ?>"><?php echo $cat[0]->name   ?></a>
    </div>

    <?php endif; ?>



	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>