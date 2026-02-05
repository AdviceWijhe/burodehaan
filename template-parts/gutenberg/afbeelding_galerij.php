<div class="afbeelding_galerij relative w-full max-w-4xl mx-auto pt-[40px]">
    <!-- Swiper Container -->
    <div class="swiper afbeelding-swiper relative w-full ">
        <div class="swiper-wrapper">
            <!-- Slide 1 - Hoofdafbeelding -->
             <?php $afbeeldingen = get_field('afbeeldingen') ?>
             <?php foreach($afbeeldingen as $afbeelding) : ?>
            <div class="swiper-slide h-96">
                <?php 
                if ($afbeelding && isset($afbeelding['ID'])) {
                  echo wp_get_attachment_image($afbeelding['ID'], 'large', false, array(
                    'class' => 'relative w-full h-96 object-cover object-center',
                    'alt' => $afbeelding['alt'] ?? '',
                    'loading' => 'lazy'
                  ));
                } else if ($afbeelding && isset($afbeelding['url'])) {
                  $image_srcset = isset($afbeelding['ID']) ? wp_get_attachment_image_srcset($afbeelding['ID']) : '';
                  $image_sizes = isset($afbeelding['ID']) ? wp_get_attachment_image_sizes($afbeelding['ID']) : '';
                ?>
                  <img src="<?= esc_url($afbeelding['url']) ?>" 
                       <?php if ($image_srcset) : ?>srcset="<?= esc_attr($image_srcset) ?>" sizes="<?= esc_attr($image_sizes ?: '100vw') ?>"<?php endif; ?>
                       alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>" 
                       class="relative w-full h-96 object-cover object-center"
                       loading="lazy">
                <?php } ?>
            </div>
            <?php endforeach; ?>

          
        </div>
        <div class="swiper-controls flex justify-center lg:justify-between items-center mt-[40px] px-[12%]">
            <div class="swiper-pagination text-center! lg:text-left!"></div>
                <div class="swiper-arrows hidden lg:flex gap-4">
                
                    <div class="swiper-prev rotate-180">
                        <svg id="Laag_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 5.3 8.48">
                        <path d="M0,7.42l3.18-3.18L0,1.06,1.06,0l3.18,3.18h0s1.06,1.06,1.06,1.06L1.06,8.48,0,7.42Z"/>
                        </svg>
                    </div>
                    <div class="swiper-next">
                        <svg id="Laag_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 5.3 8.48">
                        <path d="M0,7.42l3.18-3.18L0,1.06,1.06,0l3.18,3.18h0s1.06,1.06,1.06,1.06L1.06,8.48,0,7.42Z"/>
                        </svg>
                    </div>
            </div>
          </div>
        
    </div>
</div>

<style>

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.afbeelding-swiper', {
        // Basis configuratie
        loop: true,
        centeredSlides: true,
        spaceBetween: 36,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        
        // Navigatie
        navigation: {
            nextEl: '.swiper-next',
            prevEl: '.swiper-prev',
        },
        
        // Paginatie
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            bulletClass: 'swiper-pagination-bullet',
            bulletActiveClass: 'swiper-pagination-bullet-active',
        },
        
        // Effecten
        effect: 'slide',
        speed: 600,
        
        // Responsive breakpoints
        breakpoints: {
            320: {
                slidesPerView: 1.1,

            },
            768: {
                slidesPerView: 1.3,      spaceBetween: 0,
            },
            1024: {
                slidesPerView: 1.3,
            }
        }
    });
});
</script>