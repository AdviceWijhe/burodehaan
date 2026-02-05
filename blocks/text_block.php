<?php
/**
 * Block: Text Block
 * 
 * Een eenvoudig tekstblok met optionele heading
 */


 $soortBlock = get_sub_field('soort_content');
?>

<!-- Text Block -->
<div class="<?php echo is_single() ? 'lg:pb-[100px] pb-[60px]' : get_spacing_bottom_class(); ?>">
    <div class="container mx-auto px-0! lg:px-[20px]">
        <?php 
        if($soortBlock === 'intro') { ?>
            <div class="w-full lg:w-7/12">
                <?php if (get_sub_field('content')) : ?>
                    <div class="title-large">
                        <?php the_sub_field('tekst'); ?>
                    </div>
                <?php endif; ?>
            </div>
       <?php  }
        if($soortBlock === 'default') { ?>
            <div class="w-full lg:w-6/12 mx-auto px-[20px] lg:px-[62px] default-content">
                <?php if (get_sub_field('content')) : ?>
                    <div class="">
                        <?php the_sub_field('content'); ?>
                    </div>
                <?php endif; ?>
            </div>
       <?php  }
        if($soortBlock === 'columns') {
           ?>
           <div class="">
            <?php if (get_sub_field('content')) : ?>
                <!-- De content automatisch in 2 kolommen -->
                <div class="content-columns title-large">
                    <?php the_sub_field('tekst'); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php  }
        ?>
        
    </div>
</div>