<?php

get_header(); 


$label = 'Artikel';

if(get_post_type() == 'project') {
    $label = 'Project';
}

?>


<main id="main" class="site-main">
    

    <div class="post-header bg-black pt-[60px] lg:pt-[100px] mb-[40px] lg:mb-[160px] ">
        <div class="container pb-[80px] lg:pb-[120px]">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-[28px]">
                <div class="w-full lg:col-span-8 lg:col-start-3">
                    <p class="label-large text-primary mb-[20px]!"><?= $label; ?></p>
                    <h1 class="text-white text-4xl font-bold mb-[40px]"><?= get_the_title(); ?></h1>
                    <div class="text-white body-large max-w-[790px]"><?php echo get_field('introtekst', get_the_ID()); ?></div>
                </div>
            </div>
        </div>
        <?php if(get_post_type() == 'project') : ?>
            <div class="bg-secondary">
                <div class="container py-[32px]!">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-[28px]">
                        <div class="col-span-1 lg:col-span-8 lg:col-start-3 flex gap-y-[40px] flex-wrap lg:gap-[120px] lg:items-center">
                        <?php if(get_field('project_informatie', get_the_ID())['locatie']) : ?>    
                        <div class="w-1/2 lg:w-auto">
                                <p class="label label-large mb-[20px]!">Locatie</p>
                                <p class="mb-0!"><?= get_field('project_informatie', get_the_ID())['locatie']; ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if(get_field('project_informatie', get_the_ID())['opdrachtgever']) : ?>
                            <div class="w-1/2 lg:w-auto">
                                <p class="label label-large mb-[20px]!">Opdrachtgever</p>
                                <p class="mb-0!"><?= get_field('project_informatie', get_the_ID())['opdrachtgever']; ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if(get_field('project_informatie', get_the_ID())['bouwjaar']) : ?>
                            <div class="w-1/2 lg:w-auto">
                                <p class="label label-large mb-[20px]!">Bouwjaar</p>
                                <p class="mb-0!"><?= get_field('project_informatie', get_the_ID())['bouwjaar']; ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if(get_field('project_informatie', get_the_ID())['overige']) : ?>
                                
                                <?php foreach(get_field('project_informatie', get_the_ID())['overige'] as $overig) : ?>
                                    <div class="w-1/2 lg:w-auto">
                                        <p class="label label-large mb-[20px]!"><?= $overig['label']; ?></p>
                                        <p class="mb-0!"><?= $overig['waarde']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                             
                            <?php endif; ?>
                        </div>
                        <div class="w-full lg:col-span-2 lg:col-start-11">
                            <a href="#gallerij" class="btn border w-full lg:w-auto border-black text-black gap-[14px]">Direct naar gallerij <svg xmlns="http://www.w3.org/2000/svg" width="20" height="12" viewBox="0 0 20 12" fill="none">
  <rect x="19.9996" width="2.22222" height="2.22222" transform="rotate(90 19.9996 0)" fill="#161616"/>
  <rect x="8.148" y="5.92578" width="2.22222" height="2.22222" transform="rotate(90 8.148 5.92578)" fill="#161616"/>
  <rect x="5.18511" y="2.96094" width="2.22222" height="2.22222" transform="rotate(90 5.18511 2.96094)" fill="#161616"/>
  <rect x="2.22222" y="0.000183105" width="2.22222" height="2.22222" transform="rotate(90 2.22222 0.000183105)" fill="#161616"/>
  <rect x="17.0367" y="2.96094" width="2.22222" height="2.22222" transform="rotate(90 17.0367 2.96094)" fill="#161616"/>
  <rect x="11.1109" y="8.89062" width="2.22222" height="2.22222" transform="rotate(90 11.1109 8.89062)" fill="#161616"/>
  <rect x="14.0738" y="5.92578" width="2.22222" height="2.22222" transform="rotate(90 14.0738 5.92578)" fill="#161616"/>
</svg></a>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
        <div class="post-header-image h-[200px] lg:h-[650px]">
          
            <img src="<?= get_field('header_afbeelding', get_the_ID())['sizes']['1536x1536']; ?>" alt="<?= get_field('header_afbeelding', get_the_ID())['alt']; ?>" class="w-full h-full object-cover">
        </div>
        </div>
    </div>









    <!-- Flexible Content -->
    <div class="flexible-content">
          

                <?php if (function_exists('have_rows') && have_rows('blocks', get_the_ID())) : ?>
                    
                    <?php while (have_rows('blocks', get_the_ID())) : the_row(); ?>
                        
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
