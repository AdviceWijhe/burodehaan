<?php
/**
 * Main template file - Archief met categorieën
 */

get_header(); 

$title = 'projecten';
$filter_panel_taxonomies = array(
    'category' => array('label' => __('Categorie', 'advice2025')),
    'thema' => array('label' => __('Thema', 'advice2025')),
    'expertise' => array('label' => __('Expertise', 'advice2025')),
);

?>

<main id="main" class="site-main">

<div class="container mx-auto mb-[28px] pt-[120px]">
    <div class="flex items-center justify-between">

            <h1 class="headline-large">
                Onze <b><?= $title; ?>.</b>
            </h1>


            <div class="search_filter flex items-center gap-[16px]">
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
    $archive_query_vars['advice2025_filters'] = array();
    ?>
    <div
        class="container  "
        data-posts-archive-search
        data-query-vars="<?php echo esc_attr(wp_json_encode($archive_query_vars)); ?>"
    >
        
        <?php if (have_posts()) : ?>
            <div
                id="archive-post-grid"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[28px]"
            >
            <?php while (have_posts()) : the_post(); ?>
                <?= get_template_part('template-parts/card', get_post_type()); ?>
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
        
    </div>

    <?php if ($max_pages > 1) : ?>
        <div class="mt-[30px] lg:mt-[60px] text-center">
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
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
