<section class="switch__title <?php echo get_spacing_bottom_class(); ?>">
    <div class="container">
        <div class="flex gap-[28px]">
            <div class="w-full lg:w-5/12 ml-[calc(100%/12)] lg:pr-[10%]">
                <p class="label label-large text-primary mb-[20px]!"><?php echo get_sub_field('label'); ?></p>
                <div><?php echo get_sub_field('titel'); ?></div>
            </div>
            <div class="w-full lg:w-5/12">
                <div class="opacity-70"><?php echo get_sub_field('content'); ?></div>

                <?php if(get_sub_field('buttons')) { ?>
                    <div class="mt-[24px]! lg:mt-[32px]!">
                      <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
                    </div>
                <?php } ?>
            </div>

        </div>
        
    </div>
</section>