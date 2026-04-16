<?php
// Haal CTA data op (gedeelde logica)
include locate_template('template-parts/core/cta-get-data.php');

$border_color = 'rgba(22,22,22,0.12)';
$text_color = 'black';
if($cta_background_color) {
    $text_color = $cta_background_color == 'primary' ? 'black' : 'white';
    $border_color = $cta_background_color == 'primary' ? 'rgba(22,22,22,0.12)' : 'rgba(247,245,240,0.12)';
}



?>

<section class="cta  <?php echo get_spacing_bottom_class(); ?> <?php echo isset($args['cta_type']) ? '' : ''; ?> bg-<?php echo $cta_background_color; ?> text-<?php echo $text_color; ?>! <?php if($cta_background_color) { echo 'pt-[60px] lg:pt-[120px]';} ?>">
    <div class="container">
        <div class="w-full lg:w-10/12 mx-auto px-0 lg:px-[65px] overflow-hidden relative">
            <div class="flex flex-col items-center lg:flex-row border border-[<?php echo $border_color; ?>] rounded-[20px] p-[36px] lg:p-[60px]">
            <div class="w-full lg:w-6/12 order-1 lg:order-2 relative mb-[24px] lg:mb-0! lg:pr-[160px]">
                <?php if (!empty($cta_titel)): ?>
                    <div class="mt-0! mb-[28px] lg:mb-[40px] pr-[12%]"><?php echo $cta_titel; ?></div>
                <?php endif; ?>
                <?php if (!empty($cta_content)): ?>
                    <div class="opacity-80 body-medium"><?php echo $cta_content; ?></div>
                <?php endif; ?>
            </div>
            <div class="w-full lg:w-6/12 order-3 relative">
                <div class="mb-[32px]">
                 <?= get_template_part('template-parts/card-contactpersoon', null, array('variant' => 'default', 'medewerker' => $cta_contactpersoon)) ?>    
                </div>
                <div class="">
                <?php if (!empty($cta_buttons)): ?>
                        <?php get_template_part('template-parts/core/buttons', null, array('buttons' => $cta_buttons, 'no_margin' => true, 'align_items' => 'stretch', 'full_width' => false)); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>

