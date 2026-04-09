<?php
// Haal CTA data op (gedeelde logica)
include locate_template('template-parts/core/cta-get-data.php');
?>

<section class="cta <?php echo get_spacing_bottom_class(); ?> <?php echo isset($args['cta_type']) ? '' : 'mt-[20px]'; ?>">
    <div class="container">
        <div class="w-full lg:w-10/12 mx-auto px-0 lg:px-[65px] overflow-hidden relative">
            <div class="flex flex-col items-center lg:flex-row border border-dark/10 rounded-[20px] p-[20px] lg:p-[60px]">
            <div class="w-full lg:w-6/12 order-1 lg:order-2 relative mb-[24px] lg:mb-0! lg:pr-[60px]">
                <?php if (!empty($cta_titel)): ?>
                    <h3 class="headline-small mt-0! mb-0!"><?php echo $cta_titel; ?></h3>
                <?php endif; ?>
                <?php if (!empty($cta_content)): ?>
                    <div class="opacity-80"><?php echo $cta_content; ?></div>
                <?php endif; ?>
            </div>
            <div class="w-full lg:w-6/12 lg:flex lg:justify-start lg:pr-[60px] order-3 relative mt-[24px] lg:mt-0!">
            <?= get_template_part('template-parts/card-contactpersoon', null, array('variant' => 'default')) ?>    
            <?php if (!empty($cta_buttons)): ?>
                    <?php get_template_part('template-parts/core/buttons', null, array('buttons' => $cta_buttons, 'no_margin' => true, 'align_items' => 'stretch', 'full_width' => false)); ?>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </div>
</section>

