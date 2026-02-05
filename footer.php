    <footer id="colophon" class="site-footer bg-blue text-white mt-auto relative overflow-hidden">
    <?= get_template_part('template-parts/core/backgrounds', null, array('color' => 'primary', 'scale' => '60', 'scaleLg' => '100')) ?>
    <div class="container mx-auto py-20! lg:py-25! relative z-10">
    <div class="w-full mb-[80px]">
            <!-- Site logo from customizer -->
             <?php if (has_custom_logo()) : ?>
                <div class="site-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                        <?php 
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        if ($logo) {
                            echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="h-[50px] lg:h-[80px] w-auto object-contain">';
                        }
                        ?>
                    </a>
                </div>
            <?php endif; ?>
          </div>
        <div class="flex flex-col lg:flex-row w-full">
          <div class="w-full lg:w-6/12">
            <div class="flex flex-col lg:flex-row">
              <!-- 3x een menu locatie -->
              <?php if ( has_nav_menu( 'footer-menu-1' ) ) : ?>
                <nav class="footer-menu-1 w-full lg:w-1/2">
                  <?php 
                  $menu_items = wp_get_nav_menu_items( get_nav_menu_locations()['footer-menu-1'] );
                  if ( $menu_items && count( $menu_items ) > 0 ) :
                    $first_item = $menu_items[0];
                    $remaining_items = array_slice( $menu_items, 1 );
                  ?>
                    <div class="footer-menu-mobile">
                      <span class="footer-menu-title font-semibold title-small mb-5 block"><?php echo esc_html( $first_item->title ); ?></span>
                      <ul class="footer-menu-items block space-y-[18px]">
                        <?php foreach ( $remaining_items as $item ) : 
                          // Haal de CSS classes op van het menu item
                          $classes = empty($item->classes) ? array() : (array) $item->classes;
                          $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
                          $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                          
                          // Genereer de link HTML met arrow icon
                          $arrow_svg = '<svg class="footer-menu-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="none">
  <path d="M1.06055 -4.6358e-08L5.30371 4.24316L1.06055 8.48535L-4.6358e-08 7.4248L3.18164 4.24316L-3.24549e-07 1.06055L1.06055 -4.6358e-08Z" fill="#96ACC0"/>
