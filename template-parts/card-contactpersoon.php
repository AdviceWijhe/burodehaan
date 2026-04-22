<?php 

$contactpersoon = $args['medewerker'];

$telefoon = get_field('telefoonnummer', $contactpersoon) ?: get_field('telefoonnummer', 'option');
$email = get_field('emailadres', $contactpersoon) ?: get_field('emailadres', 'option');

$variant = $args['variant'] ?? 'default';

$text_color = $args['text-color'] ?? 'blue';



?>

<div class="flex <?php if($variant == 'big') {echo 'lg:gap-20  flex-col lg:items-start items-center lg:flex-row';}else {echo 'lg:gap-5 gap-3 items-center';} ?>   text-<?= $text_color ?> ">
  <div class="<?php if($variant == 'default') { echo 'w-[3.75rem] h-[3.75rem]';} else if($variant == 'big') {echo 'w-[11.25rem] h-[11.25rem] lg:w-[15.625rem] lg:h-[15.625rem]';} ?> overflow-hidden">
    <?php echo wp_get_attachment_image(get_post_thumbnail_id($contactpersoon), 'large', false, array(
      'class' => 'object-cover object-top',
      'alt' => get_the_title($contactpersoon),
      'loading' => 'lazy'
    )); ?>
  </div>
  <div class="">
    <p class="mb-[0.75rem]! font-medium!"><?= get_the_title($contactpersoon) ?></p>
    <p class="mb-0! list list-small"><?= get_field('functie', $contactpersoon) ?></p>
</div>
</div>