<section id="hero__content" class="hero hero__content  <?php echo get_spacing_bottom_class('hero_banner'); ?> relative">
  <div class="container mx-auto px-0! lg:px-[20px]">
    <div class="flex flex-col-reverse lg:flex-row items-stretch">
    <div class="w-full lg:w-5/12 flex flex-col justify-between ">
    
        <div class="hero-content lg:pt-[80px] px-[20px] lg:px-0!">
        <div class="pt-[20px] lg:px-0! lg:pt-[0] relative z-1 lg:mb-[100px] xl:translate-x-[-124px]">
    <!-- yoast breadcrumbs -->
    <?php if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
    } ?>
  </div>
  <div class="text-center lg:text-left mt-[40px] lg:mt-0! pr-[32px] xl:pr-0">
            <h1 class="text-blue mb-[32px]!"><?= get_sub_field('titel') ?></h1>
            <div class="body-large lg:max-w-[540px]"><?= get_sub_field('content') ?></div>
        <?php
            if(!get_sub_field('met_contact')) { ?>
          </div>
          <?php } ?>
        </div>

     <?php 
     if(get_sub_field('met_contact')) { ?>
     <div class="contact-info mt-[80px] ">
      <div class="flex flex-col lg:flex-row gap-[20px] lg:gap-[80px]">
        <div class="">
        <span class="block">Telefoon</span>
        <a href="tel:<?= get_field('telefoonnummer', 'option') ?>" class="font-normal"><?= get_field('telefoonnummer', 'option') ?></a>
        </div>
        <div class="">
        <span class="block">Email</span>
        <a href="mailto:<?= get_field('emailadres', 'option') ?>" class="font-normal"><?= get_field('emailadres', 'option') ?></a>
        </div>
      </div>
      <?php if (!empty(get_field('socials', 'option'))) { ?>
                <div class="footer_socials socials mb-[60px] lg:mb-0 mt-[60px]">
                <?php foreach (get_field('socials', 'option') as $social) { ?>
                    <a href="<?= $social['link']['url'] ?>" target="<?= esc_attr($social['link']['target'] ?: '_blank') ?>" rel="noopener" class="btn border border-primary text-primary inline-flex gap-2">
                        <?= $social['icon'] ?> LinkedIn
                    </a>
                <?php } ?>
                </div>
              <?php } ?>
     </div>
                </div>
     

     <div class="flex gap-[80px] px-[20px] lg:px-0!">
      <div>
        <span class="block">Locatie</span>
        <p class="font-normal"><?= get_field('adres', 'option') ?><br><?= get_field('postcode_+_woonplaats', 'option') ?></p>
      </div>
      <div>
        <span class="block">KVK</span>
        <p class="font-normal"><?= get_field('kvk_nummer', 'option') ?></p>
      </div>
      <div>
        <span class="block">BTW</span>
        <p class="font-normal"><?= get_field('btw_nummer', 'option') ?></p>
      </div>
 
    </div>
    <?php } ?>

    <?php if(get_sub_field('met_cta')) { ?>
      <div class="px-[20px] lg:px-0!">
        <?php get_template_part('template-parts/core/cta-compact'); ?>
      </div>
     <?php } ?>



    </div>
               
                
    <div class="w-full lg:w-7/12 pt-[40px] lg:pt-[80px]">
        <div class="hero-image aspect-landscape relative overflow-hidden lg:mt-[160px]">
            <?php 
            $afbeelding = get_sub_field('afbeelding');
            if ($afbeelding && isset($afbeelding['ID'])) {
              echo wp_get_attachment_image($afbeelding['ID'], 'full', false, array(
                'class' => 'w-full h-full object-cover object-center',
                'alt' => $afbeelding['alt'] ?? '',
                'loading' => 'eager' // Hero image
              ));
            } else if ($afbeelding && isset($afbeelding['ID'])) {
              $image_srcset = wp_get_attachment_image_srcset($afbeelding['ID'], 'full');
              $image_sizes = wp_get_attachment_image_sizes($afbeelding['ID'], 'full');
              // Gebruik 'full' size als src voor beste kwaliteit bij hero images
              $image_src = wp_get_attachment_image_url($afbeelding['ID'], 'full');
            ?>
              <img src="<?= esc_url($image_src) ?>" 
                   <?php if ($image_srcset) : ?>srcset="<?= esc_attr($image_srcset) ?>" sizes="<?= esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 58vw') ?>"<?php endif; ?>
                   alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>" 
                   class="w-full h-full object-cover object-center"
                   loading="eager">
            <?php } else if ($afbeelding && isset($afbeelding['url'])) { ?>
              <img src="<?= esc_url($afbeelding['url']) ?>" 
                   alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>" 
                   class="w-full h-full object-cover object-center"
                   loading="eager">
            <?php } ?>
        </div>
    </div>
    </div>

</div>
</section>