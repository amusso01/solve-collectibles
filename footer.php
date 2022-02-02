<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @author Andrea Musso
 *
 * @package foundry
 */

?>

</div><!-- #content -->

	<footer class="site-footer">
		<div class="site-footer__inner ">
			<div class="site-footer__main site-footer__main-grid">
				<div class="site-footer__left site-footer__grid-item">
					<div class="site-footer__currency">
						<p>Currency</p>
						<?php echo do_shortcode('[woocommerce_currency_switcher_drop_down_box]')?>
					</div>
					<div class="site-footer__menus">
						<div class="site-footer__menu"><?php get_template_part( 'components/navigation/footer1' ); ?></div>
						<div class="site-footer__menu"><?php get_template_part( 'components/navigation/footer-2' ); ?></div>
						<!-- <div class="site-footer__menu"><?php get_template_part( 'components/navigation/footer-3' ); ?></div> -->
						<div class="site-footer__menu">
							<ul>
								<li>0330 122 8509</li>
								<li><a href="mailto:Info@solvecollectibles.com">Info@solvecollectibles.com</a></li>
							</ul>
						</div>
					</div>
					<div class="site-footer__payments">
						<ul>
							<li><?php echo get_template_part( 'svg-template/svg', 'visa' ) ?></li>
							<li><?php echo get_template_part( 'svg-template/svg', 'mastercard' ) ?></li>
							<li><?php echo get_template_part( 'svg-template/svg', 'apple' ) ?></li>
							<li><?php echo get_template_part( 'svg-template/svg', 'gpay' ) ?></li>
							<li><?php echo get_template_part( 'svg-template/svg', 'paypal' ) ?></li>
							<li><?php echo get_template_part( 'svg-template/svg', 'american' ) ?></li>
							
						</ul>
					</div>
				</div>
				<div class="site-footer__right site-footer__grid-item">
					<h3>Subscribe Today</h3>
					<p>Subscribe to our mailing list to be automatically entered in to our weekly giveaways</p>
					<div class="subscribe-container">
						<?php echo do_shortcode( '[contact-form-7 id="32259" title="Mailchimp"]' ) ?>
					</div>

					<div class="site-footer__social">
						<ul>
							<li><a href="https://www.instagram.com/solvecollectibles/" rel=”noopener noreferrer” target="_blank"><?php echo get_template_part( 'svg-template/svg', 'instagram' ) ?></a></li>
							<li><a href="https://twitter.com/SolveCollect" rel=”noopener noreferrer” target="_blank" ><?php echo get_template_part( 'svg-template/svg', 'twitter' ) ?></a></li>
							<li><a href="https://www.facebook.com/SolveCollectibles" rel=”noopener noreferrer” target="_blank" ><?php echo get_template_part( 'svg-template/svg', 'facebook' ) ?></a></li>
							<li><a href="https://www.youtube.com/c/SolveCollectibles" rel=”noopener noreferrer” target="_blank" ><?php echo get_template_part( 'svg-template/svg', 'youtube' ) ?></a></li>
							<li><a href="https://www.tiktok.com/@luke_solvecollectibles" rel=”noopener noreferrer” target="_blank" ><?php echo get_template_part( 'svg-template/svg', 'tik-tok' ) ?></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="site-footer__copy">
				<?php get_template_part( 'components/footer/copyright' ) ?>
			</div>
		</div>
		
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>