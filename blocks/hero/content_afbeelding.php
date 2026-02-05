<?php
$image = get_sub_field('afbeelding');
?>

<section class="hero__content_afbeelding <?php echo get_spacing_bottom_class('hero_banner'); ?>">
<div class="hero relative bg-blue">

  

<div class="flex flex-col-reverse lg:flex-row items-stretch">

  <div class="w-full lg:w-6/12 p-[20px] pb-[60px] lg:px-[60px] lg:py-[60px] xl:px-[80px] xl:py-[80px] text-white flex flex-col justify-between">
  <div class="relative z-1 mb-3 lg:mb-12">
    <!-- yoast breadcrumbs -->
    <?php if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
    } ?>
  </div>
  <div class="mt-[46px] lg:mt-[60px] xl:mt-0">
    <h1 class="text-white mb-[32px]"><?= get_sub_field('titel') ?></h1>

   <div class="body-large max-w-[630px]"><?= get_sub_field('content') ?></div> 


    <?php if(get_sub_field('buttons')) { ?>
      <div class="mt-[32px]!">
        <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
      </div>
    <?php } ?>
    </div>
  </div>

  <div class="w-full lg:w-5/12 ml-auto js-it-image js-image-text-animate aspect-square relative">
    
  <?php if ($image && isset($image['ID'])) : 
    $image_srcset = wp_get_attachment_image_srcset($image['ID'], 'full');
    $image_sizes = wp_get_attachment_image_sizes($image['ID'], 'full');
    // Gebruik 'full' size als src voor beste kwaliteit bij hero images
    $image_src = wp_get_attachment_image_url($image['ID'], 'full');
  ?>
  <div class="first-image js-image-animate w-full h-full">
                    <img src="<?php echo esc_url($image_src); ?>" 
                         <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 42vw'); ?>"<?php endif; ?>
                         alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" 
                         loading="eager"
                         class="w-full h-full  object-cover object-center absolute inset-0 <?php if(get_sub_field('blok_variant') == 'link_list') { ?> pl-[20px] lg:pl-0 <?php } ?>">
                  </div>
                  <div class="second-image js-image-animate w-full h-full absolute top-0 left-0">
                    <img src="<?php echo esc_url($image_src); ?>" 
                         <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 42vw'); ?>"<?php endif; ?>
                         alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" 
                         loading="eager"
                         class="w-full h-full  object-cover object-center absolute  top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2  <?php if(get_sub_field('blok_variant') == 'link_list') { ?> pl-[20px] lg:pl-0 <?php } ?>">
                  </div>
                  <div class="third-image js-image-animate w-full h-full absolute top-0 left-0">
                    <img src="<?php echo esc_url($image_src); ?>" 
                         <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 42vw'); ?>"<?php endif; ?>
                         alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" 
                         loading="eager"
                         class="w-full h-full  object-cover object-center absolute  top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2  <?php if(get_sub_field('blok_variant') == 'link_list') { ?> pl-[20px] lg:pl-0 <?php } ?>">
                  </div>
                  
  <?php endif; ?>
    
  </div>
</div>

</section>