<?php 
/**
 * Buttons Template Part
 * 
 * HOW TO USE:
 * 
 * 1. Direct met buttons array:
 *    get_template_part('template-parts/core/buttons', null, [
 *        'buttons' => [
 *            ['link' => ['url' => '/offerte/', 'title' => 'Offerte'], 'knop_kleur' => 'pink']
 *        ]
 *    ]);
 * 
 * 2. Via ACF field name (voor repeater/flexible content):
 *    get_template_part('template-parts/core/buttons', null, [
 *        'field_name' => 'buttons',
 *        'post_id' => 123  // optioneel, default is current post
 *    ]);
 * 
 * 3. Binnen ACF repeater loop (gebruikt get_sub_field als fallback):
 *    while(have_rows('mijn_repeater')) {
 *        the_row();
 *        get_template_part('template-parts/core/buttons');
 *    }
 */

// Flexibele button data handling
$buttons = null;

// 1. Eerst kijken naar direct meegegeven buttons array
if (isset($args['buttons']) && is_array($args['buttons'])) {
    $buttons = $args['buttons'];
}
// 2. Dan kijken naar field_name parameter (voor repeater/flexible content)
elseif (isset($args['field_name'])) {
    $post_id = $args['post_id'] ?? get_the_ID();
    // Probeer eerst get_sub_field (binnen repeater context)
    if (function_exists('get_sub_field') && get_row_index()) {
        $buttons = get_sub_field($args['field_name']);
    }
    // Anders gebruik get_field
    if (!$buttons && function_exists('get_field')) {
        $buttons = get_field($args['field_name'], $post_id);
    }
}
// 3. Fallback naar sub_field 'buttons' (binnen flexible content/repeater)
elseif (function_exists('get_sub_field')) {
    $buttons = get_sub_field('buttons');
}

// Overige parameters
$no_margin = $args['no_margin'] ?? false;
$align_items = $args['align_items'] ?? 'start'; // 'start' of 'stretch'
$full_width = $args['full_width'] ?? null; // null = auto (stretch), true = altijd w-full, false = nooit w-full

// Icon SVG templates
$icon_templates = [
  'geen' => '',
    'default' => '',
  'scroll' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="12" viewBox="0 0 20 12" fill="none">
  <rect x="20" width="2.22222" height="2.22222" transform="rotate(90 20 0)" fill="#161616"/>
  <rect x="8.14844" y="5.92578" width="2.22222" height="2.22222" transform="rotate(90 8.14844 5.92578)" fill="#161616"/>
  <rect x="5.18555" y="2.96094" width="2.22222" height="2.22222" transform="rotate(90 5.18555 2.96094)" fill="#161616"/>
  <rect x="2.22266" y="0.000183105" width="2.22222" height="2.22222" transform="rotate(90 2.22266 0.000183105)" fill="#161616"/>
  <rect x="17.0371" y="2.96094" width="2.22222" height="2.22222" transform="rotate(90 17.0371 2.96094)" fill="#161616"/>
  <rect x="11.1113" y="8.89062" width="2.22222" height="2.22222" transform="rotate(90 11.1113 8.89062)" fill="#161616"/>
  <rect x="14.0742" y="5.92578" width="2.22222" height="2.22222" transform="rotate(90 14.0742 5.92578)" fill="#161616"/>
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
    'fill' => 'btn bg-primary text-white! border border-primary hover:bg-black hover:text-white',
    'outline' => 'btn border border-primary text-primary hover:bg-primary hover:text-white',
  ],
  'secondary' => [
    'fill' => 'btn bg-secondary text-black border border-secondary hover:bg-primary hover:text-white',
    'outline' => 'btn border border-secondary text-secondary hover:bg-secondary hover:text-black',
  ],
  'white' => [
    'fill' => 'btn bg-white text-black border border-white hover:bg-secondary hover:text-black',
    'outline' => 'btn border border-white text-white hover:bg-white hover:text-black',
  ],
  'black' => [
    'fill' => 'btn bg-black text-white border border-black hover:bg-primary hover:text-white',
    'outline' => 'btn border border-black text-black hover:bg-black hover:text-white',
  ],
];






if($buttons) {
  if ($buttons && is_array($buttons)) {
      $align_class = $align_items === 'stretch' ? 'items-stretch' : 'items-start';
      
      // Bepaal width class
      if ($full_width === true) {
          $width_class = 'w-full lg:flex-col! ';
      } elseif ($full_width === false) {
          $width_class = '';
      } else {
          // Auto: w-full alleen bij stretch
          $width_class = $align_items === 'stretch' ? 'w-full' : '';
      }
      
      echo '<div class="relative flex flex-col lg:flex-row gap-4 flex-wrap ' . $align_class . ' ' . $width_class . '">';
      foreach ($buttons as $button) {
        $button_color = $button['knop_kleur'] ?? 'primary';
        if (!isset($colors[$button_color])) {
            $button_color = 'primary';
        }
        $btnStyle = $colors[$button_color];

        // soort_knop=true betekent outline variant.
        $class = !empty($button['soort_knop']) ? $btnStyle['outline'] : $btnStyle['fill'];

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
                  echo '<a' . $href_attr . $target_attr . $onclick_attr . ' class="'.$class.' flex items-center no-underline!' . $button_justify . '">' . $icon_svg . esc_html($button['link']['title']) . '</a>';
              } else {
                  echo '<a' . $href_attr . $target_attr . $onclick_attr . ' class="'.$class.' flex items-center gap-[0.875rem] no-underline! ' . $button_justify . '"><span>' . esc_html($button['link']['title']) . '</span>' . $icon_svg . '</a>';
              }
          }
      }
      echo '</div>';
  }
}