<?php
// Haal CTA data op (gedeelde logica)
include locate_template('template-parts/core/cta-get-data.php');
?>

<section class="cta cta--center <?php echo get_spacing_bottom_class(); ?>">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-[2.5rem]">
            <div class="w-full lg:col-span-6 lg:col-start-4 text-black text-center">
                <?php if (!empty($cta_titel)) : ?>
                    <div class="mb-[1.5rem] lg:mb-[2rem]"><?php echo $cta_titel; ?></div>
                <?php endif; ?>

                <?php if (!empty($cta_content)) : ?>
                    <div class="opacity-80 body-medium mb-[1.5rem] lg:mb-[2rem]"><?php echo $cta_content; ?></div>
                <?php endif; ?>

                <?php if (!empty($cta_buttons)) : ?>
                    <div class="flex flex-row flex-wrap justify-center gap-4 [&>div]:!flex-row [&>div]:!justify-center">
                        <?php get_template_part('template-parts/core/buttons', null, array(
                            'buttons'     => $cta_buttons,
                            'no_margin'   => true,
                            'align_items' => 'start',
                            'full_width'  => false,
                        )); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
