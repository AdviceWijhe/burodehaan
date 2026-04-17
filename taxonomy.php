<?php

get_header(); 


$tax_slug = get_queried_object()->taxonomy;
$tax_id = get_queried_object()->term_id;
$term_key = $tax_slug . '_' . $tax_id;
$cta = get_field('header_cta', $term_key);

$header_image = get_field('headerafbeelding', $term_key);
$header_image_url = '';
if (is_array($header_image) && !empty($header_image['url'])) {
    $header_image_url = (string) $header_image['url'];
} elseif (is_numeric($header_image)) {
    $header_image_url = (string) wp_get_attachment_image_url((int) $header_image, 'full');
}
?>


<main id="main" class="site-main">
    

    <?php if ($tax_slug === 'thema' && $header_image_url !== '') : ?>
        <div class="tax-header relative mb-[160px] bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo esc_url($header_image_url); ?>');">
            <div class="absolute top-0 right-0 h-full w-1/2 pointer-events-none" style="opacity: 0.3; background: linear-gradient(90deg, rgba(0, 0, 0, 0.00) 0%, #000 100%);"></div>
            <div class="container relative">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-[28px] items-end pt-[80px] pb-[30px]">
                    <div class="w-full lg:col-span-6 lg:col-start-3">
                        <div class="text-white body-large max-w-[790px]"><?php echo get_field('introtekst', $term_key); ?></div>
                    </div>
                    <div class="w-full lg:col-span-3 lg:col-start-10">
                        <?php if ($cta) : ?>
                            <div class="glass rounded-lg p-[40px]">
                                <div class="cta-content">
                                    <div class="text-white text-2xl font-bold mb-[32px]"><?php echo wp_kses_post($cta['titel'] ?? ''); ?></div>
                                    <div class="mb-[32px]">
                                        <?= get_template_part('template-parts/card-contactpersoon', null, array('variant' => 'default', 'text-color' => 'white', 'medewerker' => $cta['contactpersoon'] ?? null)) ?>
                                    </div>

                                    <?= get_template_part('template-parts/core/buttons', null, array('buttons' => $cta['buttons'] ?? array(), 'align_items' => 'stretch', 'full_width' => true)) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="tax-header bg-black pt-[60px] lg:pt-[100px] mb-[40px] lg:mb-[160px]">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-[28px]">
                <div class="w-full lg:col-span-6 lg:col-start-3">
                    <p class="label-large text-primary mb-[20px]!"><?php echo esc_html($tax_slug); ?></p>
                    <h1 class="text-white text-4xl font-bold mb-[40px]"><?php single_term_title(); ?></h1>
                    <div class="text-white body-large max-w-[790px]"><?php echo get_field('introtekst', $term_key); ?></div>
                </div>
                <div class="w-full lg:col-span-3 lg:col-start-10">
                    <div class="glass rounded-lg p-[40px] mb-[-16px]">
                        <?php if($cta) : ?>
                            <div class="cta-content">
                                <div class="text-white text-2xl font-bold mb-[32px]"><?php echo wp_kses_post($cta['titel'] ?? ''); ?></div>
                                <div class="mb-[32px]">
                                    <?= get_template_part('template-parts/card-contactpersoon', null, array('variant' => 'default', 'text-color' => 'white', 'medewerker' => $cta['contactpersoon'] ?? null)) ?>
                                </div>

                                <?= get_template_part('template-parts/core/buttons', null, array('buttons' => $cta['buttons'] ?? array(), 'align_items' => 'stretch', 'full_width' => true)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    <?php endif; ?>









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
