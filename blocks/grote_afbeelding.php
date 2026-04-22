<!-- Section heeft achtergrondafbeelding -->
<div class="grote_afbeelding <?php echo get_spacing_bottom_class(); ?> overflow-hidden">
    <?php if(is_single()) : ?>
<div class="container">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-[1.75rem]">
        <?php endif; ?>
  <div class="min-h-[33.4375rem] lg:min-h-[43.75rem] relative flex items-end max-md:p-0! <?php if(is_single()) : ?>col-span-1 lg:col-span-10 lg:col-start-2<?php endif; ?>">
    <div class="absolute h-full w-[12.5rem] lg:w-[18.75rem] z-1 bottom-0 left-0" style="opacity: 0.5;
background: linear-gradient(90deg, #0A2031 0%, rgba(10, 32, 49, 0.00) 100%);"></div>
    <div class="absolute h-[12.5rem] lg:h-[25rem] w-full z-1 bottom-0 left-0" style="background: linear-gradient(180deg, rgba(22, 22, 22, 0.00) 0%, #161616 100%);"></div>

    <div class="absolute h-full w-full overflow-hidden">
      <?php 
      $afbeelding = get_sub_field('afbeelding');
      if ($afbeelding && isset($afbeelding['ID'])) {
        echo wp_get_attachment_image($afbeelding['ID'], 'full', false, array(
          'class' => 'h-full w-full object-cover object-center will-change-transform transform-gpu origin-center',
          'alt' => $afbeelding['alt'] ?: '',
          'data-scroll-grote-afbeelding' => '',
          'data-scroll-speed' => '2',
          'loading' => 'eager' // Hero image, load immediately
        ));
      } else if ($afbeelding && isset($afbeelding['ID'])) {
        $image_srcset = wp_get_attachment_image_srcset($afbeelding['ID'], 'full');
        $image_sizes = wp_get_attachment_image_sizes($afbeelding['ID'], 'full');
        // Gebruik 'full' size als src voor beste kwaliteit
        $image_src = wp_get_attachment_image_url($afbeelding['ID'], 'full');
      ?>
        <img src="<?= esc_url($image_src) ?>" 
             <?php if ($image_srcset) : ?>srcset="<?= esc_attr($image_srcset) ?>" sizes="<?= esc_attr($image_sizes ?: '100vw') ?>"<?php endif; ?>
             class="h-full w-full object-cover object-center will-change-transform transform-gpu  origin-center" 
             alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>"
             loading="eager"
             data-scroll-grote-afbeelding
             data-scroll-speed="2">
      <?php } else if ($afbeelding && isset($afbeelding['url'])) { ?>
        <img src="<?= esc_url($afbeelding['url']) ?>" 
             class="h-full w-full object-cover object-center will-change-transform transform-gpu origin-center" 
             alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>"
             loading="eager"
             data-scroll-grote-afbeelding
             data-scroll-speed="2">
      <?php } ?>
    </div>
    <div class="container">
    <div class="w-full flex flex-col lg:flex-row relative z-2 justify-between gap-[2rem] items-end lg:pb-[6.25rem] pb-[1.25rem] ">
      <div class="w-full lg:w-5/12 lg:pl-[2.5rem] text-white">
        
        <div class=" mb-[2.5rem] max-w-[37.1875rem]"><?= get_sub_field('titel') ?></div>
        <div class="opacity-80"><?= get_sub_field('content', null) ?></div>
        
      </div>
      <div class="w-full lg:w-6/12 px-5 lg:px-5 lg:pr-[2.5rem] flex lg:justify-end">
      <?php if(get_sub_field('buttons')) { ?>
          <div class="">
            <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'align_items' => 'stretch')) ?>
          </div>
        <?php } ?>
      </div>
    </div>
    </div>
  </div>
  <?php if(is_single()) : ?>
  </div>
  </div>
<?php endif; ?>
  <script>
    (function(){
      if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);
        gsap.to("[data-scroll-grote-afbeelding]", {
          yPercent: -5,
          ease: "none",
          force3D: true,
          overwrite: "auto",
          scrollTrigger: {
            trigger: ".grote_afbeelding",
            start: "top bottom",
            end: "bottom top",
            scrub: 0.2,
            invalidateOnRefresh: true
          }
        });
      }
    })();
  </script>
</div>