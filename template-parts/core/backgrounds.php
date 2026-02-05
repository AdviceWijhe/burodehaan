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




<div class="backgrounds w-[3535px] h-[3535px] rotate-[-45deg] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 scale-<?= $scale ?> lg:scale-<?= $scaleLg ?>">
  <div class="background absolute top-0 left-0 w-full h-full" style="background: <?= $bgColors[$color]['gradient-1'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[3093px] h-[3093px]" style="background: <?= $bgColors[$color]['gradient-2'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[2649px] h-[2649px]" style="background: <?= $bgColors[$color]['gradient-1'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[2205px] h-[2205px]" style="background: <?= $bgColors[$color]['gradient-2'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1761px] h-[1761px]" style="background: <?= $bgColors[$color]['gradient-1'] ?>"></div>
  <div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1317px] h-[1317px] bg-<?= $color ?>"></div>
</div>