<section class="switch__title <?php echo get_spacing_bottom_class(); ?>">
    <div class="container">
        <div class="flex flex-col lg:flex-row gap-[1.75rem]">
            <div class="w-full lg:w-5/12 lg:ml-[calc(100%/12)] lg:pr-[10%]">
                <p class="label label-large text-primary mb-[1.25rem]!"><?php echo get_sub_field('label'); ?></p>
                <div><?php echo get_sub_field('titel'); ?></div>
            </div>
            <div class="w-full lg:w-5/12">
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