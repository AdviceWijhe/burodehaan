<?php
/**
 * Block: Image + Text Block - Before/After variant
 *
 * Werkt hetzelfde als de standard variant, maar in plaats van een afbeelding
 * wordt het 'shortcode' WYSIWYG-veld naast de content getoond.
 */
$label = get_sub_field('label');
$heading = get_sub_field('titel');
$shortcode = get_sub_field('shortcode');
$content = get_sub_field('content');
$image_position = get_sub_field('image_position') ?: 'left';
$background_color = get_sub_field('background_color');
$block_id = 'image-text-' . uniqid();

$text_width = 'lg:w-6/12 px-[1.25rem] lg:px-[5rem] pb-[3.75rem]';
$image_width = 'lg:w-[50vw]';
$content_margin = '';
$image_margin = '';

if($background_color === '') {
    $text_width = 'lg:w-4/12 lg:ml-[calc(100%/12)]';
    $image_width = 'lg:w-[50vw]';
    $content_margin = '';
    $image_margin = 'lg:mr-[calc(50%-50vw)] lg:ml-auto';
}
if($image_position === 'left') {
    $text_width = 'lg:w-4/12';
    $content_margin = 'lg:ml-[calc(100%/12)]';
    $image_margin = 'lg:ml-[calc(50%-50vw)]';
} else {
    $image_margin = 'lg:mr-[calc(50%-50vw)] lg:ml-auto';
}

$text_color = 'white';

if($background_color === '') {
    $text_color = 'dark-blue';
}else if($background_color === 'white') {
    $text_color = 'dark-blue';
}
?>
<?php
$content_first = $image_position === 'right';
?>
<!-- Image + Text Block - Before/After -->
<div id="<?php echo esc_attr($block_id); ?>" class="js-image-text <?php echo $background_color === '' ? 'js-image-text--bg-empty' : ''; ?> <?php echo get_spacing_bottom_class(); ?> lg:relative flex flex-col lg:flex-row">
    <div class="container mx-auto max-md:px-0! lg:px-0! order-1 lg:order-0">
		<div class="js-it-row flex flex-col-reverse lg:flex-row <?php echo $background_color === '' ? 'items-center' : 'items-stretch'; ?> order-1">

              <div class="js-it-content max-md:px-[1.25rem]! relative w-full pt-[2.5rem] lg:pt-[3.75rem] overflow-hidden default-content flex flex-col justify-center items-start lg:py-[3.75rem]  text-<?= $text_color ?>  <?php echo $content_first ? 'order-1 lg:order-1' : 'order-1 lg:order-2'; ?> <?php echo $content_margin; ?> <?= $text_width ?>">
              <div class="bg-<?php echo $background_color; ?> absolute w-full <?php echo $content_first ? 'right-0' : 'left-0'; ?> top-0 h-full"></div>

                <?php if ($label) : ?>
                    <div class="label label-large text-primary mb-[1rem]! lg:mb-[1.25rem]!"><?php echo $label; ?></div>
                <?php endif; ?>
                  <?php if ($heading) : ?>
                 <div class="mb-[1.75rem] lg:mb-[2.5rem]">  <?php echo $heading; ?></div>
                <?php endif; ?>

                   <?php if ($content) : ?>
                    <div class="relative">
                        <?php echo $content; ?>
                    </div>
                  <?php endif; ?>

                <?php if(get_sub_field('buttons')) { ?>
                    <div class="mt-[1.5rem]! lg:mt-[2.5rem]!">
                      <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
                    </div>
                <?php } ?>
            </div>

            <div class="js-it-image js-image-text-animate flex items-center <?= $image_width ?> <?= $image_margin ?> relative w-full  <?php echo $content_first ? 'order-2 lg:order-2' : 'order-2 lg:order-1'; ?> ">
                <?php if ($shortcode) : ?>
                  <div class="first-image js-image-animate js-image-animate-first w-full">
                    <?php echo do_shortcode($shortcode); ?>
                  </div>
                <?php else: ?>
                    <!-- Placeholder als er geen shortcode is -->
                    <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <p class="text-sm">Geen shortcode</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
