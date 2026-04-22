<?php
// Haal CTA data op (gedeelde logica)
include locate_template('template-parts/core/cta-get-data.php');
?>

<div class="flex flex-col gap-4">
    <div class="flex items-center gap-4 w-full max-w-[24.375rem]">
        <?php if (!empty($cta_afbeelding) && !empty($cta_afbeelding['url'])): ?>
            <div class="aspect-square w-[5rem] flex-shrink-0">
                <?php 
                if ($cta_afbeelding && isset($cta_afbeelding['ID'])) {
                  echo wp_get_attachment_image($cta_afbeelding['ID'], 'thumbnail', false, array(
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
                      <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '80px'); ?>"<?php endif; ?>
                      alt="<?php echo esc_attr($cta_afbeelding['alt'] ?? ''); ?>" 
                      class="w-full h-full object-cover object-center" 
                      loading="lazy"
                  >
                <?php } ?>
            </div>
        <?php endif; ?>
        <div class="w-full">
            <?php if (!empty($cta_titel)): ?>
                <h6 class="title-small"><?php echo esc_html($cta_titel); ?></h6>
            <?php endif; ?>
        </div>
    </div>
    <div class="flex items-center gap-4 w-full">
        <?php if (!empty($cta_buttons)): ?>
            <?php get_template_part('template-parts/core/buttons', null, array('buttons' => $cta_buttons, 'align_items' => 'stretch')); ?>
        <?php endif; ?>
    </div>
</div>

