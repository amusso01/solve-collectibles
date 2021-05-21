<?php
/**
 * HomePage Hero
 *
 * @author Andrea Musso
 * 
 * @package foundry
 **/
$slides = get_field('hero_slider');
?>
<section class="home-hero glide-hero">
    <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
            <?php foreach($slides as $slide) : ?>

                <li class="slide" style="background-image:url(<?php echo $slide['slide_image'] ?>);">
                    <div class="slide-text">
                        <h2><?php echo $slide['slide_title'] ?></h2>
                        <?php if($slide['slide_cta']) : ?>
                        <a href="<?php echo $slide['slide_cta']['url'] ?>" class="button"><?php echo $slide['slide_cta']['title'] ?></a>
                        <?php endif; ?>
                    </div>
                </li>
            
            <?php endforeach; ?>
           
        </ul>
    </div>

    <div class="glide__arrows" data-glide-el="controls">
        <button class="glide__arrow glide__arrow--left" data-glide-dir="<"><?php get_template_part( 'svg-template/svg', 'left-arrow-black' ) ?></button>
        <button class="glide__arrow glide__arrow--right" data-glide-dir=">"><?php get_template_part( 'svg-template/svg', 'right-arrow-black' ) ?></button>
    </div>
</section>