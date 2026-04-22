<?php 

$color = $args['color'] ?? 'primary';
$scale = $args['scale'] ?? '80';
$scaleLg = $args['scaleLg'] ?? '80';
$bgColors = [
    'primary' => [
        'gradient-1' => 'conic-gradient(from 180deg at 50% 50%, #0F2030 0deg, #08334A 180deg, #0F2030 360deg)',
        'gradient-2' => 'conic-gradient(from 180deg at 50% 50%, #08334A 0deg, #0F2030 180deg, #08334A 360deg)',
    ],
    'blue' => [
        'gradient-1' => 'conic-gradient(from 180deg at 50% 50%, #0F2030 0deg, #08334A 180deg, #0F2030 360deg)',
        'gradient-2' => 'conic-gradient(from 180deg at 50% 50%, #08334A 0deg, #0F2030 180deg, #08334A 360deg)',
    ],
    'secondary' => [
        'gradient-1' => 'conic-gradient(from 180deg at 50% 50%, #480E25 0deg, #6C0733 180deg, #480E25 360deg)',
        'gradient-2' => 'conic-gradient(from 180deg at 50% 50%, #6C0733 0deg, #480E25 180deg, #6C0733 360deg)',
    ],
    'pink' => [
        'gradient-1' => 'conic-gradient(from 180deg at 50% 50%, #480E25 0deg, #6C0733 180deg, #480E25 360deg)',
        'gradient-2' => 'conic-gradient(from 180deg at 50% 50%, #6C0733 0deg, #480E25 180deg, #6C0733 360deg)',
    ],
]

?>




<div class="backgrounds w-[220.9375rem] h-[220.9375rem] rotate-[-45deg] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 scale-<?= $scale ?> lg:scale-<?= $scaleLg ?>">
  <div class="background absolute top-0 left-0 w-full h-full" style="background: <?= $bgColors[$color]['gradient-1'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[193.3125rem] h-[193.3125rem]" style="background: <?= $bgColors[$color]['gradient-2'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[165.5625rem] h-[165.5625rem]" style="background: <?= $bgColors[$color]['gradient-1'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[137.8125rem] h-[137.8125rem]" style="background: <?= $bgColors[$color]['gradient-2'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[110.0625rem] h-[110.0625rem]" style="background: <?= $bgColors[$color]['gradient-1'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[82.3125rem] h-[82.3125rem] bg-<?= $color ?>"></div>
</div>