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
                <div class="p-[2.5rem] h-[320px] flex items-center justify-center">
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

      