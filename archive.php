<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package foundry
 */

get_header();
?>
<header class="post-template__header">
    <h1><?php echo get_the_title(get_option('page_for_posts', true)) ?></h1>
	<?php the_content(get_option('page_for_posts', true)); ?>
</header>


<main role="main" class=" post-main archive-main">
<?php 
	$categories = get_categories();
	
?>

	<div class="post-nav">
		<nav>
			<ul>

			<?php foreach($categories as $key => $category) {
					if (is_category($category->term_id )){
						echo '<li><a class="active" href="'. get_category_link($category->term_id) .'">' . $category->name . '</a></li>';
					}else{
						echo '<li><a href="'. get_category_link($category->term_id) .'">' . $category->name . '</a></li>';
					}

				}
				?>

			</ul>
		</nav>
	</div>

<?php if ( have_posts() ) : ?>

	<div class="post-container">
	<?php
	/* Start the Loop */
	while ( have_posts() ) :
		the_post();?>
		<article>
			<a href="<?php echo get_the_permalink() ?>">
			<div class="post-image">
				<img src="<?php echo get_the_post_thumbnail_url() ?>" alt="<?php echo get_the_title() ;?> image">
				<?php $day = get_the_date( 'd'); ?>
				<?php $month = get_the_date( 'M');?>
				<div class="post-date">
					<P class="day"><?php echo $day ?></P>
					<p class="month"><?php echo $month ?></p>
				</div>
			</div>
			<header>
				<h2 class="post-title"><?php echo get_the_title() ?></h2>
				<p><?php echo get_the_excerpt(); ?></p>
			</header>
			</a>
		</article>
<?php
	endwhile;?>
	</div>
	
<?php
else :

	get_template_part( 'template-parts/content', 'none' );

endif;
?>

</main><!-- #main -->


<?php
get_footer();
