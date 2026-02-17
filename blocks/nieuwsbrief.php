<?php
/**
 * Block: Nieuwsbrief
 *
 * Blok met achtergrondafbeelding, zwarte overlay 50%, titel, tekst en Gravity Forms shortcode.
 */

$titel = get_sub_field('titel');
$tekst = get_sub_field('tekst');
$achtergrond = get_sub_field('achtergrond');
$gravityforms_shortcode = get_sub_field('gravityforms_shortcode');

$bg_style = '';
$has_bg = $achtergrond && !empty($achtergrond['url']);
if ($has_bg) {
    $bg_style = ' style="background-image: url(' . esc_url($achtergrond['url']) . ');"';
}
?>

<section class="nieuwsbrief-section <?php echo get_spacing_bottom_class(); ?>">
    <div class="flex justify-center">
        <div class="relative min-h-[400px] lg:min-h-[500px] w-full max-w-6xl mx-[20px] lg:mx-[80px] flex items-center justify-center bg-cover bg-center bg-no-repeat rounded-lg overflow-hidden <?php echo $has_bg ? '' : 'bg-gray-800'; ?>"<?php echo $bg_style; ?>>
            <!-- Zwarte overlay 50% -->
            <div class="absolute inset-0 bg-black/50" aria-hidden="true"></div>

            <div class="container mx-auto px-[20px] lg:px-[80px] py-[60px] lg:py-[80px] relative z-10 flex flex-col items-center text-center">
                <div class="max-w-2xl w-full">
                <?php if ($titel) : ?>
                    <h2 class="text-white mb-6 lg:mb-8 mt-0"><?php echo esc_html($titel); ?></h2>
                <?php endif; ?>

                <?php if ($tekst) : ?>
                    <div class="text-white mb-8 body-medium lg:mb-10 max-w-[500px] ml-auto mr-auto">
                        <?php echo $tekst; ?>
                    </div>
                <?php endif; ?>

                <?php if ($gravityforms_shortcode) : ?>
                    <div class="nieuwsbrief-form">
                        <?php echo do_shortcode($gravityforms_shortcode); ?>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
