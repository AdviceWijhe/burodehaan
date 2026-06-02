<div class="video <?php echo get_spacing_bottom_class(); ?>" relative">

  <style>
    .video-toggle.video-toggle--persist {
      opacity: 1 !important;
      --tw-scale-x: 1 !important;
      --tw-scale-y: 1 !important;
    }
  </style>

  <div class="container mx-auto relative px-0! lg:px-[1.25rem]">
    
    <?php 
      $video_input = trim((string) get_sub_field('video_id'));
      $fallback_image = get_sub_field('terugval_afbeelding');
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

      // ACF fallback image gets priority over platform thumbnail.
      if (!empty($fallback_image['url'])) {
        $thumbnail_url = !empty($fallback_image['sizes']['1536x1536'])
          ? $fallback_image['sizes']['1536x1536']
          : $fallback_image['url'];
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
          <!-- Player container (iframe gets injected here) -->
          <div class="video-player absolute inset-0" data-video-player></div>
          <!-- Thumbnail -->
          <?php if ($thumbnail_url) : ?>
          <img 
            src="<?= esc_url($thumbnail_url) ?>" 
            alt="<?= esc_attr(!empty($fallback_image['alt']) ? $fallback_image['alt'] : 'Video thumbnail') ?>" 
            loading="lazy" 
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
            data-video-thumb
          >
          <?php endif; ?>
          <!-- Dark gradient overlay -->
          <div class="pointer-events-none absolute inset-0 bg-black/50 transition-opacity duration-300" data-video-gradient></div>

          <!-- TItle -->
          <?php if (get_sub_field('titel')) : ?>
          <div class="absolute left-1/2 bottom-[1.25rem] lg:bottom-[1.75rem] -translate-x-1/2 w-full text-center px-[1.25rem] transition-opacity duration-300" data-video-title>
            <h3 class="title-medium text-white"><?= get_sub_field('titel') ?></h3>
          </div>
          <?php endif; ?>
          <!-- Custom play button (initial state) -->
          <div 
            class="pointer-events-none absolute text-center left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2 transition-opacity duration-300"
            aria-hidden="true"
            data-video-initial
          >
         <svg xmlns="http://www.w3.org/2000/svg" class="video-block-play-icon mb-[0.5rem]! lg:mb-[1.25rem]!" width="120" height="120" viewBox="0 0 120 120" fill="none">
  <foreignObject x="-40" y="-40" width="200" height="200"><div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(20px);clip-path:url(#bgblur_0_4403_7484_clip_path);height:100%;width:100%"></div></foreignObject><g data-figma-bg-blur-radius="40">
    <circle cx="60" cy="60" r="60" fill="white" fill-opacity="0.05"/>
    <circle cx="60" cy="60" r="59.5" stroke="white" stroke-opacity="0.2"/>
  </g>
  <path d="M76 60L52 73.8564L52 46.1436L76 60Z" fill="white"/>
  <defs>
    <clipPath id="bgblur_0_4403_7484_clip_path" transform="translate(40 40)"><circle cx="60" cy="60" r="60"/>
  </clipPath></defs>
</svg>
<span class="title-medium text-white text-center font-light! mt-0! lg:mt-[1.25rem]!">Speel af</span>
          </div>

          <!-- Play/Pause toggle (visible once the video has started) -->
          <button 
            type="button"
            class="video-toggle absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100 transition-all duration-500 ease-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70 rounded-full"
            aria-label="Pauzeer video"
            data-video-toggle
            hidden
          >
            <span class="sr-only" data-video-toggle-label>Pauzeer video</span>
            <!-- Pause icon -->
            <svg xmlns="http://www.w3.org/2000/svg" data-video-icon-pause width="96" height="96" viewBox="0 0 120 120" fill="none">
              <foreignObject x="-40" y="-40" width="200" height="200"><div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(20px);clip-path:url(#bgblur_<?= esc_attr($instance_id) ?>_pause_clip);height:100%;width:100%"></div></foreignObject>
              <circle cx="60" cy="60" r="60" fill="white" fill-opacity="0.08"/>
              <circle cx="60" cy="60" r="59.5" stroke="white" stroke-opacity="0.2"/>
              <rect x="48" y="44" width="8" height="32" rx="1" fill="white"/>
              <rect x="64" y="44" width="8" height="32" rx="1" fill="white"/>
              <defs>
                <clipPath id="bgblur_<?= esc_attr($instance_id) ?>_pause_clip" transform="translate(40 40)"><circle cx="60" cy="60" r="60"/></clipPath>
              </defs>
            </svg>
            <!-- Play icon -->
            <svg xmlns="http://www.w3.org/2000/svg" data-video-icon-play width="96" height="96" viewBox="0 0 120 120" fill="none" class="hidden">
              <foreignObject x="-40" y="-40" width="200" height="200"><div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(20px);clip-path:url(#bgblur_<?= esc_attr($instance_id) ?>_play_clip);height:100%;width:100%"></div></foreignObject>
              <circle cx="60" cy="60" r="60" fill="white" fill-opacity="0.08"/>
              <circle cx="60" cy="60" r="59.5" stroke="white" stroke-opacity="0.2"/>
              <path d="M76 60L52 73.8564L52 46.1436L76 60Z" fill="white"/>
              <defs>
                <clipPath id="bgblur_<?= esc_attr($instance_id) ?>_play_clip" transform="translate(40 40)"><circle cx="60" cy="60" r="60"/></clipPath>
              </defs>
            </svg>
          </button>
        </div>
        
        <script>
          (function(){
            var root = document.getElementById('<?= esc_js($instance_id) ?>');
            if(!root) return;
            
            var videoType = root.getAttribute('data-video-type');
            var videoId = root.getAttribute('data-video-id');

            var playerContainer = root.querySelector('[data-video-player]');
            var toggleBtn = root.querySelector('[data-video-toggle]');
            var toggleLabel = root.querySelector('[data-video-toggle-label]');
            var iconPause = root.querySelector('[data-video-icon-pause]');
            var iconPlay = root.querySelector('[data-video-icon-play]');
            var coverEls = [
              root.querySelector('[data-video-thumb]'),
              root.querySelector('[data-video-gradient]'),
              root.querySelector('[data-video-title]'),
              root.querySelector('[data-video-initial]')
            ];

            var player = null;
            var isPlaying = false;
            var hasStarted = false;

            function hideCover(){
              coverEls.forEach(function(el){
                if(!el) return;
                el.classList.add('opacity-0');
                el.classList.add('pointer-events-none');
              });
            }

            function showToggle(){
              if(!toggleBtn) return;
              toggleBtn.hidden = false;
            }

            function updateToggleVisibility(){
              if(!toggleBtn) return;
              // Always visible when paused, only on hover when playing
              if(isPlaying){
                toggleBtn.classList.remove('video-toggle--persist');
              } else {
                toggleBtn.classList.add('video-toggle--persist');
              }
            }

            function setPlayingState(playing){
              isPlaying = playing;
              if(iconPause && iconPlay){
                iconPause.classList.toggle('hidden', !playing);
                iconPlay.classList.toggle('hidden', playing);
              }
              if(toggleBtn) toggleBtn.setAttribute('aria-label', playing ? 'Pauzeer video' : 'Speel video af');
              if(toggleLabel) toggleLabel.textContent = playing ? 'Pauzeer video' : 'Speel video af';
              updateToggleVisibility();
            }

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
              playerContainer.innerHTML = '<div id="<?= esc_js($instance_id) ?>_player" class="absolute inset-0 w-full h-full"></div>';
              player = new YT.Player('<?= esc_js($instance_id) ?>_player', {
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
                  onReady: function(e){ e.target.playVideo(); },
                  onStateChange: function(e){
                    if(e.data === YT.PlayerState.PLAYING){
                      setPlayingState(true);
                    } else if(e.data === YT.PlayerState.PAUSED || e.data === YT.PlayerState.ENDED){
                      setPlayingState(false);
                    }
                  }
                }
              });
            }

            function ytToggle(){
              if(!player) return;
              if(isPlaying){ player.pauseVideo(); } else { player.playVideo(); }
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
                var check = setInterval(function(){
                  if(window.Vimeo && window.Vimeo.Player){ clearInterval(check); cb(); }
                }, 50);
              }
            }

            function createVimeoPlayer(){
              playerContainer.innerHTML = '<iframe id="<?= esc_js($instance_id) ?>_player" src="https://player.vimeo.com/video/' + videoId + '?autoplay=1&background=0&controls=0&loop=0&muted=0&playsinline=1" class="absolute inset-0 w-full h-full" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
              player = new Vimeo.Player(document.getElementById('<?= esc_js($instance_id) ?>_player'));
              player.on('play', function(){ setPlayingState(true); });
              player.on('pause', function(){ setPlayingState(false); });
              player.on('ended', function(){ setPlayingState(false); });
            }

            function vimeoToggle(){
              if(!player) return;
              if(isPlaying){ player.pause(); } else { player.play(); }
            }

            function startVideo(){
              if(hasStarted) return;
              hasStarted = true;
              hideCover();
              showToggle();
              setPlayingState(true);
              if(videoType === 'youtube'){
                loadYTScript(createYTPlayer);
              } else if(videoType === 'vimeo'){
                loadVimeoScript(createVimeoPlayer);
              }
            }

            function togglePlayback(){
              if(videoType === 'youtube'){ ytToggle(); }
              else if(videoType === 'vimeo'){ vimeoToggle(); }
            }

            root.addEventListener('click', function(e){
              if(!hasStarted){
                startVideo();
                return;
              }
              // Once started, ignore clicks that originate inside the iframe player;
              // only the toggle button (or the surrounding overlay) controls playback.
              if(e.target.closest('[data-video-toggle]')){
                e.preventDefault();
                togglePlayback();
              }
            });

            if(toggleBtn){
              toggleBtn.addEventListener('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                togglePlayback();
              });
            }
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
</div>