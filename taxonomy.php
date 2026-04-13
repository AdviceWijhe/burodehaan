<?php

get_header(); 


$tax_slug = get_queried_object()->taxonomy;
$tax_id = get_queried_object()->term_id;
?>


<main id="main" class="site-main">
    

    <div class="tax-header bg-black pt-[100px] mb-[160px]">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-[28px]">
            <div class="w-full lg:col-span-6 lg:col-start-3">
                <p class="label-large text-primary mb-[20px]!"><?php echo $tax_slug; ?></p>
                <h1 class="text-white text-4xl font-bold mb-[40px]"><?php single_term_title(); ?></h1>
                <div class="text-white body-large max-w-[790px]"><?php echo get_field('introtekst', $tax_slug.'_'.$tax_id); ?></div>
            </div>
            <div class="w-full lg:col-span-3 lg:col-start-10">
                <div class="glass rounded-lg p-[40px] mb-[-16px]">
                    <?php $cta = get_field('header_cta', $tax_slug.'_'.$tax_id ); 
                    ?>
                    <?php if($cta) : ?>
                        <div class="cta-content">
                            <div class="text-white text-2xl font-bold mb-[32px]"><?php echo $cta['titel']; ?></div>
                            <div class="mb-[32px]">
                                <?= get_template_part('template-parts/card-contactpersoon', null, array('variant' => 'default', 'text-color' => 'white', 'medewerker' => $cta['contactpersoon'])) ?>
                            </div>

                            <?= get_template_part('template-parts/core/buttons', null, array('buttons' => $cta['buttons'], 'align_items' => 'stretch', 'full_width' => true)) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            </div>
        </div>
    </div>









    <!-- Flexible Content -->
    <div class="flexible-content">
          

                <?php if (function_exists('have_rows') && have_rows('blocks', $tax_slug.'_'.$tax_id)) : ?>
                    
                    <?php while (have_rows('blocks', $tax_slug.'_'.$tax_id)) : the_row(); ?>
                        
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
</main>








<?php get_footer(); ?>
