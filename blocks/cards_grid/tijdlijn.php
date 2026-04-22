<?php $tid = 'tl-' . wp_rand(1000, 9999); ?>

<div class="tijdlijn" id="<?php echo esc_attr($tid); ?>">
    <div class="tijdlijn__track">
        <?php foreach ($args['items'] as $item) : ?>
            <article class="tijdlijn__item" data-tl-item>
                <?php echo wp_get_attachment_image($item['icoon']['ID'], 'large', false, array('class' => 'tijdlijn__item__bg')); ?>
                <div class="tijdlijn__item__gradient"></div>
                <div class="tijdlijn__item__content">
                    <div class="label label-large text-primary mb-[1rem]"><?php echo esc_html($item['jaartal']); ?></div>
                    <h3 class="tijdlijn__item__title headline-small mb-[1.75rem]"><?php echo $item['card_title']; ?></h3>
                    <div class="tijdlijn__item__desc"><?php echo $item['content']; ?></div>
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
        }
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item {
        flex: 0 0 auto;
        position: relative;
        height: 580px;
        overflow: hidden;
        /* Width is set and animated by GSAP — no CSS transition here */
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__gradient {
        position: absolute;
        inset: auto 0 0 0;
        height: 280px;
        background: linear-gradient(180deg, rgba(22,22,22,0) 0%, #161616 100%);
        opacity: 0.8;
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__content {
        position: absolute;
        bottom: 40px;
        left: 40px;
        right: 40px;
        color: #fff;
        opacity: 0;
        /* Opacity animated by GSAP */
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__title {
        color: #fff;
        max-width: 500px;
    }

    #<?php echo esc_attr($tid); ?> .tijdlijn__item__desc {
        opacity: 0.8;
        font-size: 16px;
        line-height: 1.5;
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

        /* ── Controls: look for prev/next + progress in parent container ── */
        var container    = root.closest('.container') || root.parentElement;
        var prevBtn      = container ? container.querySelector('.swiper-prev') : null;
        var nextBtn      = container ? container.querySelector('.swiper-next') : null;
        var controlsKey  = '<?php echo esc_js($args['controls_class'] ?? ''); ?>';
        var progressEl   = controlsKey ? container.querySelector('.' + controlsKey + '-progress')  : null;
        var currentEl    = controlsKey ? container.querySelector('.' + controlsKey + '-current')   : null;
        var totalEl      = controlsKey ? container.querySelector('.' + controlsKey + '-total')     : null;

        function pad(n) { return String(n).padStart(2, '0'); }

        function updateControls() {
            var total = items.length;
            var step  = activeIndex + 1;

            if (currentEl) currentEl.textContent = pad(step);
            if (totalEl)   totalEl.textContent   = pad(total);

            if (progressEl) {
                var trackW     = 235; /* matches the fixed w-[14.6875rem] in cards_grid.php */
                var indicatorW = progressEl.offsetWidth || 90.7336;
                var maxMove    = trackW - indicatorW;
                var progress   = total > 1 ? (step - 1) / (total - 1) : 0;
                progressEl.style.transform = 'translateX(' + (maxMove * progress) + 'px)';
            }
        }

        /* ── Dimensions ────────────────────────────────────────────── */
        var GAP         = window.innerWidth < 768 ? 16 : 28;
        var activeIndex = 0;
        var inactiveW   = 0;   /* measured once at init from CSS */
        var activeW     = 0;   /* calculated from container width */
        var dragger     = null; /* kept for onComplete update call */

        function calcDimensions() {
            GAP = window.innerWidth < 768 ? 16 : 28;
            var containerW = root.offsetWidth;
            /*
             * Target: active + 1.5 × inactive = containerW
             * → inactive = containerW / (active_ratio/inactive_ratio + 1.5)
             * We choose active : inactive ≈ 3.5 : 1, so:
             * inactive = containerW / (3.5 + 1.5) = containerW / 5
             */
            inactiveW = Math.round(containerW / 5);
            activeW   = containerW - Math.round(2 * inactiveW);
            if (containerW < 768) {
                inactiveW = Math.round(containerW * 0.15);
                activeW   = Math.round(containerW * 0.82);
            }
        }

        /* Snap X for item i = sum of all items before it at inactiveW */
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

            /* Build a single GSAP timeline so everything moves together */
            var tl = window.gsap.timeline({ overwrite: true });

            /* Animate each item's width */
            items.forEach(function (item, i) {
                var w = (i === activeIndex) ? activeW : inactiveW;
                tl.to(item, { width: w, duration: duration, ease: ease }, 0);

                /* Fade content in/out */
                var content = item.querySelector('.tijdlijn__item__content');
                if (i === activeIndex) {
                    tl.to(content, {
                        opacity: 1,
                        duration: animate ? 0.3 : 0,
                        ease: 'none',
                        delay: animate ? 0.2 : 0
                    }, 0);
                } else {
                    tl.to(content, { opacity: 0, duration: animate ? 0.15 : 0, ease: 'none' }, 0);
                }
            });

            /* Animate track X — position calculated from KNOWN inactiveW, not from DOM */
            var targetX = Math.max(minX(), Math.min(0, snapXFor(activeIndex)));
            tl.to(track, { x: targetX, duration: duration, ease: ease }, 0);

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

            /* Set all items to inactiveW first (no animation) */
            items.forEach(function (item) {
                window.gsap.set(item, { width: inactiveW });
                window.gsap.set(item.querySelector('.tijdlijn__item__content'), { opacity: 0 });
            });

            window.gsap.set(track, { x: 0 });

            initDragger();

            /* Show first slide + init controls */
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
