<?php
/**
 * Main template file - Archief met categorieën
 */

get_header(); ?>

<main id="main" class="site-main">



<section class="hero pb-0 mb-20 lg:mb-0 lg:pb-20 pt-8 relative">
  <div class="absolute bg-gray lg:h-full h-[calc(100%-30px)] max-h-[362px] left-0 top-0 w-full"></div>
  <div class="px-5 lg:px-8 relative z-1 mb-3 lg:mb-20">
    <!-- yoast breadcrumbs -->
    <?php if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
    } ?>
  </div>
  <div class="container mx-auto">
    <div class="flex flex-col lg:flex-row relative">
      <div class="w-full lg:w-5/12">
        <div class="label badge">Kennisbank</div>
        <h1 class="mb-10">Alle downloads en informatie op een rijtje</h1>



        <!-- Deze knop moet een scroll down van 250px krijgen onclick -->
      <!-- Zoekbalk voor de kennisbank  -->
       <?php get_template_part('searchform-kennisbank'); ?>
       
      </div>

      <div class="w-full hidden lg:block lg:w-4/12 lg:ml-auto bg-white relative z-2 lg:absolute lg:top-0 lg:right-0">
        

        <div class="bg-blue p-10">
          <?= get_template_part('template-parts/card-contactpersoon', null, array('medewerker' => get_field('kennisbank_contactpersoon', 'option'), 'text-color' => 'white')) ?>
        </div>
      </div>
    </div>



   
  </div>
</section>



    <div class="container mx-auto px-4 pt-8 pb-16">
        




    <div class="w-full lg:w-8/12">
        <?php 
        // Check if this is a search
        $is_search = is_search() && isset($_GET['kennisbank_search']);
        $search_query = $is_search ? get_search_query() : '';
        
        if ($is_search && !empty($search_query)) {
            // Search functionality - zoek in alle posts van dit post type
            $search_args = array(
                'post_type' => get_post_type(),
                'post_status' => 'publish',
                's' => $search_query,
                'posts_per_page' => -1
            );
            $search_posts = get_posts($search_args);
        } else {
            // Regular archive functionality - toon alle posts van dit post type
            $search_posts = get_posts(array(
                'numberposts' => -1,
                'post_type' => get_post_type(),
                'post_status' => 'publish'
            ));
        }
        
        if (!empty($search_posts)) : ?>
            
            <?php
            // Groepeer posts per categorie
            $posts_by_category = array();
            
            foreach ($search_posts as $post) {
                $categories = get_the_category($post->ID);
                if (!empty($categories)) {
                    $category_name = $categories[0]->name;
                    if (!isset($posts_by_category[$category_name])) {
                        $posts_by_category[$category_name] = array();
                    }
                    $posts_by_category[$category_name][] = $post;
                }
            }
            
            // Show search results header if searching (only for non-AJAX searches)
            if ($is_search && !empty($search_query) && !wp_doing_ajax()) {
                echo '<div class="mb-8">';
                echo '<h2 class="text-2xl font-bold text-[#092354] mb-4">Zoekresultaten voor: "' . esc_html($search_query) . '"</h2>';
                echo '<p class="text-gray-600">' . count($search_posts) . ' resultaten gevonden</p>';
                echo '</div>';
            }
            ?>
            
            <?php foreach ($posts_by_category as $category_name => $category_posts) : ?>
                
                <!-- Categorie titel -->
                <div class="mb-8">
                    <h2 class="">
                        <?php echo esc_html($category_name); ?>

                    </h2>
                </div>
                
                <!-- Posts in deze categorie -->
                <div class="pb-[6.25rem]">
                    <?php foreach ($category_posts as $index => $post) : 
                        setup_postdata($post);
                        $is_even = ($index % 2 == 1);
                        $bg_class = $is_even ? 'bg-white' : 'bg-gray-100';
                    ?>
                        
                        <div class="<?php echo $bg_class; ?>  w-full flex items-center justify-between relative p-3 pe-3 lg:pe-10">
                            <div class="flex gap-4 items-center">
                            <!-- Document thumbnail -->
                            <div class=" min-w-26 min-h-26 w-26 h-26 bg-white border border-gray-200 flex items-center justify-center p-2">
                                <?php if (has_post_thumbnail($post->ID)) : ?>
                                    <?php echo wp_get_attachment_image(get_post_thumbnail_id($post->ID), 'large', false, array(
                                        'class' => 'h-full w-auto object-contain',
                                        'alt' => get_the_title($post->ID),
                                        'loading' => 'lazy'
                                    )); ?>
                                <?php else : ?>
                                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Document titel -->
                            <div class="">
                                <h3 class="">
                                    <?php echo get_the_title($post->ID); ?>
                                </h3>
                            </div>
                            </div>
                            <!-- Download button -->
                            <div class="">
                                <?php 
                                // Zoek naar attachments voor deze post
                                $attachments = get_attached_media('', $post->ID);
                                $download_url = get_field('download')['url'];
                                
                                
                                ?>
                                <a href="<?php echo esc_url($download_url); ?>"  target="_blank" download
                                   class="btn btn-red flex items-center gap-2 transition-colors self-end lg:min-w-[195px]"
                                   <?php if (!empty($attachments)) echo 'download'; ?>>
                                   <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15" fill="none">
  <path d="M5.39746 12.1455L0.0761716 6.82519L1.14062 5.76074L4.69922 9.32031L4.69922 -2.63079e-07L6.2041 -1.97299e-07L6.2041 9.21191L9.6543 5.76074L10.7178 6.8252L5.39746 12.1455Z" fill="white"/>
  <rect y="13.4952" width="10.6409" height="1.50488" fill="white"/>
</svg>
                                    <span class="hidden lg:block">Download document</span>
                                </a>
                            </div>
                            
                        </div>
                        
                    <?php endforeach; ?>
                </div>
                
            <?php endforeach; 
            wp_reset_postdata(); ?>
            
        <?php else : ?>
            
            <section class="no-results not-found">
                <header class="page-header mb-8">
                    <?php if ($is_search && !empty($search_query)) : ?>
                        <h1 class="page-title text-3xl font-bold text-gray-900">Geen resultaten gevonden</h1>
                        <p class="text-gray-600 mt-4">Er zijn geen resultaten gevonden voor: "<strong><?php echo esc_html($search_query); ?></strong>"</p>
                    <?php else : ?>
                        <h1 class="page-title text-3xl font-bold text-gray-900">Geen kennisbank items gevonden</h1>
                        <p class="text-gray-600 mt-4">Er zijn momenteel geen items beschikbaar in de kennisbank.</p>
                    <?php endif; ?>
                </header>
                
                <div class="page-content prose prose-lg">
                    <p>Probeer een andere zoekterm of bekijk alle beschikbare categorieën.</p>
                    <?php get_template_part('searchform-kennisbank'); ?>
                </div>
            </section>
            
        <?php endif; ?>
        </div>
    </div>

    <!-- Voeg flexible content toe vanaf pagina met id 236 -->
    <div class="flexible-content">
                <?php if (function_exists('have_rows') && have_rows('blocks', 91)) : ?>
                    
                    <?php while (have_rows('blocks', 91)) : the_row(); ?>
                        
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

<?php

get_footer();
?>
