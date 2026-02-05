<?php


get_header(); ?>

<main id="main" class="site-main flex-1">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header -->
            <?php if (get_the_title()) : ?>
                <section class="hero py-20 relative">
  <div class="absolute bg-gray h-full max-h-[600px] left-0 top-0 w-full"></div>
  <div class="container mx-auto">
    <div class="flex flex-col xl:flex-row relative">
      <div class="w-full lg:w-5/12">
        <div class="label badge">Project</div>
        <h1 class="mb-5"><?= get_the_title() ?></h1>

        <div class="max-w-[580px] lead"><?= get_field('introtekst') ?></div>


        <!-- Deze knop moet een scroll down van 250px krijgen onclick -->
      
        <div class="flex items-center gap-4 hover:cursor-pointer mt-[40px] mb-15" onclick="window.scrollTo({top: 1000, behavior: 'smooth'})">
          <div class="icon-round min-w-[50px] min-h-[50px] lg:min-w-[60px] lg:min-h-[60px] rounded-full border border-gray-300 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
              <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>

          </div>
          <span>Ontdek dit project</span>
        </div>
      </div>

      <div class="w-full lg:w-4/12 lg:ml-auto bg-white relative z-2 lg:absolute lg:top-0 lg:right-0">
        

        <div class="bg-blue p-10">
          <?= get_template_part('template-parts/card-contactpersoon', null, array('medewerker' => get_field('contactpersoon'), 'text-color' => 'white')) ?>
        </div>
      </div>
    </div>

    <div class="flex  relative z-1">
      <div class="w-full max-h-[600px] overflow-hidden">
        <?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'full', false, array(
          'class' => 'w-full h-full object-cover object-center will-change-transform transform-gpu scale-120! lg:scale-120! origin-center',
          'alt' => get_the_title(),
          'loading' => 'eager', // Hero image
          'data-scroll-hero' => '',
          'data-scroll-speed' => '2'
        )); ?>
      </div>
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
   
  </div>
</section>
            <?php endif; ?>
            
            <!-- Gutenberg Content -->
            <div class="gutenberg-content lg:pt-16">
                <div class="container mx-auto">
                    
                    <?php if (has_blocks()) : ?>
                        <div class="flex flex-col xl:flex-row">
                        <!-- Enhanced Gutenberg Blocks -->
                        <div class="blocks-container max-w-none w-full lg:w-7/12">
                            <?php
                            // Parse blocks for custom styling
                            $blocks = parse_blocks(get_the_content());
                            $count = 0;
                            foreach ($blocks as $block) :
                                $block_name = $block['blockName'];
                                $block_content = render_block($block);
                                
                                // Add custom wrapper classes based on block type
                                $wrapper_classes = 'block-wrapper mb-8';
                                
                                switch ($block_name) {
                                  case 'core/heading':
                                      $wrapper_classes .= ' heading-block lg:px-25 mb-[20px]';
                                      if($count > 0) {
                                        $wrapper_classes .= ' mt-[80px]';
                                      }
                                      break;
                                  case 'core/paragraph':
                                      $wrapper_classes .= ' paragraph-block lg:px-25';
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
                                   case 'core/table':
                                      $wrapper_classes .= ' table-block lg:px-25';
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
                                
                            <?php 
                          $count++;
                  
                          endforeach; ?>
                        </div>

                        <div class="w-full lg:w-4/12 lg:ml-auto">
                        <div class="bg-blue p-10 lg:sticky lg:top-[100px]">
          <?= get_template_part('template-parts/card-contactpersoon', null, array('medewerker' => get_field('contactpersoon'), 'text-color' => 'white')) ?>
        </div>
                        </div>
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
            
        </article>
        
    <?php endwhile; ?>


    <div class="relative overflow-hidden py-[160px]">

    <div class="container mx-auto px-4">
      <div class="flex items-center justify-center text-center flex-col mb-[40px]">
        <!-- <div class="label badge mb-4">Boventitel</div>  -->
        <!-- <h2>Eén van onze andere projecten</h2> -->
         <h2><?= get_field('titel_other_projects', 'option') ?></h2>
      </div>

      <!-- Als field soort_items posts is dan moet hier een slider met swiperjs komen waarin een query staat verwerkt op de post type uit het veld post_type -->
          <?php

          $swiper_setting = [
              'slidesPerView' => 1.2,
              'spaceBetween' => 20,
              'pagination' => [
                  'el' => '.swiper-pagination',
                  'clickable' => true,
              ],
              'navigation' => [
                  'nextEl' => '.swiper-next',
                  'prevEl' => '.swiper-prev',
              ],
              'breakpoints' => [
                  640 => ['slidesPerView' => 1.2, 'spaceBetween' => 20],
                  768 => ['slidesPerView' => 2, 'spaceBetween' => 30],
                  1024 => ['slidesPerView' => 2, 'spaceBetween' => 40],
              ],
          ];

          $rand_class = rand() . '-swiper';
                $args = array(
                  'post_type' => 'project',
                  'posts_per_page' => 8,
                  'post__not_in' => array(get_the_ID()),
                );
                $query = new WP_Query($args);
                if($query->have_posts()) : ?>
            <?php $rand_class = 'swiper-container-' . wp_rand(1000,9999); ?>
            <div class="swiper-container <?= $rand_class ?>">
                    <div class="swiper-wrapper">
                      <?php while($query->have_posts()) : $query->the_post(); ?>
                        <div class="swiper-slide">
                          <?php get_template_part('template-parts/card', 'project', array('post' => get_the_ID())); ?>
                        </div>
                      <?php endwhile; wp_reset_query(); ?>
                    </div>
                    <div class="swiper-controls flex justify-center lg:justify-between items-center mt-6">
                    <div class="swiper-pagination text-center! lg:text-left!"></div>
                    <div class="swiper-arrows hidden lg:flex gap-4">
                    
                    <div class="swiper-prev">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
          </svg>
                    </div>
                    <div class="swiper-next">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
          </svg>
                    </div>
                    </div>
                    </div>
                  </div>
                  <script>
                    document.addEventListener('DOMContentLoaded', function () {
                      var swiper = new Swiper('.<?= $rand_class ?>', 
                        <?php echo json_encode($swiper_setting); ?>
                      );
                    });
                  </script>
          



                <?php 
                
              endif;

            ?>
              </div>
            </div>

    <?php
    
    get_template_part('blocks/cta', null, array('contactpersoon' => get_field('contactpersoon'), 'titel' => 'Opzoek naar jouw maatwerkoplossing?', 'top_titel' => 'Wij staan voor je klaar'))
    
    ?>


</div>
    
</main>

<?php get_footer(); ?>
