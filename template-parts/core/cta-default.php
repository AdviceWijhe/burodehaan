<?php
// Haal CTA data op (gedeelde logica)
include locate_template('template-parts/core/cta-get-data.php');

$border_color = 'rgba(22,22,22,0.12)';
$text_color = 'black';
if($cta_background_color) {
    $text_color = $cta_background_color == 'primary' ? 'black' : 'white';
    $border_color = $cta_background_color == 'primary' ? 'rgba(22,22,22,0.12)' : 'rgba(247,245,240,0.12)';
}
$cta_bg_class = $cta_background_color ? 'bg-' . $cta_background_color : '';
$cta_padding_class = $cta_background_color ? 'pt-[3.75rem] lg:pt-[7.5rem]' : '';
if (!empty($args['disable_cta_padding']) || !empty($args['footer_cta'])) {
    $cta_padding_class = '';
}
// Footer: verticale ruimte loopt via de wrapper in footer.php; geen dubbele bottom-spacing op de section.
$cta_section_bottom_spacing = !empty($args['footer_cta']) ? '' : get_spacing_bottom_class();

$show_cta_contact_toggle = (bool) get_sub_field('contactpersoon_tonen');
if (!empty($args['footer_cta'])) {
    if (array_key_exists('footer_cta_contact_toggle', $args)) {
        $show_cta_contact_toggle = (bool) $args['footer_cta_contact_toggle'];
    } else {
        $cta_group_option = get_field('cta_groep', 'option');
        if (is_array($cta_group_option)) {
            $show_cta_contact_toggle = !empty($cta_group_option['cta_contactpersoon_tonen']);
            if (!empty($cta_group_option['cta_contactpersoon']) && empty($cta_contactpersoon)) {
                $cta_contactpersoon = $cta_group_option['cta_contactpersoon'];
            }
        } else {
            $show_cta_contact_toggle = false;
            $cta_contactpersoon = null;
        }
    }
}
$show_cta_contact = (bool) ($show_cta_contact_toggle && !empty($cta_contactpersoon));


?>

<section class="cta <?php echo $cta_section_bottom_spacing; ?> <?php echo $cta_bg_class; ?> text-<?php echo $text_color; ?>! <?php echo $cta_padding_class; ?>">
    <div class="container">
        <div class="w-full lg:w-10/12 mx-auto px-0 lg:px-[4.0625rem] overflow-hidden relative">
            <div class="flex flex-col items-center lg:flex-row lg:items-stretch border border-[<?php echo $border_color; ?>] rounded-[1.25rem] p-[2.25rem] lg:p-[3.75rem]">
            <div class="w-full lg:w-6/12 order-1 lg:order-2 relative mb-[1.5rem] lg:mb-0! lg:pr-[10rem]">
                <?php if (!empty($cta_titel)): ?>
                    <div class="mt-0! mb-[1.75rem] lg:mb-[2.5rem] pr-[12%]"><?php echo $cta_titel; ?></div>
                <?php endif; ?>
                <?php if (!empty($cta_content)): ?>
                    <div class="opacity-80 body-medium"><?php echo $cta_content; ?></div>
                <?php endif; ?>
            </div>
            <div class="w-full lg:w-6/12 order-3 relative flex flex-col">
                <?php if ($show_cta_contact) : ?>
                <div class="mb-[2rem]">
                 <?= get_template_part('template-parts/card-contactpersoon', null, array('variant' => 'default', 'medewerker' => $cta_contactpersoon)) ?>    
                </div>
                <?php endif; ?>
                <div class="lg:mt-auto w-full <?php echo $show_cta_contact ? '' : 'lg:flex lg:justify-end'; ?>">
                <?php if (!empty($cta_buttons)): ?>
                        <?php
                        $cta_primary_dark_hover = (bool) ($cta_background_color && $cta_background_color !== 'primary');
                        get_template_part(
                            'template-parts/core/buttons',
                            null,
                            array(
                                'buttons' => $cta_buttons,
                                'no_margin' => true,
                                'align_items' => $show_cta_contact ? 'stretch' : 'start',
                                'full_width' => false,
                                'primary_hover_on_dark' => $cta_primary_dark_hover,
                            )
                        );
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>

