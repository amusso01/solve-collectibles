<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
global $wp_query;

get_header( 'shop' );


/**
	 * Hook: woocommerce_before_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 * @hooked WC_Structured_Data::generate_website_data() - 30
	 */
	do_action( 'woocommerce_before_main_content' );

    $FDcat = $wp_query->get_queried_object();
	$thumbnail_id = get_term_meta( $FDcat->term_id, 'thumbnail_id', true );
	$image = wp_get_attachment_url( $thumbnail_id );
	$secondaryTitle = get_field('title_description', $FDcat);

	$slugIndividuals = $FDcat->slug;
	$slugIndividualsArray = explode( '-', $slugIndividuals);
	$slugIndividuals = $slugIndividualsArray[0] === 'individuals' ? $slugIndividuals : false ;

	$term = get_queried_object();
	$isParent = $term->parent === 0;

	?>
	<header class="woocommerce-products-header fd-woo__archive-header">
		<!-- Header Image -->
		<?php  if(!is_product_tag()) :?>
			<div class="woo-products-header__image">
				<img src="<?php echo $image ?>" alt="<?php echo  $FDcat->name ?>">
			</div>
		<?php else: ?>
			<?php 
			$tagImage = get_field('thumbnail', 'product_tag_'.$FDcat->term_id ) ;
			$tagImageUrl = wp_get_attachment_image( $tagImage, 'medium' );
			?>
			<div class="woo-products-header__image">
				<?php echo $tagImageUrl ?>
			</div>
		<?php endif; ?>
		<!-- Header description -->
		<?php if($term->parent === 0) : ?>
			<?php  if(!is_product_tag()) :?>
				<div class="woo-products-header__description">
					<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
					<p class="subTitle"><?php echo $secondaryTitle ?></p>
					<?php
					/**
					 * Hook: woocommerce_archive_description.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );
					?>
				</div>
			<?php else: ?>
				<div class="woo-products-header__description">
					<?php 
					$parentCatName = get_term($FDcat->parent, 'product_tag');	
					?>
					<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
					<?php
					/**
					 * Hook: woocommerce_archive_description.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );
					?>
				</div>
			<?php endif; ?>
		<?php elseif(is_product_category( array( 'individuals', $slugIndividuals ) )) : ?>
			<?php 
			$parentCatName = get_term($FDcat->parent, 'product_cat');	
			?>
			<div class="woo-products-header__description">
				<h1 class="woocommerce-products-header__title page-title"><?php echo $parentCatName->name ?></h1>
				<p class="subTitle">Individual Cards</p>
			<?php
			/**
			 * Hook: woocommerce_archive_description.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
			?>
		</div>
		<?php else : ?>
			<div class="woo-products-header__description">
				<?php 
				$parentCatName = get_term($FDcat->parent, 'product_cat');	
				?>
				<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				<p class="subTitle"><?php echo $parentCatName->name ?></p>
				<?php
				/**
				 * Hook: woocommerce_archive_description.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				do_action( 'woocommerce_archive_description' );
				?>
			</div>
		<?php endif; ?>
	</header>
	<?php


		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );

        	// ARCHIVE NAV BAR ANCHOR LINK
	
		if ( $term->parent === 0  ) : 
			wc_get_template_part( 'custom/custom', 'archive-anchor-nav-rewards' ); 		
		else :
			wc_get_template_part( 'custom/custom', 'rewards-single-anchor-nav' ); 
		endif;
	?>

	<!-- PACKS -->
	<?php if($isParent && $term->parent !== 2567 ) { ?>

    <section id="packs-list" class="fd-woo__shop-grid fd-woo__shop-grid-tag">
    <?php
    woocommerce_product_loop_start();

    if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();
            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action( 'woocommerce_shop_loop' );

            wc_get_template_part( 'content', 'product' );
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action( 'woocommerce_after_shop_loop' );

    ?>
    </section>  

<!-- <?php echo do_shortcode( '[ajax_load_more post_type="product" columns="4" container_type="ul" css_classes="products" posts_per_page="4" transition="fade"  taxonomy="product_cat" taxonomy_terms="'.$FDcat->name.'" taxonomy_operator="IN" button_label=""]' ) ?> -->
<?php } else{ ?>
<section  class="fd-woo__shop-grid fd-woo__shop-grid-sub-category">
<?php
    woocommerce_product_loop_start();

    if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();

            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action( 'woocommerce_shop_loop' );

            wc_get_template_part( 'custom/custom', 'product' );
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action( 'woocommerce_after_shop_loop' );

?>
</section> <!--  fd-woo__shop-grid-individuals  -->
<?php } ?>
<?php
/**
* Hook: woocommerce_after_main_content.
*
* @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
*/
do_action( 'woocommerce_after_main_content' );?>
        
<!-- TEAM -->
<?php if($isParent && !is_product_tag() && !is_product_category( array('new-in'))) { ?>

    <?php
    $taxonomy     = 'product_cat';
    $orderby      = 'name';  
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no  
    $title        = '';  
    $empty        = 0;
    $args = array(
        'taxonomy'     => $taxonomy,
        'orderby'      => $orderby,
        'show_count'   => $show_count,
        'pad_counts'   => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li'     => $title,
        'hide_empty'   => $empty
);
$all_categories = get_categories( $args );
foreach ($all_categories as $cat) {

if($cat->category_parent == 0) {
    $category_id = $cat->term_id;    
    $slugBuild = 'individuals-'.$FDcat->slug;
    $term_objects = get_term_by( 'slug', 'individuals', $taxonomy );
    if($term_objects){
        $term_ids[] = (int) $term_objects->term_id;   	
    }
    $term_objects = get_term_by( 'slug', $slugBuild, $taxonomy );
    if($term_objects){
        $term_ids[] = (int) $term_objects->term_id;   	
    }

    $args2 = array(
            'taxonomy'     => $taxonomy,
            'child_of'     => 0,
            'parent'       => $FDcat->term_id,
            'orderby'      => $orderby,
            'show_count'   => $show_count,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'title_li'     => $title,
            'hide_empty'   => $empty,
            'exclude' => $term_ids
            
    );
    }       
}
    $sub_cats = get_categories( $args2);
if($sub_cats)  : ?>
<section id="teams-list" class="fd-woo__teams">

    <h3>Rewards Category </h3>

    <ul class="fd-teams-grid">

<?php 	foreach($sub_cats as $sub_category) : 
            $teamThumbnail_id = get_term_meta( $sub_category->term_id, 'thumbnail_id', true );
            $teamImage        = wp_get_attachment_image_src( $teamThumbnail_id , 'thumbnail')[0];

?>
        <li><a href="<?php echo get_term_link($sub_category->slug, 'product_cat'); ?>" title="<?php echo  $sub_category->name ?>"> <img src="<?php echo 	$teamImage  ?>" alt="<?php echo  $sub_category->name ?>"><p><?php echo $sub_category->name ?></p></a></li>
    <?php endforeach; ?>

    </ul>
</section>
<?php endif; ?>

<?php } 

get_footer( 'shop' );