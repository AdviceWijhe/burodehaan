<?php
$switch_title_label  = get_sub_field('label');
$switch_title_has_lbl = is_string($switch_title_label) && trim($switch_title_label) !== '';
// Op lg moet de content-tekst op dezelfde startregel staan als het titel-veld, niet boven de labelregel.
$switch_title_lg_row = $switch_title_has_lbl ? 'lg:row-start-2' : 'lg:row-start-1';
?>
<section class="switch__title <?php echo get_spacing_bottom_class(); ?>">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-x-[1.75rem] lg:gap-y-0">
            <?php if ($switch_title_has_lbl) : ?>
            <p class="label label-large text-primary mb-[1.25rem]! lg:col-span-5 lg:col-start-2 lg:pr-[10%] lg:row-start-1">
                <?php echo $switch_title_label; ?>
            </p>
            <?php endif; ?>
            <div class="[&>*:first-child]:mt-0 lg:col-span-5 lg:col-start-2 lg:pr-[10%] <?php echo esc_attr($switch_title_lg_row); ?>">
                <?php echo get_sub_field('titel'); ?>
            </div>
            <div class="lg:col-span-5 lg:col-start-7 <?php echo esc_attr($switch_title_lg_row); ?>">
                <div class="opacity-70"><?php echo get_sub_field('content'); ?></div>

                <?php if(get_sub_field('buttons')) { ?>
                    <div class="mt-[1.5rem]! lg:mt-[2rem]!">
                      <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
                    </div>
                <?php } ?>
            </div>

        </div>
        
    </div>
</section>