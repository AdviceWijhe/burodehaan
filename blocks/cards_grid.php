<?php
/**
 * Block: Cards Grid Beutech
 * 
 * Een grid met kaarten/cards
 */

$swiper_setting = [];

if(get_sub_field('slider')) {
    $swiper_setting = [
        'slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_mobiel'] ?: 1.2,
        'spaceBetween' => 32,
        'pagination' => [
            'el' => '.swiper-pagination',
            'clickable' => true,
        ],
        'navigation' => [
            'nextEl' => '.swiper-next',
            'prevEl' => '.swiper-prev',
        ],
        'breakpoints' => [
            640 => ['slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_mobiel'] ?: 1.2, 'spaceBetween' => 32],
            768 => ['slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_tablet'] ?: 3, 'spaceBetween' => 32],
            1024 => ['slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_desktop'] ?: 4, 'spaceBetween' => 32],
        ],
    ];
}else {
  $swiper_setting = [
        'slidesPerView' => 1.2,
        'spaceBetween' => 32,
        'pagination' => [
            'el' => '.swiper-pagination',
            'clickable' => true,
        ],
        'navigation' => [
            'nextEl' => '.swiper-next',
            'prevEl' => '.swiper-prev',
        ],
        'breakpoints' => [
            640 => ['slidesPerView' => 1.2, 'spaceBetween' => 32],
            768 => ['slidesPerView' => 3, 'spaceBetween' => 32],
            1024 => ['slidesPerView' => 4, 'spaceBetween' => 32],
        ],
    ];
}

$background_color = get_sub_field('achtergrondkleur');



$backgroundPatroon = 'pink';

?>

