<?php
/**
* 
*/

defined( 'ABSPATH' ) || exit;

?>

<section class="fd-woo__individuals-anchor">

    <?php do_action( 'fd_sort_by' ) ?>

 

    <div id="layoutSelector" class="layout-view">
        <div class="layout-view__single" id="tiles">
            <?php get_template_part( 'svg-template/svg', 'tiles-solve' ) ?> Tiles 
        </div>
        <div class="layout-view__single" id="grid">
        <?php get_template_part( 'svg-template/svg', 'grid-solve' ) ?> Grid
        </div>
    </div>

</section>