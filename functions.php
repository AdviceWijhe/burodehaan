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
require_once get_template_directory() . '/inc/import_vacatures.php';

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
            'color' => '#161616',
        ),
        array(
            'name'  => __('White', 'advice2025'),
            'slug'  => 'white',
            'color' => '#FFFFFF',
        ),
        array(
            'name'  => __('Primary', 'advice2025'),
            'slug'  => 'primary',
            'color' => '#EC663C',
        ),
        array(
            'name'  => __('Secondary', 'advice2025'),
            'slug'  => 'secondary',
            'color' => '#F7F5F0',
        ),

        
    );
}

/**
 * Forceer knop_kleur veld naar thema-kleuren.
 */
add_filter('acf/load_field/name=knop_kleur', function ($field) {
    $field['choices'] = array(
        'primary' => __('Primary', 'advice2025'),
        'secondary' => __('Secondary', 'advice2025'),
        'white' => __('White', 'advice2025'),
        'black' => __('Black', 'advice2025'),
    );
    return $field;
});

/**
 * Convert a px (or unitless numeric) length to rem.
 *
 * @param string $value Raw attribute value.
 * @return string Converted value or original input.
 */
function advice2025_convert_length_to_rem($value) {
    if (!is_string($value)) {
        return $value;
    }

    if (!preg_match('/^\s*(-?\d*\.?\d+)\s*(px)?\s*$/i', $value, $matches)) {
        return $value;
    }

    $rem = ((float) $matches[1]) / 16;
    $formatted = rtrim(rtrim(sprintf('%.6F', $rem), '0'), '.');

    if ($formatted === '-0') {
        $formatted = '0';
    }

    return $formatted . 'rem';
}

/**
 * Convert inline style width/height declarations from px to rem.
 *
 * @param string $style Inline style value.
 * @return string
 */
function advice2025_convert_svg_style_px_to_rem($style) {
    if (!is_string($style) || $style === '') {
        return $style;
    }

    return (string) preg_replace_callback(
        '/\b(width|height)\s*:\s*(-?\d*\.?\d+)px\b/i',
        static function ($matches) {
            $rem = ((float) $matches[2]) / 16;
            $formatted = rtrim(rtrim(sprintf('%.6F', $rem), '0'), '.');

            if ($formatted === '-0') {
                $formatted = '0';
            }

            return $matches[1] . ': ' . $formatted . 'rem';
        },
        $style
    );
}

/**
 * Normalize backend-inserted SVG dimensions (width/height) from px to rem.
 *
 * @param string $html HTML fragment that may contain SVG markup.
 * @return string
 */
function advice2025_convert_svg_px_dimensions_to_rem($html) {
    if (!is_string($html) || stripos($html, '<svg') === false) {
        return $html;
    }

    if (!class_exists('DOMDocument')) {
        return $html;
    }

    $internal_errors = libxml_use_internal_errors(true);
    $document = new DOMDocument('1.0', 'UTF-8');
    $wrapper_id = 'advice2025-svg-wrapper';
    $wrapped_html = '<?xml encoding="UTF-8"><div id="' . $wrapper_id . '">' . $html . '</div>';

    $loaded = $document->loadHTML($wrapped_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    if (!$loaded) {
        libxml_clear_errors();
        libxml_use_internal_errors($internal_errors);
        return $html;
    }

    $svg_nodes = $document->getElementsByTagName('svg');
    foreach ($svg_nodes as $svg_node) {
        if ($svg_node->hasAttribute('width')) {
            $svg_node->setAttribute('width', advice2025_convert_length_to_rem($svg_node->getAttribute('width')));
        }
        if ($svg_node->hasAttribute('height')) {
            $svg_node->setAttribute('height', advice2025_convert_length_to_rem($svg_node->getAttribute('height')));
        }
        if ($svg_node->hasAttribute('style')) {
            $svg_node->setAttribute('style', advice2025_convert_svg_style_px_to_rem($svg_node->getAttribute('style')));
        }
    }

    $wrapper = $document->getElementById($wrapper_id);
    if (!$wrapper) {
        libxml_clear_errors();
        libxml_use_internal_errors($internal_errors);
        return $html;
    }

    $result = '';
    foreach ($wrapper->childNodes as $child_node) {
        $result .= $document->saveHTML($child_node);
    }

    libxml_clear_errors();
    libxml_use_internal_errors($internal_errors);

    return $result;
}

/**
 * Apply SVG dimension normalization to ACF rich text outputs.
 */
function advice2025_normalize_svg_dimensions_in_acf_content($value, $post_id, $field) {
    if (is_admin() && !wp_doing_ajax()) {
        return $value;
    }

    return advice2025_convert_svg_px_dimensions_to_rem($value);
}
add_filter('acf/format_value/type=wysiwyg', 'advice2025_normalize_svg_dimensions_in_acf_content', 20, 3);
add_filter('acf/format_value/type=textarea', 'advice2025_normalize_svg_dimensions_in_acf_content', 20, 3);

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
        'topbar-menu' => __('Topbar Menu', 'advice2025'),
        'footer-menu-1' => __('Footer Menu 1', 'advice2025'),
        'footer-menu-2' => __('Footer Menu 2', 'advice2025'),
        'footer-menu-3' => __('Footer Menu 3', 'advice2025'),
        'copyright-menu' => __('Copyright Menu', 'advice2025'),
    ));
}
add_action('after_setup_theme', 'advice2025_setup');