<!-- Cards Grid -->
<div class=" <?= $background_color ? 'pt-[60px] lg:pt-[100px]' : '' ?> <?= get_spacing_bottom_class(); ?> relative overflow-hidden <?= get_sub_field('tekst_kleur') ? 'text-'.get_sub_field('tekst_kleur') : '' ?> ">
  <?php if(get_sub_field('post_type') != 'medewerker') : ?>
      <?php if($background_color) : ?><div class="absolute z-0 <?= get_sub_field('achtergrond_positie') ?>-0 left-0 w-full h-[50%] bg-<?= $background_color; ?>"></div><?php endif; ?>
    <?php endif; ?>
    <div class="container mx-auto px-4">
      <div class="flex mb-[24px] lg:mb-[32px] justify-between items-center">
       
          <?php if(get_sub_field('section_heading')) : ?>
            <div class="w-full lg:w-6/12">
        <h2 class="mb-0!"><?= get_sub_field('section_heading') ?></h2>
        </div>
          <?php endif; ?>

          <?php if (get_sub_field('soort_items') == 'posts'): ?>
          <div class="swiper-controls flex justify-center lg:justify-between items-center gap-4">
            <div class="swiper-controls-inner w-full lg:w-auto flex flex-col lg:flex-row gap-4 items-center justify-center lg:justify-start">
          <!-- <div class="swiper-pagination text-center! left-auto! bottom-auto! w-auto! lg:text-left! mb-5 lg:pb-0!"></div> -->
          <?php if(get_sub_field('buttons')) : ?>
            <div class="hidden lg:block">
              <?php get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'no_margin' => true)); ?>
            </div>
          <?php endif; ?>
          </div>
          <div class="swiper-arrows hidden lg:flex gap-2">
          
          <div class="swiper-prev">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M212.7 331.3C206.5 325.1 206.5 314.9 212.7 308.7L372.7 148.7C378.9 142.5 389.1 142.5 395.3 148.7C401.5 154.9 401.5 165.1 395.3 171.3L246.6 320L395.3 468.7C401.5 474.9 401.5 485.1 395.3 491.3C389.1 497.5 378.9 497.5 372.7 491.3L212.7 331.3z"/></svg>
          </div>
          <div class="swiper-next rotate-180">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M212.7 331.3C206.5 325.1 206.5 314.9 212.7 308.7L372.7 148.7C378.9 142.5 389.1 142.5 395.3 148.7C401.5 154.9 401.5 165.1 395.3 171.3L246.6 320L395.3 468.7C401.5 474.9 401.5 485.1 395.3 491.3C389.1 497.5 378.9 497.5 372.7 491.3L212.7 331.3z"/></svg>
          </div>
          </div>
          </div>
          <?php endif; ?>
      </div>

      <!-- Als field soort_items posts is dan moet hier een slider met swiperjs komen waarin een query staat verwerkt op de post type uit het veld post_type -->
      <?php if(get_sub_field('soort_items') == 'posts') { 

      $args = array(
        'post_type' => get_sub_field('post_type'),
        'posts_per_page' => 8,
      );
      // Voeg categorie-filter toe voor kennisbank indien geselecteerd in ACF
      if (get_sub_field('post_type') === 'kennisbank') {
        $selected_categories = get_sub_field('categorie');
        if (!empty($selected_categories)) {
          if (!is_array($selected_categories)) {
            $selected_categories = array($selected_categories);
          }
          $args['tax_query'] = array(
            array(
              'taxonomy' => 'category',
              'field' => 'term_id',
              'terms' => $selected_categories,
            ),
          );
        }
      }
      $query = new WP_Query($args);
      if($query->have_posts()) : ?>
  <?php if(get_sub_field('slider')) : ?>
  <?php $rand_class = 'swiper-container-' . wp_rand(1000,9999); ?>
  <div class="swiper-container <?= $rand_class ?>">
          <div class="swiper-wrapper">
            <?php while($query->have_posts()) : $query->the_post(); ?>
              <div class="swiper-slide h-auto!">
                <?php get_template_part('template-parts/card', get_sub_field('post_type'), array('post' => get_the_ID())); ?>
              </div>
            <?php endwhile; wp_reset_query(); ?>
          </div>
          
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.<?= $rand_class ?>', 
              <?php echo json_encode($swiper_setting); ?>
            );
          });
        </script>
  <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
          <?php while($query->have_posts()) : $query->the_post(); ?>
            <div class="h-auto!">
              <?php get_template_part('template-parts/card', get_sub_field('post_type'), array('post' => get_the_ID())); ?>
            </div>
          <?php endwhile; wp_reset_query(); ?>
        </div>
  <?php endif; ?>
 
  <?php if(get_sub_field('buttons')) : ?>
            <div class="block lg:hidden mt-[24px]">
              <?php get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'no_margin' => true)); ?>
            </div>
          <?php endif; ?>


      <?php 
      
    endif;

  }else if(get_sub_field('soort_items') == 'werkwijze'){

      $items = get_field('werkwijze_items', 'option');



      if(get_sub_field('eigen_werkwijze')) {
        $items = get_sub_field('eigen_werkwijze_items');
      }

      if(is_array($items) && !empty($items)) : ?>
        <!-- Swiper js block  -->
  <?php $rand_class2 = 'swiper-container-' . wp_rand(1000,9999); ?>
  <div class="swiper-container <?= $rand_class2 ?>">
  <?php if (is_array($items)): ?>
                <?php if (count($items) > 1): ?>
                <div class="swiper-controls flex justify-center lg:justify-between items-center">
                  <div class="swiper-controls-inner w-full lg:w-1/2 flex flex-col lg:flex-row gap-4 items-center justify-center lg:justify-start">
                <div class="swiper-pagination text-center! left-auto! bottom-auto! w-auto! lg:text-left! mb-5 lg:pb-0!"></div>
                <?php if(get_sub_field('buttons')) : ?>
      
                  <?php get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'no_margin' => true)); ?>
                <?php endif; ?>
                <?php endif; ?>
                </div>
                <div class="swiper-arrows hidden lg:flex gap-4">
                
                <div class="swiper-prev">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M212.7 331.3C206.5 325.1 206.5 314.9 212.7 308.7L372.7 148.7C378.9 142.5 389.1 142.5 395.3 148.7C401.5 154.9 401.5 165.1 395.3 171.3L246.6 320L395.3 468.7C401.5 474.9 401.5 485.1 395.3 491.3C389.1 497.5 378.9 497.5 372.7 491.3L212.7 331.3z"/></svg>
          </div>
          <div class="swiper-next rotate-180">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M212.7 331.3C206.5 325.1 206.5 314.9 212.7 308.7L372.7 148.7C378.9 142.5 389.1 142.5 395.3 148.7C401.5 154.9 401.5 165.1 395.3 171.3L246.6 320L395.3 468.7C401.5 474.9 401.5 485.1 395.3 491.3C389.1 497.5 378.9 497.5 372.7 491.3L212.7 331.3z"/></svg>
          </div>
                </div>
                </div>
                <?php endif; ?>
          <div class="swiper-wrapper">
            <?php 
            $count = 0;
            foreach($items as $item) : 
            $count++; ?>
              <div class="swiper-slide h-auto!">
                <?= get_template_part('template-parts/card-werkwijze', null, array('item' => $item, 'count' => $count)) ?>
            </div>
            <?php endforeach; ?>
            </div>
            </div>


            <script>
          document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.<?= $rand_class2 ?>', 
              <?php echo json_encode($swiper_setting); ?>
            );
          });
        </script>
 
      <?php endif;
    } else if(get_sub_field('soort_items') == 'eigen'){
      $items = get_sub_field('cards');
      if(is_array($items) && !empty($items)) : 
        $rand_class_eigen = 'swiper-container-' . wp_rand(1000,9999); 
        ?>
        <style>
          .<?= $rand_class_eigen ?> .swiper-wrapper {
            align-items: stretch;
          }
          .<?= $rand_class_eigen ?> .swiper-slide {
            height: auto;
            display: flex;
          }
          @media (min-width: 1024px) {
            .<?= $rand_class_eigen ?> .swiper-slide {
              height: auto;
            }
          }
        </style>
        <div class="swiper-container-eigen <?= $rand_class_eigen ?>">
          <div class="swiper-wrapper lg:!grid lg:grid-cols-3 gap-0 lg:gap-9 relative">
            <?php foreach($items as $item) : ?>
              <div class="swiper-slide w-full bg-white p-[40px] flex flex-col items-start relative overflow-hidden text-[var(--color-secondary)] rounded-[16px]">
              <?php 
              $icoon = $item['icoon'];
              if ($icoon && isset($icoon['ID'])) {
                echo wp_get_attachment_image($icoon['ID'], 'medium', false, array(
                  'class' => 'mb-[40px] h-[80px] w-auto relative z-10',
                  'alt' => $icoon['alt'] ?? '',
                  'loading' => 'lazy'
                ));
              } else if ($icoon && isset($icoon['url'])) {
              ?>
                <img src="<?= esc_url($icoon['url']) ?>" 
                     alt="<?= esc_attr($icoon['alt'] ?? '') ?>" 
                     class="mb-[40px] lg:mb-[60px] h-[80px] w-auto relative z-10"
                     loading="lazy">
              <?php } ?>
              
                <h4 class="mb-[12px]! lg:mb-[12px]! relative z-10 text-[var(--color-secondary)]"><?= $item['card_title'] ?></h4>
                <div class="relative text-[var(--color-secondary)]">
                  <?= $item['content'] ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var swiperEigen = new Swiper('.<?= $rand_class_eigen ?>', {
              slidesPerView: 1.07,
              spaceBetween: 32,
              watchSlidesProgress: true,
              breakpoints: {
                1024: {
                  enabled: false,
                  slidesPerView: 3,
                  spaceBetween: 32
                }
              },
              on: {
                init: function() {
                  setTimeout(equalizeHeight, 100);
                },
                resize: function() {
                  setTimeout(equalizeHeight, 100);
                }
              }
            });
            
            function equalizeHeight() {
              const slides = document.querySelectorAll('.<?= $rand_class_eigen ?> .swiper-slide');
              
              if (window.innerWidth < 1024) {
                let maxHeight = 0;
                
                // Reset height eerst
                slides.forEach(slide => {
                  slide.style.height = 'auto';
                });
                
                // Bereken max height
                slides.forEach(slide => {
                  const height = slide.offsetHeight;
                  if (height > maxHeight) {
                    maxHeight = height;
                  }
                });
                
                // Pas max height toe
                slides.forEach(slide => {
                  slide.style.height = maxHeight + 'px';
                });
              } else {
                // Reset heights op desktop
                slides.forEach(slide => {
                  slide.style.height = 'auto';
                });
              }
            }
            
            // Voeg resize listener toe
            window.addEventListener('resize', function() {
              setTimeout(equalizeHeight, 100);
            });
          });
        </script>
      <?php endif;
    } else if(get_sub_field('soort_items') == 'eigen_producten') {

      $items = get_sub_field('eigen_producten');
      if(get_sub_field('slider')) : ?>
        <?php $rand_class = 'swiper-container-' . wp_rand(1000,9999); ?>
        <div class="swiper-container <?= $rand_class ?>">
        <?php if (is_array($items)): ?>
                <?php if (count($items) > 1): ?>
                <div class="swiper-controls flex justify-center lg:justify-between items-center mt-5">
                  <div class="swiper-controls-inner w-full lg:w-1/2 flex flex-col lg:flex-row gap-4 items-center justify-center lg:justify-start">
                <div class="swiper-pagination text-center! left-auto! bottom-auto! w-auto! lg:text-left! mb-5 lg:pb-0!"></div>
                <?php if(get_sub_field('buttons')) : ?>
      
                  <?php get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'no_margin' => true)); ?>
                <?php endif; ?>
                <?php endif; ?>
                </div>
                <div class="swiper-arrows hidden lg:flex gap-4">
                
                <div class="swiper-prev">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M212.7 331.3C206.5 325.1 206.5 314.9 212.7 308.7L372.7 148.7C378.9 142.5 389.1 142.5 395.3 148.7C401.5 154.9 401.5 165.1 395.3 171.3L246.6 320L395.3 468.7C401.5 474.9 401.5 485.1 395.3 491.3C389.1 497.5 378.9 497.5 372.7 491.3L212.7 331.3z"/></svg>
          </div>
          <div class="swiper-next rotate-180">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M212.7 331.3C206.5 325.1 206.5 314.9 212.7 308.7L372.7 148.7C378.9 142.5 389.1 142.5 395.3 148.7C401.5 154.9 401.5 165.1 395.3 171.3L246.6 320L395.3 468.7C401.5 474.9 401.5 485.1 395.3 491.3C389.1 497.5 378.9 497.5 372.7 491.3L212.7 331.3z"/></svg>
          </div>
                </div>
                </div>
                <?php endif; ?>
                <div class="swiper-wrapper">
                  <?php foreach($items as $item) : 
                    //print_r($item);
                    ?>
                    <div class="swiper-slide h-auto!">
                      <?php
                    get_template_part('template-parts/card-product', null, array('post' => $item->ID));
                    
                    ?>
                    </div>
                  <?php endforeach; ?>
                </div>

              </div>
              <script>
                document.addEventListener('DOMContentLoaded', function () {
                  var swiper = new Swiper('.<?= $rand_class ?>', 
                    <?php echo json_encode($swiper_setting); ?>
                  );
                });
              </script>
    <?php endif; 
    }; ?>
    </div>
</div>