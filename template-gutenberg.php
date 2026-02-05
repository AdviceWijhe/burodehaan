<?php
/**
 * Template Name: Gutenberg Content
 * 
 * Template voor pagina's met Gutenberg blok content
 */

get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header -->
            <?php if (get_the_title()) : ?>
                <header class="page-header bg-gradient-to-r from-gray-900 to-gray-700 text-white py-16">
                    <div class="container mx-auto px-4">
                        <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                        <?php if (get_the_excerpt()) : ?>
                            <p class="text-xl text-gray-200 max-w-3xl"><?php echo get_the_excerpt(); ?></p>
                        <?php endif; ?>
                    </div>
                </header>
            <?php endif; ?>
            
            <!-- Gutenberg Content -->
            <div class="gutenberg-content py-16">
                <div class="container mx-auto px-4">
                    
                    <?php if (has_blocks()) : ?>
                        
                        <!-- Enhanced Gutenberg Blocks -->
                        <div class="blocks-container max-w-none">
                            <?php
                            // Parse blocks for custom styling
                            $blocks = parse_blocks(get_the_content());
                            
                            foreach ($blocks as $block) :
                                $block_name = $block['blockName'];
                                $block_content = render_block($block);
                                
                                // Add custom wrapper classes based on block type
                                $wrapper_classes = 'block-wrapper mb-8';
                                
                                switch ($block_name) {
                                    case 'core/heading':
                                        $wrapper_classes .= ' heading-block';
                                        break;
                                    case 'core/paragraph':
                                        $wrapper_classes .= ' paragraph-block max-w-4xl mx-auto';
                                        break;
                                    case 'core/image':
                                        $wrapper_classes .= ' image-block max-w-6xl mx-auto';
                                        break;
                                    case 'core/gallery':
                                        $wrapper_classes .= ' gallery-block max-w-6xl mx-auto';
                                        break;
                                    case 'core/quote':
                                        $wrapper_classes .= ' quote-block max-w-4xl mx-auto';
                                        break;
                                    case 'core/list':
                                        $wrapper_classes .= ' list-block max-w-4xl mx-auto';
                                        break;
                                    case 'core/columns':
                                        $wrapper_classes .= ' columns-block max-w-6xl mx-auto';
                                        break;
                                    case 'core/group':
                                        $wrapper_classes .= ' group-block';
                                        break;
                                    case 'core/cover':
                                        $wrapper_classes .= ' cover-block full-width';
                                        break;
                                    default:
                                        $wrapper_classes .= ' default-block max-w-4xl mx-auto';
                                        break;
                                }
                                ?>
                                
                                <div class="<?php echo esc_attr($wrapper_classes); ?>">
                                    <?php echo $block_content; ?>
                                </div>
                                
                            <?php endforeach; ?>
                        </div>
                        
                    <?php else : ?>
                        
                        <!-- Fallback voor klassieke content -->
                        <div class="classic-content max-w-4xl mx-auto">
                            <div class="prose prose-lg max-w-none">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                    <?php endif; ?>
                    
                </div>
            </div>
            
            <!-- Post Navigation -->
            <?php if (get_post_type() === 'post') : ?>
                <nav class="post-navigation bg-gray-50 py-8">
                    <div class="container mx-auto px-4">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                            
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            ?>
                            
                            <?php if ($prev_post) : ?>
                                <div class="prev-post">
                                    <a href="<?php echo get_permalink($prev_post); ?>" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-500">Vorige post</div>
                                            <div class="font-medium"><?php echo get_the_title($prev_post); ?></div>
                                        </div>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($next_post) : ?>
                                <div class="next-post">
                                    <a href="<?php echo get_permalink($next_post); ?>" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                                        <div class="text-right">
                                            <div class="text-sm text-gray-500">Volgende post</div>
                                            <div class="font-medium"><?php echo get_the_title($next_post); ?></div>
                                        </div>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </nav>
            <?php endif; ?>
            
        </article>
        
        <!-- Comments Section -->
        <?php if (comments_open() || get_comments_number()) : ?>
            <section class="comments-section py-16 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <?php comments_template(); ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?>
