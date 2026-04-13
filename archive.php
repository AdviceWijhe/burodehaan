<?php
/**
 * Main template file - Archief met categorieën
 */

get_header(); 

$title = 'artikelen';

?>

<main id="main" class="site-main">

<div class="container mx-auto mb-[28px]">
    <div class="">

            <h1 class="headline-large">
                Onze <?= $title; ?>
            </h1>
    </div>
</div>

    <?php
    $current_page = max(1, get_query_var('paged'));
    $max_pages = (int) $wp_query->max_num_pages;
    ?>
    <div class="container mx-auto py-[40px]! lg:py-[80px]!">
        
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
        <div class="mt-[100px] lg:mt-[120px] text-center">
            <button
                id="archive-load-more"
                class="btn bg-pink text-white"
                type="button"
                data-post-type="<?php echo esc_attr(get_post_type()); ?>"
                data-current-page="<?php echo esc_attr($current_page); ?>"
                data-max-pages="<?php echo esc_attr($max_pages); ?>"
                data-query-vars="<?php echo esc_attr(wp_json_encode($wp_query->query_vars)); ?>"
            >
                Meer laden
            </button>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
