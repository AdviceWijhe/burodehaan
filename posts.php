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
                            
                            <div class="bg-light p-6 rounded-b-[16px]">
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
                                    <div class="flex-1 bg-light p-4 rounded-r-[16px]">
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
    
    <!-- Posts Overzicht: 3x3 grid, meest recent, 9 per pagina -->
    <?php
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
    $recent_query = new WP_Query(array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 9,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
    ));
    ?>
    <section class="recent-posts py-[60px] lg:py-[80px]">
        <div class="container mx-auto px-[20px] lg:px-[80px]">
            <h2 class="headline-medium mb-[40px]! text-center lg:text-left text-black">Meest recent</h2>

            <?php if ($recent_query->have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    while ($recent_query->have_posts()) :
                        $recent_query->the_post();
                        $categories = get_the_category();
                        $category = !empty($categories) ? $categories[0] : null;
                    ?>
                        <a href="<?php the_permalink(); ?>" class="block group overflow-hidden rounded-[16px] bg-white">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="relative w-full aspect-[16/10] overflow-hidden rounded-t-[16px]">
                                    <?php the_post_thumbnail('medium_large', array(
                                        'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105',
                                        'alt'   => get_the_title(),
                                        'loading' => 'lazy',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <div class="bg-light p-4 lg:p-5 rounded-b-[16px]">
                                <div class="flex items-center gap-3 mb-2">
                                    <?php if ($category) : ?>
                                        <span class="label-medium text-primary"><?php echo esc_html($category->name); ?></span>
                                    <?php endif; ?>
                                    <span class="body-small text-gray-600"><?php echo get_the_date(); ?></span>
                                </div>
                                <h3 class="headline-small lg:body-large-bold text-black group-hover:text-primary transition-colors">
                                    <?php the_title(); ?>
                                </h3>
                                <div class="body-small text-gray-700 mt-2 line-clamp-2">
                                    <?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 15, '...'); ?>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>

                <?php
                $total_pages = $recent_query->max_num_pages;
                if ($total_pages > 1) :
                    echo '<nav class="recent-posts-pagination mt-10 flex justify-center" aria-label="' . esc_attr__('Posts paginering', 'advice2025') . '">';
                    echo paginate_links(array(
                        'total'     => $total_pages,
                        'current'   => $paged,
                        'prev_text' => '&larr; Vorige',
                        'next_text' => 'Volgende &rarr;',
                        'type'      => 'list',
                    ));
                    echo '</nav>';
                endif;

                wp_reset_postdata();
                ?>
            <?php else : ?>
                <p class="body-large text-gray-600">Er zijn nog geen posts.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_sidebar();
get_footer();
?>

