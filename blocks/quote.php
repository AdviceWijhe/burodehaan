<div class="quote <?php echo get_spacing_bottom_class(); ?>">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-12">
            <div class="col-span-12 md:col-span-5 md:col-start-3">
                <div class="quote-content p-[20px] lg:p-[60px] border border-[rgba(22,22,22,0.12)]">
                    <div class="quote-text headline-small text-center">
                        <?php echo get_sub_field('quote'); ?>
                    </div>
                    <div class="quote-author flex items-center justify-center gap-[16px] mt-[40px]">
                        <div class="quote-author__image w-[60px] h-[60px] rounded-full overflow-hidden">
                            <?php echo wp_get_attachment_image(get_sub_field('foto')['ID'], 'medium', false, array(
                                'class' => 'w-full h-full object-cover object-center',
                                'alt' => get_sub_field('naam') ?? '',
                                'loading' => 'lazy'
                            )); ?>
                        </div>
                        <div class="quote-author__name">
                            <span class="block font-medium"><?php echo get_sub_field('naam'); ?></span>
                            <span class="quote-author__function">   <?php echo get_sub_field('bedrijf'); ?></span>
                        </div>
                    </div>
                </div>
            </div>