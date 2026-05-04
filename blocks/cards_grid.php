<?php
/**
 * Block: Cards Grid Beutech
 * 
 * Een grid met kaarten/cards
 * 
 * https://www.beterbouwengroep.nl/wp-json/wp/v2/vacature
 */

$soort_items_cards  = get_sub_field('soort_items');
$slider_ingeschakeld = (bool) get_sub_field('slider');
$cards_rand_class   = (
	$slider_ingeschakeld && in_array( $soort_items_cards, array( 'posts', 'eigen', 'tijdlijn' ), true )
)
	? 'cards-grid-' . wp_rand( 1000, 9999 )
	: '';
$cards_nav_root     = $cards_rand_class ? '.' . $cards_rand_class . '-controls' : '.cards-grid-controls';

$swiper_setting = [];

if(get_sub_field('slider')) {
    $swiper_setting = [
        'slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_mobiel'] ?: 1.2,
        'spaceBetween' => 16,
        'pagination' => [
            'el' => '.swiper-pagination',
            'clickable' => true,
        ],
        'navigation' => [
            'nextEl' => $cards_nav_root . ' .swiper-next',
            'prevEl' => $cards_nav_root . ' .swiper-prev',
        ],
        'breakpoints' => [
            640 => ['slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_mobiel'] ?: 1.2, 'spaceBetween' => 16],
            768 => ['slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_tablet'] ?: 3, 'spaceBetween' => 32],
            1024 => ['slidesPerView' => get_sub_field('aantal_slides')['aantal_slides_desktop'] ?: 4, 'spaceBetween' => 32],
        ],
    ];
}else {
  $swiper_setting = [
        'slidesPerView' => 1.2,
        'spaceBetween' => 16,
        'pagination' => [
            'el' => '.swiper-pagination',
            'clickable' => true,
        ],
        'navigation' => [
            'nextEl' => $cards_nav_root . ' .swiper-next',
            'prevEl' => $cards_nav_root . ' .swiper-prev',
        ],
        'breakpoints' => [
            640 => ['slidesPerView' => 1.2, 'spaceBetween' => 16],
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

/*
 * ACF kloon van repeater "Buttons": afhankelijk van kloon-instellingen zit de data
 * onder o.a. `knoppen` (kloonveldnaam), `buttons` (naadloos = oorspronkelijke naam),
 * of `knoppen_buttons` (veldnaamvoorvoegsel + repeater).
 */
$knoppen_buttons = array();
$knoppen_field_names = array( 'knoppen', 'buttons', 'knoppen_buttons' );
if ( function_exists( 'get_sub_field' ) ) {
    foreach ( $knoppen_field_names as $fname ) {
        $v = get_sub_field( $fname );
        if ( ! is_array( $v ) || array() === $v ) {
            continue;
        }
        if ( isset( $v['buttons'] ) && is_array( $v['buttons'] ) && $v['buttons'] !== array() ) {
            $v = $v['buttons'];
        }
        if ( isset( $v['link'] ) && ! array_key_exists( 0, $v ) && ! array_key_exists( '0', $v ) ) {
            $knoppen_buttons = array( $v );
            break;
        }
        $as_rows = array_values( array_filter( $v, 'is_array' ) );
        if ( $as_rows !== array() ) {
            $knoppen_buttons = $as_rows;
            break;
        }
    }
}
if ( $knoppen_buttons === array() && function_exists( 'have_rows' ) ) {
    foreach ( $knoppen_field_names as $fname ) {
        if ( ! have_rows( $fname ) ) {
            continue;
        }
        while ( have_rows( $fname ) ) {
            the_row();
            $row = get_row( true );
            if ( is_array( $row ) ) {
                $knoppen_buttons[] = $row;
            }
        }
        if ( $knoppen_buttons !== array() ) {
            break;
        }
    }
}
$has_knoppen = $knoppen_buttons !== array();
$has_swiper_controls = (
    in_array( $soort_items_cards, array( 'posts', 'eigen', 'tijdlijn' ), true )
    && $slider_ingeschakeld
);

$backgroundPatroon = 'pink';

?>

<!-- Cards Grid -->
<div class="<?= get_spacing_bottom_class() ?> relative overflow-hidden <?= get_sub_field('tekst_kleur') ? 'text-'.get_sub_field('tekst_kleur') : '' ?> ">
  <?php if(get_sub_field('post_type') != 'medewerker') : ?>
      <?php if($background_color) : ?><div class="absolute z-0 <?= get_sub_field('achtergrond_positie') ?>-0 left-0 w-full h-[50%] bg-<?= $background_color; ?>"></div><?php endif; ?>
    <?php endif; ?>
    <div class="container">
      <div class="mb-[1.5rem] lg:mb-[2rem] flex flex-wrap items-center justify-between lg:grid lg:grid-cols-12 lg:gap-1">
       
          <?php if (!empty($cards_heading) || $has_knoppen) : ?>
            <div class="flex w-full min-w-0 flex-1 flex-wrap items-center justify-between gap-4 sm:flex-nowrap <?php echo $has_swiper_controls ? 'lg:col-span-5' : 'lg:col-span-8'; ?> <?php if (get_sub_field('soort_items') == 'themas' || get_sub_field('soort_items') == 'artikelen') : ?>lg:col-start-3<?php endif; ?>">
              <?php if (!empty($cards_heading)) : ?>
                <div class="min-w-0 w-full sm:w-auto sm:flex-1 lg:max-w-[50%] <?php if (!$has_knoppen) : ?>lg:w-6/12<?php endif; ?>">
                  <div class="mb-0!"><?php echo wp_kses_post($cards_heading); ?></div>
                </div>
              <?php endif; ?>
              <?php if ($has_knoppen && !$has_swiper_controls) : ?>
                <div class="shrink-0 sm:ml-0 <?php echo empty($cards_heading) ? 'ml-auto w-full sm:w-auto' : ''; ?>">
                  <?php
                  get_template_part('template-parts/core/buttons', null, array(
                    'buttons'   => $knoppen_buttons,
                    'no_margin' => true,
                  ));
                  ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <?php if ($has_swiper_controls): ?>
          <div class="swiper-controls cards-grid-controls hidden md:flex flex-col lg:flex-row lg:items-center lg:justify-end gap-4 lg:col-span-7 <?= esc_attr($cards_rand_class); ?>-controls">
            <div class="w-full lg:w-auto flex items-center gap-4 xl:gap-8">
              <div class="w-[10rem] xl:w-[14.6875rem] h-[0.125rem] relative overflow-hidden bg-[#161616]/20">
                <span class="<?= esc_attr($cards_rand_class); ?>-progress absolute left-0 top-0 h-full bg-primary transition-transform duration-300" style="width: 90.7336px; transform: translateX(0px);"></span>
              </div>
              <div class="title-large text-black whitespace-nowrap shrink-0">
                <span class="<?= esc_attr($cards_rand_class); ?>-current font-bold">01</span> /
                <span class="<?= esc_attr($cards_rand_class); ?>-total">00</span>
              </div>
            </div>

            <div class="w-full lg:w-auto flex items-stretch gap-4">
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
      // Op single pagina's het huidige item uitsluiten uit dezelfde post type.
      if (is_singular()) {
        $current_post_id = get_queried_object_id();
        $current_post_type = $current_post_id ? get_post_type($current_post_id) : '';
        if ($current_post_id && $current_post_type === $selected_post_type) {
          $args['post__not_in'] = array($current_post_id);
        }
      }
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
            var controlsRoot = document.querySelector('.<?= esc_js($cards_rand_class); ?>-controls');
            var currentEl = controlsRoot ? controlsRoot.querySelector('.<?= esc_js($cards_rand_class); ?>-current') : null;
            var totalEl = controlsRoot ? controlsRoot.querySelector('.<?= esc_js($cards_rand_class); ?>-total') : null;
            var progressEl = controlsRoot ? controlsRoot.querySelector('.<?= esc_js($cards_rand_class); ?>-progress') : null;
            var prevBtn = controlsRoot ? controlsRoot.querySelector('.swiper-prev') : null;
            var nextBtn = controlsRoot ? controlsRoot.querySelector('.swiper-next') : null;

            function formatSlideNumber(num) {
              return String(num).padStart(2, '0');
            }

            function getScrollProgress(sw) {
              if (!sw || sw.destroyed) return 0;
              var maxT = sw.maxTranslate();
              var minT = sw.minTranslate();
              if (!isFinite(maxT) || !isFinite(minT) || maxT === minT) return 1;
              var t = typeof sw.translate === 'number' ? sw.translate : 0;
              return Math.max(0, Math.min(1, (t - maxT) / (minT - maxT)));
            }

            function updateProgress() {
              if (!currentEl || !totalEl || !progressEl) return;
              var totalSlides = (swiper.slides && swiper.slides.length) ? swiper.slides.length : 1;
              var raw = getScrollProgress(swiper);
              var p;
              if (totalSlides <= 1) {
                p = 1;
              } else {
                /* Omgekeerd t.o.v. ruwe translate: start = 0, einde = 1 (tijdlijn + pijlen) */
                p = Math.max(0, Math.min(1, 1 - raw));
              }
              var currentStep;

              if (totalSlides <= 1) {
                currentStep = 1;
                p = 1;
              } else if (p <= 0.001) {
                currentStep = 1;
                p = 0;
              } else if (p >= 0.999) {
                currentStep = totalSlides;
                p = 1;
              } else {
                currentStep = Math.min(totalSlides, Math.max(1, Math.round(p * (totalSlides - 1)) + 1));
              }

              currentEl.textContent = formatSlideNumber(currentStep);
              totalEl.textContent = formatSlideNumber(totalSlides);

              var trackEl = progressEl.parentElement;
              var trackWidth = trackEl && trackEl.offsetWidth ? trackEl.offsetWidth : 235;
              var indicatorWidth = progressEl.offsetWidth || 90.7336;
              var maxTranslate = Math.max(0, trackWidth - indicatorWidth);
              progressEl.style.transform = 'translateX(' + (maxTranslate * p) + 'px)';

              var atStart = p <= 0.001;
              var atEnd = p >= 0.999;
              if (prevBtn) {
                prevBtn.style.opacity = atStart ? '0.35' : '1';
                prevBtn.style.pointerEvents = atStart ? 'none' : '';
                prevBtn.setAttribute('aria-disabled', atStart ? 'true' : 'false');
              }
              if (nextBtn) {
                nextBtn.style.opacity = atEnd ? '0.35' : '1';
                nextBtn.style.pointerEvents = atEnd ? 'none' : '';
                nextBtn.setAttribute('aria-disabled', atEnd ? 'true' : 'false');
              }
            }

            updateProgress();
            swiper.on('slideChange', updateProgress);
            swiper.on('transitionEnd', updateProgress);
            swiper.on('touchEnd', updateProgress);
            swiper.on('progress', updateProgress);
            window.addEventListener('resize', function () {
              requestAnimationFrame(updateProgress);
            });
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
            <div class="flex justify-center lg:hidden mt-[1.5rem]">
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