</svg>';
                          $link_html = '<a href="' . esc_url( $item->url ) . '" class="footer-menu-link text-white hover:text-white hover:cursor-pointer font-normal inline-flex items-center gap-2 group relative translate-x-[-15px] hover:translate-x-0  duration-300 transition-all">' . $arrow_svg . '<span>' . esc_html( $item->title ) . '</span></a>';
                          
                          // Voeg vacature counter toe als nodig
                          $link_html = advice2025_add_vacature_counter_to_manual_menu($link_html, $class_names);
                          ?>
                          <li<?php echo $class_names; ?>><?php echo $link_html; ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php else : ?>
                    <?php wp_nav_menu( array( 'theme_location' => 'footer-menu-1', 'container_class' => 'footer-menu' ) ); ?>
                  <?php endif; ?>
                </nav>
              <?php endif; ?>
              <?php if ( has_nav_menu( 'footer-menu-2' ) ) : ?>
                <nav class="footer-menu-2 w-full lg:w-1/2 mt-[60px] lg:mt-0!">
                  <?php 
                  $menu_items = wp_get_nav_menu_items( get_nav_menu_locations()['footer-menu-2'] );
                  if ( $menu_items && count( $menu_items ) > 0 ) :
                    $first_item = $menu_items[0];
                    $remaining_items = array_slice( $menu_items, 1 );
                  ?>
                    <div class="footer-menu-mobile">
                      <span class="footer-menu-title font-semibold title-small mb-5 block"><?php echo esc_html( $first_item->title ); ?></span>
                      <ul class="footer-menu-items block space-y-[18px]">
                        <?php foreach ( $remaining_items as $item ) : 
                          // Haal de CSS classes op van het menu item
                          $classes = empty($item->classes) ? array() : (array) $item->classes;
                          $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
                          $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                          
                          // Genereer de link HTML met arrow icon
                          $arrow_svg = '<svg class="footer-menu-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="none">
                          <path d="M1.06055 -4.6358e-08L5.30371 4.24316L1.06055 8.48535L-4.6358e-08 7.4248L3.18164 4.24316L-3.24549e-07 1.06055L1.06055 -4.6358e-08Z" fill="#96ACC0"/>
                        </svg>';
                                                  $link_html = '<a href="' . esc_url( $item->url ) . '" class="footer-menu-link text-white hover:text-white hover:cursor-pointer font-normal inline-flex items-center gap-2 group relative translate-x-[-15px] hover:translate-x-0  duration-300 transition-all">' . $arrow_svg . '<span>' . esc_html( $item->title ) . '</span></a>';
                                                 
                          // Voeg vacature counter toe als nodig
                          $link_html = advice2025_add_vacature_counter_to_manual_menu($link_html, $class_names);
                          ?>
                          <li<?php echo $class_names; ?>><?php echo $link_html; ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php else : ?>
                    <?php wp_nav_menu( array( 'theme_location' => 'footer-menu-2', 'container_class' => 'footer-menu' ) ); ?>
                  <?php endif; ?>
                </nav>
              <?php endif; ?>
            </div>
          </div>
          <div class="w-full lg:w-5/12 mt-[60px] lg:mt-0! lg:ml-auto">
              <div class="w-full min-w-full">
                <h6 class="mb-5">Contact</h5>
                <div class="flex align-center flex-col lg:flex-row gap-4">
                <a href="tel:<?= get_field('telefoonnummer', 'option') ?>" class="btn border border-white text-white inline-flex items-center justify-center lg:justify-start "><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path class="" d="M3.86467 0.577066C3.68421 0.141147 3.20845 -0.0908738 2.75378 0.0333395L0.691376 0.595815C0.283581 0.70831 0 1.07861 0 1.50046C0 7.29864 4.70136 12 10.4995 12C10.9214 12 11.2917 11.7164 11.4042 11.3086L11.9667 9.24621C12.0909 8.79155 11.8589 8.31579 11.4229 8.13533L9.17303 7.19787C8.79102 7.0385 8.34807 7.14865 8.08792 7.46973L7.14109 8.62515C5.49116 7.84471 4.15529 6.50884 3.37485 4.85891L4.53027 3.91442C4.85135 3.65193 4.9615 3.21132 4.80213 2.82931L3.86467 0.579409V0.577066Z" fill="white"/>
                </svg> <?= get_field('telefoonnummer', 'option') ?></a>
                <a href="mailto:<?= get_field('emailadres', 'option') ?>" class="btn border border-white text-white inline-flex items-center justify-center lg:justify-start lg:items-start"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="9" viewBox="0 0 12 9" fill="none">
                  <path class="" d="M1.125 0C0.503906 0 0 0.503906 0 1.125C0 1.47891 0.166406 1.81172 0.45 2.025L5.55 5.85C5.81719 6.04922 6.18281 6.04922 6.45 5.85L11.55 2.025C11.8336 1.81172 12 1.47891 12 1.125C12 0.503906 11.4961 0 10.875 0H1.125ZM0 2.625V7.5C0 8.32734 0.672656 9 1.5 9H10.5C11.3273 9 12 8.32734 12 7.5V2.625L6.9 6.45C6.36562 6.85078 5.63438 6.85078 5.1 6.45L0 2.625Z" fill="white"/>
                </svg> <?= get_field('emailadres', 'option') ?></a>
                </div>
              </div>
              <div class="flex flex-col lg:flex-row mt-[80px]">
              <div class="lg:w-1/2 mb-[40px]">
                <h6 class="mb-5">Locatie</h5>
                <p class="mb-0!"><?= get_field('adres', 'option') ?></p>
                <p class="mb-0!"><?= get_field('postcode_+_woonplaats', 'option') ?></p>
              </div>
              <div class="lg:w-1/2 mt-10 lg:mt-0 flex gap-3">
                <?php $header_cta = get_field('header_cta', 'option'); ?>
                <div class="relative w-[90px] rotate-[-7deg]" style="margin-top: -35px;">
                  <img src="<?= $header_cta['afbeelding']['url'] ?>" alt="<?= $header_cta['afbeelding']['alt'] ?>" class="w-[72px] h-[100px] shadow-xl absolute top-1/2 -translate-y-1/2 left-[-10px]">
                </div>
                 
                    <div class="w-full">
                    <h6 class="mb-5">Raising Giants</h5>
                    <a href="" class="flex w-full justify-between items-center">
                      <div class="span">
                      <p class="mb-0! font-bold"><?= $header_cta['titel'] ?></p>
                      <p class="mb-0! text-white/70"><?= $header_cta['subtitel'] ?></p>
                      </div>
                      <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12" stroke="#96ACC0"/>
                            <g class="arrow-group">
                                <rect x="7" y="11.7427" width="10" height="1" fill="#96ACC0"/>
                                <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="#96ACC0"/>
                                <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="#96ACC0"/>
                            </g>
                            </svg>
                    </a>
                    </div>
                    
                 
              </div>
            </div>
          </div>
        </div>
        <div class="flex flex-col-reverse lg:flex-row items-center mt-[80px]">
                <div class="w-full lg:w-8/12">
                  <!-- Copyright menu -->
                  <?php if (has_nav_menu( 'copyright-menu' ) ) : ?>
                    <nav class="copyright-menu">
                      <?php wp_nav_menu( array( 'theme_location' => 'copyright-menu', 'container_class' => 'footer-menu' , 'menu_class' => 'flex flex-col lg:flex-row flex-wrap gap-[20px] small' ) ); ?>
                    </nav>
                  <?php endif; ?>
                </div>
                <div class="w-full lg:w-4/12">
                <?php if (!empty(get_field('socials', 'option'))) { ?>
                <div class="footer_socials socials mb-[40px] lg:mb-0!">
                <?php foreach (get_field('socials', 'option') as $social) { ?>
                    <a href="<?= $social['link']['url'] ?>" target="<?= esc_attr($social['link']['target'] ?: '_blank') ?>" rel="noopener" class="btn border border-white text-white flex justify-center items-center lg:justify-start lg:inline-flex gap-2">
                        <?= $social['icon'] ?> LinkedIn
                    </a>
                <?php } ?>
                </div>
              <?php } ?>
                </div>
              </div>
        </div>
    </footer>

