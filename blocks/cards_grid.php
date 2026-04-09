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
$cards_heading = (string) get_sub_field('titel', false, false);
if (trim($cards_heading) === '') {
  // Fallback op oude veldnaam.
  $cards_heading = (string) get_sub_field('section_heading', false, false);
}
$cards_heading = preg_replace('/^\s*<p>(.*)<\/p>\s*$/si', '$1', $cards_heading);



$backgroundPatroon = 'pink';

?>

<!-- Cards Grid -->
<div class="py-[200px] relative overflow-hidden <?= get_sub_field('tekst_kleur') ? 'text-'.get_sub_field('tekst_kleur') : '' ?> ">
  <?php if(get_sub_field('post_type') != 'medewerker') : ?>
      <?php if($background_color) : ?><div class="absolute z-0 <?= get_sub_field('achtergrond_positie') ?>-0 left-0 w-full h-[50%] bg-<?= $background_color; ?>"></div><?php endif; ?>
    <?php endif; ?>
    <div class="container mx-auto px-4">
      <div class="flex mb-[24px] lg:mb-[32px] justify-between items-center">
       
          <?php if(!empty($cards_heading)) : ?>
            <div class="w-full lg:w-6/12">
        <div class="mb-0!"><?php echo wp_kses_post($cards_heading); ?></div>
        </div>
          <?php endif; ?>

          <?php if (get_sub_field('soort_items') == 'posts' && get_sub_field('slider')): ?>
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
      if (!in_array($selected_post_type, array('post', 'project', 'thema', 'expertise'), true)) {
        $selected_post_type = 'post';
      }
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
                <?php if ($is_bericht_post_type) : ?>
                  <a href="<?php the_permalink(); ?>" class="block h-full group">
                    <div class="relative overflow-hidden aspect-[517/320]">
                      <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 w-full h-full object-cover')); ?>
                      <?php endif; ?>
                    </div>
                    <div class="bg-white p-[40px] flex items-end justify-between gap-4" style="border: 1px solid rgba(22, 22, 22, 0.12);">
                      <div class="min-w-0">
                        <h3 class="title-medium text-black mb-3!"><?php the_title(); ?></h3>
                        <div class="body-small text-black/70 mb-0!">
                          <?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 20, '...')); ?>
                        </div>
                      </div>
                      <span class="shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                          <rect width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="2.96094" y="2.96289" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#EC663C"/>
                        </svg>
                      </span>
                    </div>
                  </a>
                <?php else : ?>
                  <a href="<?php the_permalink(); ?>" class="block relative overflow-hidden aspect-[5/7] group">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 w-full h-full object-cover')); ?>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute left-3 right-3 bottom-3 rounded-[12px] border border-white/20 bg-white/5 backdrop-blur-[20px] p-[32px] text-white flex items-end justify-between gap-3">
                      <div>
                        <?php
                        $term_label = '';
                        $taxonomies = get_object_taxonomies(get_post_type(), 'names');
                        if (!empty($taxonomies)) {
                          foreach ($taxonomies as $tax) {
                            $terms = get_the_terms(get_the_ID(), $tax);
                            if (!empty($terms) && !is_wp_error($terms)) {
                              $term_label = $terms[0]->name;
                              break;
                            }
                          }
                        }
                        ?>
                        <?php if ($term_label) : ?>
                          <div class="label-medium text-white mb-[16px]"><?php echo esc_html($term_label); ?></div>
                        <?php endif; ?>
                        <h3 class="title-medium text-white mb-0! w-2/3"><?php the_title(); ?></h3>
                      </div>
                      <span class="shrink-0 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                          <rect width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="2.96094" y="2.96313" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        </svg>
                      </span>
                    </div>
                  </a>
                <?php endif; ?>
              </div>
            <?php endwhile; wp_reset_query(); ?>
          </div>
          
        </div>
        <style>
          .<?= $cards_rand_class ?>-controls .cards-grid-nav-btn {
            width: 59px !important;
            height: 59px !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            background: var(--color-secondary) !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
          }
          .<?= $cards_rand_class ?>-controls .cards-grid-nav-btn svg {
            width: 12px !important;
            height: 20px !important;
            transform: none !important;
            display: block;
          }
          .<?= $cards_rand_class ?>-controls .cards-grid-nav-btn:hover svg {
            transform: none !important;
          }
          .<?= $cards_rand_class ?>-controls .cards-grid-nav-divider svg {
            height: 59px !important;
            display: block;
          }
        </style>
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
  <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php while($query->have_posts()) : $query->the_post(); ?>
            <div class="h-auto!">
              <?php if ($is_bericht_post_type) : ?>
                <a href="<?php the_permalink(); ?>" class="block h-full group">
                  <div class="relative overflow-hidden aspect-[517/320]">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 w-full h-full object-cover')); ?>
                    <?php endif; ?>
                  </div>
                  <div class="bg-white p-5 flex items-end justify-between gap-4" style="border: 1px solid rgba(22, 22, 22, 0.12);">
                    <div class="min-w-0">
                      <h3 class="title-medium text-black mb-3!"><?php the_title(); ?></h3>
                      <div class="body-small text-black/70 mb-0!">
                        <?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 20, '...')); ?>
                      </div>
                    </div>
                    <span class="shrink-0">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                        <rect width="2.22222" height="2.22222" fill="#EC663C"/>
                        <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#EC663C"/>
                        <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#EC663C"/>
                        <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="#EC663C"/>
                        <rect x="2.96094" y="2.96289" width="2.22222" height="2.22222" fill="#EC663C"/>
                        <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#EC663C"/>
                        <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#EC663C"/>
                      </svg>
                    </span>
                  </div>
                </a>
              <?php else : ?>
                <a href="<?php the_permalink(); ?>" class="block relative overflow-hidden aspect-[5/7] group">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 w-full h-full object-cover')); ?>
                  <?php endif; ?>
                  <div class="absolute inset-0 bg-black/20"></div>
                  <div class="absolute left-3 right-3 bottom-3 rounded-[12px] border border-white/20 bg-white/5 backdrop-blur-[20px] p-5 text-white flex items-end justify-between gap-3">
                    <div>
                      <?php
                      $term_label = '';
                      $taxonomies = get_object_taxonomies(get_post_type(), 'names');
                      if (!empty($taxonomies)) {
                        foreach ($taxonomies as $tax) {
                          $terms = get_the_terms(get_the_ID(), $tax);
                          if (!empty($terms) && !is_wp_error($terms)) {
                            $term_label = $terms[0]->name;
                            break;
                          }
                        }
                      }
                      ?>
                      <?php if ($term_label) : ?>
                        <div class="label-small text-white/70 mb-2"><?php echo esc_html($term_label); ?></div>
                      <?php endif; ?>
                      <h3 class="title-medium text-white mb-0! w-2/3"><?php the_title(); ?></h3>
                    </div>
                    <span class="shrink-0 text-white">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                        <rect width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        <rect x="2.96094" y="2.96313" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                      </svg>
                    </span>
                  </div>
                </a>
              <?php endif; ?>
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