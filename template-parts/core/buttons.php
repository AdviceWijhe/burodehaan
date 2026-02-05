<?php 
// HOW TO USE:  get_template_part('template-parts/core/buttons', null, array('buttons' => array(array('link' => array('url' => '/offerte-aanvragen/', 'title' => 'Offerte aanvragen'), 'knop_kleur' => 'pink')))) 
// 
// 
// 
// 



$buttons = $args['buttons'] ?? get_sub_field('buttons');
$no_margin = $args['no_margin'] ?? false;
$align_items = $args['align_items'] ?? 'start'; // 'start' of 'stretch'
$full_width = $args['full_width'] ?? null; // null = auto (stretch), true = altijd w-full, false = nooit w-full

// Icon SVG templates
$icon_templates = [
  'default' => '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
      <g class="arrow-group">
        <rect x="7" y="11.7427" width="10" height="1" fill="currentColor"/>
        <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
        <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
      </g>
    </svg>',
  'scroll' => '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" class="down-arrow" xmlns="http://www.w3.org/2000/svg">
    <circle cx="12.5" cy="12.5" r="12" transform="rotate(90 12.5 12.5)" stroke="currentColor"/>
    <g class="arrow-group">
    <rect x="12.7573" y="15.3817" width="0.5" height="5.5" transform="rotate(-135 12.7573 15.3817)" fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
    <rect x="12.7573" y="15.3817" width="5.5" height="0.5" transform="rotate(-135 12.7573 15.3817)" fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
    </g>
  </svg>',
  'tel' => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0 0.875L3.9375 0L6.125 3.9375L3.83359 5.76953C4.8207 7.63984 6.35742 9.17656 8.23047 10.1664L10.0625 7.875L14 10.0625L13.125 14H12.25C5.48516 14 0 8.51484 0 1.75V0.875Z" fill="currentColor"/>
  </svg>',
  'email' => '<svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0 0H14V2.1875L7 7L0 2.1875V0ZM0 10.5V3.24844L6.50508 7.72187L7 8.06094L7.49492 7.71914L14 3.24844V10.5H0Z" fill="currentColor"/>
  </svg>',
  'none' => ''
];

 
$colors = [
  'primary' => [
    'bg' => 'blue',  
    'text' => 'text-white', 
    'border' => 'border-blue', 
    'hover' => 'hover:bg-primary'
  ], 
  'secondary' => [
    'bg' => 'secondary', 
    'text' => 'text-white', 
    'border' => 'border-secondary', 
    'hover' => 'hover:bg-secondary hover:text-white'
  ], 
  'blue' => [
    'bg' => 'blue', 
    'text' => 'text-white', 
    'border' => 'border-blue', 
    'hover' => 'hover:bg-primary'
  ], 
  'light-blue' => [
    'bg' => 'light-blue', 
    'text' => 'text-primary', 
    'border' => 'border-light-blue', 
    'hover' => 'hover:bg-blue hover:text-white'
  ], 
  'pink' => [
    'bg' => 'pink', 
    'text' => 'text-white', 
    'border' => 'border-pink', 
    'hover' => 'hover:bg-secondary hover:text-white'
  ], 
  'light-pink' => [
    'bg' => 'light-pink', 
    'text' => 'text-secondary', 
    'border' => 'border-light-pink', 
    'hover' => 'hover:bg-pink hover:text-white'
  ], 
  'white' => [
    'bg' => 'white', 
    'text' => 'text-primary', 
    'border' => 'border-primary', 
    'hover' => 'hover:bg-primary hover:text-white'
    ]
  ];






if($buttons) {
  if ($buttons && is_array($buttons)) {
      $align_class = $align_items === 'stretch' ? 'items-stretch' : 'items-start';
      
      // Bepaal width class
      if ($full_width === true) {
          $width_class = 'w-full';
      } elseif ($full_width === false) {
          $width_class = '';
      } else {
          // Auto: w-full alleen bij stretch
          $width_class = $align_items === 'stretch' ? 'w-full' : '';
      }
      
      echo '<div class="relative flex flex-col lg:flex-row gap-4 ' . $align_class . ' ' . $width_class . '">';
      foreach ($buttons as $button) {
        $btnStyle = $colors[$button['knop_kleur']];

         // Voeg opties toe als soort_knop true is dan alleen border
        $class = 'btn bg-' . $btnStyle['bg'] . ' ' . $btnStyle['text'] . ' ' . $btnStyle['border'] . ' ' . $btnStyle['hover'];
        if($button['soort_knop']) {
          $class = 'btn border border-' . $btnStyle['bg'] . ' text-' . $btnStyle['bg'] . ' ' . $btnStyle['border'] . ' hover:bg-' . $btnStyle['bg'] . ' hover:'.$btnStyle['text'];
        }

          if (!empty($button['link']['title'])) {
              $url = $button['link']['url'];
              $is_contact_link = false;
              
              // Check of het een tel: of mailto: link is
              if (str_starts_with($url, 'tel:')) {
                  $knop_icon = 'tel';
                  $is_contact_link = true;
              } elseif (str_starts_with($url, 'mailto:')) {
                  $knop_icon = 'email';
                  $is_contact_link = true;
              } else {
                  // Icon SVG ophalen op basis van knop_icon veld
                  $knop_icon = $button['knop_icon'] ?? 'default';
              }
              
              $icon_svg = $icon_templates[$knop_icon] ?? $icon_templates['default'];
              
              // Target attribute toevoegen als link external is
              $target_attr = !empty($button['link']['target']) ? ' target="' . esc_attr($button['link']['target']) . '"' : '';
              
              // Scroll onclick toevoegen voor scroll buttons
              $onclick_attr = '';
              $href_attr = ' href="' . esc_url($url) . '"';
              
              if ($knop_icon === 'scroll') {
                  $onclick_attr = ' onclick="window.scrollTo({top: 1000, behavior: \'smooth\'})"';
                  $href_attr = '';
              }
              
              // Button justify bepalen op basis van align_items
              $button_justify = $align_items === 'stretch' ? 'justify-center' : 'justify-center lg:justify-start';
              
              // Tel en email icons komen voor de title, andere icons erna
              if ($is_contact_link) {
                  echo '<a' . $href_attr . $target_attr . $onclick_attr . ' class="'.$class.' flex items-center ' . $button_justify . '">' . $icon_svg . esc_html($button['link']['title']) . '</a>';
              } else {
                  echo '<a' . $href_attr . $target_attr . $onclick_attr . ' class="'.$class.' flex items-center ' . $button_justify . '">' . esc_html($button['link']['title']) . $icon_svg . '</a>';
              }
          }
      }
      echo '</div>';
  }
}