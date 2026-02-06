<?php
/**
 * Advice 2025 Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

include_once 'inc/class.tailwindWalker.php';
include_once 'inc/class.tailwindWalkerSimple.php';

/**
 * Get theme color palette
 * Centrale functie die de kleuren array retourneert voor gebruik in editor en CSS variables
 * 
 * @return array Array met kleuren voor editor color palette
 */
function advice2025_get_color_palette() {
    return array(
        array(
            'name'  => __('Black', 'advice2025'),
            'slug'  => 'black',
            'color' => 'rgb(0 0 0)',
        ),
        array(
            'name'  => __('White', 'advice2025'),
            'slug'  => 'white',
            'color' => 'rgb(255 255 255)',
        ),
        array(
            'name'  => __('Primary', 'advice2025'),
            'slug'  => 'primary',
            'color' => 'rgb(235 238 240)',
        ),
        array(
            'name'  => __('Secondary', 'advice2025'),
            'slug'  => 'secondary',
            'color' => 'rgb(225 50 44)',
        ),
        array(
            'name'  => __('Tertiary', 'advice2025'),
            'slug'  => 'tertiary',
            'color' => 'rgb(66 171 65)',
        ),
        array(
            'name'  => __('Quaternary', 'advice2025'),
            'slug'  => 'quaternary',
            'color' => 'rgb(9 35 84)',
        ),
    );
}

/**
 * Theme setup
 */
function advice2025_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Expose theme colors to the block editor / ACFE color pickers
    add_theme_support('editor-color-palette', advice2025_get_color_palette());
    
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        // 'height'      => 100,
        // 'width'       => 400,
        // 'flex-height' => true,
        // 'flex-width'  => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'advice2025'),
        'footer-menu-1' => __('Footer Menu 1', 'advice2025'),
        'footer-menu-2' => __('Footer Menu 2', 'advice2025'),
        'footer-menu-3' => __('Footer Menu 3', 'advice2025'),
        'copyright-menu' => __('Copyright Menu', 'advice2025'),
    ));
}
add_action('after_setup_theme', 'advice2025_setup');

/**
 * Output theme colors as CSS root variables
 * Maakt CSS root variables aan op basis van de editor color palette
 */
function advice2025_output_color_variables() {
    // Haal de color palette op via de helper functie
    $color_palette = advice2025_get_color_palette();
    
    // Genereer CSS root variables
    echo '<style id="advice2025-color-variables">';
    echo ':root {';
    
    foreach ($color_palette as $color) {
        $slug = isset($color['slug']) ? $color['slug'] : '';
        $color_value = isset($color['color']) ? $color['color'] : '';
        
        if ($slug && $color_value) {
            // Converteer slug naar CSS variable naam (bijv. 'light-blue' -> '--color-light-blue')
            $css_var_name = '--color-' . esc_attr($slug);
            echo "\n\t" . $css_var_name . ': ' . esc_attr($color_value) . ';';
        }
    }
    
    echo "\n" . '}';
    echo '</style>';
}
add_action('wp_head', 'advice2025_output_color_variables', 1);

/**
 * Enqueue scripts and styles
 */
