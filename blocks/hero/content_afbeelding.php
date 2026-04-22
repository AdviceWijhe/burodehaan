<?php
$image = get_sub_field('afbeelding');
$hero_title = get_sub_field('titel');
$content = get_sub_field('content');
$subtitle = get_sub_field('label');
?>

<section class="hero__content_afbeelding bg-secondary relative mb-[3.75rem] lg:mb-[10rem]">
<div class="hero__content--image lg:absolute lg:top-0 lg:left-0 lg:w-[calc(50%-0.875rem)] h-full">
 <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="w-full h-full object-cover object-center">
</div>

<div class="container">
    <div class="hero__content--content max-md:py-[2.5rem] w-full lg:w-1/2 ml-auto lg:p-[5rem]">
        <div class="hero__content--content-inner">
            <div class="hero__content--content-inner-label mb-[1.25rem]">
                <p class="label label-large text-primary mb-0!"><?php echo $subtitle; ?></p>
            </div>
            <div class="hero__content--content-inner-title mb-[2.5rem]">
                <?php echo $hero_title; ?>
            </div>
            <div class="hero__content--content-inner-content body-large">
                <?php echo $content; ?>
            </div>
            <?php if (get_sub_field('met_formulier')): ?>
                <div class="pt-[2rem]!">
                <?php echo do_shortcode('[gravityform id="'.get_sub_field('formulier').'" title="false" description="false"]'); ?>
                </div>
            <?php endif; ?>
            <?php if (get_sub_field('buttons')): ?>
                <div class="hero__content--content-inner-buttons mt-[3.75rem]">
                    <?php get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>