</div><!-- #page -->

<!-- Voor elke popup uit post type popup  -->
<?php
/**
 * Helper functie: Haal labels op uit ACF veld
 */
function get_popup_labels($popup_id) {
    $labels = get_field('labels', $popup_id);
    $labels_array = array();
    
    if ($labels) {
        foreach ($labels as $label) {
            $labels_array[] = $label['label'];
        }
    }
    
    return $labels_array;
}

/**
 * Helper functie: Haal USPs op uit ACF veld
 */
function get_popup_usps($popup_id) {
    $usps = get_field('usps', $popup_id);
    $usps_array = array();
    
    if ($usps) {
        foreach ($usps as $usp) {
            $usps_array[] = $usp['usp'];
        }
    }
    
    return $usps_array;
}

/**
 * Helper functie: Haal formulier ID op (ondersteunt meerdere formats)
 */
function get_popup_formulier_id($formulier) {
    if (!$formulier) {
        return null;
    }
    
    // Check verschillende formaten
    if (is_array($formulier) && isset($formulier['id'])) {
        return $formulier['id'];
    } elseif (is_object($formulier) && isset($formulier->ID)) {
        return $formulier->ID;
    } elseif (is_numeric($formulier)) {
        return $formulier;
    }
    
    return null;
}

/**
 * Helper functie: Formatteer popup afbeelding data
 */
function get_popup_image_data($popup_afbeelding) {
    if (!$popup_afbeelding) {
        return null;
    }
    
    return array(
        'url' => $popup_afbeelding['url'],
        'alt' => $popup_afbeelding['alt']
    );
}

// Haal alle gepubliceerde popups op
$popups = get_posts(array(
    'post_type' => 'popup',
    'post_status' => 'publish',
    'numberposts' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
));

if (!empty($popups)) :
    // Maak een array met alle popup data voor JavaScript
    $popups_data = array();
    
    foreach ($popups as $popup) {
        // Haal ACF velden op
        $formulier = get_field('formulier', $popup->ID);
        $formulier_id = get_popup_formulier_id($formulier);
        
        // Voeg popup data toe aan array (zonder formulier HTML - wordt lazy loaded)
        $popups_data[] = array(
            'id' => $popup->ID,
            'title' => get_field('titel', $popup->ID) ?: get_the_title($popup->ID),
            'content' => get_field('content', $popup->ID),
            'formulier_id' => $formulier_id,
            'labels' => get_popup_labels($popup->ID),
            'usps' => get_popup_usps($popup->ID),
            'image' => get_popup_image_data(get_field('popup_afbeelding', $popup->ID))
        );
    }
    ?>

    <!-- Popup container -->
    <div id="popup-overlay" class="fixed inset-0 bg-black/50 z-50 hidden items-end lg:items-center justify-center pb-0 lg:pb-8 py-4 lg:p-8 z-[100]" aria-modal="true" role="dialog" aria-labelledby="popup-title">
        <div class="relative max-w-[1628px] w-full h-[100vh] lg:max-h-[90vh] shadow-2xl flex flex-col">
            <div id="popup-container" class="flex-1 flex flex-col lg:flex-row right-0 lg:right-auto overflow-y-auto lg:overflow-hidden">
                <!-- Close button -->
                <button 
                    id="popup-close" 
                    class="self-end lg:absolute lg:top-4 lg:right-4 z-50 w-[60px] h-[60px] lg:w-10 lg:h-10 flex items-center justify-center text-[#00344c] hover:text-[#0a2031] transition-colors hover:cursor-pointer bg-light-blue lg:bg-transparent flex-shrink-0"
                    aria-label="Sluit popup"
                >
                <svg class="w-[36px] h-[36px] lg:w-[40px] lg:h-[40px]" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect x="26.5156" y="8.83882" width="1" height="25" transform="rotate(45 26.5156 8.83882)" fill="#00344C"/>
  <rect x="26.5156" y="26.5165" width="1" height="25" transform="rotate(135 26.5156 26.5165)" fill="#00344C"/>
