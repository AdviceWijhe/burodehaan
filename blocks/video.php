<section class="video <?php echo get_spacing_bottom_class(); ?>" relative">

  <div class="container mx-auto relative px-0! lg:px-[20px]">
    
    <?php 
      $video_input = trim((string) get_sub_field('video_id'));
      $instance_id = uniqid('video_');
      
      // Detect video platform and extract video ID
      $video_type = 'youtube';
      $video_id = '';
      $thumbnail_url = '';
      
      if ($video_input) {
        // Check if it's a YouTube URL
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_input, $matches)) {
          $video_type = 'youtube';
          $video_id = $matches[1];
          $thumbnail_url = 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg';
        }
        // Check if it's a Vimeo URL
        elseif (preg_match('/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/', $video_input, $matches)) {
          $video_type = 'vimeo';
          $video_id = $matches[1];
          // Use vumbnail.com for Vimeo thumbnails (free service)
          $thumbnail_url = 'https://vumbnail.com/' . $video_id . '.jpg';
        }
        // Assume it's a YouTube video ID if it's just an 11-character alphanumeric string
        elseif (preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_input)) {
          $video_type = 'youtube';
          $video_id = $video_input;
          $thumbnail_url = 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg';
        }
        // Assume it's a Vimeo ID if it's just numeric
        elseif (preg_match('/^\d+$/', $video_input)) {
          $video_type = 'vimeo';
          $video_id = $video_input;
          $thumbnail_url = 'https://vumbnail.com/' . $video_id . '.jpg';
        }
      }
    ?>
    <div class="w-full lg:w-8/12 lg:mx-auto">
      <?php if ($video_id) : ?>
        <div 
          id="<?= esc_attr($instance_id) ?>" 
          class="video-wrap relative overflow-hidden cursor-pointer group" 
          data-video-id="<?= esc_attr($video_id) ?>"
          data-video-type="<?= esc_attr($video_type) ?>"
          style="--ar:56.25%"
        >
          <!-- 16:9 aspect ratio -->
          <div class="block w-full" style="padding-top:var(--ar);"></div>
          <!-- Thumbnail -->
          <?php if ($thumbnail_url) : ?>
          <img 
            src="<?= esc_url($thumbnail_url) ?>" 
            alt="Video thumbnail" 
            loading="lazy" 
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
          >
          <?php endif; ?>
          <!-- Dark gradient overlay -->
          <div class="pointer-events-none absolute inset-0 bg-black/50"></div>

          <!-- TItle -->
          <?php if (get_sub_field('titel')) : ?>
          <div class="absolute left-1/2 bottom-[20px] lg:bottom-[28px] -translate-x-1/2 w-full text-center px-[20px]">
            <h3 class="title-medium text-white"><?= get_sub_field('titel') ?></h3>
          </div>
          <?php endif; ?>
          <!-- Custom play button -->
          <div 
            class="pointer-events-none absolute text-center left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2"
            aria-hidden="true"
          >
          <svg xmlns="http://www.w3.org/2000/svg" class="mb-[20px]!" width="120" height="120" viewBox="0 0 120 120" fill="none">
  <foreignObject x="-40" y="-40" width="200" height="200"><div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(20px);clip-path:url(#bgblur_0_4403_7484_clip_path);height:100%;width:100%"></div></foreignObject><g data-figma-bg-blur-radius="40">
    <circle cx="60" cy="60" r="60" fill="white" fill-opacity="0.05"/>
    <circle cx="60" cy="60" r="59.5" stroke="white" stroke-opacity="0.2"/>
  </g>
  <path d="M76 60L52 73.8564L52 46.1436L76 60Z" fill="white"/>
  <defs>
    <clipPath id="bgblur_0_4403_7484_clip_path" transform="translate(40 40)"><circle cx="60" cy="60" r="60"/>
  </clipPath></defs>
</svg>
<span class="title-medium text-white text-center font-light! mt-[20px]!">Speel af</span>
          </div>
        </div>
        
        <script>
          (function(){
            var root = document.getElementById('<?= esc_js($instance_id) ?>');
            if(!root) return;
            
            var videoType = root.getAttribute('data-video-type');
            var videoId = root.getAttribute('data-video-id');
            
            // YouTube player functions
            function loadYTScript(cb){
              if (window.YT && window.YT.Player) { cb(); return; }
              if (window.YT && typeof window.YT.ready === 'function') {
                window.YT.ready(cb);
                return;
              }
              var prev = window.onYouTubeIframeAPIReady;
              window.onYouTubeIframeAPIReady = function(){
                if (typeof prev === 'function') prev();
                cb();
              };
              if (!document.getElementById('yt-iframe-api')) {
                var tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                tag.id = 'yt-iframe-api';
                document.head.appendChild(tag);
              }
            }

            function createYTPlayer(){
              root.innerHTML = '<div class="relative w-full" style="padding-top:56.25%"><div id="<?= esc_js($instance_id) ?>_player" class="absolute inset-0"></div></div>';
              new YT.Player('<?= esc_js($instance_id) ?>_player', {
                videoId: videoId,
                width: '100%',
                height: '100%',
                playerVars: {
                  autoplay: 1,
                  controls: 0,
                  rel: 0,
                  fs: 0,
                  iv_load_policy: 3,
                  modestbranding: 1,
                  playsinline: 1,
                  disablekb: 1,
                  origin: window.location.origin
                },
                events: {
                  onReady: function(e){ e.target.playVideo(); }
                }
              });
            }
            
            // Vimeo player functions
            function loadVimeoScript(cb){
              if (window.Vimeo && window.Vimeo.Player) { cb(); return; }
              if (!document.getElementById('vimeo-player-api')) {
                var tag = document.createElement('script');
                tag.src = "https://player.vimeo.com/api/player.js";
                tag.id = 'vimeo-player-api';
                tag.onload = cb;
                document.head.appendChild(tag);
              } else {
                cb();
              }
            }

            function createVimeoPlayer(){
              root.innerHTML = '<div class="relative w-full" style="padding-top:56.25%"><iframe id="<?= esc_js($instance_id) ?>_player" src="https://player.vimeo.com/video/' + videoId + '?autoplay=1&background=0&controls=0&loop=0&muted=0&playsinline=1" class="absolute inset-0 w-full h-full" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>';
            }

            root.addEventListener('click', function(){
              if(videoType === 'youtube'){
                loadYTScript(function(){
                  createYTPlayer();
                });
              } else if(videoType === 'vimeo'){
                loadVimeoScript(function(){
                  createVimeoPlayer();
                });
              }
            }, {once:true});
          })();
        </script>
      <?php else: ?>
        <div 
          id="<?= esc_attr($instance_id) ?>" 
          class="video-wrap relative overflow-hidden cursor-pointer group" 
          style="--ar:56.25%"
        >
        <div class="block w-full" style="padding-top:var(--ar);"></div>
          <!-- Thumbnail -->
          <?php if (get_sub_field('terugval_afbeelding')) : ?>
          <img 
            src="<?= get_sub_field('terugval_afbeelding')['sizes']['1536x1536'] ?>" 
            alt="Video thumbnail" 
            loading="lazy" 
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
          >
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
    
  
  </div>
</section>