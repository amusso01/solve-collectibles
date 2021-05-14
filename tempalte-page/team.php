<?php
/**
 * Template Name: Teams page
 *
 * The template for displaying the teams page.
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

<header class="teams-template__header">
    <h1>FOOTBALL TEAMS</h1>
    <div class="teams-content">
    <?php the_content() ?>
    </div>
</header>


<main class="main teams-main" role="main">

    <section class="teams-grid">
        <?php 
            // The Loop
        if ( $teams ) :
             foreach($teams as $team)  : ;
             $teamImage = get_field('thumbnail', $team);
             $teamImageUrl = wp_get_attachment_image( $teamImage, 'thumbnail' );
             ?>

            <div class="team">
                <a href="<?php echo get_term_link( $team->term_id )  ?>">
                <?php echo $teamImageUrl ?>
                <p class="team-name"><?php echo $team->name ?></p>
                </a>
            </div>

            <?php    
            endforeach;
        endif;
        
        // Reset Post Data
        wp_reset_postdata();
        
        ?>    
    </section>

</main>


<?php get_footer(); ?>