</svg>
                </button>
                
                <!-- Popup content wordt hier dynamisch ingevuld -->
                <div id="popup-content" class="flex flex-col lg:flex-row w-full">
                    <!-- Content wordt via JavaScript ingevuld -->
                </div>
            </div>
            
            <!-- Mobile scroll gradient (absoluut gepositioneerd onderaan, BOVEN alle content) -->
            <div id="mobile-scroll-gradient" class="lg:hidden absolute bottom-0 left-0 right-0 w-full h-[80px] pointer-events-none transition-opacity duration-300 z-[60]" style="background: linear-gradient(to bottom, rgba(10, 32, 49, 0) 0%, rgba(10, 32, 49, 0.95) 100%); opacity: 0;"></div>
        </div>
    </div>

    <style>
    /* Popup fade in/out animaties */
    #popup-overlay {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    
    #popup-overlay.popup-open {
        opacity: 1;
    }
    
    #popup-overlay > div {
        transform: scale(0.95);
        opacity: 0;
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    
    #popup-overlay.popup-open > div {
        transform: scale(1);
        opacity: 1;
    }
    
    /* Mobiel: Gradient onderaan hele popup */
    #mobile-scroll-gradient {
        transition: opacity 0.3s ease, background 0.3s ease;
    }
    
    /* Lichte gradient wanneer rechter sectie zichtbaar is */
    #mobile-scroll-gradient.showing-right {
        background: linear-gradient(to bottom, rgba(229, 232, 235, 0) 0%, rgba(229, 232, 235, 0.95) 100%) !important;
    }
    
    #mobile-scroll-gradient.has-scroll {
        opacity: 1 !important;
    }
    
    #mobile-scroll-gradient.at-bottom {
        opacity: 0 !important;
    }
    
    /* Scroll indicators voor popup */
    .popup-scroll-section {
        position: relative;
    }
    
    /* Gradient overlay die onderaan blijft staan (alleen desktop) */
    .scroll-gradient-overlay {
        display: none;
    }
    
    @media (min-width: 1024px) {
        .scroll-gradient-overlay {
            display: block;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }
    }
    
    /* Gradient voor donkere linker sectie */
    .scroll-gradient-left {
        background: linear-gradient(to bottom, rgba(10, 32, 49, 0) 0%, rgba(10, 32, 49, 0.95) 100%);
    }
    
    /* Gradient voor lichte rechter sectie */
    .scroll-gradient-right {
        background: linear-gradient(to bottom, rgba(229, 232, 235, 0) 0%, rgba(229, 232, 235, 0.95) 100%);
    }
    
    /* Toon gradient wanneer er meer content is om te scrollen */
    .scroll-gradient-overlay.has-scroll {
        opacity: 1;
    }
    
    /* Verberg gradient wanneer gebruiker onderaan is */
    .scroll-gradient-overlay.at-bottom {
        opacity: 0;
    }
    
    /* Scroll shadow bovenaan (wanneer gescrolled - alleen desktop) */
    @media (min-width: 1024px) {
        .popup-scroll-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 20px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }
        
        .popup-left::before {
            background: linear-gradient(to bottom, rgba(10, 32, 49, 0.3) 0%, rgba(10, 32, 49, 0) 100%);
        }
        
        .popup-right::before {
            background: linear-gradient(to bottom, rgba(0, 52, 76, 0.1) 0%, rgba(0, 52, 76, 0) 100%);
        }
        
        .popup-scroll-section.is-scrolled::before {
            opacity: 1;
        }
    }
    
    /* Verberg scrollbars */
    .popup-scroll-section::-webkit-scrollbar {
        display: none;
    }
    
    /* Firefox scrollbar verbergen */
    .popup-scroll-section {
        scrollbar-width: none;
    }
    
    /* IE en Edge scrollbar verbergen */
    .popup-scroll-section {
        -ms-overflow-style: none;
    }
    
    /* Spinner animatie voor formulier laden */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>

    <script>
    // Maak popups data beschikbaar voor popup.js
    window.popupsData = <?php echo json_encode($popups_data); ?>;
    </script>

    <?php
endif;
?>

<?php wp_footer(); ?>

</body>
</html>
