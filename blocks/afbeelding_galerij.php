<?php
$afbeeldingen = get_sub_field('afbeeldingen');
?>

<section class="afbeelding_galerij overflow-hidden">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-[1.75rem]">
            <div class="col-span-1 lg:col-span-10 lg:col-start-2">
            <div class="swiper-galerij relative">
                <div class="swiper-wrapper">
                    <?php foreach($afbeeldingen as $afbeelding) : ?>
                        <div class="swiper-slide">
                            <div class=" h-[200px] lg:h-[700px]">
                            <?php echo wp_get_attachment_image($afbeelding['ID'], 'large', false, array('class' => 'w-full h-full object-cover object-center')); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-buttons flex absolute top-0 right-0" style="z-index: 2;">
                    <div class="swiper-prev w-[60px] h-[60px] bg-secondary flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                    <rect x="11.1128" y="20.0001" width="2.22222" height="2.22222" transform="rotate(-180 11.1128 20.0001)" fill="#161616"/>
                    <rect x="5.18707" y="8.14801" width="2.22222" height="2.22222" transform="rotate(-180 5.18707 8.14801)" fill="#161616"/>
                    <rect x="8.15191" y="5.18511" width="2.22222" height="2.22222" transform="rotate(-180 8.15191 5.18511)" fill="#161616"/>
                    <rect x="11.1127" y="2.22222" width="2.22222" height="2.22222" transform="rotate(-180 11.1127 2.22222)" fill="#161616"/>
                    <rect x="8.15191" y="17.0372" width="2.22222" height="2.22222" transform="rotate(-180 8.15191 17.0372)" fill="#161616"/>
                    <rect x="2.22222" y="11.1109" width="2.22222" height="2.22222" transform="rotate(-180 2.22222 11.1109)" fill="#161616"/>
                    <rect x="5.18707" y="14.0743" width="2.22222" height="2.22222" transform="rotate(-180 5.18707 14.0743)" fill="#161616"/>
                  </svg>
                    </div>
                    <span class="cards-grid-nav-divider block">
                  <svg xmlns="http://www.w3.org/2000/svg" width="1" height="59" viewBox="0 0 1 59" fill="none">
                    <line opacity="0.12" x1="0.5" y1="2.18558e-08" x2="0.499997" y2="59" stroke="#161616"/>
                  </svg>
                </span>
                    <div class="swiper-next w-[60px] h-[60px] bg-secondary flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                    <rect width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="2.96094" y="14.8148" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="0.00012207" y="17.7777" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="2.96094" y="2.96313" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#161616"/>
                  </svg>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <script> 
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.afbeelding_galerij .swiper-galerij', {
                loop: true,
                centeredSlides: true,
                spaceBetween: 36,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-galerij .swiper-next',
                    prevEl: '.swiper-galerij .swiper-prev',
                },
            });
        });
    </script>
</section>