/**
 * Color variables are now managed in src/input.css
 * They are automatically synced from this color palette using: npm run sync-colors
 * This happens automatically before each build (prebuild hook in package.json)
 */

/**
 * Enqueue scripts and styles
 */
function advice2025_scripts() {
    $css_file = get_template_directory() . '/assets/css/style.css';

    wp_enqueue_style(
        'advice2025-fonts',
        'https://fonts.googleapis.com/css2?family=Fira+Sans:wght@300;400;600&display=swap',
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
        // // Fallback to CDN if compiled CSS doesn't exist
        // wp_enqueue_style(
        //     'advice2025-tailwind-fallback',
        //     'https://cdn.tailwindcss.com/4.1.0',
        //     array(),
        //     '4.1.0'
        // );
        
        // // Add inline styles for custom CSS when using CDN fallback
        // $custom_css = "
        //     :root {
        //         --font-family-primary: 'Open Sans', 'Roboto', sans-serif;
        //         --color-primary: #2563eb;
        //         --color-primary-dark: #1d4ed8;
        //         --color-secondary: #64748b;
        //     }
        //     body { font-family: var(--font-family-primary); line-height: 1.6; }
        //     .prose { color: #374151; }
        //     .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 { color: #111827; font-weight: 700; line-height: 1.25; }
        //     .prose a { color: var(--color-primary); text-decoration: none; transition: color 0.2s ease; }
        //     .prose a:hover { color: var(--color-primary-dark); text-decoration: underline; }
        // ";
        // wp_add_inline_style('advice2025-tailwind-fallback', $custom_css);
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

    wp_enqueue_script(
        'gsap-draggable',
        'https://cdn.jsdelivr.net/npm/gsap@3/dist/Draggable.min.js',
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
    wp_localize_script('advice2025-script', 'advice2025ArchiveLoadMore', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('archive_load_more_nonce')
    ));

    // Dropdown menu JavaScript
    wp_enqueue_script(
        'advice2025-dropdown',
        get_template_directory_uri() . '/assets/js/dropdown-menu.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Header Navigation JavaScript
    wp_enqueue_script(
        'advice2025-header-navigation',
        get_template_directory_uri() . '/js/header-navigation.js',
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
            var varNames = ['--color-black','--color-white','--color-primary','--color-secondary'];
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
 * AJAX handler for archive load more button
 */
function advice2025_archive_load_more_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'archive_load_more_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'), 403);
        return;
    }

    $page = isset($_POST['page']) ? max(1, (int) $_POST['page']) : 1;
    $query_vars_raw = isset($_POST['query_vars']) ? wp_unslash($_POST['query_vars']) : '';
    $query_vars = json_decode($query_vars_raw, true);

    if (!is_array($query_vars)) {
        wp_send_json_error(array('message' => 'Invalid query context'), 400);
        return;
    }

    $template_variant = isset($query_vars['advice2025_template']) ? sanitize_key((string) $query_vars['advice2025_template']) : '';
    $raw_filters = isset($query_vars['advice2025_filters']) && is_array($query_vars['advice2025_filters'])
        ? $query_vars['advice2025_filters']
        : array();
    $sanitized_filters = array();

    foreach ($raw_filters as $taxonomy => $term_ids) {
        $taxonomy_key = sanitize_key((string) $taxonomy);

        if (empty($taxonomy_key) || !taxonomy_exists($taxonomy_key) || !is_array($term_ids)) {
            continue;
        }

        $ids = array_values(array_filter(array_map('intval', $term_ids)));
        if (empty($ids)) {
            continue;
        }

        $sanitized_filters[$taxonomy_key] = $ids;
    }

    unset(
        $query_vars['advice2025_template'],
        $query_vars['advice2025_filters'],
        $query_vars['paged'],
        $query_vars['page'],
        $query_vars['offset'],
        $query_vars['name'],
        $query_vars['pagename'],
        $query_vars['post_name__in']
    );

    $query_vars['post_status'] = 'publish';
    $query_vars['paged'] = $page;

    if (!empty($sanitized_filters)) {
        $query_vars['tax_query'] = array('relation' => 'AND');

        foreach ($sanitized_filters as $taxonomy => $term_ids) {
            $query_vars['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_ids,
                'operator' => 'IN',
            );
        }
    }

    $query = new WP_Query($query_vars);

    if (!$query->have_posts()) {
        wp_send_json_success(array(
            'html' => '',
            'nextPage' => $page,
            'hasMore' => false
        ));
        return;
    }

    ob_start();
    while ($query->have_posts()) {
        $query->the_post();
        if ($template_variant === 'card_kennisbank') {
            $queried_object = get_post();
            get_template_part('template-parts/card-kennisbank', null, array('item' => $queried_object));
            continue;
        }

        get_template_part('template-parts/card', get_post_type());
    }
    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => ob_get_clean(),
        'nextPage' => $page + 1,
        'hasMore' => $page < (int) $query->max_num_pages
    ));
}
add_action('wp_ajax_archive_load_more', 'advice2025_archive_load_more_ajax');
add_action('wp_ajax_nopriv_archive_load_more', 'advice2025_archive_load_more_ajax');


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
    <div class="custom-pagination flex flex-col sm:flex-row gap-1 mb-[2.5rem] sm:mb-0! items-stretch w-full">
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

/**
 * Wijs automatisch posts.php template toe aan de WordPress posts archive pagina
 * Werkt voor de standaard blog/berichten pagina
 */
