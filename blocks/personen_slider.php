<?php
/**
 * Block: Personen Slider
 * 
 * Een slider met personen en hun contactgegevens
 */

$titel = get_sub_field('titel');
$tekst = get_sub_field('tekst');
$personen = get_sub_field('personen');
$block_id = 'personen-slider-' . uniqid();

if ($personen && is_array($personen) && !empty($personen)) : 
    $rand_class = 'swiper-personen-slider-' . wp_rand(1000, 9999);
    ?>
    
    <section class="personen-slider <?php echo get_spacing_bottom_class(); ?>">
        <div class="container mx-auto px-[1.25rem] lg:px-0">
            <!-- Titel en Tekst naast elkaar -->
            <div class="flex flex-col lg:flex-row gap-[1.5rem] lg:gap-[2.5rem] mb-[2.5rem] lg:mb-[3.75rem]">
                <!-- Titel links -->
                <div class="w-full lg:w-1/2">
                    <?php if ($titel) : ?>
                        <h2 class="mb-0!"><?php echo esc_html($titel); ?></h2>
                    <?php endif; ?>
                </div>
                
                <!-- Tekst rechts -->
                <div class="w-full lg:w-1/2">
                    <?php if ($tekst) : ?>
                        <div class="content">
                            <?php echo $tekst; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Personen Slider -->
            <div class="swiper-container personen-slider-swiper <?php echo esc_attr($rand_class); ?> overflow-hidden">
                <div class="swiper-wrapper">
                    <?php foreach ($personen as $persoon) : 
                        $foto = $persoon['foto'];
                        $naam = $persoon['naam'];
                        $functie = $persoon['functie'];
                        $telefoon = $persoon['telefoon'];
                        $emailadres = $persoon['emailadres'];
                    ?>
                        <div class="swiper-slide">
                            <div class="flex flex-col items-center text-center">
                                <!-- Foto -->
                                <?php if ($foto) : 
                                    $foto_url = is_array($foto) ? $foto['url'] : $foto;
                                    $foto_alt = is_array($foto) ? $foto['alt'] : ($naam ?: 'Foto');
                                    $foto_id = is_array($foto) && isset($foto['id']) ? $foto['id'] : null;
                                ?>
                                    <div class="mb-[1.5rem] w-full aspect-[3/4] rounded-[20px] overflow-hidden bg-black">
                                        <?php if ($foto_id) : 
                                            $foto_src = wp_get_attachment_image_url($foto_id, 'medium');
                                            $foto_srcset = wp_get_attachment_image_srcset($foto_id, 'medium');
                                            $foto_sizes = wp_get_attachment_image_sizes($foto_id, 'medium');
                                        ?>
                                            <img src="<?php echo esc_url($foto_src); ?>" 
                                                 <?php if ($foto_srcset) : ?>srcset="<?php echo esc_attr($foto_srcset); ?>" sizes="<?php echo esc_attr($foto_sizes ?: '(max-width: 1024px) 100vw, 25vw'); ?>"<?php endif; ?>
                                                 alt="<?php echo esc_attr($foto_alt); ?>" 
                                                 loading="lazy"
                                                 class="w-full h-full object-cover object-center">
                                        <?php else : ?>
                                            <img src="<?php echo esc_url($foto_url); ?>" 
                                                 alt="<?php echo esc_attr($foto_alt); ?>" 
                                                 loading="lazy"
                                                 class="w-full h-full object-cover object-center">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Naam -->
                                <?php if ($naam) : ?>
                                    <h3 class="mb-[0.5rem]! text-lg lg:text-xl font-semibold"><?php echo esc_html($naam); ?></h3>
                                <?php endif; ?>
                                
                                <!-- Functie -->
                                <?php if ($functie) : ?>
                                    <p class="mb-[0.75rem]! text-base text-gray-600"><?php echo esc_html($functie); ?></p>
                                <?php endif; ?>
                                
                                <!-- Telefoon -->
                                <?php if ($telefoon) : ?>
                                    <p class="mb-[0.5rem]! text-base">
                                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $telefoon)); ?>" class="hover:underline">
                                            <?php echo esc_html($telefoon); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- Emailadres -->
                                <?php if ($emailadres) : ?>
                                    <p class="mb-0! text-base">
                                        <a href="mailto:<?php echo esc_attr($emailadres); ?>" class="hover:underline">
                                            <?php echo esc_html($emailadres); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var swiperEl = document.querySelector('.<?php echo esc_js($rand_class); ?>');
            var slides = swiperEl.querySelectorAll('.swiper-slide');
            var totalSlides = slides.length;
            
            // Functie om te bepalen of loop moet worden ingeschakeld
            function shouldEnableLoop() {
                var width = window.innerWidth;
                var visibleSlides;
                if (width >= 1024) {
                    visibleSlides = 4;
                } else if (width >= 768) {
                    visibleSlides = 3;
                } else if (width >= 640) {
                    visibleSlides = 2;
                } else {
                    visibleSlides = 1;
                }
                return totalSlides > visibleSlides;
            }
            
            var swiperConfig = {
                slidesPerView: 1,
                spaceBetween: 24,
                speed: 600,
                loop: false,
                freeMode: false,
                grabCursor: false,
                centeredSlides: false,
                allowTouchMove: false,
                watchOverflow: true,
                resistance: false,
                resistanceRatio: 0,
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 24
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 32
                    }
                }
            };
            
            // Update configuratie op basis van aantal slides
            function updateConfig() {
                var enableLoop = shouldEnableLoop();
                swiperConfig.loop = enableLoop;
                swiperConfig.allowTouchMove = enableLoop;
                swiperConfig.grabCursor = enableLoop;
                if (enableLoop) {
                    swiperConfig.loopAdditionalSlides = 2;
                }
            }
            
            updateConfig();
            var swiper = new Swiper('.<?php echo esc_js($rand_class); ?>', swiperConfig);
            
            // Update bij resize
            var resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    var newLoopState = shouldEnableLoop();
                    if (newLoopState !== swiper.params.loop) {
                        swiper.destroy(true, true);
                        updateConfig();
                        swiper = new Swiper('.<?php echo esc_js($rand_class); ?>', swiperConfig);
                    }
                }, 250);
            });
        });
    </script>

<?php endif; ?>