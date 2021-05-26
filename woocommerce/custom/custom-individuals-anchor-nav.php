<?php
/**
* 
*/

defined( 'ABSPATH' ) || exit;

?>

<section class="fd-woo__individuals-anchor">

    <?php do_action( 'fd_sort_by' ) ?>

    <div class="fd-filter-button">
        <p id="fdFilterButton">FILTER <span><?php get_template_part( 'svg-template/svg', 'down-carret' ) ?></span></p>
    </div>

    <div id="fdFilter" class="fd-filter">
        <div class="filter-close">X</div>
        <?php echo do_shortcode('[wpf-filters id=1]') ?>   
    </div>

</section>