function advice2025_scripts() {
    $css_file = get_template_directory() . '/assets/css/style.css';

    wp_enqueue_style(
        'advice2025-fonts',
        'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap',
        array(),
        wp_get_theme()->get('Version')
    );
    
    // Check if compiled CSS exists, otherwise use fallbacks
    if (file_exists($css_file)) {
        // Try direct file first
        $css_url = get_template_directory_uri() . '/assets/css/style.css';
        $file_time = filemtime($css_file);
        
        wp_enqueue_style(
            'advice2025-tailwind',
            $css_url,
            array(),
            $file_time // Use file modification time for cache busting
        );
    } elseif (file_exists(get_template_directory() . '/serve-css.php')) {
        // Fallback: use PHP CSS server
        wp_enqueue_style(
            'advice2025-tailwind-php',
            get_template_directory_uri() . '/serve-css.php',
            array(),
            wp_get_theme()->get('Version')
        );
    } else {
        // Fallback to CDN if compiled CSS doesn't exist
        wp_enqueue_style(
            'advice2025-tailwind-fallback',
            'https://cdn.tailwindcss.com/4.1.0',
            array(),
            '4.1.0'
        );
        
        // Add inline styles for custom CSS when using CDN fallback
        $custom_css = "
            :root {
                --font-family-primary: 'Open Sans', 'Roboto', sans-serif;
                --color-primary: #2563eb;
                --color-primary-dark: #1d4ed8;
                --color-secondary: #64748b;
            }
            body { font-family: var(--font-family-primary); line-height: 1.6; }
            .prose { color: #374151; }
            .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 { color: #111827; font-weight: 700; line-height: 1.25; }
            .prose a { color: var(--color-primary); text-decoration: none; transition: color 0.2s ease; }
            .prose a:hover { color: var(--color-primary-dark); text-decoration: underline; }
        ";
        wp_add_inline_style('advice2025-tailwind-fallback', $custom_css);
    }
    
    // Theme stylesheet (for WordPress theme info)
   

   

    // SwiperJS for sliders
wp_enqueue_style(
        'swiperjs-css',
        'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.0.7/swiper-bundle.css',
        array(),
        wp_get_theme()->get('Version')
    );


    wp_enqueue_script(
        'swiperjs',
        'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.0.7/swiper-bundle.min.js',
        array(),
        null,
        false
    );

    // Popup management script
    wp_enqueue_script(
        'advice2025-popup',
        get_template_directory_uri() . '/assets/js/popup.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Localize script voor AJAX URL
    wp_localize_script('advice2025-popup', 'popupData', array(
        'ajaxUrl' => admin_url('admin-ajax.php')
    ));
    
    // Voeg FontAwesome 6 CDN toe
    wp_enqueue_script(
        'fontawesome-kit',
        'https://kit.fontawesome.com/841b11f5c7.js',
        array(),
        null,
        false
    );

    

 
    
    // GSAP + ScrollTrigger
    wp_enqueue_script(
        'gsap',
        'https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js',
        array(),
        null,
        false
    );

    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js',
        array('gsap'),
        null,
        false
    );

    // Theme JavaScript
    wp_enqueue_script(
        'advice2025-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Dropdown menu JavaScript
    wp_enqueue_script(
        'advice2025-dropdown',
        get_template_directory_uri() . '/assets/js/dropdown-menu.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Kennisbank search script wordt apart geladen in advice2025_kennisbank_search_scripts()

    wp_enqueue_script(
        'advice2025-observer',
        'https://unpkg.com/tailwindcss-intersect@2.x.x/dist/observer.min.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

     wp_enqueue_style(
        'advice2025-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style(
        'advice2025-fonts',
        get_template_directory_uri().'/assets/css/fonts.css',
        array(),
        wp_get_theme()->get('Version')
    );
   
}
// Laad theme-styles later (na plugins zoals Gravity Forms) zodat overrides winnen
add_action('wp_enqueue_scripts', 'advice2025_scripts', 20);

/**
 * Register widget areas
 */
function advice2025_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'advice2025'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'advice2025'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'advice2025_widgets_init');

/**
 * Custom template for ACF flexible content
 */
function advice2025_get_flexible_template() {
    return 'template-flexible.php';
}

/**
 * Add custom body classes
 */
function advice2025_body_classes($classes) {
    if (is_page_template('template-flexible.php')) {
        $classes[] = 'flexible-content-page';
    }
    if (is_page_template('template-gutenberg.php')) {
        $classes[] = 'gutenberg-content-page';
    }
    return $classes;
}
add_filter('body_class', 'advice2025_body_classes');

/**
 * Debug function to check CSS file status
 */
function advice2025_debug_css() {
    if (current_user_can('administrator') && isset($_GET['debug_css'])) {
        $css_file = get_template_directory() . '/assets/css/style.css';
        $css_url = get_template_directory_uri() . '/assets/css/style.css';
        
        echo '<div style="background: #f1f1f1; padding: 20px; margin: 20px; border: 1px solid #ccc;">';
        echo '<h3>CSS Debug Info:</h3>';
        echo '<p><strong>CSS File Path:</strong> ' . $css_file . '</p>';
        echo '<p><strong>CSS File URL:</strong> ' . $css_url . '</p>';
        echo '<p><strong>File Exists:</strong> ' . (file_exists($css_file) ? 'Yes' : 'No') . '</p>';
        
        if (file_exists($css_file)) {
            echo '<p><strong>File Size:</strong> ' . filesize($css_file) . ' bytes</p>';
            echo '<p><strong>Last Modified:</strong> ' . date('Y-m-d H:i:s', filemtime($css_file)) . '</p>';
            echo '<p><strong>Permissions:</strong> ' . substr(sprintf('%o', fileperms($css_file)), -4) . '</p>';
        }
        
        echo '<p><strong>Template Directory:</strong> ' . get_template_directory() . '</p>';
        echo '<p><strong>Template Directory URI:</strong> ' . get_template_directory_uri() . '</p>';
        echo '</div>';
    }
}
add_action('wp_head', 'advice2025_debug_css');

/**
 * Optioneel: Dequeue standaard Gravity Forms CSS zodat theme-styling leidend is
 * Comment de functie aan/uit afhankelijk van de behoefte.
 */
add_action('wp_enqueue_scripts', function () {
    // Standaard GF style handles
    $gf_handles = array(
        'gforms_reset_css',
        'gforms_formsmain_css',
        'gforms_ready_class_css',
        'gforms_browsers_css',
    );

    foreach ($gf_handles as $handle) {
        if (wp_style_is($handle, 'enqueued')) {
            wp_dequeue_style($handle);
        }
    }
}, 100);

/**
 * Forceer uitschakelen van alle standaard Gravity Forms CSS (betrouwbaarste methode)
 * - 'gform_disable_css' schakelt klassieke GF-CSS uit
 * - 'gform_disable_form_theme_css' schakelt het nieuwe Theme Framework (2.5+) uit
 */
add_filter('gform_disable_css', '__return_true');
add_filter('gform_disable_form_theme_css', '__return_true');

/**
 * Laatste vangnet: heel laat in de cyclus alsnog dequeue, mocht een plugin het opnieuw enqueuen
 */
add_action('wp_print_styles', function () {
    $gf_handles = array('gforms_reset_css','gforms_formsmain_css','gforms_ready_class_css','gforms_browsers_css');
    foreach ($gf_handles as $handle) {
        if (wp_style_is($handle, 'enqueued')) {
            wp_dequeue_style($handle);
        }
    }
}, 999);

/**
 * Gravity Forms: forceer AJAX-verzenden voor alle formulieren
 * Werkt voor shortcodes, blokken en aanroepen van gravity_form().
 */
add_filter('gform_form_args', function ($args) {
    $args['ajax'] = true;
    return $args;
}, 10, 1);

// Zorg dat [gravityform] shortcode ook standaard ajax="true" krijgt
add_filter('shortcode_atts_gravityform', function ($out, $pairs = array(), $atts = array()) {
    $out['ajax'] = 'true';
    return $out;
}, 10, 3);

/**
 * Get block spacing classes
 * 
 * @param string $spacing_top Top spacing value
 * @param string $spacing_bottom Bottom spacing value
 * @return string CSS classes for spacing
 */
function get_block_spacing_classes($spacing_top = 'large', $spacing_bottom = 'large') {
    $spacing_map = array(
        'none' => '',
        'small' => 'py-8',
        'medium' => 'py-12', 
        'large' => 'py-16',
        'xlarge' => 'py-20',
        'xxlarge' => 'py-24'
    );
    
    // Als beide waarden hetzelfde zijn, gebruik py-* class
    if ($spacing_top === $spacing_bottom && isset($spacing_map[$spacing_top])) {
        return $spacing_map[$spacing_top];
    }
    
    // Anders gebruik aparte pt-* en pb-* classes
    $top_map = array(
        'none' => '',
        'small' => 'pt-8',
        'medium' => 'pt-12',
        'large' => 'pt-16', 
        'xlarge' => 'pt-20',
        'xxlarge' => 'pt-24'
    );
    
    $bottom_map = array(
        'none' => '',
        'small' => 'pb-8',
        'medium' => 'pb-12',
        'large' => 'pb-16',
        'xlarge' => 'pb-20', 
        'xxlarge' => 'pb-24'
    );
    
    $classes = array();
    
    if (isset($top_map[$spacing_top]) && $top_map[$spacing_top] !== '') {
        $classes[] = $top_map[$spacing_top];
    }
    
    if (isset($bottom_map[$spacing_bottom]) && $bottom_map[$spacing_bottom] !== '') {
        $classes[] = $bottom_map[$spacing_bottom];
    }
    
    return implode(' ', $classes);
}

/**
 * Get block spacing classes from ACF fields
 * 
 * @return string CSS classes for spacing
 */
function get_acf_block_spacing_classes() {
    $spacing_top = get_sub_field('spacing_top') ?: 'large';
    $spacing_bottom = get_sub_field('spacing_bottom') ?: 'large';
    
    return get_block_spacing_classes($spacing_top, $spacing_bottom);
}

/**
 * Set default template for new pages
 */
function advice2025_set_default_page_template( $post_id, $post, $update ) {
    // Only run for pages
    if ( $post->post_type !== 'page' ) {
        return;
    }

    // Only set when creating a new page (not updating)
    if ( $update ) {
        return;
    }

    // Avoid infinite loops
    remove_action( 'save_post', 'advice2025_set_default_page_template', 10 );

    // Set the default template if not already set
    $current_template = get_post_meta( $post_id, '_wp_page_template', true );
    if ( empty( $current_template ) || $current_template === 'default' ) {
        update_post_meta( $post_id, '_wp_page_template', 'template-flexible.php' );
    }

    // Re-add the hook
    add_action( 'save_post', 'advice2025_set_default_page_template', 10, 3 );
}
add_action( 'save_post', 'advice2025_set_default_page_template', 10, 3 );

/**
 * ACF(e) Color Picker: load theme colors (CSS variables) as palettes
 *
 * Loads the six main brand colors (black, white, red, gray, green, blue)
 * from :root CSS variables and exposes them to all ACF/ACFE color pickers
 * as the palette. This runs in the ACF input admin footer so it only loads
 * where fields are edited.
 */
add_action('acf/input/admin_footer', function () {
    ?>
    <script>
    (function(){
        try{
            // CSS variable names that exist in your CSS (:root in src/input.css)
            var varNames = ['--color-black','--color-white','--color-red','--color-gray','--color-green','--color-blue'];
            var rootStyles = getComputedStyle(document.documentElement);

            // Resolve CSS variables to computed color values (e.g. rgb(...))
            var palette = varNames.map(function(name){
                var v = rootStyles.getPropertyValue(name).trim();
                // Fallback to the raw variable name if not found (prevents empty palette entries)
                return v && v.length ? v : name;
            });

            // Convert rgb(x, y, z) to hex for better wpColorPicker compatibility
            function rgbToHex(rgb){
                // rgb or rgba -> hex
                var m = rgb.match(/rgba?\(([^)]+)\)/);
                if(!m){
                    // Already a hex or a named color
                    return rgb;
                }
                var parts = m[1].split(',').map(function(p){return parseFloat(p.trim());});
                var r = parts[0]|0, g = parts[1]|0, b = parts[2]|0;
                function toHex(n){ return ('0' + n.toString(16)).slice(-2); }
                return '#' + toHex(r) + toHex(g) + toHex(b);
            }

            var hexPalette = palette.map(function(c){ return rgbToHex(c); });

            // Apply to all ACF color pickers
            if (window.acf && typeof acf.add_filter === 'function') {
                acf.add_filter('color_picker_args', function(args, $field){
                    args.palettes = hexPalette;
                    return args;
                });
            }

            // ACFE (if any extra hooking is needed later)
            // Many ACFE color pickers rely on the same ACF filter above, so this is sufficient.
            // If ACFE exposes a specific JS filter, you could also set it here.
        }catch(e){
        }
    })();
    </script>
    <?php
});

/**
 * Gravity Forms: stijl de submit-knop met Tailwind (rood)
 */
function advice2025_gf_submit_button($button, $form) {
    // Tailwind classes voor een rode knop die past bij het thema
    $tw_classes = 'btn bg-blue text-white hover:bg-light-blue hover:text-blue';

    // Als er al een class attribute is, voeg onze classes toe
    if (strpos($button, 'class=') !== false) {
        $button = preg_replace('/class=("|\')([^"\']*)(\1)/', 'class=$1$2 ' . $tw_classes . '$1', $button, 1);
    } else {
        // Anders voegen we een class attribute toe aan het input element
        $button = preg_replace('/<input\s+/', '<input class="' . $tw_classes . '" ', $button, 1);
    }

    return $button;
}
add_filter('gform_submit_button', 'advice2025_gf_submit_button', 10, 2);

/**
 * Forceer Gravity Forms knop-stijl (override GF CSS) met hogere specificiteit
 */
// add_action('wp_enqueue_scripts', function () {
//     $css = 
//         ".gform_wrapper .gform_footer input[type=submit],\n" .
//         ".gform_wrapper .gform_page_footer input[type=button].gform_next_button,\n" .
//         ".gform_wrapper .gform_page_footer input[type=submit].gform_previous_button,\n" .
//         ".gform_wrapper .gform_footer .gform_button,\n" .
//         ".gform_wrapper .gform_footer .button {\n" .
//         "  background-color: #E1322C !important;\n" .
//         "  color: #ffffff !important;\n" .
//         "  padding: 0.75rem 1.5rem !important;\n" .
//         "  border-radius: 0.375rem !important;\n" .
//         "  font-weight: 600 !important;\n" .
//         "  line-height: 1.25 !important;\n" .
//         "  display: inline-flex !important;\n" .
//         "  align-items: center !important;\n" .
//         "  justify-content: center !important;\n" .
//         "  transition: background-color 150ms ease-in-out !important;\n" .
//         "}\n" .
//         ".gform_wrapper .gform_footer input[type=submit]:hover,\n" .
//         ".gform_wrapper .gform_page_footer input[type=button].gform_next_button:hover,\n" .
//         ".gform_wrapper .gform_page_footer input[type=submit].gform_previous_button:hover,\n" .
//         ".gform_wrapper .gform_footer .gform_button:hover,\n" .
//         ".gform_wrapper .gform_footer .button:hover {\n" .
//         "  background-color: #c12a25 !important;\n" .
//         "}\n" .
//         ".gform_wrapper .gform_footer input[type=submit]:focus,\n" .
//         ".gform_wrapper .gform_page_footer input[type=button].gform_next_button:focus,\n" .
//         ".gform_wrapper .gform_page_footer input[type=submit].gform_previous_button:focus,\n" .
//         ".gform_wrapper .gform_footer .gform_button:focus,\n" .
//         ".gform_wrapper .gform_footer .button:focus {\n" .
//         "  outline: 2px solid #E1322C !important;\n" .
//         "  outline-offset: 2px !important;\n" .
//         "}\n";

//     // Hang het inline CSS aan het hoofd Tailwind bestand als het bestaat; anders aan het theme stylesheet
//     if (wp_style_is('advice2025-tailwind', 'enqueued')) {
//         wp_add_inline_style('advice2025-tailwind', $css);
//     } elseif (wp_style_is('advice2025-style', 'enqueued')) {
//         wp_add_inline_style('advice2025-style', $css);
//     } else {
//         // Fallback naar een bestaand handle
//         wp_add_inline_style('advice2025-tailwind-fallback', $css);
//     }
// }, 20);

/**
 * Yoast SEO: Aangepaste broodkruimel voor vacature post type
 * Voegt "Werken bij" toe tussen Home en de vacature titel
 * Vervangt "case" door "portfolio" in breadcrumbs
 */
function advice2025_yoast_breadcrumb_links($crumbs) {
    // Alleen voor single vacature posts
    if (is_singular('vacature')) {
        // Zoek de index van de huidige post in de broodkruimel
        $current_post_index = -1;
        foreach ($crumbs as $index => $crumb) {
            if (isset($crumb['id']) && $crumb['id'] == get_the_ID()) {
                $current_post_index = $index;
                break;
            }
        }
        
        // Als we de huidige post vinden, voeg "Werken bij" toe ervoor
        if ($current_post_index > 0) {
            // Zoek de "werken-bij" pagina
            $werken_bij_page = get_page_by_path('werken-bij');
            if ($werken_bij_page) {
                $werken_bij_crumb = array(
                    'url' => get_permalink($werken_bij_page->ID),
                    'text' => 'Werken bij',
                    'id' => $werken_bij_page->ID
                );
                
                // Voeg "Werken bij" toe voor de huidige post
                array_splice($crumbs, $current_post_index, 0, array($werken_bij_crumb));
            }
        }
    }
    
    // Vervang "case" door "Portfolio" in breadcrumbs voor case post type
    if (is_singular('case') || is_post_type_archive('case')) {
        foreach ($crumbs as $index => $crumb) {
            if (isset($crumb['text'])) {
                // Vervang "case" en "cases" (case-insensitive) door "Portfolio"
                $text = $crumb['text'];
                // Vervang eerst meervoud, dan enkelvoud om "Portfolios" te voorkomen
                $text = preg_replace('/\bcases\b/i', 'Portfolio', $text);
                $text = preg_replace('/\bcase\b/i', 'Portfolio', $text);
                $crumb['text'] = $text;
                $crumbs[$index] = $crumb;
            }
        }
    }
    
    return $crumbs;
}
add_filter('wpseo_breadcrumb_links', 'advice2025_yoast_breadcrumb_links');

/**
 * Kennisbank Search AJAX Handlers
 */

// Enqueue kennisbank search script
function advice2025_kennisbank_search_scripts() {
    // Check if we're on a kennisbank page - simplified detection
    $current_url = $_SERVER['REQUEST_URI'] ?? '';
    $is_kennisbank_page = strpos($current_url, 'kennisbank') !== false || 
                         is_page_template('archive-kennisbank.php') ||
                         (is_search() && isset($_GET['kennisbank_search'])) ||
                         (is_archive() && get_post_type() === 'kennisbank') ||
                         (is_singular('kennisbank'));
    
    // Debug: Log the page detection (only when debugging)
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Kennisbank page detection: ' . ($is_kennisbank_page ? 'true' : 'false'));
        error_log('Current page template: ' . get_page_template_slug());
        error_log('Current URL: ' . $current_url);
    }
    
    // Always load the script and AJAX variables
    wp_enqueue_script(
        'advice2025-kennisbank-search',
        get_template_directory_uri() . '/assets/js/kennisbank-search.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Always add AJAX variables - the JavaScript will check if it's on the right page
    wp_localize_script('advice2025-kennisbank-search', 'kennisbankSearch', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('kennisbank_search_nonce'),
        'isKennisbankPage' => $is_kennisbank_page
    ));
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('AJAX variables added to script (isKennisbankPage: ' . ($is_kennisbank_page ? 'true' : 'false') . ')');
    }
}
add_action('wp_enqueue_scripts', 'advice2025_kennisbank_search_scripts');

// AJAX handler for kennisbank search
function advice2025_kennisbank_search_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'kennisbank_search_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $query = sanitize_text_field($_POST['query']);
    
    if (empty($query)) {
        wp_send_json_error('Empty query');
        return;
    }
    
    // Log for debugging
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Kennisbank search query: ' . $query);
    }
    
    // Search arguments - zoek in alle kennisbank posts
    $search_args = array(
        'post_type' => 'kennisbank',
        'post_status' => 'publish',
        's' => $query,
        'posts_per_page' => -1
    );
    
    $posts = get_posts($search_args);
    $formatted_posts = array();
    
    // Log for debugging
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Found ' . count($posts) . ' posts for query: ' . $query);
    }
    
    foreach ($posts as $post) {
        // Get categories
        $categories = get_the_category($post->ID);
        
        // Get thumbnail
        $thumbnail = '';
        if (has_post_thumbnail($post->ID)) {
            $thumbnail = get_the_post_thumbnail_url($post->ID, 'large');
        }
        
        // Get download URL
        $attachments = get_attached_media('', $post->ID);
        $download_url = '';
        $has_attachment = false;
        
        if (!empty($attachments)) {
            $first_attachment = reset($attachments);
            $download_url = wp_get_attachment_url($first_attachment->ID);
            $has_attachment = true;
        } else {
            $download_url = get_permalink($post->ID);
        }
        
        $formatted_posts[] = array(
            'id' => $post->ID,
            'title' => get_the_title($post->ID),
            'excerpt' => get_the_excerpt($post->ID),
            'permalink' => get_permalink($post->ID),
            'thumbnail' => $thumbnail,
            'download_url' => $download_url,
            'has_attachment' => $has_attachment,
            'categories' => $categories
        );
    }
    
    wp_send_json_success($formatted_posts);
}
add_action('wp_ajax_kennisbank_search', 'advice2025_kennisbank_search_ajax');
add_action('wp_ajax_nopriv_kennisbank_search', 'advice2025_kennisbank_search_ajax');

