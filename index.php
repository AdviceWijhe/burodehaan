<?php
/**
 * Main template file - Archief met categorieën
 */


//  GITHUB TEST !!!!
get_header(); ?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (have_posts()) : ?>
            
            <?php
            // Groepeer posts per categorie
            $posts_by_category = array();
            $all_posts = get_posts(array(
                'numberposts' => -1,
                'post_type' => 'post',
                'post_status' => 'publish'
            ));
            
            foreach ($all_posts as $post) {
                $categories = get_the_category($post->ID);
                if (!empty($categories)) {
                    $category_name = $categories[0]->name;
                    if (!isset($posts_by_category[$category_name])) {
                        $posts_by_category[$category_name] = array();
                    }
                    $posts_by_category[$category_name][] = $post;
                }
            }
            ?>
            
            <?php foreach ($posts_by_category as $category_name => $category_posts) : ?>
                
                <!-- Categorie titel -->
                <div class="mb-8">
                    <h2 class="text-4xl font-normal text-blue-950 leading-tight font-['Greycliff_CF:Regular',_sans-serif]">
                        <?php echo esc_html($category_name); ?>
                        <span class="text-red-600 text-xs ml-2 font-['Zapf_Dingbats:Regular',_sans-serif]">■</span>
                    </h2>
                </div>
                
                <!-- Posts in deze categorie -->
                <div class="space-y-4 mb-16">
                    <?php foreach ($category_posts as $index => $post) : 
                        setup_postdata($post);
                        $is_even = ($index % 2 == 1);
                        $bg_class = $is_even ? 'bg-white' : 'bg-gray-100';
                    ?>
                        
                        <div class="<?php echo $bg_class; ?> h-32 w-full flex items-center relative">
                            
                            <!-- Document thumbnail -->
                            <div class="absolute left-36 w-26 h-26 bg-white border border-gray-200 flex items-center justify-center">
                                <?php if (has_post_thumbnail($post->ID)) : ?>
                                    <?php echo wp_get_attachment_image(get_post_thumbnail_id($post->ID), 'thumbnail', false, array(
                                        'class' => 'w-20 h-20 object-contain shadow-lg',
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
                            <div class="absolute left-80 top-1/2 transform -translate-y-1/2 w-104">
                                <h3 class="text-xl font-normal text-blue-950 leading-relaxed font-['Greycliff_CF:Regular',_sans-serif]">
                                    <?php echo get_the_title($post->ID); ?>
                                </h3>
                            </div>
                            
                            <!-- Download button -->
                            <div class="absolute right-1/2 top-1/2 transform -translate-y-1/2 translate-x-1/2">
                                <?php 
                                // Zoek naar attachments voor deze post
                                $attachments = get_attached_media('', $post->ID);
                                $download_url = '';
                                
                                if (!empty($attachments)) {
                                    // Neem het eerste attachment als download
                                    $first_attachment = reset($attachments);
                                    $download_url = wp_get_attachment_url($first_attachment->ID);
                                } else {
                                    // Fallback naar permalink als er geen attachment is
                                    $download_url = get_permalink($post->ID);
                                }
                                ?>
                                <a href="<?php echo esc_url($download_url); ?>" 
                                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded flex items-center gap-2 transition-colors"
                                   <?php if (!empty($attachments)) echo 'download'; ?>>
                                    <svg class="w-3 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium font-['Greycliff_CF:Medium',_sans-serif]">Download document</span>
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
                    <h1 class="page-title text-3xl font-bold text-gray-900">Niets gevonden</h1>
                </header>
                
                <div class="page-content prose prose-lg">
                    <p>Het lijkt erop dat we niet kunnen vinden wat je zoekt. Misschien kan zoeken helpen.</p>
                    <?php get_search_form(); ?>
                </div>
            </section>
            
        <?php endif; ?>
        
    </div>
</main>

<?php
get_sidebar();
get_footer();
?>
