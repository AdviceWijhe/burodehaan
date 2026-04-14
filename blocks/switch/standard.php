<?php
/**
 * Block: Image + Text Block
 * 
 * Een blok met afbeelding en tekst naast elkaar
 */

 $heading = get_sub_field('titel');
 $image = get_sub_field('image');
 $content = get_sub_field('content');
 $image_position = get_sub_field('image_position') ?: 'left';
 $background_color = get_sub_field('background_color');
 $block_id = 'image-text-' . uniqid();

//  Als background-color leeg is dan moet de breedte van de tekst 5/12 zijn en de afbeelding 6/12 ml-auto en als image_position left is moet de content ml-auto hebben en de image niet ml-auto hebben

$text_width = 'lg:w-6/12 px-[20px] lg:px-[80px] pb-[60px]';
$image_width = 'lg:w-[50vw]';
$content_margin = '';
$image_margin = '';


if($background_color === '') {
    $text_width = 'lg:w-4/12 lg:ml-[calc(100%/12)]';
    $image_width = 'lg:w-[50vw]';
    $content_margin = '';
    $image_margin = 'lg:mr-[calc(50%-50vw)] lg:ml-auto';
}
if($image_position === 'left') {
    $text_width = 'lg:w-4/12';
    $content_margin = 'lg:ml-[calc(100%/12)]';
    $image_margin = 'lg:ml-[calc(50%-50vw)]';
} else {
    $image_margin = 'lg:mr-[calc(50%-50vw)] lg:ml-auto';
}


// Haal velden op met fallback voor oude field names


$text_color = 'white';

if($background_color === '') {
    $text_color = 'dark-blue';
}else if($background_color === 'white') {
    $text_color = 'dark-blue';
}

// USP checkmark iconen
$usp_icon_light = '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="12.5" cy="12.5" r="12" stroke="#00344C"/>
  <rect x="7.92969" y="12.0708" width="6" height="1" transform="rotate(45 7.92969 12.0708)" fill="#00344C"/>
  <rect x="18.5352" y="8.53564" width="1" height="11" transform="rotate(45 18.5352 8.53564)" fill="#00344C"/>
</svg>';

$usp_icon_pink = '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="12.5" cy="12.5" r="12.5" fill="#DB9EB4"/>
  <rect x="7.92969" y="12.0708" width="6" height="1" transform="rotate(45 7.92969 12.0708)" fill="#480E25"/>
  <rect x="18.5352" y="8.53564" width="1" height="11" transform="rotate(45 18.5352 8.53564)" fill="#480E25"/>
</svg>';

// Kies het juiste icoon op basis van achtergrondkleur
$usp_icon = ($background_color === '' || $background_color === 'leef') ? $usp_icon_light : $usp_icon_pink;
?>
 <?php 
            $content_first = $image_position === 'right';
            ?>
<!-- Image + Text Block -->
<div id="<?php echo esc_attr($block_id); ?>" class="js-image-text <?php echo $background_color === '' ? 'js-image-text--bg-empty' : ''; ?> <?php echo get_spacing_bottom_class(); ?> lg:relative flex flex-col lg:flex-row">
  <!-- <div class="order-2 lg:absolute lg:order-none js-image-text-extend left-0 lg:left-auto lg:right-0 bottom-[60px] lg:top-[0px] lg:bottom-auto w-full h-[60px] lg:h-full bg-gray-100 <?php echo $content_first ? '' : 'hidden'; ?>" id="extend-element-<?php echo esc_attr($block_id); ?>"></div> -->
    <div class="container mx-auto px-[20px] lg:px-0! order-1 lg:order-none">
		<div class="js-it-row flex flex-col-reverse lg:flex-row <?php echo $background_color === '' ? 'items-center' : 'items-stretch'; ?> order-1">
            
           
            
            <div class="js-it-content relative w-full pt-[40px] lg:pt-[60px] overflow-hidden default-content <?php if($background_color) { echo 'flex flex-col justify-center items-start';} ?>  lg:py-[60px]  text-<?= $text_color ?>  <?php echo $content_first ? 'order-1 lg:order-1' : 'order-1 lg:order-2'; ?> <?php echo $content_margin; ?> <?= $text_width ?>">
              <div class="bg-<?php echo $background_color; ?> absolute w-full <?php echo $content_first ? 'right-0' : 'left-0'; ?> top-0 h-full"></div>
                
                
                  <?php if ($heading) : ?>
                 <div class="mb-[40px]">  <?php echo $heading; ?></div>
                <?php endif; ?>
     

                   <?php if ($content) : ?>
                    <div class="relative">
                     
                        <?php echo $content; ?>
                    </div>
                  <?php endif; ?>

     

                
                <?php if(get_sub_field('buttons')) { ?>
                    <div class="mt-[24px]! lg:mt-[32px]!">
                      <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
                    </div>
                <?php } ?>
            </div>
            
            <div class="js-it-image js-image-text-animate aspect-square <?= $image_width ?> <?= $image_margin ?>  lg:aspect-square relative w-full  <?php echo $content_first ? 'order-2 lg:order-2' : 'order-2 lg:order-1'; ?> ">
              
                <?php if ($image) : 
                  // Get image ID if it's an attachment URL
                  $image_id = attachment_url_to_postid($image);
                  if ($image_id) {
                    $image_srcset = wp_get_attachment_image_srcset($image_id, 'full');
                    $image_sizes = wp_get_attachment_image_sizes($image_id, 'full');
                    // Gebruik 'large' size als src voor goede kwaliteit (niet hero, dus iets kleiner is ok)
                    $image_src = wp_get_attachment_image_url($image_id, 'large');
                  } else {
                    $image_src = $image;
                    $image_srcset = '';
                    $image_sizes = '';
                  }
                ?>
                  <div class="first-image js-image-animate js-image-animate-first w-full h-full">
                    <img src="<?php echo esc_url($image_src); ?>" 
                         <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 50vw'); ?>"<?php endif; ?>
                         alt="<?php echo esc_attr($heading ?: 'Image'); ?>" 
                         loading="lazy"
                         class="w-full h-full  object-cover object-center absolute inset-0 <?php if(get_sub_field('blok_variant') == 'link_list') { ?> pl-[20px] lg:pl-0 <?php } ?>">
                  </div>
                <?php else: ?>
                    <!-- Placeholder als er geen afbeelding is -->
                    <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm">Geen afbeelding</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>