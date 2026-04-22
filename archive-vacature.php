<?php
/**
 * Archive Template voor Vacatures
 * Toont alle vacatures op de "Werken bij" pagina
 */

get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <!-- Page Header -->
    <section class="hero pt-8 pb-20 relative">
        <div class="absolute bg-gray h-full max-h-[37.5rem] left-0 top-0 w-full"></div>
        <div class="px-8 relative z-1 mb-12">
            <!-- yoast breadcrumbs -->
            <?php if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
            } ?>
        </div>
        <div class="container mx-auto">
            <div class="flex relative">
                <div class="w-full lg:w-5/12">
                    <div class="label badge">Werken bij</div>
                    <h1 class="mb-5">Werken bij <?php bloginfo('name'); ?></h1>
                    <div class="max-w-[36.25rem] lead">
                        <p>Ontdek de mogelijkheden om bij ons te komen werken. We zoeken altijd naar getalenteerde mensen die ons team willen versterken.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vacatures Lijst -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <?php if (have_posts()) : ?>
                <div class="w-full lg:w-8/12 mx-auto">
                    <div class="vacatures-lijst flex flex-col gap-6">
                        <?php 
                        $index = 0;
                        while (have_posts()) : the_post(); 
                            $is_even = ($index + 1) % 2 === 0;
                            $background_class = $is_even ? 'bg-white' : 'bg-gray';
                        ?>
                            <?php get_template_part('template-parts/card-vacature', null, array('vacature' => get_the_ID(), 'background_class' => $background_class)) ?>
                        <?php 
                            $index++;
                        endwhile; 
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="w-full lg:w-8/12 mx-auto">
                    <div class="text-center">
                        <p class="text-gray-600">Er zijn momenteel geen vacatures beschikbaar.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Sectie -->
    <?php
    get_template_part('blocks/cta', null, array(
        'contactpersoon' => null, // Pas aan naar gewenste contactpersoon
        'titel' => 'Geen passende vacature gevonden?',
        'top_titel' => 'Neem contact met ons op'
    ))
    ?>

</main>

<?php get_footer(); ?>


