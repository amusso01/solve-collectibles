<?php
/**
 * The template for displaying frontpage by default
 *
 * @author Andrea Musso
 *
 * @package foundry
 */

get_header();
?>


	
<?php get_template_part( 'components/front/hero' ); ?>



<main class="main homepage-main" role="main">

<section class="home-mostPopular">
		<h3>Popular right now</h3>
		<?php 
		$query_args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => '5',
			'tax_query' => array(
				array(
					'taxonomy'	=> 'product_cat',
					'field'	=> 'slug',
					'terms' => 'popular-right-now'
				)
			),
			'meta_key' => 'total_sales',
			'orderby' => 'meta_value_num',
			'meta_query' => array( // (array) - Custom field parameters (available with Version 3.1).
				'key' => '_stock_status',
				'value' => 'outofstock',
				'compare' => '!=',
				),
			// 'meta_query' => WC()->query->get_meta_query()
		);
	
		$best_sell_products_query = new WP_Query($query_args );
		// return $best_sell_products_query;
		woocommerce_product_loop_start();

		while ( $best_sell_products_query->have_posts() ) : $best_sell_products_query->the_post(); 
			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		endwhile;

		woocommerce_product_loop_end();	
		wp_reset_query();
		?>

	</section>



	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); // @codingStandardsIgnoreLine ?>
			<?php $featureImage = has_post_thumbnail() ? get_the_post_thumbnail_url() : false ; ?>
			<article class="home-content" style="background-image:url(<?php echo $featureImage ? $featureImage : '' ?>)">
				<div class="entry-content">
					<?php the_content() ?>
				</div>
			</article>

		<?php endwhile; ?>

	<?php else :?>

		<?php get_template_part( 'template-parts/content', 'none' );?>

	<?php endif; ?>

	<section class="shopbyCollection glide-collection">
		<?php $collections = get_field('categories_slider');  ?>
		<div class="title">
			<h3>Shop by collection</h3>
			<div class="glide__arrows" data-glide-el="controls">
				<button class="glide__arrow glide__arrow--left" data-glide-dir="<"><?php get_template_part( 'svg-template/svg', 'left-arrow-black' ) ?></button>
				<button class="glide__arrow glide__arrow--right" data-glide-dir=">"><?php get_template_part( 'svg-template/svg', 'right-arrow-black' ) ?></button>
			</div>
		</div>

		<div class="glide__track" data-glide-el="track">
			<ul class="glide__slides">
				<?php foreach($collections as $collection) : ?>
					<?php 
					$collectionImage_id = get_term_meta( $collection['category']->term_id, 'thumbnail_id', true );
					$collectionImage = wp_get_attachment_image_src( $collectionImage_id , 'thumbnail')[0];
					?>
					<li>
						<a href="<?php echo get_term_link($collection['category']->slug, 'product_cat') ?>">
						<figure>
							<img src="<?php echo $collectionImage ?>" alt="<?php echo $collection['category']->name ?>">
							<figcaption class="catName"><?php echo  $collection['category']->name ?></figcaption>
						</figure>
							<p>SHOP ALL</p>
						</a>
					</li>
				
				<?php endforeach; ?>
			
			</ul>
		</div>
	</section>
	<!-- HOME BANNER -->
	<?php $banner = get_field('home_banner'); ?>
	<section class="home-banner" style="background-image:url(<?php echo $banner['banner_image'] ?>);">
		<div class="banner-info">
			<h2><?php echo $banner['banner_title'] ?></h2>
			<p><?php echo $banner['banner_text'] ?></p>		
		</div>
		<?php if($banner['banner_cta']) : ?>		
			<a href="<?php echo $banner['banner_cta']['banner_url'] ?>" class="button"><?php echo $banner['banner_cta']['title'] ?></a>
		<?php endif; ?>
	</section>
	<section class="shopby">
		<div class="shopby-single">
			<h2>Shop by football team</h2>
			<a href="<?php echo  site_url( '/teams' ) ?>" class="button">VIEW ALL</a>
		</div>
		<div class="shopby-single">
			<h2>Who are Solve Collectables?</h2>
			<a href="<?php echo  site_url( '/about' ) ?>" class="button">DISCOVER MORE</a>
		</div>
	</section>



</main>

<?php get_footer(); ?>
