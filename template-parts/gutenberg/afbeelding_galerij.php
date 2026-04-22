<?php
// ACF Block | Afbeelding Galerij – alleen voor Gutenberg (veld: afbeeldingen)
$afbeeldingen = get_field('afbeeldingen');
$swiper_class = 'afbeelding-swiper-' . wp_rand(1000, 9999);
if (empty($afbeeldingen) || !is_array($afbeeldingen)) {
    return;
}
?>
<div class="afbeelding_galerij relative w-full max-w-4xl mx-auto py-[5rem]">
    <div class="swiper <?php echo esc_attr($swiper_class); ?> relative w-full">
        <div class="swiper-wrapper">
            <?php foreach ($afbeeldingen as $afbeelding) : ?>
            <div class="swiper-slide h-96 rounded-[1rem] overflow-hidden">
                <?php
                if ($afbeelding && isset($afbeelding['ID'])) {
                    echo wp_get_attachment_image($afbeelding['ID'], 'large', false, array(
                        'class' => 'relative w-full h-96 object-cover object-center rounded-[1rem]',
                        'alt' => $afbeelding['alt'] ?? '',
                        'loading' => 'lazy'
                    ));
                } elseif ($afbeelding && isset($afbeelding['url'])) {
                    $image_srcset = isset($afbeelding['ID']) ? wp_get_attachment_image_srcset($afbeelding['ID']) : '';
                    $image_sizes = isset($afbeelding['ID']) ? wp_get_attachment_image_sizes($afbeelding['ID']) : '';
                    ?>
                    <img src="<?= esc_url($afbeelding['url']) ?>"
                         <?php if ($image_srcset) : ?>srcset="<?= esc_attr($image_srcset) ?>" sizes="<?= esc_attr($image_sizes ?: '100vw') ?>"<?php endif; ?>
                         alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>"
                         class="relative w-full h-96 object-cover object-center rounded-[1rem]"
                         loading="lazy">
                <?php } ?>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Pijltjes in de slider, links en rechts van de middelste foto -->
        <div class="swiper-arrows absolute inset-y-0 left-0 right-0 flex justify-between items-center pointer-events-none px-2 lg:px-4 z-10">
            <div class="swiper-prev pointer-events-auto w-10 h-10 lg:w-12 lg:h-12 rounded-[0.25rem] border-[0.0625rem] bg-white/90 hover:bg-white border border-gray-200 flex items-center justify-center cursor-pointer transition-colors shadow-sm" aria-label="<?php esc_attr_e('Vorige', 'advice2025'); ?>">
                <svg class="w-4 h-4 text-gray-700 rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5.3 8.48" fill="currentColor">
                    <path d="M0,7.42l3.18-3.18L0,1.06,1.06,0l3.18,3.18h0s1.06,1.06,1.06,1.06L1.06,8.48,0,7.42Z"/>
                </svg>
            </div>
            <div class="swiper-next pointer-events-auto w-10 h-10 lg:w-12 lg:h-12 rounded-[0.25rem] border-[0.0625rem] bg-white/90 hover:bg-white border border-gray-200 flex items-center justify-center cursor-pointer transition-colors shadow-sm" aria-label="<?php esc_attr_e('Volgende', 'advice2025'); ?>">
                <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5.3 8.48" fill="currentColor">
                    <path d="M0,7.42l3.18-3.18L0,1.06,1.06,0l3.18,3.18h0s1.06,1.06,1.06,1.06L1.06,8.48,0,7.42Z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var swiperEl = document.querySelector('.afbeelding_galerij .<?php echo esc_js($swiper_class); ?>');
    if (!swiperEl || typeof Swiper === 'undefined') return;
    var swiper = new Swiper('.<?php echo esc_js($swiper_class); ?>', {
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
            nextEl: swiperEl.closest('.afbeelding_galerij').querySelector('.swiper-next'),
            prevEl: swiperEl.closest('.afbeelding_galerij').querySelector('.swiper-prev'),
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