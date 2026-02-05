<?php

get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header -->
            <?php if (get_the_title()) : ?>
                <header class="page-header mb-[60px] lg:mb-[200px]">

<div class="px-[20px] pt-[20px] lg:px-[80px] lg:pt-[80px] relative z-1 mb-[60px] lg:mb-12">
    <!-- yoast breadcrumbs -->
    <?php if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
    } ?>
  </div>
                    <div class="container mx-auto px-0!">
                        <div class="w-full lg:w-8/12 mx-auto  px-[20px] lg:px-0!">
                        <div class="flex items-center justify-center gap-2 text-center mb-[20px]">
                        <?php 
                        if(get_post_type() == 'case') {
                            $categories = get_the_category();
                            foreach($categories as $category) {
                                echo '<div class="label-medium text-white bg-blue border border-light-blue  badge">' . $category->name . '</div>';
                            }
                            // Jaartal van get_the_date('Y')
                            echo '<div class="label-medium text-blue bg-white border border-light-blue  badge">' . get_the_date('Y') . '</div>';
                        }
                        if(get_post_type() == 'vacature') {
                            $locatie = get_field('locatie');
                            $uren = get_field('uren_per_week');
                            $type = get_field('type_contract');

                            echo '<div class="flex items-center gap-2">';
                            echo '<div class="label-medium text-white bg-blue border border-light-blue  badge">' . $locatie . '</div>';
                            echo '<div class="label-medium text-blue bg-white border border-light-blue  badge">' . $uren . '</div>';
                            echo '<div class="label-medium text-blue bg-white border border-light-blue  badge">' . $type . '</div>';
                            echo '</div>';
                        }
                        ?>
                        </div>
                        <h1 class="headline-large text-center mb-[32px]!"><?php the_title(); ?></h1>
                        <?php if (get_field('header')['intro']) : ?>
                            <div class="body-large text-center max-w-[630px] mx-auto"><?= get_field('header')['intro'] ?></div>
                        <?php endif; ?>
                    </div>



                    <!-- Image -->
                    <?php if(has_post_thumbnail()) : ?>
                        <div class="w-full mx-auto max-h-[600px] overflow-hidden mt-[60px]">
                            <?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'full', false, array(
                                'class' => 'w-full h-full object-cover object-center',
                                'alt' => get_the_title(),
                                'loading' => 'eager' // Above the fold
                            )); ?>
                        </div>
                    <?php endif; ?>





                    <?php if(get_field('header')['usps']) : ?>

                <div class="bg-pink flex flex-wrap gap-2 justify-center items-center flex-col lg:flex-row relative overflow-hidden lg:py-[60px]">
                    <?= get_template_part('template-parts/core/backgrounds', null, array('color' => 'pink', 'scale' => '35')) ?>
                    <?php foreach(get_field('header')['usps'] as $usp) : ?>
                        <div class="usp flex gap-2 items-center flex-1 w-[calc(100%-40px)] justify-center flex-col border-b lg:border-b-0 border-white/30 lg:border-r text-white py-[40px] relative z-10">
                        
                            <div class="label text-light-pink mb-[12px]!"><?= $usp['label'] ?></div>
                            <span class="title-large leading-[100%]!"><?= $usp['titel'] ?></span>
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