// AJAX handler for loading all kennisbank posts
function advice2025_kennisbank_load_all_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'kennisbank_search_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    // Log for debugging
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Loading all kennisbank posts');
    }
    
    // Get all kennisbank posts
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'kennisbank',
        'post_status' => 'publish'
    ));
    
    $formatted_posts = array();
    
    // Log for debugging
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Found ' . count($posts) . ' total posts');
    }
    
    foreach ($posts as $post) {
        // Get categories
        $categories = get_the_category($post->ID);
        
        // Get thumbnail
        $thumbnail = '';
        if (has_post_thumbnail($post->ID)) {
            $thumbnail = get_the_post_thumbnail_url($post->ID, 'large');
        }
        
        // Get download URL
        $attachments = get_attached_media('', $post->ID);
        $download_url = '';
        $has_attachment = false;
        
        if (!empty($attachments)) {
            $first_attachment = reset($attachments);
            $download_url = wp_get_attachment_url($first_attachment->ID);
            $has_attachment = true;
        } else {
            $download_url = get_permalink($post->ID);
        }
        
        $formatted_posts[] = array(
            'id' => $post->ID,
            'title' => get_the_title($post->ID),
            'excerpt' => get_the_excerpt($post->ID),
            'permalink' => get_permalink($post->ID),
            'thumbnail' => $thumbnail,
            'download_url' => $download_url,
            'has_attachment' => $has_attachment,
            'categories' => $categories
        );
    }
    
    wp_send_json_success($formatted_posts);
}
add_action('wp_ajax_kennisbank_load_all', 'advice2025_kennisbank_load_all_ajax');
add_action('wp_ajax_nopriv_kennisbank_load_all', 'advice2025_kennisbank_load_all_ajax');

