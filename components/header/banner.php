<?php
/**
 * Banner
 * 
 * @author Andrea Musso
 * 
 * @package Foundry
 */
$hasBanner = get_field('display_banner', 'option');
$bannerText = get_field('banner_text', 'option');

?>
<?php if($hasBanner) : ?>
<section class="site-banner">
	<p><?php echo $bannerText ?></p>
</section>
<?php endif; ?>