<?php
/**
 * Block: Logo Slider
 * 
 * Een slider met logos in rechthoekige blokken
 */

$logos = get_sub_field('logos');
$block_id = 'logo-slider-' . uniqid();

if ($logos && is_array($logos) && !empty($logos)) : 
    $rand_class = 'swiper-logo-slider-' . wp_rand(1000, 9999);
    ?>
    
    <div class="logo-slider <?php echo get_spacing_bottom_class(); ?> overflow-hidden">
        <div class="container mx-auto px-0!">
            <div class="text-center">
                <div class="body-large mb-[1.25rem] lg:mb-[2.5rem] font-medium!"><?php echo get_sub_field('titel'); ?></div>
            </div>
            <div class="swiper-container logo-slider-swiper <?php echo esc_attr($rand_class); ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($logos as $logo_item) : 
                        $logo = $logo_item['logo'];
                        if ($logo) :
                            // Get image URL
                            $logo_url = is_array($logo) ? $logo['url'] : $logo;
                            $logo_alt = is_array($logo) ? $logo['alt'] : '';
                    ?>
                        <div class="swiper-slide">
                            <div class="rounded-[20px] p-5 flex items-center justify-center aspect-[210/120] w-full">
                                <?php if (is_array($logo) && isset($logo['id'])) : 
                                    // Use WordPress image functions for better quality
                                    $logo_src = wp_get_attachment_image_url($logo['id'], 'medium');
                                    $logo_srcset = wp_get_attachment_image_srcset($logo['id'], 'medium');
                                    $logo_sizes = wp_get_attachment_image_sizes($logo['id'], 'medium');
                                ?>
                                    <img src="<?php echo esc_url($logo_src); ?>" 
                                         <?php if ($logo_srcset) : ?>srcset="<?php echo esc_attr($logo_srcset); ?>" sizes="<?php echo esc_attr($logo_sizes ?: '(max-width: 1024px) 100vw, 16.66vw'); ?>"<?php endif; ?>
                                         alt="<?php echo esc_attr($logo_alt ?: 'Logo'); ?>" 
                                         loading="lazy"
                                         class="max-w-full max-h-full w-auto h-auto object-contain">
                                <?php else : ?>
                                    <img src="<?php echo esc_url($logo_url); ?>" 
                                         alt="<?php echo esc_attr($logo_alt ?: 'Logo'); ?>" 
                                         loading="lazy"
                                         class="max-w-full max-h-full w-auto h-auto object-contain">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php 
                        endif;
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.<?php echo esc_js($rand_class); ?>', {
                slidesPerView: 6,
                spaceBetween: 36,
                speed: 4000,
                loop: true,
                loopAdditionalSlides: 6,
                freeMode: true,
                freeModeMomentum: false,
                grabCursor: true,
                centeredSlides: false,
                allowTouchMove: false,
                watchOverflow: true,
                resistance: true,
                resistanceRatio: 0.85,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false
                },
                breakpoints: {
                    320: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    640: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    },
                    1024: {
                        slidesPerView: 6,
                        spaceBetween: 36
                    }
                }
            });

            swiper.wrapperEl.style.transitionTimingFunction = 'linear';
        });
    </script>

<?php endif; ?>

