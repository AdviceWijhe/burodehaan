<?php

get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header -->
            <?php if (get_the_title()) : ?>
                <section class="hero pt-8 pb-20 relative">
  <!-- <div class="absolute bg-gray h-full max-h-[700px] left-0 top-0 w-full"></div> -->
  <div class="px-8 relative z-1 mb-20">
    <!-- yoast breadcrumbs -->
    <?php if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
    } ?>
  </div>
  <div class="container mx-auto">
    <div class="flex flex-col xl:flex-row ">
      <div class="w-full lg:w-6/12">
        <div class="label badge">Kom langs!</div>
     
        <!-- <h1 class="mb-5"><?= get_the_title() ?></h1> -->
<!-- Datum in format 11 juni 2025
 get field output is 23/10/2025 -->
 <?php $datum = get_field('datum'); 
 //echo $datum;
// format 23/10/2025 naar 23 oktober 2025
$datum = date('d M Y', strtotime($datum));
//echo $datum;
// de echo is 01 jan 1970
 ?>
        <h1 class="mb-5"> Meld je aan voor de excursie op <?= date('d M Y', strtotime($datum)) ?></h1>

        <div class="max-w-[580px] lead prose"><?= the_content() ?></div>
        <!-- Deze knop moet een scroll down van 250px krijgen onclick -->
      
        <div class="flex items-center gap-4 hover:cursor-pointer mt-[40px] mb-15" onclick="window.scrollTo({top: 1000, behavior: 'smooth'})">
          <div class="icon-round min-w-[50px] min-h-[50px] lg:min-w-[60px] lg:min-h-[60px] rounded-full border border-gray-300 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
              <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>

          </div>
          <span>Meld je aan</span>
        </div>
      </div>

      <div class="w-full lg:w-5/12 max-h-[600px] overflow-hidden relative z-1 lg:absolute lg:top-0 lg:right-0">
        <?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'full', false, array(
          'class' => 'w-full h-full object-cover object-center will-change-transform transform-gpu scale-120! lg:scale-120! origin-center',
          'alt' => get_the_title(),
          'loading' => 'eager', // Hero image
          'data-scroll-hero' => '',
          'data-scroll-speed' => '2'
        )); ?>
      </div>


  <script>
    (function(){
      if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);
        gsap.to("[data-scroll-hero]", {
          yPercent: -20,
          ease: "none",
          force3D: true,
          overwrite: "auto",
          scrollTrigger: {
            trigger: ".hero",
            start: "top bottom",
            end: "bottom top",
            scrub: 0.2,
            invalidateOnRefresh: true
          }
        });
      }
    })();
  </script>
</section>
            <?php endif; ?>
            
            <!-- Gutenberg Content -->
            <div class="gutenberg-content lg:pt-16">
                <div class="container mx-auto">
                    
                    <?php if (has_blocks()) : ?>
                        <div class="flex flex-col xl:flex-row">
                        <!-- Enhanced Gutenberg Blocks -->
                        <div class="blocks-container max-w-none w-full lg:w-6/12 pb-[80px]">
                            <?php
                            // Parse blocks for custom styling
                            $blocks = parse_blocks(get_the_content());
                            
                            foreach ($blocks as $block) :
                                $block_name = $block['blockName'];
                                $block_content = render_block($block);
                                
                                // Add custom wrapper classes based on block type
                                $wrapper_classes = 'block-wrapper';
                                
                                switch ($block_name) {
                                    case 'core/heading':
                                        $wrapper_classes .= ' heading-block lg:px-25 mb-[20px] mt-[80px]';
                                        break;
                                    case 'core/paragraph':
                                        $wrapper_classes .= ' paragraph-block lg:pl-25 lg:pr-21';
                                        break;
                                    case 'core/image':
                                        $wrapper_classes .= ' image-block';
                                        break;
                                    case 'core/gallery':
                                        $wrapper_classes .= ' gallery-block';
                                        break;
                                    case 'core/quote':
                                        $wrapper_classes .= ' quote-block';
                                        break;
                                    case 'core/list':
                                        $wrapper_classes .= ' list-block lg:px-25';
                                        break;
                                    case 'core/columns':
                                        $wrapper_classes .= ' columns-block';
                                        break;
                                    case 'core/group':
                                        $wrapper_classes .= ' group-block';
                                        break;
                                    case 'core/cover':
                                        $wrapper_classes .= ' cover-block full-width';
                                        break;
                                    default:
                                        $wrapper_classes .= ' default-block max-w-4xl mx-auto ';
                                        break;
                                }
                                ?>
                                
                                <div class="<?php echo esc_attr($wrapper_classes); ?>">
                                    <?php echo $block_content; ?>
                                </div>
                                
                            <?php endforeach; ?>
                        </div>

                        <div class="w-full lg:w-4/12 lg:ml-auto">
                        <div class="bg-blue p-10 sticky top-[100px]">
          <?= get_template_part('template-parts/card-contactpersoon', null, array('medewerker' => get_field('contactpersoon'), 'text-color' => 'white')) ?>

          
        </div>
                        </div>
                        </div>
                    <?php else : ?>
                        
                        <!-- Fallback voor klassieke content -->
                        <!-- <div class="classic-content max-w-4xl mx-auto">
                            <div class="prose prose-lg max-w-none">
                                <?php the_content(); ?>
                            </div>
                        </div> -->
                        
                    <?php endif; ?>
                    
                </div>
                    </div>
            
        </article>
        
    <?php endwhile; ?>

        <!-- Solicitatie formulier vanuit block formulier -->

        <?php get_template_part('blocks/formulier', null, array('formulier' => 5, 'medewerker' => get_field('contactpersoon'), 'label' => 'Excursie', 'titel' => 'Meld je aan voor de excursie')) ?>

</div>
    
</main>

<?php get_footer(); ?>
