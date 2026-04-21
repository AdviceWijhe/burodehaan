<?php
$achtergrond = get_sub_field('achtergrond_kleur');
$tekst_kleur = 'text-white';
$border_kleur = 'border-[rgba(247,245,240,0.12)]';

if($achtergrond != 'black') {
    $tekst_kleur = 'text-black';
    $border_kleur = 'border-[rgba(22,22,22,0.12)]';
}
$expertises = get_terms(
    array(
        'taxonomy' => 'expertise',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    )
);

$is_groot = (bool) get_sub_field('groot_of_klein');
$block_id = 'expertises-' . wp_unique_id();

$visible_expertises = $is_groot ? $expertises : array_slice($expertises, 0, 5);
$remaining_expertises = $is_groot ? array() : array_slice($expertises, 5);


?>


<div id="<?= esc_attr($block_id); ?>" class="expertises <?php echo get_spacing_bottom_class(); ?> <?php if(!is_front_page()) { echo 'pt-[60px] lg:pt-[140px]';} ?>">
    <?php if($achtergrond) : ?>
        <div class="bg-<?= $achtergrond ?> pb-[60px] lg:pb-[120px]">
    <?php endif; ?>
    <div class="container">
        <div class="expertises_title <?= $tekst_kleur ?> mb-[28px] lg:mb-[48px]">
            <?= get_sub_field('titel') ?>
        </div>
        <?php if ($is_groot) : ?>
            <div class="expertises_hover_preview" aria-hidden="true">
                <img src="" alt="" />
            </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 lg:gap-[28px] gap-[16px]">
            <?php foreach ($visible_expertises as $expertise) : ?>
                <?php $thumbnail_url = advice2025_get_term_thumbnail_url($expertise, 'large'); ?>
                <a
                    href="<?= get_term_link($expertise->term_id); ?>"
                    class="expertise border <?= $border_kleur ?> p-[28px] lg:p-[32px] <?= $tekst_kleur ?> <?php if ($is_groot) { echo 'js-expertise-hover-trigger'; } ?>"
                    <?php if ($is_groot && !empty($thumbnail_url)) : ?>
                        data-hover-image="<?= esc_url($thumbnail_url); ?>"
                    <?php endif; ?>
                >
                    
                <div class="expertise__icoon mb-[20px]">
                    <?= get_field('icoon', 'expertise_' . $expertise->term_id); ?>
                </div>
                
                <h4 class="expertise_title mb-[40px]! lg:mb-[132px]!">
                        <?= $expertise->name ?>
                    </h4>

                    <div class="expertise_content flex items-end justify-between">
                        <div class="opacity-70"><?= $expertise->description ?></div>
                        <div class="expertise_content_icon shrink-0">

                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                            <rect width="2.22222" height="2.22222" fill="#EC663C"/>
                            <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#EC663C"/>
                            <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#EC663C"/>
                            <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="#EC663C"/>
                            <rect x="2.96094" y="2.96289" width="2.22222" height="2.22222" fill="#EC663C"/>
                            <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#EC663C"/>
                            <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#EC663C"/>
                        </svg>
                    </div>
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if (!$is_groot && !empty($remaining_expertises)) : ?>
                <div class="expertises_overige border border-[rgba(247,245,240,0.12)] p-[28px] lg:p-[32px] text-white flex flex-col justify-between">
                <div>
                <div class="expertise__icoon mb-[20px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
  <path d="M30.8571 1.14286V14.8571H16V1.14286H30.8571ZM30.8571 16V30.8571H16V16H30.8571ZM14.8571 14.8571H1.14286V1.14286H14.8571V14.8571ZM1.14286 16H14.8571V30.8571H1.14286V16ZM1.14286 0H0V32H32V0H1.14286Z" fill="#EC663C"/>
</svg>
                </div>    
                <h4 class="expertise_title mb-[40px]! lg:mb-[132px]!">Alle expertises</h4>
                </div>
                    <ul class="space-y-[10px]">
                        <?php foreach ($remaining_expertises as $expertise) : ?>
                            <li>
                                <a href="<?= get_term_link($expertise->term_id); ?>" class="body-small font-medium!">
                                    <?= $expertise->name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(get_sub_field('afbeelding')) : ?>
                <div class="expertises_image lg:col-span-2 lg:row-start-2 lg:col-start-3">
                    <?= wp_get_attachment_image(get_sub_field('afbeelding')['ID'], 'full', false, array('class' => 'w-full h-full object-cover')); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if($achtergrond) : ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($is_groot) : ?>
    <style>
        .expertises_hover_preview {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            width: 500px;
            height: 667px;
            pointer-events: none;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
            will-change: opacity;
        }

        .expertises_hover_preview.is-visible {
            opacity: 1;
        }

        .expertises_hover_preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    </style>
    <script>
        (() => {
            const block = document.getElementById('<?= esc_js($block_id); ?>');
            if (!block) return;

            const preview = block.querySelector('.expertises_hover_preview');
            const img = preview?.querySelector('img');
            const triggers = block.querySelectorAll('.js-expertise-hover-trigger[data-hover-image]');

            if (!preview || !img || !triggers.length) return;

            const W = 500;
            const H = 667;
            const GAP = 24;
            let hideTimer = null;

            const setPosition = (mx, my) => {
                const x = (mx + GAP + W > window.innerWidth) ? mx - W - GAP : mx + GAP;
                const y = (my + GAP + H > window.innerHeight) ? my - H - GAP : my + GAP;
                preview.style.transform = `translate(${x}px, ${y}px)`;
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('mouseenter', (e) => {
                    const url = trigger.dataset.hoverImage;
                    if (!url) return;

                    clearTimeout(hideTimer);
                    if (img.src !== url) img.src = url;

                    setPosition(e.clientX, e.clientY);
                    preview.classList.add('is-visible');
                });

                trigger.addEventListener('mousemove', (e) => setPosition(e.clientX, e.clientY));

                trigger.addEventListener('mouseleave', () => {
                    preview.classList.remove('is-visible');
                    hideTimer = setTimeout(() => { img.src = ''; }, 300);
                });
            });
        })();
    </script>
<?php endif; ?>