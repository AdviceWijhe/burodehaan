<?php
/**
 * Posts Archive Template
 * 
 * Custom template voor de standaard WordPress posts archive pagina
 * Met hero sectie, zoekbalk en posts overzicht
 */

get_header(); 

// Haal ACF velden op van de posts archive pagina
$posts_page_id = get_option('page_for_posts');
$hero_foto = get_field('hero_foto', $posts_page_id);
$titel = get_field('titel', $posts_page_id);
$introtekst = get_field('introtekst', $posts_page_id);

?>

<main id="main" class="site-main">
    
    <!-- Hero Sectie -->
    <?php if ($hero_foto || $titel || $introtekst) : ?>
        <section class="hero-section relative w-full h-[80vh] flex items-end <?php echo $hero_foto ? '' : 'bg-gray'; ?>">
            <?php if ($hero_foto) : ?>
                <div class="absolute inset-0 z-0">
                    <?php 
                    // Handle ACF image field (kan array of ID zijn)
                    if (is_array($hero_foto) && isset($hero_foto['url'])) {
                        $hero_url = $hero_foto['url'];
                        $hero_alt = isset($hero_foto['alt']) ? $hero_foto['alt'] : ($titel ?: 'Hero afbeelding');
                    } elseif (is_numeric($hero_foto)) {
                        $hero_url = wp_get_attachment_image_src($hero_foto, 'full')[0];
                        $hero_alt = get_post_meta($hero_foto, '_wp_attachment_image_alt', true) ?: ($titel ?: 'Hero afbeelding');
                    } else {
                        $hero_url = $hero_foto;
                        $hero_alt = $titel ?: 'Hero afbeelding';
                    }
                    ?>
                    <img 
                        src="<?php echo esc_url($hero_url); ?>" 
                        alt="<?php echo esc_attr($hero_alt); ?>"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>
            <?php endif; ?>
            
            <div class="container mx-auto px-[20px] lg:px-[80px] pb-[60px] lg:pb-[80px] relative z-10 w-full">
                <div class="max-w-4xl">
                    <?php if ($titel) : ?>
                        <h1 class="headline-large <?php echo $hero_foto ? 'text-white' : 'text-black'; ?> mb-[24px]!"><?php echo esc_html($titel); ?></h1>
                    <?php endif; ?>
                    
                    <?php if ($introtekst) : ?>
                        <div class="body-large <?php echo $hero_foto ? 'text-white' : 'text-black'; ?> mb-[40px]!">
                            <?php echo wp_kses_post($introtekst); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Zoekbalk -->
                    <div class="max-w-2xl">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Populaire Items Sectie -->
    <?php
    // Haal alle posts op en sorteer op view count
    $all_posts_for_popular = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => -1
    ));
    
    if (!empty($all_posts_for_popular)) {
        // Sorteer op view count (hoogste eerst)
        usort($all_posts_for_popular, function($a, $b) {
            $count_a = advice2025_get_post_view_count($a->ID);
            $count_b = advice2025_get_post_view_count($b->ID);
            return $count_b - $count_a; // Sorteer van hoog naar laag
        });
        
        // Neem de eerste 4 posts
        $popular_posts = array_slice($all_posts_for_popular, 0, 4);
    } else {
        $popular_posts = array();
    }
    
    // Toon sectie als er posts zijn
    if (!empty($popular_posts) && count($popular_posts) > 0) :
        $first_post = $popular_posts[0];
        $other_posts = count($popular_posts) > 1 ? array_slice($popular_posts, 1, 3) : array();
    ?>
        <section class="popular-posts py-[60px] lg:py-[80px]">
            <div class="container mx-auto px-[20px] lg:px-[80px]">
                <h2 class="headline-medium mb-[40px]! text-center lg:text-left text-black">Populair</h2>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <!-- Grote post links (6 kolommen) -->
                    <div class="lg:col-span-6">
                        <a href="<?php echo esc_url(get_permalink($first_post->ID)); ?>" class="block group overflow-hidden rounded-[16px] bg-white">
                            <?php if (has_post_thumbnail($first_post->ID)) : ?>
                                <div class="relative w-full h-[400px] lg:h-[500px] overflow-hidden rounded-t-[16px]">
                                    <?php echo get_the_post_thumbnail($first_post->ID, 'large', array(
                                        'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105',
                                        'alt' => get_the_title($first_post->ID)
                                    )); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="bg-primary p-6 rounded-b-[16px]">
                                <!-- Categorie en Datum -->
                                <div class="flex items-center gap-4 mb-4">
                                    <?php 
                                    $categories = get_the_category($first_post->ID);
                                    if (!empty($categories)) : 
                                        $category = $categories[0];
                                    ?>
                                        <span class="label-medium text-primary"><?php echo esc_html($category->name); ?></span>
                                    <?php endif; ?>
                                    <span class="body-small text-gray-600"><?php echo get_the_date('', $first_post->ID); ?></span>
                                </div>
                                
                                <!-- Titel -->
                                <h2 class="headline-medium mb-4 text-black group-hover:text-primary transition-colors">
                                    <?php echo esc_html(get_the_title($first_post->ID)); ?>
                                </h2>
                                
                                <!-- Samenvatting -->
                                <div class="body-medium text-gray-700">
                                    <?php echo wp_trim_words(get_the_excerpt($first_post->ID) ?: get_the_content($first_post->ID), 30, '...'); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- 3 kleinere posts rechts (6 kolommen) -->
                    <?php if (!empty($other_posts)) : ?>
                        <div class="lg:col-span-6 space-y-6">
                            <?php foreach ($other_posts as $post) : 
                                $categories = get_the_category($post->ID);
                                $category = !empty($categories) ? $categories[0] : null;
                            ?>
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="block group overflow-hidden rounded-[16px] bg-white">
                                <div class="flex">
                                    <!-- Afbeelding links -->
                                    <?php if (has_post_thumbnail($post->ID)) : ?>
                                        <div class="flex-shrink-0 w-[120px] h-[120px] lg:w-[150px] lg:h-[150px] overflow-hidden rounded-l-[16px]">
                                            <?php echo get_the_post_thumbnail($post->ID, 'thumbnail', array(
                                                'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105',
                                                'alt' => get_the_title($post->ID)
                                            )); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Content rechts -->
                                    <div class="flex-1 bg-primary p-4 rounded-r-[16px]">
                                        <!-- Categorie en Datum -->
                                        <div class="flex items-center gap-3 mb-2">
                                            <?php if ($category) : ?>
                                                <span class="label-medium text-primary text-sm"><?php echo esc_html($category->name); ?></span>
                                            <?php endif; ?>
                                            <span class="body-small text-gray-600"><?php echo get_the_date('', $post->ID); ?></span>
                                        </div>
                                        
                                        <!-- Titel -->
                                        <h3 class="body-large-bold text-black group-hover:text-primary transition-colors">
                                            <?php echo esc_html(get_the_title($post->ID)); ?>
                                        </h3>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </section>
        <?php 
        wp_reset_postdata();
        endif; 
        ?>
    
    <!-- Posts Overzicht -->
    <div class="container mx-auto px-[20px] lg:px-[80px] py-[60px] lg:py-[80px]">
        
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

