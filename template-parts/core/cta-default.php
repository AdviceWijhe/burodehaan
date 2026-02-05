<?php
// Haal CTA data op (gedeelde logica)
include locate_template('template-parts/core/cta-get-data.php');
?>

<section class="cta <?php echo get_spacing_bottom_class(); ?> <?php echo isset($args['cta_type']) ? '' : 'mt-[20px]'; ?>">
    <div class="container mx-auto lg:p-0!">
        <div class="flex flex-col items-center lg:flex-row bg-blue p-[20px] lg:p-0 overflow-hidden relative py-[60px] lg:py-0!">
            <?= get_template_part('template-parts/core/backgrounds', null, array('color' => 'blue', 'scale' => '35', 'scaleLg' => '50')) ?>
            <?php if (!empty($cta_afbeelding) && !empty($cta_afbeelding['url'])): ?>
                <div class="cta__image order-2 lg:order-1 aspect-square w-[99px]  lg:w-[260px] relative">
                    <?php 
                    if ($cta_afbeelding && isset($cta_afbeelding['ID'])) {
                      echo wp_get_attachment_image($cta_afbeelding['ID'], 'medium', false, array(
                        'class' => 'w-full h-full object-cover object-center',
                        'alt' => $cta_afbeelding['alt'] ?? '',
                        'loading' => 'lazy'
                      ));
                    } else {
                      $image_srcset = isset($cta_afbeelding['ID']) ? wp_get_attachment_image_srcset($cta_afbeelding['ID']) : '';
                      $image_sizes = isset($cta_afbeelding['ID']) ? wp_get_attachment_image_sizes($cta_afbeelding['ID']) : '';
                    ?>
                      <img 
                          src="<?php echo esc_url($cta_afbeelding['url']); ?>" 
                          <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '260px'); ?>"<?php endif; ?>
                          alt="<?php echo esc_attr($cta_afbeelding['alt'] ?? ''); ?>" 
                          class="w-full h-full object-cover object-center"
                          loading="lazy"
                      >
                    <?php } ?>
                </div>
            <?php endif; ?>
            <div class="w-full lg:w-6/12 lg:pl-[60px] order-1 lg:order-2 relative mb-[24px] lg:mb-0! text-center lg:text-left">
                <?php if (!empty($cta_titel)): ?>
                    <h3 class="headline-small text-white mt-0! mb-0!"><?php echo esc_html($cta_titel); ?></h3>
                <?php endif; ?>
            </div>
            <div class="w-full lg:w-6/12 lg:flex lg:justify-end lg:pr-[60px] order-3 relative mt-[24px] lg:mt-0!">
                <?php if (!empty($cta_buttons)): ?>
                    <?php get_template_part('template-parts/core/buttons', null, array('buttons' => $cta_buttons, 'no_margin' => true, 'align_items' => 'stretch', 'full_width' => false)); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

