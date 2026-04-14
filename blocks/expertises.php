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


<section id="<?= esc_attr($block_id); ?>" class="expertises bg-<?= $achtergrond ?> <?php echo get_spacing_bottom_class(); ?> <?php if(!is_front_page()) { echo 'pt-[140px]';} ?>">
    <div class="container">
        <div class="expertises_title <?= $tekst_kleur ?> mb-[48px]">
            <?= get_sub_field('titel') ?>
        </div>
        <?php if ($is_groot) : ?>
            <div class="expertises_hover_preview fixed top-0 left-0 z-50 hidden pointer-events-none w-[500px] h-[667px] overflow-hidden">
                <img src="" alt="" class="expertises_hover_preview_image w-full h-full object-cover" />
            </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[28px]">
            <?php foreach ($visible_expertises as $expertise) : ?>
                <?php $thumbnail_url = advice2025_get_term_thumbnail_url($expertise, 'large'); ?>
                <a
                    href="<?= get_term_link($expertise->term_id); ?>"
                    class="expertise border <?= $border_kleur ?> p-[32px] <?= $tekst_kleur ?> <?php if ($is_groot) { echo 'js-expertise-hover-trigger'; } ?>"
                    <?php if ($is_groot && !empty($thumbnail_url)) : ?>
                        data-hover-image="<?= esc_url($thumbnail_url); ?>"
                    <?php endif; ?>
                >
                    
                <div class="expertise__icoon mb-[20px]">
                    <?= get_field('icoon', 'expertise_' . $expertise->term_id); ?>
                </div>
                
                <h4 class="expertise_title mb-[132px]!">
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
                <div class="expertises_overige border border-[rgba(247,245,240,0.12)] rounded-lg p-[32px] text-white flex flex-col justify-between">
                <div>
                <div class="expertise__icoon mb-[20px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
  <path d="M30.8571 1.14286V14.8571H16V1.14286H30.8571ZM30.8571 16V30.8571H16V16H30.8571ZM14.8571 14.8571H1.14286V1.14286H14.8571V14.8571ZM1.14286 16H14.8571V30.8571H1.14286V16ZM1.14286 0H0V32H32V0H1.14286Z" fill="#EC663C"/>
</svg>
                </div>    
                <h4 class="expertise_title mb-[132px]!">Alle expertises</h4>
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
</section>

<?php if ($is_groot) : ?>
    <script>
        (() => {
            const block = document.getElementById('<?= esc_js($block_id); ?>');
            if (!block) {
                return;
            }

            const preview = block.querySelector('.expertises_hover_preview');
            const previewImage = block.querySelector('.expertises_hover_preview_image');
            const triggers = block.querySelectorAll('.js-expertise-hover-trigger[data-hover-image]');

            if (!preview || !previewImage || !triggers.length) {
                return;
            }

            const offset = 20;
            const movePreview = (event) => {
                const previewWidth = preview.offsetWidth;
                const previewHeight = preview.offsetHeight;
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;

                let x = event.clientX + offset;
                let y = event.clientY + offset;

                if (x + previewWidth > viewportWidth) {
                    x = event.clientX - previewWidth - offset;
                }

                if (y + previewHeight > viewportHeight) {
                    y = event.clientY - previewHeight - offset;
                }

                x = Math.max(0, x);
                y = Math.max(0, y);

                preview.style.transform = `translate(${x}px, ${y}px)`;
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('mouseenter', (event) => {
                    const imageUrl = trigger.getAttribute('data-hover-image');
                    if (!imageUrl) {
                        return;
                    }

                    previewImage.src = imageUrl;
                    preview.classList.remove('hidden');
                    movePreview(event);
                });

                trigger.addEventListener('mousemove', movePreview);

                trigger.addEventListener('mouseleave', () => {
                    preview.classList.add('hidden');
                    previewImage.src = '';
                });
            });
        })();
    </script>
<?php endif; ?>