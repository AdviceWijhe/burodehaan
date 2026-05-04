<?php
$items = get_sub_field('cards');
$swiper_setting = $args['swiper_setting'];
$rand_class = $args['rand_class'];
$cards_rand_class = $args['cards_rand_class'];
?>
  <div class="swiper-container <?= $rand_class ?> <?= esc_attr($cards_rand_class); ?>">
          <div class="swiper-wrapper">
            <?php foreach($items as $item) : ?>
              <div class="swiper-slide h-auto! border border-[rgba(22,22,22,0.12)]">
                <div class="p-[2.5rem] h-[20rem] flex items-center justify-center">
                <?php if($item['icoon']) : ?>

                    <?php echo wp_get_attachment_image($item['icoon']['ID'], 'large', false, array('class' => 'w-full h-full object-contain')); ?>

                <?php endif ?>
                </div>
                <div class="p-[2.5rem] border-t border-[rgba(22,22,22,0.12)]">
                <h3 class="mb-[1.75rem]!"><?php echo $item['card_title']; ?></h3>
                <div class="text-black/70 mb-0!"><?= $item['content']; ?></div>
                </div>
              </div>
            <?php endforeach; ?>
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

      