function advice2025_assign_posts_template($template) {
    // Check of we op de posts archive pagina zijn (blog/berichten pagina)
    if (is_home() && !is_front_page()) {
        $posts_template = locate_template('posts.php');
        if ($posts_template) {
            return $posts_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'advice2025_assign_posts_template', 99);

/**
 * Post View Counter - Registreer views per sessie
 */

// Start sessie voor view tracking
function advice2025_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'advice2025_start_session', 1);

// AJAX handler om post view te registreren
function advice2025_register_post_view() {
    // Security check
    if (!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
        wp_send_json_error(array('message' => 'Ongeldig post ID'));
        return;
    }
    
    $post_id = intval($_POST['post_id']);
    
    // Check of post bestaat
    if (!get_post($post_id)) {
        wp_send_json_error(array('message' => 'Post niet gevonden'));
        return;
    }
    
    // Start sessie als die nog niet bestaat
    if (!session_id()) {
        session_start();
    }
    
    // Check of deze post al is bekeken in deze sessie
    $viewed_posts = isset($_SESSION['viewed_posts']) ? $_SESSION['viewed_posts'] : array();
    
    if (!in_array($post_id, $viewed_posts)) {
        // Voeg post toe aan bekeken posts in deze sessie
        $viewed_posts[] = $post_id;
        $_SESSION['viewed_posts'] = $viewed_posts;
        
        // Verhoog view count in post meta
        $current_count = get_post_meta($post_id, '_post_view_count', true);
        $new_count = $current_count ? intval($current_count) + 1 : 1;
        update_post_meta($post_id, '_post_view_count', $new_count);
        
        wp_send_json_success(array(
            'message' => 'View geregistreerd',
            'view_count' => $new_count
        ));
    } else {
        // Al bekeken in deze sessie
        $current_count = get_post_meta($post_id, '_post_view_count', true);
        wp_send_json_success(array(
            'message' => 'Al bekeken in deze sessie',
            'view_count' => $current_count ? intval($current_count) : 0
        ));
    }
}
add_action('wp_ajax_register_post_view', 'advice2025_register_post_view');
add_action('wp_ajax_nopriv_register_post_view', 'advice2025_register_post_view');

// Helper functie om view count op te halen
function advice2025_get_post_view_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = get_post_meta($post_id, '_post_view_count', true);
    return $count ? intval($count) : 0;
}

