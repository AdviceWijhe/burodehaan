<?php 

$usps = get_sub_field('usps');

if(get_sub_field('globaal')) {
    $usps = get_field('usps', 'option');

    if(is_page('werken-bij') || is_singular('vacature')) {

      $usps = get_field('werken_bij_usps', 'option');
    }
}

?>

<div class="usps <?php echo get_spacing_bottom_class(); ?>">
  <div class="container mx-auto">
  <?php 
    $titel = get_sub_field('titel');
    $content = get_sub_field('content');
    if ($titel || $content) : 
  ?>
    <div class="w-full lg:w-5/12 pr-4 lg:pr-30 mb-[80px] lg:mb-[120px]">
      <?php if($titel) : ?> 
        <h2 class="headline-medium mb-5"><?= $titel ?></h2>
      <?php endif; ?>
      <?php if($content) : ?>
        <p class="headline-small no-after"><?= $content ?></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

    <div class="flex flex-col gap-[28px] lg:gap-0 lg:flex-row w-full items-center justify-between">
      <?php 
      $count = 0;
      $delay = 0;
      foreach($usps as $usp) : 
        $count++;
        $delay += 300;
        ?>

        <div class="w-full lg:w-4/12 flex items-center gap-5 lg:pr-24 intersect:animate-fade-up intersect-once" style="animation-delay: <?= intval($delay) ?>ms">
          <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" class="min-w-[50px] min-h-[50px]" viewBox="0 0 50 50" fill="none">
<circle cx="25" cy="25" r="24" stroke="#42AB41" stroke-width="2"/>
<rect x="17.3945" y="26.6067" width="8" height="2" transform="rotate(45 17.3945 26.6067)" fill="#42AB41"/>
<rect x="32.2422" y="20.2427" width="2" height="17" transform="rotate(45 32.2422 20.2427)" fill="#42AB41"/>
</svg>
        <h5><?= $usp['usp'] ?></h5>
        </div>

        <?php endforeach; ?>
    </div>
  </div>
</div>