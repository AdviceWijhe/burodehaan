<?php
/**
 * Main template file - Archief met categorieën
 */

get_header(); 

$title = 'artikelen';
$filter_panel_taxonomies = array(
    'category' => array('label' => __('Categorie', 'advice2025')),
    'thema' => array('label' => __('Thema', 'advice2025')),
    'expertise' => array('label' => __('Expertise', 'advice2025')),
);

?>

<main id="main" class="site-main">

<div class="container mx-auto mb-[3.75rem] lg:mb-[1.75rem] pt-[3.75rem] lg:pt-[7.5rem]">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between">

            <h1 class="headline-large mb-[1.75rem]! lg:mb-0!">
                Onze <b><?= $title; ?>.</b>
            </h1>


            <div class="search_filter flex items-center gap-[1rem]">
                <div class="search">
                    <?php get_search_form(); ?>
                </div>
                <div class="filter">
                    <?php
                    get_template_part(
                        'template-parts/archive-filter-panel',
                        null,
                        array(
                            'panel_id' => 'posts-archive-filters',
                            'label' => __('Filter', 'advice2025'),
                            'taxonomies' => $filter_panel_taxonomies,
                            'active_filters' => array(),
                        )
                    );
                    ?>
                </div>
            </div>
    </div>
</div>

    <?php
    $current_page = max(1, get_query_var('paged'));
    $max_pages = (int) $wp_query->max_num_pages;
    $archive_query_vars = $wp_query->query_vars;
    $archive_query_vars['advice2025_template'] = 'card_kennisbank';
    $archive_query_vars['advice2025_filters'] = array();
    $total_posts = (int) $wp_query->found_posts;
    $loaded_posts = (int) $wp_query->post_count;
    ?>
    <div
        class="container mx-auto"
        data-posts-archive-search
        data-query-vars="<?php echo esc_attr(wp_json_encode($archive_query_vars)); ?>"
    >
        
        <?php if (have_posts()) : 
            
           
            ?>
            <div
                id="archive-post-grid"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-[1.75rem]"
            >
            <?php while (have_posts()) : the_post(); 
            
            $queried_object = get_post();
            ?>
                <?= get_template_part('template-parts/card-kennisbank', null, array('item' => $queried_object)); ?>
            <?php endwhile; ?>
            </div>
   
           <?php wp_reset_postdata(); ?>
           
           
            
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

        <?php if ($total_posts > 0) : ?>
            <div class="mt-[1.25rem] lg:mt-[1.875rem] pb-[5rem] lg:pb-[12.5rem] text-center">
                <?php if ($max_pages > 1) : ?>
                    <button
                        id="archive-load-more"
                        class="btn border border-black text-black hover:bg-black hover:text-white"
                        type="button"
                        data-post-type="<?php echo esc_attr(get_post_type()); ?>"
                        data-current-page="<?php echo esc_attr($current_page); ?>"
                        data-max-pages="<?php echo esc_attr($max_pages); ?>"
                        data-query-vars="<?php echo esc_attr(wp_json_encode($archive_query_vars)); ?>"
                    >
                        Laad meer <?= $title; ?>
                    </button>
                <?php endif; ?>
                <p
                    id="archive-load-more-status"
                    class="body-medium text-black/60 <?php echo $max_pages > 1 ? 'mt-[1rem]' : ''; ?>"
                    data-loaded="<?php echo esc_attr($loaded_posts); ?>"
                    data-total="<?php echo esc_attr($total_posts); ?>"
                    data-label="<?php echo esc_attr($title); ?>"
                >
                    <?php echo esc_html(sprintf('%d van de %d %s getoond', $loaded_posts, $total_posts, $title)); ?>
                </p>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php
get_footer();
?>