// Enqueue script voor view tracking op single posts (alle post types)
function advice2025_enqueue_post_view_tracker() {
    if (is_single()) {
        wp_enqueue_script(
            'advice2025-post-view-tracker',
            get_template_directory_uri() . '/assets/js/post-view-tracker.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
        
        wp_localize_script('advice2025-post-view-tracker', 'postViewData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'postId' => get_the_ID(),
            'nonce' => wp_create_nonce('post_view_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'advice2025_enqueue_post_view_tracker');

/**
 * Voeg View Count kolom toe aan posts lijst in admin
 */

// Voeg kolom toe aan posts lijst
function advice2025_add_view_count_column($columns) {
    $columns['view_count'] = __('Views', 'advice2025');
    return $columns;
}
add_filter('manage_posts_columns', 'advice2025_add_view_count_column');
add_filter('manage_pages_columns', 'advice2025_add_view_count_column');

// Voeg view count kolom toe aan custom post types
function advice2025_add_view_count_column_all_post_types($columns, $post_type) {
    // Voeg toe aan alle post types behalve attachment
    if ($post_type !== 'attachment') {
        $columns['view_count'] = __('Views', 'advice2025');
    }
    return $columns;
}
add_filter('manage_posts_columns', 'advice2025_add_view_count_column_all_post_types', 10, 2);

// Toon view count in de kolom
function advice2025_show_view_count_column($column, $post_id) {
    if ($column === 'view_count') {
        $view_count = advice2025_get_post_view_count($post_id);
        echo '<strong>' . number_format_i18n($view_count) . '</strong>';
    }
}
add_action('manage_posts_custom_column', 'advice2025_show_view_count_column', 10, 2);
add_action('manage_pages_custom_column', 'advice2025_show_view_count_column', 10, 2);

// Maak kolom sorteerbaar
function advice2025_make_view_count_column_sortable($columns) {
    $columns['view_count'] = 'view_count';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'advice2025_make_view_count_column_sortable');
add_filter('manage_edit-page_sortable_columns', 'advice2025_make_view_count_column_sortable');

// Handle sorting op view count
function advice2025_sort_posts_by_view_count($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if ($query->get('orderby') === 'view_count') {
        $query->set('meta_key', '_post_view_count');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'advice2025_sort_posts_by_view_count');

// Voeg view count meta box toe aan post editor
function advice2025_add_view_count_meta_box() {
    $post_types = get_post_types(array('public' => true), 'names');
    foreach ($post_types as $post_type) {
        if ($post_type !== 'attachment') {
            add_meta_box(
                'advice2025_view_count',
                __('View Count', 'advice2025'),
                'advice2025_view_count_meta_box_callback',
                $post_type,
                'side',
                'default'
            );
        }
    }
}
add_action('add_meta_boxes', 'advice2025_add_view_count_meta_box');

// Meta box callback
function advice2025_view_count_meta_box_callback($post) {
    $view_count = advice2025_get_post_view_count($post->ID);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin: 0; font-size: 24px; font-weight: bold; color: #2271b1;">
            <?php echo number_format_i18n($view_count); ?>
        </p>
        <p style="margin: 5px 0 0 0; color: #646970; font-size: 13px;">
            <?php _e('Totaal aantal views (per sessie)', 'advice2025'); ?>
        </p>
    </div>
    <?php
}

/**
 * WordPress Customizer - Header Settings
 * Voeg controles toe voor het instellen van verschillende header layouts
 */
function advice2025_customize_register($wp_customize) {
    // Add Header Settings Section
    $wp_customize->add_section('advice2025_header_settings', array(
        'title'    => __('Header Instellingen', 'advice2025'),
        'priority' => 30,
    ));

    // Header Layout Selection
    $wp_customize->add_setting('header_layout', array(
        'default'           => 'layout-1',
        'sanitize_callback' => 'advice2025_sanitize_header_layout',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_layout', array(
        'label'       => __('Header Layout', 'advice2025'),
        'description' => __('Kies het gewenste header layout', 'advice2025'),
        'section'     => 'advice2025_header_settings',
        'type'        => 'select',
        'choices'     => array(
            'layout-1' => __('Layout 1: Logo links + Menu rechts + Topbar', 'advice2025'),
            'layout-2' => __('Layout 2: Logo + Menu links + Button rechts + Topbar', 'advice2025'),
            'layout-3' => __('Layout 3: Logo links + Buttons rechts + Menu gecentreerd onder', 'advice2025'),
            'layout-4' => __('Layout 4: Logo links + Menu gecentreerd + Buttons rechts', 'advice2025'),
            'layout-5' => __('Layout 5: Floating menubalk (binnen container, 40px ruimte boven)', 'advice2025'),
        ),
    ));

    // Enable/Disable Topbar
    $wp_customize->add_setting('header_enable_topbar', array(
        'default'           => true,
        'sanitize_callback' => 'advice2025_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_enable_topbar', array(
        'label'       => __('Topbar Activeren', 'advice2025'),
        'description' => __('Schakel de topbar in of uit', 'advice2025'),
        'section'     => 'advice2025_header_settings',
        'type'        => 'checkbox',
    ));

    // Container Width Setting
    $wp_customize->add_setting('header_container_width', array(
        'default'           => 'full-width',
        'sanitize_callback' => 'advice2025_sanitize_container_width',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_container_width', array(
        'label'       => __('Container Breedte', 'advice2025'),
        'description' => __('Bepaal of de header binnen of buiten de container valt', 'advice2025'),
        'section'     => 'advice2025_header_settings',
        'type'        => 'select',
        'choices'     => array(
            'full-width' => __('Volledige breedte (buiten container)', 'advice2025'),
            'contained'  => __('Binnen container', 'advice2025'),
        ),
    ));

    // Topbar Style (for layouts that support it)
    $wp_customize->add_setting('header_topbar_style', array(
        'default'           => 'voordelen',
        'sanitize_callback' => 'advice2025_sanitize_topbar_style',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('header_topbar_style', array(
        'label'       => __('Topbar Stijl', 'advice2025'),
        'description' => __('Kies welke informatie in de topbar wordt weergegeven', 'advice2025'),
        'section'     => 'advice2025_header_settings',
        'type'        => 'select',
        'choices'     => array(
            'voordelen'       => __('Voordelen (Layout 1)', 'advice2025'),
            'contact-info'    => __('Contact informatie (Layout 2)', 'advice2025'),
        ),
    ));
}
add_action('customize_register', 'advice2025_customize_register');

/**
 * Sanitize header layout
 */
function advice2025_sanitize_header_layout($input) {
    $valid = array('layout-1', 'layout-2', 'layout-3', 'layout-4', 'layout-5');
    return in_array($input, $valid, true) ? $input : 'layout-1';
}

/**
 * Sanitize checkbox
 */
function advice2025_sanitize_checkbox($input) {
    return ($input === true || $input === '1') ? true : false;
}

/**
 * Sanitize container width
 */
function advice2025_sanitize_container_width($input) {
    $valid = array('full-width', 'contained');
    return in_array($input, $valid, true) ? $input : 'full-width';
}

/**
 * Sanitize topbar style
 */
function advice2025_sanitize_topbar_style($input) {
    $valid = array('voordelen', 'contact-info');
    return in_array($input, $valid, true) ? $input : 'voordelen';
}

/**
 * Add Mega Menu Custom Fields to Menu Items
 */
function advice2025_add_menu_item_custom_fields($item_id, $item, $depth, $args) {
    // Get saved values
    $enable_mega_menu = get_post_meta($item_id, '_menu_item_enable_mega_menu', true);
    $show_expertises = get_post_meta($item_id, '_menu_item_show_expertises', true);
    $mega_menu_cta_text = get_post_meta($item_id, '_menu_item_mega_menu_cta_text', true);
    $mega_menu_cta_url = get_post_meta($item_id, '_menu_item_mega_menu_cta_url', true);
    $icon_type = get_post_meta($item_id, '_menu_item_icon_type', true) ?: 'none';
    $fa_icon = get_post_meta($item_id, '_menu_item_fa_icon', true);
    $custom_icon = get_post_meta($item_id, '_menu_item_custom_icon', true);
    
    // Top-level items: Mega Menu options
    if ($depth == 0) {
        ?>
        <p class="field-enable-mega-menu description description-wide" style="margin: 10px 0;">
            <label for="edit-menu-item-enable-mega-menu-<?php echo $item_id; ?>">
                <input type="checkbox" 
                       id="edit-menu-item-enable-mega-menu-<?php echo $item_id; ?>" 
                       name="menu-item-enable-mega-menu[<?php echo $item_id; ?>]" 
                       value="1" 
                       <?php checked($enable_mega_menu, '1'); ?> />
                <?php _e('Enable Mega Menu (100% width)', 'advice2025'); ?>
            </label>
        </p>

        <p class="field-show-expertises description description-wide" style="margin: 10px 0;">
            <label for="edit-menu-item-show-expertises-<?php echo $item_id; ?>">
                <input type="checkbox"
                       id="edit-menu-item-show-expertises-<?php echo $item_id; ?>"
                       name="menu-item-show-expertises[<?php echo $item_id; ?>]"
                       value="1"
                       <?php checked($show_expertises, '1'); ?> />
                <?php _e('Toon expertises in dropdown (desktop)', 'advice2025'); ?>
            </label><br />
            <span class="description"><?php _e('Wanneer actief, wordt de dropdown automatisch gevuld met alle expertises.', 'advice2025'); ?></span>
        </p>
        
        <p class="field-mega-menu-cta-text description description-wide" style="margin: 10px 0;">
            <label for="edit-menu-item-mega-menu-cta-text-<?php echo $item_id; ?>">
                <?php _e('Mega Menu CTA Text', 'advice2025'); ?><br />
                <input type="text" 
                       id="edit-menu-item-mega-menu-cta-text-<?php echo $item_id; ?>" 
                       class="widefat" 
                       name="menu-item-mega-menu-cta-text[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($mega_menu_cta_text); ?>" 
                       placeholder="<?php _e('Optional CTA button text', 'advice2025'); ?>" />
            </label>
        </p>
        
        <p class="field-mega-menu-cta-url description description-wide" style="margin: 10px 0;">
            <label for="edit-menu-item-mega-menu-cta-url-<?php echo $item_id; ?>">
                <?php _e('Mega Menu CTA URL', 'advice2025'); ?><br />
                <input type="text" 
                       id="edit-menu-item-mega-menu-cta-url-<?php echo $item_id; ?>" 
                       class="widefat" 
                       name="menu-item-mega-menu-cta-url[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($mega_menu_cta_url); ?>" 
                       placeholder="<?php _e('Optional CTA button URL', 'advice2025'); ?>" />
            </label>
        </p>
        <?php
    }
    
    // All items: Icon options
    ?>
    <div class="menu-item-icon-options" style="border-top: 1px solid #ddd; margin-top: 15px; padding-top: 15px;">
        <p class="description description-wide" style="margin: 10px 0;">
            <strong><?php _e('Menu Item Icon', 'advice2025'); ?></strong>
        </p>
        
        <p class="field-icon-type description description-wide" style="margin: 10px 0;">
            <label for="edit-menu-item-icon-type-<?php echo $item_id; ?>">
                <?php _e('Icon Type', 'advice2025'); ?><br />
                <select id="edit-menu-item-icon-type-<?php echo $item_id; ?>" 
                        name="menu-item-icon-type[<?php echo $item_id; ?>]" 
                        class="widefat menu-icon-type-select"
                        data-item-id="<?php echo $item_id; ?>">
                    <option value="none" <?php selected($icon_type, 'none'); ?>><?php _e('No Icon', 'advice2025'); ?></option>
                    <option value="fontawesome" <?php selected($icon_type, 'fontawesome'); ?>><?php _e('Font Awesome Icon', 'advice2025'); ?></option>
                    <option value="custom" <?php selected($icon_type, 'custom'); ?>><?php _e('Custom Image', 'advice2025'); ?></option>
                </select>
            </label>
        </p>
        
        <div class="menu-icon-fontawesome-field" 
             id="menu-icon-fontawesome-<?php echo $item_id; ?>" 
             style="display: <?php echo ($icon_type === 'fontawesome') ? 'block' : 'none'; ?>;">
            <p class="field-fa-icon description description-wide" style="margin: 10px 0;">
                <label for="edit-menu-item-fa-icon-<?php echo $item_id; ?>">
                    <?php _e('Font Awesome Icon Class', 'advice2025'); ?><br />
                    <input type="text" 
                           id="edit-menu-item-fa-icon-<?php echo $item_id; ?>" 
                           class="widefat menu-fa-icon-input" 
                           name="menu-item-fa-icon[<?php echo $item_id; ?>]" 
                           value="<?php echo esc_attr($fa_icon); ?>" 
                           placeholder="fa-solid fa-home" />
                    <span class="description">
                        <?php _e('Examples: fa-solid fa-home, fa-regular fa-user, fa-brands fa-wordpress', 'advice2025'); ?><br />
                        <a href="https://fontawesome.com/search?o=r&m=free" target="_blank"><?php _e('Browse Font Awesome Icons', 'advice2025'); ?></a>
                    </span>
                </label>
            </p>
            
            <?php if ($fa_icon) : ?>
            <p class="description" style="margin: 10px 0;">
                <strong><?php _e('Preview:', 'advice2025'); ?></strong><br />
                <i class="<?php echo esc_attr($fa_icon); ?>" style="font-size: 24px; margin-top: 5px;"></i>
            </p>
            <?php endif; ?>
            
            <p class="description" style="margin: 10px 0;">
                <strong><?php _e('Popular Icons:', 'advice2025'); ?></strong><br />
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-home" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-home"></i> Home
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-user" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-user"></i> User
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-envelope" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-envelope"></i> Mail
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-phone" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-phone"></i> Phone
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-cog" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-cog"></i> Settings
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-shopping-cart" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-shopping-cart"></i> Cart
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-heart" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-heart"></i> Heart
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-star" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-star"></i> Star
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-check" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-check"></i> Check
                </button>
                <button type="button" class="button button-small fa-icon-insert" data-icon="fa-solid fa-building" data-item-id="<?php echo $item_id; ?>">
                    <i class="fa-solid fa-building"></i> Building
                </button>
            </p>
        </div>
        
        <div class="menu-icon-custom-field" 
             id="menu-icon-custom-<?php echo $item_id; ?>" 
             style="display: <?php echo ($icon_type === 'custom') ? 'block' : 'none'; ?>;">
            <p class="field-custom-icon description description-wide" style="margin: 10px 0;">
                <label for="edit-menu-item-custom-icon-<?php echo $item_id; ?>">
                    <?php _e('Custom Icon Image', 'advice2025'); ?><br />
                    <input type="hidden" 
                           id="edit-menu-item-custom-icon-<?php echo $item_id; ?>" 
                           name="menu-item-custom-icon[<?php echo $item_id; ?>]" 
                           value="<?php echo esc_attr($custom_icon); ?>" 
                           class="menu-custom-icon-input" />
                    <button type="button" 
                            class="button upload-icon-button" 
                            data-item-id="<?php echo $item_id; ?>">
                        <?php _e('Upload Icon', 'advice2025'); ?>
                    </button>
                    <button type="button" 
                            class="button remove-icon-button" 
                            data-item-id="<?php echo $item_id; ?>"
                            style="<?php echo empty($custom_icon) ? 'display:none;' : ''; ?>">
                        <?php _e('Remove Icon', 'advice2025'); ?>
                    </button>
                </label>
            </p>
            
            <?php if ($custom_icon) : ?>
            <p class="custom-icon-preview" id="custom-icon-preview-<?php echo $item_id; ?>" style="margin: 10px 0;">
                <img src="<?php echo esc_url($custom_icon); ?>" style="max-width: 60px; max-height: 60px; display: block;" alt="Icon Preview" />
            </p>
            <?php else : ?>
            <p class="custom-icon-preview" id="custom-icon-preview-<?php echo $item_id; ?>" style="margin: 10px 0; display: none;">
                <img src="" style="max-width: 60px; max-height: 60px; display: block;" alt="Icon Preview" />
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'advice2025_add_menu_item_custom_fields', 10, 4);

/**
 * Save Mega Menu Custom Fields
 */
function advice2025_save_menu_item_custom_fields($menu_id, $menu_item_db_id, $args) {
    // Save mega menu enable checkbox
    if (isset($_POST['menu-item-enable-mega-menu'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_enable_mega_menu', '1');
    } else {
        delete_post_meta($menu_item_db_id, '_menu_item_enable_mega_menu');
    }

    // Save show expertises checkbox
    if (isset($_POST['menu-item-show-expertises'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_show_expertises', '1');
    } else {
        delete_post_meta($menu_item_db_id, '_menu_item_show_expertises');
    }
    
    // Save CTA text
    if (isset($_POST['menu-item-mega-menu-cta-text'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_mega_menu_cta_text', sanitize_text_field($_POST['menu-item-mega-menu-cta-text'][$menu_item_db_id]));
    }
    
    // Save CTA URL
    if (isset($_POST['menu-item-mega-menu-cta-url'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_mega_menu_cta_url', esc_url_raw($_POST['menu-item-mega-menu-cta-url'][$menu_item_db_id]));
    }
    
    // Save icon type
    if (isset($_POST['menu-item-icon-type'][$menu_item_db_id])) {
        $icon_type = sanitize_text_field($_POST['menu-item-icon-type'][$menu_item_db_id]);
        update_post_meta($menu_item_db_id, '_menu_item_icon_type', $icon_type);
    }
    
    // Save Font Awesome icon
    if (isset($_POST['menu-item-fa-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_fa_icon', sanitize_text_field($_POST['menu-item-fa-icon'][$menu_item_db_id]));
    }
    
    // Save custom icon
    if (isset($_POST['menu-item-custom-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_custom_icon', esc_url_raw($_POST['menu-item-custom-icon'][$menu_item_db_id]));
    }
}
add_action('wp_update_nav_menu_item', 'advice2025_save_menu_item_custom_fields', 10, 3);

/**
 * Enqueue admin scripts for menu icon functionality
 */
function advice2025_enqueue_menu_icon_admin_scripts($hook) {
    if ('nav-menus.php' !== $hook) {
        return;
    }
    
    wp_enqueue_media();
    
    wp_add_inline_script('jquery', "
        jQuery(document).ready(function($) {
            // Toggle icon fields based on type selection
            $(document).on('change', '.menu-icon-type-select', function() {
                var itemId = $(this).data('item-id');
                var iconType = $(this).val();
                
                $('#menu-icon-fontawesome-' + itemId).hide();
                $('#menu-icon-custom-' + itemId).hide();
                
                if (iconType === 'fontawesome') {
                    $('#menu-icon-fontawesome-' + itemId).show();
                } else if (iconType === 'custom') {
                    $('#menu-icon-custom-' + itemId).show();
                }
            });
            
            // Insert Font Awesome icon class on button click
            $(document).on('click', '.fa-icon-insert', function(e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');
                var iconClass = $(this).data('icon');
                $('#edit-menu-item-fa-icon-' + itemId).val(iconClass);
            });
            
            // Media uploader for custom icons
            $(document).on('click', '.upload-icon-button', function(e) {
                e.preventDefault();
                var button = $(this);
                var itemId = button.data('item-id');
                
                var frame = wp.media({
                    title: 'Select or Upload Icon',
                    button: { text: 'Use this icon' },
                    multiple: false,
                    library: { type: 'image' }
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#edit-menu-item-custom-icon-' + itemId).val(attachment.url);
                    $('#custom-icon-preview-' + itemId).show().find('img').attr('src', attachment.url);
                    $('.remove-icon-button[data-item-id=\"' + itemId + '\"]').show();
                });
                
                frame.open();
            });
            
            // Remove custom icon
            $(document).on('click', '.remove-icon-button', function(e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');
                $('#edit-menu-item-custom-icon-' + itemId).val('');
                $('#custom-icon-preview-' + itemId).hide().find('img').attr('src', '');
                $(this).hide();
            });
        });
    ");
}
add_action('admin_enqueue_scripts', 'advice2025_enqueue_menu_icon_admin_scripts');


/**
* SVG ondersteuning in de mediabibliotheek
*/
function advice2025_add_svg_support($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'advice2025_add_svg_support');

/**
* Sanitiseer SVG bij upload (verwijdert scripts en event handlers)
*/
function advice2025_sanitize_svg($file) {
    $is_svg = ($file['type'] === 'image/svg+xml') || (pathinfo($file['name'], PATHINFO_EXTENSION) === 'svg');
    if (!$is_svg) {
        return $file;
    }
    $content = file_get_contents($file['tmp_name']);
    if ($content === false) {
        return $file;
    }
    // Verwijder script tags en event handlers
    $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
    $content = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $content);
    $content = preg_replace('/\s+on\w+\s*=\s*[^\s>]*/i', '', $content);
    file_put_contents($file['tmp_name'], $content);
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'advice2025_sanitize_svg');

/**
* SVG bestanden in mediabibliotheek laten zien als afbeeldingen
*/
function advice2025_fix_svg_thumbnails($response, $attachment, $meta) {
    if ($response['type'] === 'image/svg+xml') {
        $response['sizes'] = array(
            'full' => array(
                'url' => $response['url'],
                'width' => $response['width'] ?? null,
                'height' => $response['height'] ?? null,
                'orientation' => ($response['width'] ?? 0) > ($response['height'] ?? 0) ? 'landscape' : 'portrait',
            ),
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'advice2025_fix_svg_thumbnails', 10, 3);

/**
 * Taxonomy term thumbnail: register hooks for all taxonomy admin screens.
 */
function advice2025_register_term_thumbnail_hooks() {
    $taxonomies = get_taxonomies(array('show_ui' => true), 'names');

    foreach ($taxonomies as $taxonomy) {
        add_action($taxonomy . '_add_form_fields', 'advice2025_render_term_thumbnail_add_field');
        add_action($taxonomy . '_edit_form_fields', 'advice2025_render_term_thumbnail_edit_field', 10, 2);
    }
}
add_action('init', 'advice2025_register_term_thumbnail_hooks');

/**
 * Render term thumbnail field on add term form.
 */
function advice2025_render_term_thumbnail_add_field($taxonomy) {
    ?>
    <div class="form-field term-thumbnail-wrap">
        <label for="advice2025-term-thumbnail-id"><?php esc_html_e('Afbeelding', 'advice2025'); ?></label>
        <input type="hidden" id="advice2025-term-thumbnail-id" name="advice2025_term_thumbnail_id" value="" />
        <div id="advice2025-term-thumbnail-preview" style="margin: 10px 0; display: none;">
            <img src="" style="max-width: 120px; height: auto; display: block;" alt="<?php echo esc_attr__('Preview', 'advice2025'); ?>" />
        </div>
        <button type="button" class="button advice2025-term-thumbnail-upload"><?php esc_html_e('Upload afbeelding', 'advice2025'); ?></button>
        <button type="button" class="button advice2025-term-thumbnail-remove" style="display: none;"><?php esc_html_e('Verwijder afbeelding', 'advice2025'); ?></button>
        <p class="description"><?php esc_html_e('Kies een afbeelding voor deze taxonomie-term.', 'advice2025'); ?></p>
        <?php wp_nonce_field('advice2025_save_term_thumbnail', 'advice2025_term_thumbnail_nonce'); ?>
    </div>
    <?php
}

/**
 * Render term thumbnail field on edit term form.
 */
function advice2025_render_term_thumbnail_edit_field($term, $taxonomy) {
    $thumbnail_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
    $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : '';
    ?>
    <tr class="form-field term-thumbnail-wrap">
        <th scope="row"><label for="advice2025-term-thumbnail-id"><?php esc_html_e('Afbeelding', 'advice2025'); ?></label></th>
        <td>
            <input type="hidden" id="advice2025-term-thumbnail-id" name="advice2025_term_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>" />
            <div id="advice2025-term-thumbnail-preview" style="margin: 10px 0; <?php echo $thumbnail_url ? '' : 'display: none;'; ?>">
                <img src="<?php echo esc_url($thumbnail_url); ?>" style="max-width: 120px; height: auto; display: block;" alt="<?php echo esc_attr__('Preview', 'advice2025'); ?>" />
            </div>
            <button type="button" class="button advice2025-term-thumbnail-upload"><?php esc_html_e('Upload afbeelding', 'advice2025'); ?></button>
            <button type="button" class="button advice2025-term-thumbnail-remove" style="<?php echo $thumbnail_url ? '' : 'display: none;'; ?>"><?php esc_html_e('Verwijder afbeelding', 'advice2025'); ?></button>
            <p class="description"><?php esc_html_e('Kies een afbeelding voor deze taxonomie-term.', 'advice2025'); ?></p>
            <?php wp_nonce_field('advice2025_save_term_thumbnail', 'advice2025_term_thumbnail_nonce'); ?>
        </td>
    </tr>
    <?php
}

/**
 * Save term thumbnail attachment ID.
 */
function advice2025_save_term_thumbnail($term_id, $tt_id = 0, $taxonomy = '') {
    if (!isset($_POST['advice2025_term_thumbnail_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['advice2025_term_thumbnail_nonce'])), 'advice2025_save_term_thumbnail')) {
        return;
    }

    $taxonomy_object = get_taxonomy($taxonomy);
    if (!$taxonomy_object || !isset($taxonomy_object->cap->manage_terms) || !current_user_can($taxonomy_object->cap->manage_terms)) {
        return;
    }

    $thumbnail_id = isset($_POST['advice2025_term_thumbnail_id']) ? absint(wp_unslash($_POST['advice2025_term_thumbnail_id'])) : 0;

    if ($thumbnail_id > 0) {
        update_term_meta($term_id, 'thumbnail_id', $thumbnail_id);
        return;
    }

    delete_term_meta($term_id, 'thumbnail_id');
}
add_action('created_term', 'advice2025_save_term_thumbnail', 10, 3);
add_action('edited_term', 'advice2025_save_term_thumbnail', 10, 3);

/**
 * Enqueue term thumbnail media uploader for taxonomy screens.
 */
function advice2025_enqueue_term_thumbnail_admin_scripts($hook) {
    if (!in_array($hook, array('edit-tags.php', 'term.php'), true)) {
        return;
    }

    $taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : '';
    if (empty($taxonomy) || !taxonomy_exists($taxonomy)) {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script('jquery', "
        jQuery(function($) {
            var frame;
            var field = $('#advice2025-term-thumbnail-id');
            var preview = $('#advice2025-term-thumbnail-preview');
            var previewImg = preview.find('img');
            var removeButton = $('.advice2025-term-thumbnail-remove');

            $(document).on('click', '.advice2025-term-thumbnail-upload', function(e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Selecteer of upload afbeelding',
                    button: { text: 'Gebruik deze afbeelding' },
                    multiple: false,
                    library: { type: 'image' }
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    field.val(attachment.id);
                    previewImg.attr('src', attachment.url);
                    preview.show();
                    removeButton.show();
                });

                frame.open();
            });

            $(document).on('click', '.advice2025-term-thumbnail-remove', function(e) {
                e.preventDefault();
                field.val('');
                previewImg.attr('src', '');
                preview.hide();
                $(this).hide();
            });
        });
    ");
}
add_action('admin_enqueue_scripts', 'advice2025_enqueue_term_thumbnail_admin_scripts');

/**
 * Get taxonomy term thumbnail URL.
 */
function advice2025_get_term_thumbnail_url($term = null, $size = 'full') {
    $term_object = $term instanceof WP_Term ? $term : get_term($term);
    if (!$term_object || is_wp_error($term_object)) {
        return '';
    }

    $thumbnail_id = (int) get_term_meta($term_object->term_id, 'thumbnail_id', true);
    if ($thumbnail_id > 0) {
        $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, $size);
        if ($thumbnail_url) {
            return $thumbnail_url;
        }
    }

    if (!function_exists('get_field')) {
        return '';
    }

    $term_context = $term_object->taxonomy . '_' . $term_object->term_id;
    $acf_field_candidates = array('thumbnail', 'image', 'afbeelding', 'term_image', 'header_afbeelding');

    foreach ($acf_field_candidates as $field_name) {
        $acf_value = get_field($field_name, $term_context);
        if (is_array($acf_value) && isset($acf_value['url'])) {
            return (string) $acf_value['url'];
        }
        if (is_numeric($acf_value)) {
            $acf_url = wp_get_attachment_image_url((int) $acf_value, $size);
            if ($acf_url) {
                return $acf_url;
            }
        }
        if (is_string($acf_value) && filter_var($acf_value, FILTER_VALIDATE_URL)) {
            return $acf_value;
        }
    }

    return '';
}

/**
 * Extra admin menu links voor taxonomiebeheer.
 */
add_action('admin_menu', function () {
    // Expertises
    add_menu_page(
        __('Expertises', 'advice2025'),
        __('Expertises', 'advice2025'),
        'manage_categories',
        'edit-tags.php?taxonomy=expertise',
        '',
        'dashicons-welcome-learn-more',
        22
    );
    add_menu_page(
        __('Thema\'s', 'advice2025'),
        __('Thema\'s', 'advice2025'),
        'manage_categories',
        'edit-tags.php?taxonomy=thema',
        '',
        'dashicons-welcome-learn-more',
        22
    );
});