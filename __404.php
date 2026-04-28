<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

<main id="main" class="site-main flex-1">
    <div class="container mx-auto px-4 py-16">
        
        <section class="error-404 not-found text-center">
            
            <!-- 404 Illustration -->
            <div class="mb-12">
                <div class="text-9xl font-bold text-gray-200 mb-4">404</div>
                <svg class="w-64 h-64 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.137 0-4.146.832-5.636 2.172M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            
            <header class="page-header mb-8">
                <h1 class="page-title text-4xl font-bold text-gray-900 mb-4">
                    Oeps! Pagina niet gsevonden
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Het lijkt erop dat de pagina die je zoekt niet bestaat of is verplaatst. 
                    Geen zorgen, we helpen je graag verder!
                </p>
            </header>
            
            <div class="page-content max-w-2xl mx-auto">
                
                <!-- Search Form -->
                <div class="mb-12">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Probeer te zoeken</h2>
                    <div class="max-w-md mx-auto">
                        <?php get_search_form(); ?>
                    </div>
                </div>
                
                <!-- Navigation Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                    
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <svg class="w-5 h-5 inline mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h2a2 2 0 012 2v1H8V5z"></path>
                            </svg>
                            Recente Posts
                        </h3>
                        <div class="space-y-2">
                            <?php
                            $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 5,
                                'post_status' => 'publish'
                            ));
                            
                            if ($recent_posts) :
                                foreach ($recent_posts as $post) :
                            ?>
                                <a href="<?php echo get_permalink($post['ID']); ?>" class="block text-blue-600 hover:text-blue-800 transition-colors">
                                    <?php echo $post['post_title']; ?>
                                </a>
                            <?php 
                                endforeach;
                                wp_reset_postdata();
                            else :
                            ?>
                                <p class="text-gray-500 text-sm">Geen recente posts gevonden.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <svg class="w-5 h-5 inline mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Categorieën
                        </h3>
                        <div class="space-y-2">
                            <?php
                            $categories = get_categories(array(
                                'orderby' => 'count',
                                'order'   => 'DESC',
                                'number'  => 5
                            ));
                            
                            if ($categories) :
                                foreach ($categories as $category) :
                            ?>
                                <a href="<?php echo get_category_link($category->term_id); ?>" class="block text-green-600 hover:text-green-800 transition-colors">
                                    <?php echo $category->name; ?> (<?php echo $category->count; ?>)
                                </a>
                            <?php 
                                endforeach;
                            else :
                            ?>
                                <p class="text-gray-500 text-sm">Geen categorieën gevonden.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Call to Action -->
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Terug naar Home
                    </a>
                    
                    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Ga Terug
                    </a>
                </div>
                
            </div>
            
        </section>
        
    </div>
</main>

<?php
get_footer();
?>
