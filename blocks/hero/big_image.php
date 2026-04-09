<section class="hero hero__big_image <?php echo get_spacing_bottom_class('hero_banner'); ?> overflow-hidden relative left-1/2 right-1/2 -mx-[50vw] w-screen">
    <div class="relative flex items-end h-screen min-h-screen w-full p-0">
        <div class="absolute inset-0 h-full w-full">
            <div class="absolute bottom-0 lg:bottom-auto lg:top-0 left-0 w-full h-full lg:w-[400px] lg:h-full  opacity-50" style="opacity: 0.5;
background: linear-gradient(90deg, #0A2031 0%, rgba(10, 32, 49, 0.00) 100%);"></div>
<div class="absolute bottom-0 left-0 w-full h-[300px]" style="opacity: 0.5;
background: linear-gradient(0deg, #0A2031 0%, rgba(10, 32, 49, 0.00) 100%);"></div>
            <?php 
            $afbeelding = get_sub_field('afbeelding');
            if ($afbeelding && isset($afbeelding['ID'])) {
              $image_srcset = wp_get_attachment_image_srcset($afbeelding['ID'], 'full');
              $image_sizes = wp_get_attachment_image_sizes($afbeelding['ID'], 'full');
              // Gebruik 'full' size als src voor beste kwaliteit, browser kiest dan uit srcset
              $image_src = wp_get_attachment_image_url($afbeelding['ID'], 'full');
            ?>
              <img src="<?= esc_url($image_src) ?>" 
                   <?php if ($image_srcset) : ?>srcset="<?= esc_attr($image_srcset) ?>" sizes="<?= esc_attr($image_sizes ?: '100vw') ?>"<?php endif; ?>
                   alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>"
                   loading="eager" class="w-full h-full object-cover object-center">
            <?php } else if ($afbeelding && isset($afbeelding['url'])) { ?>
              <img src="<?= esc_url($afbeelding['url']) ?>" 
                   alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>"
                   loading="eager" class="w-full h-full object-cover object-center">
            <?php } ?>
        </div>
        <div class="w-full relative z-2">
            <div class="w-full lg:w-6/12 lg:px-[60px] lg:py-[60px] p-[20px] pb-[20px]">
                <h1 class="text-white"><?= get_sub_field('titel') ?></h1>
                <div class="mt-[32px] hero-big-image-buttons">
                <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'align_items' => 'start')) ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hero__big_image .hero-big-image-buttons .btn {
            border-radius: 0 !important;
        }
    </style>
</section>