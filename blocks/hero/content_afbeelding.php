<?php
$image = get_sub_field('afbeelding');
$hero_title = get_sub_field('titel');
$content = get_sub_field('content');
$subtitle = get_sub_field('label');
?>

<section class="hero__content_afbeelding bg-secondary relative">
<div class="hero__content--image absolute top-0 left-0 lg:w-[calc(50%-14px)] h-full">
 <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="w-full h-full object-cover object-center">
</div>

<div class="container">
    <div class="hero__content--content w-full lg:w-1/2 ml-auto lg:p-[80px]">
        <div class="hero__content--content-inner">
            <div class="hero__content--content-inner-label mb-[20px]">
                <p class="label label-large text-primary mb-0!"><?php echo $subtitle; ?></p>
            </div>
            <div class="hero__content--content-inner-title mb-[40px]">
                <?php echo $hero_title; ?>
            </div>
            <div class="hero__content--content-inner-content body-large">
                <?php echo $content; ?>
            </div>
            <?php if (get_sub_field('met_formulier')): ?>
                <div class="pt-[32px]!">
                <?php echo do_shortcode('[gravityform id="'.get_sub_field('formulier').'" title="false" description="false"]'); ?>
                </div>
            <?php endif; ?>
            <?php if (get_sub_field('buttons')): ?>
                <div class="hero__content--content-inner-buttons mt-[60px]">
                    <?php get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>