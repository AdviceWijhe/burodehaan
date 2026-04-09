<?php 

$contactpersoon = $args['medewerker'];

$telefoon = get_field('telefoonnummer', $contactpersoon) ?: get_field('telefoonnummer', 'option');
$email = get_field('emailadres', $contactpersoon) ?: get_field('emailadres', 'option');

$variant = $args['variant'] ?? 'default';

$text_color = $args['text-color'] ?? 'blue';



?>

<div class="flex <?php if($variant == 'big') {echo 'lg:gap-20  flex-col lg:items-start items-center lg:flex-row';}else {echo 'gap-5 items-center';} ?>   text-<?= $text_color ?> ">
  <div class="<?php if($variant == 'default') { echo 'w-[60px] h-[60px]';} else if($variant == 'big') {echo 'w-[180px] h-[180px] lg:w-[250px] lg:h-[250px]';} ?> overflow-hidden">
    <?php echo wp_get_attachment_image(get_post_thumbnail_id($contactpersoon), 'large', false, array(
      'class' => 'object-cover object-top',
      'alt' => get_the_title($contactpersoon),
      'loading' => 'lazy'
    )); ?>
  </div>
  <div class="">
    <p class="mb-[12px]! font-bold"><?= get_the_title($contactpersoon) ?></p>
    <p class="mb-0! list list-small"><?= get_field('functie', $contactpersoon) ?></p>
</div>
</div>