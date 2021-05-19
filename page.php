<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package foundry
 */

get_header();
?>
<header class="default-template__header">
    <h1><?php echo get_the_title() ?></h1>
</header>


<main role="main" class="site-main default-main page-main">
<?php

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		
		the_content(); 


	endwhile; // End of the loop.

else :

	get_template_part( 'template-parts/content', 'none' );

endif;
?>

<?php if(is_page('about')) : ?>
<!-- ABOUT SECTION -->
<?php 
	$founder = get_field('the_founder');
	$affiliates = get_field('affiliates');
?>

<?php endif; ?>

</main><!-- #main -->
<section class="about-founder">
	<div class="founder-image founder-item" style="background-image:url(<?php echo $founder['image']['url'] ?>);">
		
	</div>
	<div class="founder-meet founder-item">
		<h3>MEET THE FOUNDER!</h3>
		<p class="founder-name">Luke Ambler</p>
		<p><?php echo $founder['meet_the_founder'] ?></p>
	</div>
	<div class="founder-info founder-item">
		<p><span>Team he supports:</span> <?php echo $founder['info']['team'] ?></p>
		<p><span>Favourite current player:</span><?php echo $founder['info']['favourite_player'] ?></p>
		<p><span>Favourite Ex player:</span><?php echo $founder['info']['favourite_ex_player'] ?></p>
		<p><span>Messi or Ronaldo:</span><?php echo $founder['info']['messi_or_ronaldo'] ?></p>
		<p><span>Premier League Champions Prediction:</span><?php echo $founder['info']['premiership_prediction'] ?></p>
		<p><span>Favourite football card collection:</span><?php echo $founder['info']['favourite_card_collection'] ?></p>
	</div>
</section>

<section class="our-affiliates">
	<h3>Our Affiliates</h3>
	<div class="our-affiliates__grid">
		<?php foreach($affiliates as $affliate) : ?>
			<div class="affiliate-item">
				<div class="img">
					<img src="<?php echo $affliate['image']['sizes']['thumbnail'] ?>" alt="<?php echo $affliate['name'] ?> logo">
					<p><?php echo $affliate['name'] ?></p>
				</div>
				<div class="description">
					<p><?php echo $affliate['description'] ?></p>
				</div>
				<div class="info">
					<p><span>Team he supports:</span> <?php echo $affliate['team'] ?></p>
					<p><span>Favourite current player:</span><?php echo $affliate['favourite_player'] ?></p>
					<p><span>Favourite Ex player:</span><?php echo $affliate['favourite_ex_player'] ?></p>
					<p><span>Messi or Ronaldo:</span><?php echo $affliate['messi_or_ronaldo'] ?></p>
					<p><span>Premier League Champions Prediction:</span><?php echo $affliate['premiership_prediction'] ?></p>
					<p><span>Favourite football card collection:</span><?php echo $affliate['favourite_card_collection'] ?></p>
				</div>

			</div>
		<?php endforeach; ?>
	</div>
</section>
<?php
get_footer();
