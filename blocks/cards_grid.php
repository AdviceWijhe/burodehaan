<?php
/**
 * Block: Cards Grid Beutech
 * 
 * Een grid met kaarten/cards
 * 
 * https://www.beterbouwengroep.nl/wp-json/wp/v2/vacature
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
$cards_heading = (string) get_sub_field('titel', false, false);
if (trim($cards_heading) === '') {
  // Fallback op oude veldnaam.
  $cards_heading = (string) get_sub_field('section_heading', false, false);
}
$cards_heading = preg_replace('/^\s*<p>(.*)<\/p>\s*$/si', '$1', $cards_heading);



$backgroundPatroon = 'pink';

?>

<!-- Cards Grid -->
<div class="<?= get_spacing_bottom_class() ?> relative overflow-hidden <?= get_sub_field('tekst_kleur') ? 'text-'.get_sub_field('tekst_kleur') : '' ?> ">
  <?php if(get_sub_field('post_type') != 'medewerker') : ?>
      <?php if($background_color) : ?><div class="absolute z-0 <?= get_sub_field('achtergrond_positie') ?>-0 left-0 w-full h-[50%] bg-<?= $background_color; ?>"></div><?php endif; ?>
    <?php endif; ?>
    <div class="container">
      <div class="flex mb-[24px] lg:mb-[32px] justify-between items-center">
       
          <?php if(!empty($cards_heading)) : ?>
            <div class="w-full lg:w-6/12 <?php if(get_sub_field('soort_items') == 'themas' || get_sub_field('soort_items') == 'artikelen') : ?>lg:ml-[calc(100%/12*2)]<?php endif; ?>">
        <div class="mb-0!"><?php echo wp_kses_post($cards_heading); ?></div>
        </div>
          <?php endif; ?>

          <?php if (get_sub_field('soort_items') == 'posts' && get_sub_field('slider') || get_sub_field('soort_items') == 'eigen' && get_sub_field('slider') || get_sub_field('soort_items') == 'tijdlijn' && get_sub_field('slider')): ?>
          <?php $cards_rand_class = 'cards-grid-' . wp_rand(1000, 9999); ?>
          <div class="swiper-controls flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 <?= esc_attr($cards_rand_class); ?>-controls">
            <div class="w-full lg:w-auto flex items-center gap-6">
              <div class="w-[235px] h-[2px] relative overflow-hidden bg-[#161616]/20">
                <span class="<?= esc_attr($cards_rand_class); ?>-progress absolute left-0 top-0 h-full bg-primary transition-transform duration-300" style="width: 90.7336px; transform: translateX(0px);"></span>
              </div>
              <div class="title-large text-black">
                <span class="<?= esc_attr($cards_rand_class); ?>-current font-bold">01</span> /
                <span class="<?= esc_attr($cards_rand_class); ?>-total">00</span>
              </div>
            </div>

            <div class="w-full lg:w-auto flex items-stretch gap-4 lg:ml-auto">
              <?php if(get_sub_field('buttons')) : ?>
                <div class="hidden lg:block">
                  <?php get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'no_margin' => true)); ?>
                </div>
              <?php endif; ?>
              <div class="swiper-arrows cards-grid-nav hidden lg:flex items-center gap-0">
                <div class="swiper-prev cards-grid-nav-btn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                    <rect x="11.1128" y="20.0001" width="2.22222" height="2.22222" transform="rotate(-180 11.1128 20.0001)" fill="#161616"/>
                    <rect x="5.18707" y="8.14801" width="2.22222" height="2.22222" transform="rotate(-180 5.18707 8.14801)" fill="#161616"/>
                    <rect x="8.15191" y="5.18511" width="2.22222" height="2.22222" transform="rotate(-180 8.15191 5.18511)" fill="#161616"/>
                    <rect x="11.1127" y="2.22222" width="2.22222" height="2.22222" transform="rotate(-180 11.1127 2.22222)" fill="#161616"/>
                    <rect x="8.15191" y="17.0372" width="2.22222" height="2.22222" transform="rotate(-180 8.15191 17.0372)" fill="#161616"/>
                    <rect x="2.22222" y="11.1109" width="2.22222" height="2.22222" transform="rotate(-180 2.22222 11.1109)" fill="#161616"/>
                    <rect x="5.18707" y="14.0743" width="2.22222" height="2.22222" transform="rotate(-180 5.18707 14.0743)" fill="#161616"/>
                  </svg>
                </div>
                <span class="cards-grid-nav-divider block">
                  <svg xmlns="http://www.w3.org/2000/svg" width="1" height="59" viewBox="0 0 1 59" fill="none">
                    <line opacity="0.12" x1="0.5" y1="2.18558e-08" x2="0.499997" y2="59" stroke="#161616"/>
                  </svg>
                </span>
                <div class="swiper-next cards-grid-nav-btn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                    <rect width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="2.96094" y="14.8148" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="0.00012207" y="17.7777" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="2.96094" y="2.96313" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#161616"/>
                    <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#161616"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
      </div>

      <!-- Als field soort_items posts is dan moet hier een slider met swiperjs komen waarin een query staat verwerkt op de post type uit het veld post_type -->
      <?php if(get_sub_field('soort_items') == 'posts') { 

      $selected_post_type = get_sub_field('post_type');
      // Ondersteun nieuwe benamingen uit ACF.
      if ($selected_post_type === 'bericht' || $selected_post_type === 'berichten') {
        $selected_post_type = 'post';
      }
    //   if (!in_array($selected_post_type, array('post', 'project', 'vacature'), true)) {
    //     $selected_post_type = 'post';
    //   }
      $is_bericht_post_type = ($selected_post_type === 'post');

      $args = array(
        'post_type' => $selected_post_type,
        'posts_per_page' => 12,
        'post_status' => 'publish',
      );
      // Voeg categorie-filter toe voor kennisbank indien geselecteerd in ACF
      if ($selected_post_type === 'kennisbank') {
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
  <div class="swiper-container <?= $rand_class ?> <?= esc_attr($cards_rand_class); ?>">
          <div class="swiper-wrapper">
            <?php while($query->have_posts()) : $query->the_post(); ?>
              <div class="swiper-slide h-auto!">
                
                  <?php

                  if(!is_front_page() && $selected_post_type == 'post') {
                    $selected_post_type = 'kennisbank';
                  }
                  
                  get_template_part('template-parts/card', $selected_post_type, array('post' => get_the_ID())); ?>
              
              </div>
            <?php endwhile; wp_reset_query(); ?>
          </div>
          
        </div>
        
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.<?= $rand_class ?>', <?php echo json_encode($swiper_setting); ?>);
            var currentEl = document.querySelector('.<?= esc_js($cards_rand_class); ?>-current');
            var totalEl = document.querySelector('.<?= esc_js($cards_rand_class); ?>-total');
            var progressEl = document.querySelector('.<?= esc_js($cards_rand_class); ?>-progress');

            function formatSlideNumber(num) {
              return String(num).padStart(2, '0');
            }

            function updateProgress() {
              if (!currentEl || !totalEl || !progressEl) return;
              var totalSteps = (swiper.snapGrid && swiper.snapGrid.length) ? swiper.snapGrid.length : 1;
              var currentStep = (typeof swiper.snapIndex === 'number' ? swiper.snapIndex + 1 : 1);
              if (currentStep < 1) currentStep = 1;
              if (currentStep > totalSteps) currentStep = totalSteps;

              currentEl.textContent = formatSlideNumber(currentStep);
              totalEl.textContent = formatSlideNumber(totalSteps);

              var trackWidth = 235;
              var indicatorWidth = 90.7336;
              var maxTranslate = trackWidth - indicatorWidth;
              var progress = totalSteps > 1 ? (currentStep - 1) / (totalSteps - 1) : 0;
              progressEl.style.transform = 'translateX(' + (maxTranslate * progress) + 'px)';
            }

            updateProgress();
            swiper.on('slideChange', updateProgress);
          });
        </script>
  <?php else: 
    
    
    ?>
        <div class="grid grid-cols-1 md:grid-cols-2 <?php if($selected_post_type == 'vacature') : ?>lg:grid-cols-2<?php else : ?>lg:grid-cols-3<?php endif; ?> gap-6">
          <?php while($query->have_posts()) : $query->the_post(); ?>
            <div class="h-auto!">
              <?php get_template_part('template-parts/card', $selected_post_type, array('post' => get_the_ID())); ?>
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

  } else if(get_sub_field('soort_items') == 'eigen'){
      $rand_class = 'swiper-container-' . wp_rand(1000,9999);
      get_template_part('blocks/cards_grid/eigen_invoer', null, array('rand_class' => $rand_class, 'swiper_setting' => $swiper_setting, 'cards_rand_class' => $cards_rand_class));

      ?>
           

<?php
    } else if(get_sub_field('soort_items') == 'themas' || get_sub_field('soort_items') == 'artikelen') {

      $items = get_sub_field('themas') ?: get_sub_field('artikelen');

      get_template_part('blocks/cards_grid/eigen_keuze', null, array('items' => $items));
      
    }else if(get_sub_field('soort_items') == 'tijdlijn') {

    $items = get_sub_field('tijdlijn_items');
  
    get_template_part('blocks/cards_grid/tijdlijn', null, array('items' => $items, 'controls_class' => $cards_rand_class ?? ''));
    
    
    
    
    }; ?>
    </div>
</div>