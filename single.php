<?php

get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header: titel + excerpt links, uitgelichte afbeelding rechts, op achtergrond tertiary -->
            <?php if (get_the_title()) : ?>
                <header class="page-header mb-[60px] lg:mb-[100px]">
                    <div class="bg-[var(--color-light)] w-full">
                        <!-- Breadcrumbs linksboven, 40px boven de foto/content -->
                        <div class="px-[20px] pt-[20px] lg:px-[80px] lg:pt-[80px] pb-[100px] lg:pb-[100px]">
                            <?php if (function_exists('yoast_breadcrumb')) {
                                yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
                            } ?>
                        </div>
                        <div class="w-full flex flex-col lg:flex-row lg:min-h-[320px] gap-[150px]">
                            <!-- Links: titel + excerpt -->
                            <div class="flex-1 pl-[20px] lg:pl-[80px] py-[40px] lg:py-[60px] order-2 lg:order-1 flex flex-col justify-center">
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
                                <!-- Auteur: avatar links, naam rechts -->
                                <div class="flex items-center gap-3 mt-[24px]">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 48, '', get_the_author(), array('class' => 'rounded-full w-12 h-12 object-cover')); ?>
                                    <span class="body-medium text-black"><?php the_author(); ?></span>
                                </div>
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
                    <div class="pb-[0px]">
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

            <!-- Auteurblok onderaan: 12-koloms layout, avatar + naam + bio op color-light -->
            <?php
            $author_bio = get_the_author_meta('description');
            if (empty(trim($author_bio))) {
                $author_bio = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
            }
            ?>
            <section class="author-bio py-[40px] lg:pt-[120px] lg:pb-[200px]">
                <div class="container mx-auto px-[20px] lg:px-[80px]">
                    <div class="max-w-4xl mx-auto bg-[var(--color-light)] rounded-[16px] py-[40px] lg:py-[60px] px-[24px] lg:px-[48px]">
                        <h3 class="headline-small text-black mb-[24px]!">Over deze auteur</h3>
                        <div class="flex items-center gap-4 mb-[24px]">
                            <?php echo get_avatar(get_the_author_meta('ID'), 80, '', get_the_author(), array('class' => 'rounded-full w-20 h-20 object-cover flex-shrink-0')); ?>
                            <span class="headline-small text-black"><?php the_author(); ?></span>
                        </div>
                        <div class="body-medium text-black">
                            <?php echo wp_kses_post(wpautop($author_bio)); ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3 willekeurige posts + knop naar alle artikelen -->
            <?php
            $related_posts = new WP_Query(array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 3,
                'orderby'        => 'rand',
                'post__not_in'   => array(get_the_ID()),
            ));
            ?>
            <?php if ($related_posts->have_posts()) : ?>
                <section class="related-posts pb-[60px] lg:pb-[100px]">
                    <div class="container mx-auto px-[20px] lg:px-[80px]">
                        <div class="max-w-4xl mx-auto">
                            <h2 class="headline-medium text-black mb-[32px]!">Meer artikelen</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-[40px]">
                                <?php
                                while ($related_posts->have_posts()) :
                                    $related_posts->the_post();
                                    $categories = get_the_category();
                                    $category = !empty($categories) ? $categories[0] : null;
                                ?>
                                    <a href="<?php the_permalink(); ?>" class="block group overflow-hidden rounded-[16px] bg-white flex flex-col h-full border border-gray-200">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="relative w-full aspect-[16/10] overflow-hidden rounded-t-[16px] flex-shrink-0">
                                                <?php the_post_thumbnail('medium_large', array(
                                                    'class'   => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105',
                                                    'alt'     => get_the_title(),
                                                    'loading' => 'lazy',
                                                )); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="bg-light p-4 rounded-b-[16px] flex flex-col flex-1 min-h-0">
                                            <div class="flex items-center gap-2 mb-2 flex-shrink-0">
                                                <?php if ($category) : ?>
                                                    <span class="label-small text-black"><?php echo esc_html($category->name); ?></span>
                                                <?php endif; ?>
                                                <span class="body-small text-black"><?php echo get_the_date(); ?></span>
                                            </div>
                                            <h3 class="headline-small text-black group-hover:text-primary transition-colors line-clamp-2">
                                                <?php the_title(); ?>
                                            </h3>
                                        </div>
                                    </a>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                            <div class="flex justify-center">
                                <a href="<?php echo esc_url(home_url('/nieuws')); ?>" class="btn bg-primary text-white border border-primary hover:bg-secondary hover:text-white">
                                    <?php esc_html_e('Alle artikelen', 'advice2025'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

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
