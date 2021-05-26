<?php
/**
* 
*/

defined( 'ABSPATH' ) || exit;

if ( is_product_category() ) {

    $term_id  = get_queried_object();
    $taxonomy = 'product_cat';
    $slugBuild = 'individuals-'.$term_id->slug;
    // Get subcategories of the current category
    $terms    = get_terms([
        'taxonomy'    => $taxonomy,
        'slug'       => array($slugBuild, 'individuals'),
        'parent'      => get_queried_object_id()
    ]);

    // Loop through product subcategories WP_Term Objects
    foreach ( $terms as $term ) {
        $term_link = get_term_link( $term, $taxonomy );
    }

    

}

?>
	<?php
    $FDcat = $wp_query->get_queried_object();
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
        ?>

<section class="fd-woo__archive-anchor">

    <?php if ( wc_get_loop_prop( 'total' ) ) : ?>
        <a class="anchor-item" href="#packs-list">PACKS <span> <?php get_template_part( 'svg-template/svg', 'down-arrow' ) ?></span></a>
    <?php endif; ?>


   <?php if($sub_cats)  : ?>
        <a class="anchor-item" href="#teams-list">TEAMS <span>        <?php get_template_part( 'svg-template/svg', 'down-arrow' ) ?></span></a>
    <?php endif; ?>


<?php if($terms)  : ?>
        <a class="anchor-item" href="<?php echo $term_link ?>">Individual cards <span>        <?php get_template_part( 'svg-template/svg', 'right-arrow' ) ?></span></a>
<?php endif; ?>
  

</section>