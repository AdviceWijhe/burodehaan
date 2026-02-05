<?php
/**
 * Template Name: Flexible Content (ACF)
 * 
 * Template voor pagina's met ACF Flexible Content velden
 */

get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header -->
            <!-- <?php if (get_the_title()) : ?>
                <header class="page-header bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
                    <div class="container mx-auto px-4">
                        <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                        <?php if (get_the_excerpt()) : ?>
                            <p class="text-xl text-blue-100 max-w-3xl"><?php echo get_the_excerpt(); ?></p>
                        <?php endif; ?>
                    </div>
                </header>
            <?php endif; ?> -->
            
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
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>
