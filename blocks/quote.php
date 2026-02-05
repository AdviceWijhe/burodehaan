<section class="quote <?php echo get_spacing_bottom_class(); ?>">
    <div class="container mx-auto">
        <div class="w-full lg:w-10/12 mx-auto">
        <div class="quote-content bg-pink relative overflow-hidden">
        
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-3/12">
                    <div class="quote-image h-[380px] lg:h-auto lg:aspect-[9/12]">
                        <?php 
                        $afbeelding = get_sub_field('afbeelding');
                        if ($afbeelding && isset($afbeelding['ID'])) {
                          echo wp_get_attachment_image($afbeelding['ID'], 'medium', false, array(
                            'class' => 'w-full h-full object-cover object-center',
                            'alt' => $afbeelding['alt'] ?? '',
                            'loading' => 'lazy'
                          ));
                        } else if ($afbeelding && isset($afbeelding['url'])) {
                          $image_srcset = isset($afbeelding['ID']) ? wp_get_attachment_image_srcset($afbeelding['ID']) : '';
                          $image_sizes = isset($afbeelding['ID']) ? wp_get_attachment_image_sizes($afbeelding['ID']) : '';
                        ?>
                          <img src="<?= esc_url($afbeelding['url']) ?>" 
                               <?php if ($image_srcset) : ?>srcset="<?= esc_attr($image_srcset) ?>" sizes="<?= esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 25vw') ?>"<?php endif; ?>
                               alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>" 
                               class="w-full h-full object-cover object-center"
                               loading="lazy">
                        <?php } ?>
                    </div>
                </div>
                <div class="w-full lg:w-9/12 px-[20px]  py-[60px] lg:px-[83px] flex flex-col justify-center relative overflow-hidden">
                <?= get_template_part('template-parts/core/backgrounds', null, array('color' => 'pink', 'scale' => '30', 'scaleLg' => '50')) ?>
                    <div class="quote-text text-white title-large relative">
                        <?= get_sub_field('quote'); ?>

                    </div>
                    <div class="quote-author text-light-pink mt-[32px] relative">
                        <?= get_sub_field('naam'); ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>