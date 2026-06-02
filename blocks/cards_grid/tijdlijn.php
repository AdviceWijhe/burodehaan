<?php $tid = 'tl-' . wp_rand(1000, 9999); ?>

<div class="tijdlijn" id="<?php echo esc_attr($tid); ?>">
    <div class="tijdlijn__track">
        <?php foreach ($args['items'] as $item) : ?>
            <article class="tijdlijn__item overflow-hidden flex flex-col bg-white transition-colors duration-300" data-tl-item>
                <div class="tijdlijn__item__media relative overflow-hidden shrink-0">
                    <?php if (!empty($item['icoon']['ID'])) : ?>
                        <?php echo wp_get_attachment_image($item['icoon']['ID'], 'large', false, array('class' => 'tijdlijn__item__bg w-full h-full object-cover')); ?>
                    <?php endif; ?>
                </div>
                <div class="tijdlijn__item__content p-[1.75rem] lg:p-[2.5rem]">
                    <?php if (!empty($item['jaartal'])) : ?>
                        <div class="label label-large text-primary mb-[1rem]"><?php echo esc_html($item['jaartal']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($item['card_title'])) : ?>
                        <h3 class="tijdlijn__item__title headline-small text-black mb-[1.75rem]! mt-0!"><?php echo wp_kses_post($item['card_title']); ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($item['content'])) : ?>
                        <div class="tijdlijn__item__desc body-medium text-black/80 [&_p]:mb-0"><?php echo wp_kses_post($item['content']); ?></div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<style>
    #<?php echo esc_attr($tid); ?> {
        overflow: hidden;
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__track {
        display: flex;
        align-items: stretch;
        gap: 16px;
    }

    @media (min-width: 768px) {
        #<?php echo esc_attr($tid); ?> .tijdlijn__track {
            gap: 28px;
        }
    }

    @media (max-width: 767px) {
        #<?php echo esc_attr($tid); ?> {
            margin-right: -16px;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
        }

        #<?php echo esc_attr($tid); ?>::-webkit-scrollbar {
            display: none;
        }

        #<?php echo esc_attr($tid); ?> .tijdlijn__track {
            transform: none !important;
        }

        #<?php echo esc_attr($tid); ?> .tijdlijn__item {
            width: 82vw !important;
            scroll-snap-align: start;
        }

        #<?php echo esc_attr($tid); ?> .tijdlijn__item__content {
            opacity: 1 !important;
            display: block !important;
        }
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item {
        flex: 0 0 auto;
        border: 1px solid transparent;
        /* Width is set and animated by GSAP */
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item.tijdlijn__item--active {
        border-color: rgba(22, 22, 22, 0.12);
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__media {
        height: 580px;
    }

    @media (max-width: 767px) {
        #<?php echo esc_attr($tid); ?> .tijdlijn__item__media {
            height: 320px;
        }
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__bg {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__content {
        opacity: 0;
        display: none;
        /* Opacity/display animated by GSAP on desktop */
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__title {
        max-width: 500px;
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__desc {
        max-width: 500px;
    }
</style>

<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.gsap || !window.Draggable) return;

        var root  = document.getElementById('<?php echo esc_js($tid); ?>');
        if (!root) return;

        var track = root.querySelector('.tijdlijn__track');
        var items = Array.prototype.slice.call(root.querySelectorAll('[data-tl-item]'));
        if (!track || !items.length) return;

        var isMobile = function () {
            return window.innerWidth < 768;
        };

        /* ── Controls: look for prev/next + progress in parent container ── */
        var container    = root.closest('.container') || root.parentElement;
        var controlsKey  = '<?php echo esc_js($args['controls_class'] ?? ''); ?>';
        var controlsRoot = (controlsKey && container)
            ? container.querySelector('.' + controlsKey + '-controls')
            : null;
        var prevBtn      = controlsRoot ? controlsRoot.querySelector('.swiper-prev') : null;
        var nextBtn      = controlsRoot ? controlsRoot.querySelector('.swiper-next') : null;
        var progressEl   = controlsRoot ? controlsRoot.querySelector('.' + controlsKey + '-progress') : null;
        var currentEl    = controlsRoot ? controlsRoot.querySelector('.' + controlsKey + '-current')  : null;
        var totalEl      = controlsRoot ? controlsRoot.querySelector('.' + controlsKey + '-total')    : null;

        function pad(n) { return String(n).padStart(2, '0'); }

        function updateControls() {
            var total = items.length;
            var step  = activeIndex + 1;

            if (currentEl) currentEl.textContent = pad(step);
            if (totalEl)   totalEl.textContent   = pad(total);

            if (progressEl) {
                var trackEl   = progressEl.parentElement;
                var trackW    = trackEl && trackEl.offsetWidth ? trackEl.offsetWidth : 235;
                var indicatorW = progressEl.offsetWidth || 90.7336;
                var maxMove   = Math.max(0, trackW - indicatorW);
                var progress  = total > 1 ? (step - 1) / (total - 1) : 0;
                progressEl.style.transform = 'translateX(' + (maxMove * progress) + 'px)';
            }

            if (prevBtn) {
                prevBtn.style.opacity = activeIndex <= 0 ? '0.35' : '1';
                prevBtn.style.pointerEvents = activeIndex <= 0 ? 'none' : '';
                prevBtn.setAttribute('aria-disabled', activeIndex <= 0 ? 'true' : 'false');
            }
            if (nextBtn) {
                var atEnd = activeIndex >= items.length - 1;
                nextBtn.style.opacity = atEnd ? '0.35' : '1';
                nextBtn.style.pointerEvents = atEnd ? 'none' : '';
                nextBtn.setAttribute('aria-disabled', atEnd ? 'true' : 'false');
            }
        }

        /* ── Dimensions ────────────────────────────────────────────── */
        var GAP         = window.innerWidth < 768 ? 16 : 28;
        var activeIndex = 0;
        var inactiveW   = 0;
        var activeW     = 0;
        var dragger     = null;

        function calcDimensions() {
            GAP = window.innerWidth < 768 ? 16 : 28;
            var containerW = root.offsetWidth;
            inactiveW = Math.round(containerW / 5);
            activeW   = containerW - Math.round(2 * inactiveW);
            if (containerW < 768) {
                inactiveW = Math.round(containerW * 0.15);
                activeW   = Math.round(containerW * 0.82);
            }
        }

        function snapXFor(i) {
            return -(i * (inactiveW + GAP));
        }

        function minX() {
            return snapXFor(items.length - 1);
        }

        /* ── Animation ─────────────────────────────────────────────── */
        function goTo(nextIndex, animate) {
            nextIndex = Math.max(0, Math.min(nextIndex, items.length - 1));
            activeIndex = nextIndex;

            var duration = animate ? 0.45 : 0;
            var ease     = 'power2.out';
            var tl = window.gsap.timeline({ overwrite: true });

            items.forEach(function (item, i) {
                var w = (i === activeIndex) ? activeW : inactiveW;
                tl.to(item, { width: w, duration: duration, ease: ease }, 0);
                item.classList.toggle('tijdlijn__item--active', i === activeIndex);

                var content = item.querySelector('.tijdlijn__item__content');
                if (!content) return;

                if (isMobile()) {
                    tl.set(content, { display: 'block', opacity: 1 }, 0);
                    return;
                }

                if (i === activeIndex) {
                    tl.set(content, { display: 'block' }, 0);
                    tl.to(content, {
                        opacity: 1,
                        duration: animate ? 0.3 : 0,
                        ease: 'none',
                        delay: animate ? 0.2 : 0
                    }, 0);
                } else {
                    tl.to(content, { opacity: 0, duration: animate ? 0.15 : 0, ease: 'none' }, 0);
                    tl.set(content, { display: 'none' }, animate ? 0.15 : 0);
                }
            });

            if (!isMobile()) {
                var targetX = Math.max(minX(), Math.min(0, snapXFor(activeIndex)));
                tl.to(track, { x: targetX, duration: duration, ease: ease }, 0);
            } else {
                window.gsap.set(track, { x: 0 });
            }

            updateControls();

            tl.eventCallback('onComplete', function () {
                if (dragger) {
                    dragger.applyBounds({ minX: minX(), maxX: 0 });
                    dragger.update(true);
                }
            });
        }

        function initDragger() {
            /* Drag is disabled */
        }

        /* ── Init ───────────────────────────────────────────────────── */
        function initialize() {
            calcDimensions();

            items.forEach(function (item) {
                window.gsap.set(item, { width: inactiveW });
                var content = item.querySelector('.tijdlijn__item__content');
                window.gsap.set(content, { opacity: 0, display: 'none' });
            });

            window.gsap.set(track, { x: 0 });

            initDragger();

            if (totalEl) totalEl.textContent = pad(items.length);
            goTo(0, false);
        }

        /* ── Buttons ────────────────────────────────────────────────── */
        if (prevBtn) {
            prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                goTo(activeIndex - 1, true);
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
                goTo(activeIndex + 1, true);
            });
        }

        /* ── Resize ─────────────────────────────────────────────────── */
        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                calcDimensions();
                initDragger();
                goTo(activeIndex, false);
            }, 120);
        });

        initialize();
    });
}());
</script>