/**
 * Get spacing bottom class for flexible content blocks
 * 
 * @param string $layout_name Optional layout name (e.g. 'hero_banner')
 * @return string CSS class for bottom spacing
 */
function get_spacing_bottom_class($layout_name = '') {
    $spacing_bottom = 'large'; // Default value
    
    // Try to get spacing_bottom from ACF sub field (for flexible content)
    if (function_exists('get_sub_field')) {
        $spacing_bottom = get_sub_field('spacing_bottom');
    }
    
    // If no value found, use default
    if (empty($spacing_bottom)) {
        $spacing_bottom = 'large';
    }
    
    // Map spacing values to CSS classes
    $bottom_map = array(
        'none' => '',
        'small' => 'pb-8',
        'medium' => 'pb-12',
        'large' => 'pb-16',
        'xlarge' => 'pb-20',
        'xxlarge' => 'pb-24'
    );
    
    // Return the appropriate class
    if (isset($bottom_map[$spacing_bottom])) {
        return $bottom_map[$spacing_bottom];
    }
    
    // Fallback to large if value not recognized
    return $bottom_map['large'];
}

/**
 * Markeer archive menu item als actief op single case posts
 */
function advice2025_mark_case_archive_menu_active($classes, $item, $args = null) {
    // Alleen toepassen op single case posts
    if (!is_singular('case')) {
        return $classes;
    }
    
    // Haal de archive URL op voor het case post type
    $case_archive_url = get_post_type_archive_link('case');
    
    // Als er geen archive URL is, stop hier
    if (!$case_archive_url) {
        return $classes;
    }
    
    // Normaliseer URLs (verwijder trailing slashes voor vergelijking)
    $item_url = untrailingslashit($item->url);
    $archive_url = untrailingslashit($case_archive_url);
    
    // Check of dit menu item naar de case archive linkt
    if ($item_url === $archive_url) {
        // Voeg current-menu-item class toe als die nog niet bestaat
        if (!in_array('current-menu-item', $classes)) {
            $classes[] = 'current-menu-item';
        }
        // Voeg ook current_page_parent toe voor consistentie
        if (!in_array('current_page_parent', $classes)) {
            $classes[] = 'current_page_parent';
        }
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'advice2025_mark_case_archive_menu_active', 10, 3);

/**
 * Voeg vacature counter toe aan menu items met class 'vacCounter'
 */
function advice2025_add_vacature_counter_to_menu($items, $args) {
    // Toepassen op primary menu en footer menu's
    $allowed_locations = array('primary', 'footer-menu-1', 'footer-menu-2', 'footer-menu-3');
    
    if (!in_array($args->theme_location, $allowed_locations)) {
        return $items;
    }
    
    // Zoek naar menu items met de class 'vacCounter'
    $dom = new DOMDocument();
    // Voorkom warnings bij SVG-tags in het menu HTML
    $prevLibxmlState = libxml_use_internal_errors(true);
    $loaded = $dom->loadHTML('<?xml encoding="UTF-8">' . $items, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    if ($loaded === false) {
        libxml_clear_errors();
        libxml_use_internal_errors($prevLibxmlState);
        return $items;
    }
    $xpath = new DOMXPath($dom);
    
    // Zoek naar links met de class 'vacCounter'
    $vacature_links = $xpath->query('//a[contains(@class, "vacCounter")]');
    
    if ($vacature_links->length > 0) {
        // Tel het aantal gepubliceerde vacatures
        $vacature_count = wp_count_posts('vacature');
        $published_count = isset($vacature_count->publish) ? $vacature_count->publish : 0;
        
        foreach ($vacature_links as $link) {
            // Voeg het after element toe met het aantal vacatures
            $counter_span = $dom->createElement('span');
            $counter_span->setAttribute('class', 'vacature-counter');
            $counter_span->setAttribute('data-count', $published_count);
            $counter_span->textContent = $published_count;
            
            // Voeg de counter toe na de link tekst
            $link->appendChild($counter_span);
        }
    }
    
    $result = $dom->saveHTML();
    libxml_clear_errors();
    libxml_use_internal_errors($prevLibxmlState);
    return $result;
}
add_filter('wp_nav_menu_items', 'advice2025_add_vacature_counter_to_menu', 10, 2);

/**
 * Helper functie om vacature counter toe te voegen aan handmatig gegenereerde menu items
 */
function advice2025_add_vacature_counter_to_manual_menu($item_html, $item_classes) {
    // Controleer of het item de vacCounter class heeft
    if (strpos($item_classes, 'vacCounter') !== false) {
        // Tel het aantal gepubliceerde vacatures
        $vacature_count = wp_count_posts('vacature');
        $published_count = isset($vacature_count->publish) ? $vacature_count->publish : 0;
        
        // Voeg de counter toe aan de link
        $counter_html = '<span class="vacature-counter" data-count="' . $published_count . '">' . $published_count . '</span>';
        
        // Vervang de link content
        $item_html = str_replace('</a>', $counter_html . '</a>', $item_html);
    }
    
    return $item_html;
}

/**
 * Custom pagination function volgens Figma design
 * 
 * @param array $args Optional arguments for pagination
 * @return string|void HTML output for pagination
 */
function advice2025_pagination($args = array()) {
    global $wp_query, $wp_rewrite;
    
    // Default arguments
    $defaults = array(
        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => 'Vorige projecten',
        'next_text' => 'Oudere projecten',
        'type' => 'list',
        'end_size' => 1,
        'mid_size' => 1,
        'show_all' => false,
    );
    
    $args = wp_parse_args($args, $defaults);
    
    // Don't show pagination if there's only one page
    if ($args['total'] <= 1) {
        return;
    }
    
    // Get pagination links
    $pagination_links = paginate_links($args);
    
    if (!$pagination_links) {
        return;
    }
    
    // Parse the pagination links to extract prev/next URLs
    $prev_url = '';
    $next_url = '';
    $current_page = $args['current'];
    
    // Get previous page URL
    if ($current_page > 1) {
        if ($wp_rewrite->using_permalinks()) {
            $prev_url = get_pagenum_link($current_page - 1);
        } else {
            $prev_url = add_query_arg('paged', $current_page - 1);
        }
    }
    
    // Get next page URL
    if ($current_page < $args['total']) {
        if ($wp_rewrite->using_permalinks()) {
            $next_url = get_pagenum_link($current_page + 1);
        } else {
            $next_url = add_query_arg('paged', $current_page + 1);
        }
    }
    
    // Start output
    ob_start();
    ?>
    <div class="custom-pagination flex flex-col sm:flex-row gap-1 mb-[40px] sm:mb-0! items-stretch w-full">
        <!-- Vorige projecten (links) -->
        <div class="flex items-center w-full lg:w-auto prev">
            <?php if ($prev_url) : ?>
                    <a href="<?php echo esc_url($prev_url); ?>" class="flex items-center justify-center btn bg-pink text-white w-full lg:w-auto">
                        <svg width="25" height="25" viewBox="0 0 25 25" class="rotate-180" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
                            <g class="arrow-group">
                                <rect x="7" y="11.7427" width="10" height="1" fill="currentColor"/>
                                <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                                <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                            </g>
                        </svg>
                        <?php echo esc_html($args['prev_text']); ?>
                    </a>
                <?php else : ?>
                    <span class="flex items-center justify-center btn bg-pink text-white w-full lg:w-auto opacity-20 cursor-not-allowed">
                    <svg width="25" height="25" viewBox="0 0 25 25" class="rotate-180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
                        <g class="arrow-group">
                            <rect x="7" y="11.7427" width="10" height="1" fill="currentColor"/>
                            <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                            <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                        </g>
                    </svg>
                    <?php echo esc_html($args['prev_text']); ?>
                </span>
                <?php endif; ?>
        </div>
        
        <div class="flex-1"></div>
        
        <div class="flex items-center w-full lg:w-auto">
            <?php if ($next_url) : ?>
                <a href="<?php echo esc_url($next_url); ?>" class="flex items-center justify-center btn bg-pink text-white w-full lg:w-auto">
                    <?php echo esc_html($args['next_text']); ?>
                    
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
                        <g class="arrow-group">
                            <rect x="7" y="11.7427" width="10" height="1" fill="currentColor"/>
                            <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                            <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                        </g>
                    </svg>
                </a>
            <?php else : ?>
                <span class="flex items-center justify-center btn bg-pink text-white w-full lg:w-auto opacity-20 cursor-not-allowed">
                <?php echo esc_html($args['prev_text']); ?>    
                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
                        <g class="arrow-group">
                            <rect x="7" y="11.7427" width="10" height="1" fill="currentColor"/>
                            <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                            <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
                        </g>
                    </svg>
                    
                </span>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * AJAX Handler: Laad popup formulier
 * Voor lazy loading van Gravity Forms in popups
 */
function ajax_load_popup_form() {
    // Security check
    if (!isset($_POST['form_id'])) {
        wp_send_json_error(array('message' => 'Geen formulier ID opgegeven'));
        return;
    }
    
    $form_id = intval($_POST['form_id']);
    
    if ($form_id <= 0) {
        wp_send_json_error(array('message' => 'Ongeldig formulier ID'));
        return;
    }
    
    // Genereer Gravity Forms HTML
    ob_start();
    echo do_shortcode('[gravityform id="' . $form_id . '" ajax="true" title="false" description="false"]');
    $form_html = ob_get_clean();
    
    if (empty($form_html)) {
        wp_send_json_error(array('message' => 'Formulier niet gevonden'));
        return;
    }
    
    wp_send_json_success(array(
        'html' => $form_html,
        'form_id' => $form_id
    ));
}

// Registreer AJAX handlers (voor ingelogde en niet-ingelogde gebruikers)
add_action('wp_ajax_load_popup_form', 'ajax_load_popup_form');
add_action('wp_ajax_nopriv_load_popup_form', 'ajax_load_popup_form');
