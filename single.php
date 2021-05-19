<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package foundry
 */

get_header();
?>

	<div id="primary" class="container content-area">
	<header class="single-template__header">
		<div class="single-image">
			<img src="<?php echo get_the_post_thumbnail_url() ?>" alt="<?php echo get_the_title() ?> image">
		</div>
		<div class="single-header-info">
			<h1><?php echo get_the_title() ?></h1>
			<p class="excerpt"><?php echo get_the_excerpt() ?></p>
			<p class="date"><?php echo get_the_date( 'F, j' ) ?> | by <?php echo get_the_author_meta('first_name', $post->post_author ) ?></p>
		</div>
	</header>
		<main id="main" class="single-main">
			<aside class="fd-single-share">
				<p>Share</p>
				<ul>
					<li><a href=""><?php get_template_part( 'svg-template/svg', 'facebook' ) ?></a></li>
					<li><a href=""><?php get_template_part( 'svg-template/svg', 'twitter' ) ?></a></li>
				</ul>
			</aside>
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

		

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->

	</div><!-- #primary -->

<?php
get_footer();
