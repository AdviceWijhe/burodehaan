<section class="plattegrond <?php echo get_spacing_bottom_class(); ?> overflow-hidden relative">
    <div class="absolute h-1/2 w-full bg-gray bottom-0 left-0"></div>
    <div class="container mx-auto">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full text-center mb-[2.5rem]">
                <div class="badge label mb-4"><?= get_sub_field('label') ?></div>
                <h2><?= get_sub_field('titel') ?></h2>
            </div>
            
        </div>

		<div class="flex flex-col lg:flex-row justify-center">
			<div class="relative inline-block">
				<img src="<?= get_field('plattegrond_afbeelding', 'option')['url'] ?>" class="block h-auto max-w-full" alt="<?= get_field('plattegrond_afbeelding', 'option')['alt'] ?>">

                <?php foreach(get_field('plattegrond_punten', 'option') as $index => $punt) { ?>
                    <div class="absolute transition-opacity plattegrond-punt w-fit" data-index="<?= $index ?>" style="--base-top: <?= $punt['positie_bovenkant'] ?>%; --base-left: <?= $punt['positie_links'] ?>%; top: calc(var(--base-top) - 2%); left: var(--base-left);">
                        <div class="plattegron_punt-bullet bg-white rounded-full w-[10px] h-[10px] absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 outline-4 outline-white/50 cursor-pointer z-50"></div>
                        <div class="plattegrond_punt_name pointer-events-none z-50 absolute top-1/2 -translate-y-1/2 left-[calc(50%+16px)] w-max bg-white rounded-[25px] label py-[0.5rem] px-[1.125rem] border-0 shadow-md opacity-0 transition-opacity duration-300">
                        <?= $punt['titel'] ?>
                        </div>
                        <div class="svg-container pointer-events-none w-full">
                            <img src="<?= $punt['svg']['url'] ?>" alt="<?= $punt['svg']['alt'] ?>" class="w-full h-auto plattegrond-svg">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const punten = document.querySelectorAll('.plattegrond-punt');
            
            punten.forEach(punt => {
                const bullet = punt.querySelector('.plattegron_punt-bullet');
                const naam = punt.querySelector('.plattegrond_punt_name');
                
                bullet.addEventListener('mouseenter', function() {
                    // Alle punten naar opacity-50
                    punten.forEach(p => p.style.opacity = '0.5');
                    // Huidige punt naar opacity-100
                    punt.style.opacity = '1';
                    // Naam zichtbaar maken
                    naam.style.opacity = '1';
                });
                
                bullet.addEventListener('mouseleave', function() {
                    // Alle punten terug naar opacity-100
                    punten.forEach(p => p.style.opacity = '1');
                    // Naam verbergen
                    naam.style.opacity = '0';
                });
            });
        });
        </script>
        
        <style>
        
        /* Tablet: verminder top met 0% en SVG 80% van originele grootte */
        /* @media (min-width: 768px) and (max-width: 1023px) { */
        @media (max-width: 1023px) {
            /* Pas positie aan op basis van data-index */
            .plattegrond-punt[data-index="6"] {
                top: calc(var(--base-top) - 0%) !important;
                left: calc(var(--base-left) - 1%) !important;
                img {
                    width: 32vw;
                }
            }
            .plattegrond-punt[data-index="5"] {
                top: calc(var(--base-top) - -0.5%) !important;
                left: calc(var(--base-left) - 0%) !important;
                img {
                    width: 11vw;
                }
            }
            .plattegrond-punt[data-index="4"] {
                top: calc(var(--base-top) - 0%) !important;
                left: calc(var(--base-left) - 0%) !important;
                img {
                    width: 22vw;
                }
            }
            .plattegrond-punt[data-index="3"] {
                top: calc(var(--base-top) - 0%) !important;
                left: calc(var(--base-left) - 0%) !important;
                img {
                    width: 18vw;
                }
            }
            .plattegrond-punt[data-index="2"] {
                top: calc(var(--base-top) - 0%) !important;
                left: calc(var(--base-left) - 0%) !important;
                img {
                    width: 7vw;
                }
            }
            .plattegrond-punt[data-index="1"] {
                top: calc(var(--base-top) - 0%) !important;
                left: calc(var(--base-left) - 0%) !important;
                img {
                    width: 13vw;
                }
            }
            .plattegrond-punt[data-index="0"] {
                top: calc(var(--base-top) - 0%) !important;
                left: calc(var(--base-left) - 0%) !important;
                img {
                    width: 28vw;
                }
            }
        }
        
        /* Desktop: gebruik originele positie en grootte */
        @media (min-width: 1024px) {
            .plattegrond-punt {
                top: var(--base-top) !important;
            }
            .plattegrond-svg {
                transform: scale(1);
            }
        }
        @
        </style>
    </div>
</section>