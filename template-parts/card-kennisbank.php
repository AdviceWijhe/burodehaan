<?php 

$ID = $args['post'] ?? get_the_ID();
$categories = get_the_category($ID);
?>

<?php if(!is_archive() && $categories[0]->slug == 'certificering-kwaliteit') : ?>
    <a href="" class="kennisbank h-full certificering-kwaliteit bg-white border-1 border-gray flex flex-col items-center pt-8 hover:translate-y-[-10px] transition-all duration-300">
        <div class="kennisbank__image h-[100px] w-full">
            <?php echo wp_get_attachment_image(get_post_thumbnail_id(get_the_ID()), 'full', false, array(
              'class' => 'w-full h-full object-contain',
              'alt' => get_the_title(),
              'loading' => 'lazy'
            )); ?>
        </div>
        <div class="kennisbank__content flex justify-center items-start text-center gap-3 p-8">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="23" class="min-w-[16px] min-h-[23px]" viewBox="0 0 16 23" fill="none">
<path d="M8 18L7.99902 18L0.114257 10.1152L1.69043 8.53809L6.96387 13.8105L6.96387 -3.89945e-07L9.19434 -2.92448e-07L9.19434 13.6514L14.3076 8.53809L15.8848 10.1152L8 18Z" fill="#E1322C"/>
<rect y="20" width="15.77" height="2.23026" fill="#E1322C"/>
</svg> <h3 class="headline-small no-after"><?= get_the_title() ?></h3>
        </div>
    </a>

<?php endif; ?>