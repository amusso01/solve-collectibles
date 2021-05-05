<?php
/**
* 
*/

defined( 'ABSPATH' ) || exit;

if ( is_product_category() ) {

    $term_id  = get_queried_object_id();
    $taxonomy = 'product_cat';

    // Get subcategories of the current category
    $terms    = get_terms([
        'taxonomy'    => $taxonomy,
        'slug'       => array('individuals'),
        'parent'      => get_queried_object_id()
    ]);

    // Loop through product subcategories WP_Term Objects
    foreach ( $terms as $term ) {
        $term_link = get_term_link( $term, $taxonomy );

    }
}

?>

<section class="fd-woo__archive-anchor">


        <a class="anchor-item" href="#packs-list">PACKS <span> <?php get_template_part( 'svg-template/svg', 'down-arrow' ) ?></span></a>



   
        <a class="anchor-item" href="#teams-list">TEAMS <span>        <?php get_template_part( 'svg-template/svg', 'down-arrow' ) ?></span></a>




        <a class="anchor-item" href="<?php echo $term_link ?>">Individual cards <span>        <?php get_template_part( 'svg-template/svg', 'right-arrow' ) ?></span></a>

  

</section>