<?php
/**
 * Block: Hero Banner variant for sectors
 * 
 * Een grote hero banner met achtergrondafbeelding
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$args = [
  'post_type' => 'sector',
  'posts_per_page' => 6,
  'orderby' => 'menu_order',
  'order' => 'ASC',
];

$query = new WP_Query($args);
?>

<!-- Hero Banner -->
<section class="hero hero__sectors relative <?php echo get_spacing_bottom_class('hero_banner'); ?>">
  <div class="bg-gray-100 absolute w-full h-96 z-0 top-0 left-0"></div>
  <div class="container px-0! mx-auto *:relative ">
    <div class="flex lg:gap-8 flex-col lg:flex-row items-start lg:pt-[100px]">
    <div class="hero__sectors--video aspect-video  w-full pr-[20px] lg:pr-0 lg:w-7/12">
      <?php
      if(!empty(get_sub_field('youtube_video_id'))) {
      // --- YouTube API-less fetch via oEmbed (cached) ---
      $video_id = get_sub_field('youtube_video_id'); // Expect just the ID, e.g. dQw4w9WgXcQ

      // Helper: fetch oEmbed once and cache
      function advice2025_get_youtube_oembed_by_id( $id ) {
          if ( empty( $id ) ) return null;
          $cache_key = 'advice2025_ytoembed_' . $id;
          $cached = get_transient( $cache_key );
          if ( $cached ) return $cached;

          $url = 'https://www.youtube.com/oembed?url=' . rawurlencode( 'https://www.youtube.com/watch?v=' . $id ) . '&format=json';
          $resp = wp_remote_get( $url, [ 'timeout' => 5 ] );
          if ( is_wp_error( $resp ) ) return null;
          $code = wp_remote_retrieve_response_code( $resp );
          if ( $code !== 200 ) return null;
          $body = wp_remote_retrieve_body( $resp );
          $data = json_decode( $body, true );
          if ( ! is_array( $data ) ) return null;
          set_transient( $cache_key, $data, WEEK_IN_SECONDS );
          return $data;
      }

      $yt_data = advice2025_get_youtube_oembed_by_id( $video_id );

      // Title fallback
      $video_title = $yt_data['title'] ?? __('Video', 'advice2025');
      ?>

      <div class="aspect-video relative mt-[20px] lg:mt-0 overflow-hidden" data-youtube-id="<?php echo esc_attr( $video_id ); ?>">
        <?php if ( $video_id ) : ?>
          <iframe class="absolute top-1/2 -translate-y-1/2  scale-150 left-0 w-full h-full pointer-events-none select-none"
            src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr( $video_id ); ?>?autoplay=1&mute=1&controls=0&loop=1&playlist=<?php echo esc_attr( $video_id ); ?>&modestbranding=1&rel=0&fs=0&disablekb=1&playsinline=1&iv_load_policy=3&cc_load_policy=0&showinfo=0"
            title="<?php echo esc_attr( $video_title ); ?>"
            frameborder="0"
            aria-hidden="true"
            tabindex="-1"
            allow="autoplay; encrypted-media"
            allowfullscreen>
          </iframe>
        <?php else : ?>
          <div class="absolute inset-0 w-full h-full grid place-items-center text-white/90">
            <p class="px-6 text-center"><?php _e('Geen YouTube video ID ingesteld.', 'advice2025'); ?></p>
          </div>
        <?php endif; ?>
      </div>
      <?php 
      } else { ?>
    <div class="aspect-video relative mt-[20px] lg:mt-0 overflow-hidden" ">
 
        <img src="<?= get_sub_field('background_image') ?>" alt="Welkom bij Beutech" class="w-full h-full object-cover object-center">
      </div>
    <?php } ?>
   
    
   
    </div>
    <div class="hero__sectors--sectors w-full relative lg:w-6/12 mt-[-40px] lg:mt-25 pl-[20px] lg:pl-0 lg:ml-[-8.33%]">
      <div class="hero__sectors--content w-full bg-blue-950 text-white py-[40px] px-[20px] lg:p-[60px]">
        <h2 class=" mb-[28px] mt-0 intersect:animate-fade-up intersect-once">Onze sectoren</h2>
        
        <?php 
        
        if( $query->have_posts() ) : ?>
          <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 group transition-all! duration-300! ease-in-out! ">
            <?php 
            $delay = 0;
            $count = 0;
            while( $query->have_posts() ) : $query->the_post(); 
              $count++;
              $delay += 100; // Increment delay for each item
            ?>
              <li class="sector-item opacity-100 group-hover:opacity-50! hover:opacity-100! group transition-all! ease-in-out! duration-300!">
                <a href="<?php the_permalink(); ?>" class="flex items-center justify-between py-4 intersect:animate-fade-up intersect-once  border-b border-white/20 relative pr-10" style="animation-delay: <?= intval($delay) ?>ms">
                <div class="flex gap-5 items-center">
                <img src="<?= get_field('icoon')['url'] ?>" alt="">

                <h5 class=""><?php the_title(); ?></h5>
</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11" viewBox="0 0 12 11" fill="none" class="absolute right-[8px] top-1/2 translate-y-[-50%] sector-item__icon">
<path d="M10.9482 4.20605H10.9492L12 5.25684L6.74316 10.5137L5.69238 9.46289L9.20801 5.94727H0V4.46094H9.09961L5.69043 1.05176L6.74219 0L10.9482 4.20605Z" fill="white"/>
</svg>
                </a>
              </li>
            <?php endwhile; ?>
          </ul>
        <?php else : ?>
          <p class="text-gray-300">Geen sectoren gevonden.</p>
        <?php endif; wp_reset_postdata(); ?>
        

        <!-- <a href="#sectoren" class="btn btn-outline-white mt-[40px] intersect:animate-fade-up intersect-once intersect:animate-delay-[700ms]">Bekijk al onze sectoren</a> -->
      </div>
    </div>
  </div>
  </div>
</section>