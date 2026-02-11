<?php

get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header: titel + excerpt links, uitgelichte afbeelding rechts, op achtergrond tertiary -->
            <?php if (get_the_title()) : ?>
                <header class="page-header mb-[60px] lg:mb-[200px]">
                    <div class="bg-[var(--color-light)] w-full">
                        <!-- Breadcrumbs linksboven, 40px boven de foto/content -->
                        <div class="px-[20px] pt-[20px] lg:px-[80px] lg:pt-[80px] pb-[100px] lg:pb-[100px]">
                            <?php if (function_exists('yoast_breadcrumb')) {
                                yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
                            } ?>
                        </div>
                        <div class="w-full flex flex-col lg:flex-row lg:min-h-[320px]">
                            <!-- Links: titel + excerpt -->
                            <div class="flex-1 px-[20px] lg:px-[80px] py-[40px] lg:py-[60px] order-2 lg:order-1 flex flex-col justify-center">
                                <div class="flex flex-wrap items-center gap-2 mb-[20px]">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        foreach ($categories as $category) {
                                            echo '<span class="label-medium text-black">' . esc_html($category->name) . '</span>';
                                            if ($category !== end($categories)) {
                                                echo '<span class="label-medium text-black">·</span>';
                                            }
                                        }
                                    }
                                    if (!empty($categories)) {
                                        echo '<span class="label-medium text-black">·</span>';
                                    }
                                    echo '<span class="label-medium text-black">' . esc_html(get_the_date()) . '</span>';
                                    ?>
                                </div>
                                <h1 class="headline-large text-black mb-[24px]! text-[48px]!"><?php the_title(); ?></h1>
                                <?php
                                $intro = get_field('header')['intro'] ?? '';
                                if ($intro) :
                                    echo '<div class="body-large text-black">' . wp_kses_post($intro) . '</div>';
                                elseif (has_excerpt()) :
                                    echo '<div class="body-large text-black">' . get_the_excerpt() . '</div>';
                                endif;
                                ?>
                            </div>
                            <!-- Rechts: uitgelichte afbeelding, helemaal tegen de rechterrand -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="lg:w-[45%] lg:min-w-[400px] order-1 lg:order-2 lg:ml-auto">
                                    <div class="w-full h-full min-h-[240px] lg:min-h-full aspect-[4/3] lg:aspect-auto rounded-tl-[16px] overflow-hidden" style="border-radius: 16px 0 0 0;">
                                        <?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'large', false, array(
                                            'class' => 'w-full h-full object-cover object-center',
                                            'alt' => get_the_title(),
                                            'loading' => 'eager',
                                        )); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (get_field('header')['usps']) : ?>
                            <div class="bg-pink flex flex-wrap gap-2 justify-center items-center flex-col lg:flex-row relative overflow-hidden lg:py-[60px]">
                                <?php foreach (get_field('header')['usps'] as $usp) : ?>
                                    <div class="usp flex gap-2 items-center flex-1 w-[calc(100%-40px)] justify-center flex-col border-b lg:border-b-0 border-white/30 lg:border-r text-white py-[40px] relative z-10">
                                        <div class="label text-light-pink mb-[12px]!"><?= esc_html($usp['label'] ?? '') ?></div>
                                        <span class="title-large leading-[100%]!"><?= esc_html($usp['titel'] ?? '') ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>
            <?php endif; ?>
            
            <!-- Flexible Content -->
            <div class="flexible-content">
                <?php if (function_exists('have_rows') && have_rows('blocks')) : ?>
                    
                    <?php while (have_rows('blocks')) : the_row(); ?>
                        
                        <?php
                        $layout = get_row_layout();
                        ?>
                        
                        <section class="content-block content-block-<?php echo esc_attr($layout); ?>">
                            
                            <?php
                     
                            // Probeer het block template te laden
                            $block_template = locate_template('blocks/' . $layout . '.php');
                            
                            if ($block_template) {
                                // Laad het block template
                                get_template_part('blocks/' . $layout);
                            } else {
                                // Fallback voor onbekende layouts
                                ?>
                                <div class="py-8">
                                    <div class="container mx-auto px-4">
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <p class="text-yellow-800">
                                                <strong>Layout niet gevonden:</strong> <?php echo esc_html($layout); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            
                        </section>
                        
                    <?php endwhile; ?>
                    
                <?php else : ?>
                    
                    <!-- Fallback content als ACF niet actief is of geen content -->
                    <div class="py-16">
                        <div class="container mx-auto px-4">
                            <div class="max-w-4xl mx-auto">
                                <?php if (get_the_content()) : ?>
                                    <div class="prose prose-lg max-w-none">
                                        <?php the_content(); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="text-center py-12">
                                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Geen content gevonden</h2>
                                        <p class="text-gray-600">Voeg flexible content toe via ACF of gebruik de standaard content editor.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                <?php endif; ?>
            </div>


        <?php if(get_post_type() == 'vacature') : ?>
            <div class="container mx-auto px-0">
                <div class="flex items-center justify-center">
                    <div class="bg-light-pink/25 text-pink py-[60px] lg:py-[100px] w-full">
                    <div class="max-w-[580px] mx-auto px-[20px] lg:px-0!">
                        <h5 class="headline-medium text-center mb-[32px]! text-secondary">Reageren op deze vacature?</h5>
                        <div class="body-large text-center font-normal text-secondary">Bij interesse op deze vacature kun je je cv en motivatiebrief sturen naar <a href="mailto:werkenbij@managementsecrets.nl" class="text-pink underline">werkenbij@managementsecrets.nl.</a></div>
                    </div>
                    </div>
                </div>
               
            </div>

            <?php 
                // template part cta
                get_template_part('blocks/cta', null, array('cta_type' => 'default'));
                ?>
            <?php endif; ?>
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>
