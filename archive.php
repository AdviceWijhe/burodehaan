<?php
/**
 * Main template file - Archief met categorieën
 */

get_header(); 


if(get_post_type() == 'case') {
    $pageID = 1180;
}

$page = get_post($pageID);

?>

<main id="main" class="site-main">

<div class="px-[20px] pt-[20px] lg:px-[80px] lg:pt-[80px] relative z-1 mb-3 mb-[40px] lg:mb-12">
    <!-- yoast breadcrumbs -->
    <?php if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
    } ?>
  </div>

<div class="container mx-auto">
    <div class="flex flex-col lg:flex-row items-stretch">
        <div class="w-full lg:w-5/12 flex flex-col justify-between  text-center mx-auto">
            <h1 class="headline-large mb-[32px]!">
                <?= get_the_title($pageID) ?>
            </h1>

            <div class="body-large max-w-[630px]"><?= $page->post_content ?></div>
        </div>
    </div>
</div>

    <div class="container mx-auto py-[40px]! lg:py-[80px]!">
        
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-[36px]">
            <?php while (have_posts()) : the_post(); ?>
                <?= get_template_part('template-parts/card-case'); ?>
            <?php endwhile; ?>
   
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

    <!-- Pagination -->
    <?php if (function_exists('advice2025_pagination')) : ?>
               <div class="mt-[100px] lg:mt-[120px]">
                   <?php echo advice2025_pagination(); ?>
               </div>
           <?php endif; ?>
</main>

<?php
get_footer();
?>
