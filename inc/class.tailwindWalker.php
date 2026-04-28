<?php
/**
 * Custom Tailwind CSS Nav Walker for Dropdowns
 */
class Advice2025_Nav_Walker extends Walker_Nav_Menu {
    
    private $dropdown_item_count = 0;
    private $is_mega_menu = false;
    private $mega_menu_cta_text = '';
    private $mega_menu_cta_url = '';
    
    // Start Level - Add dropdown container
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth == 0) {
            // Count dropdown items first
            $this->dropdown_item_count = 0;
            
            // Full viewport width dropdown container outside navbar
            global $current_dropdown_id;
            $dropdown_class = $current_dropdown_id ? "dropdown-menu-" . $current_dropdown_id : "dropdown-menu";
            
            // Get container width setting and current layout
            $container_width = get_theme_mod('header_container_width', 'full-width');
            $header_layout = get_theme_mod('header_layout', 'layout-1');
            
            // Check if mega menu should be contained
            $is_contained = ($container_width === 'contained' || $header_layout === 'layout-5');
            
            if ($this->is_mega_menu) {
                if ($is_contained) {
                    // Mega Menu - Contained
                    $output .= "\n$indent<div class=\"dropdown-menu mega-menu mega-menu-contained {$dropdown_class} fixed left-1/2 top-full bg-white backdrop-blur-md opacity-0 invisible transition-opacity duration-300 z-50 rounded-lg\" style=\"transform: translateX(-50%); width: 1440px; max-width: calc(100vw - 80px);\">\n";
                    $output .= "$indent\t<div class=\"px-[6.25rem] py-[5rem] max-h-[80vh] overflow-y-auto overscroll-contain\">\n";
                } else {
                    // Mega Menu - Full width
                    $output .= "\n$indent<div class=\"dropdown-menu mega-menu mega-menu-full {$dropdown_class} fixed left-0 top-full w-screen bg-white backdrop-blur-md opacity-0 invisible transform transition-opacity duration-300 z-50\">\n";
                    $output .= "$indent\t<div class=\"container-fluid mx-auto px-[6.25rem] py-[5rem] max-h-[80vh] overflow-y-auto overscroll-contain\">\n";
                }
                $output .= "$indent\t\t<div class=\"flex gap-8\">\n";
                $output .= "$indent\t\t\t<div class=\"flex-1\">\n";
                $output .= "$indent\t\t\t\t<div class=\"grid gap-6\" data-dropdown-grid data-dropdown-id=\"{$current_dropdown_id}\">\n";
            } else {
                if ($is_contained) {
                    // Regular dropdown - Contained
                    $output .= "\n$indent<div class=\"dropdown-menu {$dropdown_class} fixed left-1/2 top-full bg-white backdrop-blur-md opacity-0 invisible transition-opacity duration-300 z-50 rounded-lg\" style=\"transform: translateX(-50%); width: 1440px; max-width: calc(100vw - 80px);\">\n";
                    $output .= "$indent\t<div class=\"px-[6.25rem] py-[5rem] max-h-[80vh] overflow-y-auto overscroll-contain\">\n";
                } else {
                    // Regular dropdown - Full width
                    $output .= "\n$indent<div class=\"dropdown-menu {$dropdown_class} fixed left-0 top-full w-screen bg-white backdrop-blur-md opacity-0 invisible transform transition-opacity duration-300 z-50\">\n";
                    $output .= "$indent\t<div class=\"container-fluid mx-auto px-[6.25rem] py-[5rem] max-h-[80vh] overflow-y-auto overscroll-contain\">\n";
                }
                $output .= "$indent\t\t<div class=\"grid gap-6\" data-dropdown-grid data-dropdown-id=\"{$current_dropdown_id}\">\n";
            }
        } else {
            $output .= "\n$indent<ul class=\"dropdown-submenu\">\n";
        }
    }

    // End Level
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth == 0) {
            // Determine grid columns based on item count
            // Show 4 columns when there are 4 or fewer items, otherwise 5 columns
            $grid_cols = $this->dropdown_item_count <= 4 ? 'grid-cols-4' : 'grid-cols-5';
            
            // Get the current dropdown ID from global variable
            global $current_dropdown_id;
            $dropdown_id = $current_dropdown_id ? $current_dropdown_id : '';
            
            if ($this->is_mega_menu) {
                $output .= "$indent\t\t\t\t</div>\n";
                $output .= "$indent\t\t\t</div>\n";
                
                // Add CTA section if provided
                if (!empty($this->mega_menu_cta_text) && !empty($this->mega_menu_cta_url)) {
                    $output .= "$indent\t\t\t<div class=\"w-[21.875rem] flex-shrink-0\">\n";
                    $output .= "$indent\t\t\t\t<div class=\"bg-gray-50 rounded-lg p-8 h-full flex flex-col justify-center\">\n";
                    $output .= "$indent\t\t\t\t\t<h3 class=\"text-2xl font-bold text-[#131611] mb-4\">Ontdek meer</h3>\n";
                    $output .= "$indent\t\t\t\t\t<p class=\"text-gray-600 mb-6\">Neem contact op met ons team voor persoonlijk advies en maatwerkoplossingen.</p>\n";
                    $output .= "$indent\t\t\t\t\t<a href=\"" . esc_url($this->mega_menu_cta_url) . "\" class=\"inline-flex items-center justify-center gap-2 px-6 py-4 bg-[#FF5822] text-[#131611] rounded font-semibold hover:opacity-90 transition-opacity\">\n";
                    $output .= "$indent\t\t\t\t\t\t<span>" . esc_html($this->mega_menu_cta_text) . "</span>\n";
                    $output .= "$indent\t\t\t\t\t\t<svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n";
                    $output .= "$indent\t\t\t\t\t\t\t<path d=\"M4 10H16M16 10L11 5M16 10L11 15\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>\n";
                    $output .= "$indent\t\t\t\t\t\t</svg>\n";
                    $output .= "$indent\t\t\t\t\t</a>\n";
                    $output .= "$indent\t\t\t\t</div>\n";
                    $output .= "$indent\t\t\t</div>\n";
                }
                
                $output .= "$indent\t\t</div>\n";
                $output .= "$indent\t</div>\n";
                $output .= "$indent</div>\n";
                
                // Reset mega menu flags
                $this->is_mega_menu = false;
                $this->mega_menu_cta_text = '';
                $this->mega_menu_cta_url = '';
            } else {
                $output .= "$indent\t\t</div>\n";
                $output .= "$indent\t</div>\n";
                $output .= "$indent</div>\n";
            }
            
            // Add JavaScript to update the grid classes immediately for this dropdown
            $output .= "<script>(function(){\n";
            $output .= "  var grid = document.querySelector('[data-dropdown-grid][data-dropdown-id=\"{$dropdown_id}\"]');\n";
            $output .= "  if (!grid) return;\n";
            $output .= "  grid.classList.remove('grid-cols-4','grid-cols-5');\n";
            $output .= "  grid.classList.add('{$grid_cols}');\n";
            $output .= "  // Fallback for when CSS class isn't present yet: set explicit columns\n";
            $output .= "  var cols = '{$grid_cols}' === 'grid-cols-4' ? 4 : 5;\n";
            $output .= "  grid.style.gridTemplateColumns = 'repeat(' + cols + ', minmax(0, 1fr))';\n";
            $output .= "  grid.setAttribute('data-item-count','{$this->dropdown_item_count}');\n";
            $output .= "})();</script>\n";
            
            // Non-JS fallback: set grid-template-columns via CSS for this specific dropdown
            $cols_num = ($this->dropdown_item_count <= 4) ? 4 : 5;
            $output .= "<style>[data-dropdown-grid][data-dropdown-id=\"{$dropdown_id}\"]{grid-template-columns:repeat({$cols_num},minmax(0,1fr))}</style>\n";
        } else {
            $output .= "$indent</ul>\n";
        }
    }

    // Start Element
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);
        
        // Check if mega menu is enabled (only for top-level items with children)
        $enable_mega_menu = false;
        if ($has_children && $depth == 0) {
            $enable_mega_menu = get_post_meta($item->ID, '_menu_item_enable_mega_menu', true);
            if ($enable_mega_menu) {
                $this->is_mega_menu = true;
                $this->mega_menu_cta_text = get_post_meta($item->ID, '_menu_item_mega_menu_cta_text', true);
                $this->mega_menu_cta_url = get_post_meta($item->ID, '_menu_item_mega_menu_cta_url', true);
            }
        }
        
        // Apply filters
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        // Add dropdown functionality classes
        if ($has_children && $depth == 0) {
            $class_names .= ' relative has-dropdown';
            if ($enable_mega_menu) {
                $class_names .= ' has-mega-menu';
            }
        }
        
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        // Add data attributes for JavaScript dropdown functionality
        $data_attrs = '';
        if ($has_children && $depth == 0) {
            $data_attrs = ' data-has-dropdown="true" data-dropdown-id="menu-item-' . $item->ID . '"';
            // Store the dropdown ID in a global variable for the start_lvl function
            global $current_dropdown_id;
            $current_dropdown_id = $item->ID;
        }

        if ($depth == 0) {
            // Ensure flex item doesn't expand due to wide fixed dropdown child
            // Append min-w-0 to prevent intrinsic sizing from pushing siblings apart
            $li_class_names = $class_names ? str_replace('class="', 'class="min-w-0 ', $class_names) : ' class="min-w-0"';
            $output .= $indent . '<li' . $id . $li_class_names . $data_attrs .'>';
        } else {
            // For dropdown items, we don't need li tags since we're using a div grid
            $output .= $indent . '<div' . $id . $class_names .'>';
        }

        // Determine if this item is current/active (covers singular, archive, ancestors, etc.)
        $is_current = false;
        foreach ($classes as $menu_class) {
            if (strpos($menu_class, 'current-') === 0) {
                $is_current = true;
                break;
            }
        }

        $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        
        // Different styles for different depths
        if ($depth == 0) {
            // Top level items
            $link_classes = 'nav-link relative px-4 py-2 min-h-[6rem] text-black tracking-wide transition-all duration-300 flex items-center min-w-0';
            
            if ($has_children) {
                $link_classes .= ' ';
            }
        } else {
            // Count dropdown items
            $this->dropdown_item_count++;
            
            // Dropdown items - full width cards with images
            $link_classes = 'dropdown-link flex flex-col w-full bg-white group/submenu transition-all duration-300 h-full';
        }

        // Add active class to the link if this item is current (including archives)
        if ($is_current) {
            $link_classes .= ' is-active';
        }

        $item_output .= '<a' . $attributes . ' class="' . $link_classes . '">';
        
        // Different content for different depths
        if ($depth == 0) {
            // Top level items
            $item_output .= (isset($args->link_before) ? $args->link_before : '') . '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>' . (isset($args->link_after) ? $args->link_after : '');
            
            // Add dropdown arrow for parent items
            if ($has_children) {
                $item_output .= '<svg class="w-4 h-4 ml-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>';
            }
        } else {
            // Dropdown items with image and content
            $featured_image = get_the_post_thumbnail_url($item->object_id, 'large');
            $description = get_post_meta($item->ID, '_menu_item_description', true);
            
            // Get icon settings
            $icon_type = get_post_meta($item->ID, '_menu_item_icon_type', true);
            $fa_icon = get_post_meta($item->ID, '_menu_item_fa_icon', true);
            $custom_icon = get_post_meta($item->ID, '_menu_item_custom_icon', true);
            
            // Card content with image
            $item_output .= '<div class="flex flex-col h-full">';
            
            // Image section (priority: custom icon > featured image > Font Awesome icon > placeholder)
            $has_custom_visual = false;
            
            if ($icon_type === 'custom' && !empty($custom_icon)) {
                // Custom icon image
                $item_output .= '<div class="aspect-video mb-[2rem] overflow-hidden bg-gray-50 flex items-center justify-center p-8">';
                $item_output .= '<img src="' . esc_url($custom_icon) . '" alt="' . esc_attr($item->title) . '" class="max-w-full max-h-full object-contain">';
                $item_output .= '</div>';
                $has_custom_visual = true;
            } elseif ($featured_image) {
                // Featured image from post
                $item_output .= '<div class="aspect-video mb-[2rem] overflow-hidden bg-gray-100 group-hover/submenu:scale-90 transition-transform duration-300">';
                $item_output .= '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($item->title) . '" class="w-full h-full object-cover group-hover/submenu:scale-125 transition-transform duration-300">';
                $item_output .= '</div>';
                $has_custom_visual = true;
            } elseif ($icon_type === 'fontawesome' && !empty($fa_icon)) {
                // Font Awesome icon as main visual
                $item_output .= '<div class="aspect-video mb-[2rem] overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">';
                $item_output .= '<i class="' . esc_attr($fa_icon) . '" style="font-size: 48px; color: #131611;"></i>';
                $item_output .= '</div>';
                $has_custom_visual = true;
            }
            
            if (!$has_custom_visual) {
                // Fallback placeholder
                $item_output .= '<div class="aspect-video mb-[2rem] overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">';
                $item_output .= '<svg class="w-8 h-8 text-black-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                $item_output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>';
                $item_output .= '</svg>';
                $item_output .= '</div>';
            }
            
            // Content section met pijlen-animatie zoals productkaart
            $item_output .= '<div class="flex-1 flex-wrap">';
            $item_output .= '<div class="js-title-container overflow-hidden">';
            $item_output .= '<div class="span w-full items-center transition-transform duration-300 group-hover/submenu:-translate-x-[2.375rem]  ">';
            // $item_output .= '<svg class="mt-[0.25rem]" xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none" aria-hidden="true" focusable="false">';
            // $item_output .= '<path d="M18 7.88477V7.88574L10.1152 15.7705L8.53809 14.1934L13.8105 8.9209H0V6.69043H13.6514L8.53809 1.57715L10.1152 0L18 7.88477Z" fill="#E1322C"></path>';
            // $item_output .= '</svg>';
            $item_output .= '<h4 class="text-black transition-colors  gap-[0.625rem] duration-200 mb-1 lg:mb-0 flex items-baseline ml-[0.625rem] text-wrap with-arrows">'
                . '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>'
                . '</h4>';
            // $item_output .= '<svg class="mt-[0.25rem]" xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none" aria-hidden="true" focusable="false">';
            // $item_output .= '<path d="M18 7.88477V7.88574L10.1152 15.7705L8.53809 14.1934L13.8105 8.9209H0V6.69043H13.6514L8.53809 1.57715L10.1152 0L18 7.88477Z" fill="#E1322C"></path>';
            // $item_output .= '</svg>';
            $item_output .= '</div>';
            $item_output .= '</div>';
            // Inline script om breedte te zetten op (titelbreedte + 38px)
            // $item_output .= "<script>(function(){\n"
            //     . "  var root = document.currentScript ? document.currentScript.closest('a.dropdown-link') : null;\n"
            //     . "  if (!root) return;\n"
            //     . "  var container = root.querySelector('.js-title-container');\n"
            //     . "  var title = root.querySelector('h4');\n"
            //     . "  if (!container || !title) return;\n"
            //     . "  function updateWidth(){\n"
            //     . "    var w = Math.ceil(title.getBoundingClientRect().width) + 38 - 1;\n"
            //     . "    container.style.width = w + 'px';\n"
            //     . "  }\n"
            //     . "  updateWidth();\n"
            //     . "  window.addEventListener('resize', updateWidth, { passive: true });\n"
            //     . "  if (document.fonts && document.fonts.ready) { document.fonts.ready.then(updateWidth).catch(function(){}); }\n"
            //     . "})();</script>";
            
            if ($description) {
                $item_output .= '<p class="text-sm text-gray-600 line-clamp-2">' . esc_html($description) . '</p>';
            }
            
            $item_output .= '</div>';
            $item_output .= '</div>';
        }
        
        $item_output .= '</a>';
        
        // Add nav indicator for top level items (not dropdowns)
        if ($depth == 0 && !$has_children) {
            $item_output .= '<span class="nav-indicator absolute bottom-0 left-1/2 w-0 h-0.5 bg-blue-600 transition-all duration-300 transform -translate-x-1/2"></span>';
        }
        
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // End Element
    function end_el(&$output, $item, $depth = 0, $args = null) {
        if ($depth == 0) {
            $output .= "</li>\n";
        } else {
            $output .= "</div>\n";
        }
    }
}

/**
 * Mobile Tailwind CSS Nav Walker for Accordion-style Dropdowns
 */
class Advice2025_Mobile_Nav_Walker extends Walker_Nav_Menu {
    
    private $mobile_dropdown_item_count = 0;
    private bool $is_mobile_expertises   = false;

    /**
     * Sanitize expertise icon markup, allowing basic inline SVG.
     */
    private function sanitize_expertise_icon_markup( string $markup ): string {
        if ( $markup === '' ) {
            return '';
        }

        return wp_kses(
            $markup,
            array(
                'svg'    => array(
                    'xmlns'       => true,
                    'width'       => true,
                    'height'      => true,
                    'viewbox'     => true,
                    'viewBox'     => true,
                    'fill'        => true,
                    'stroke'      => true,
                    'aria-hidden' => true,
                    'focusable'   => true,
                    'role'        => true,
                    'class'       => true,
                ),
                'path'   => array(
                    'd'              => true,
                    'fill'           => true,
                    'stroke'         => true,
                    'stroke-width'   => true,
                    'stroke-linecap' => true,
                    'stroke-linejoin'=> true,
                    'class'          => true,
                ),
                'rect'   => array(
                    'x'            => true,
                    'y'            => true,
                    'width'        => true,
                    'height'       => true,
                    'rx'           => true,
                    'ry'           => true,
                    'fill'         => true,
                    'stroke'       => true,
                    'stroke-width' => true,
                    'class'        => true,
                ),
                'circle' => array(
                    'cx'           => true,
                    'cy'           => true,
                    'r'            => true,
                    'fill'         => true,
                    'stroke'       => true,
                    'stroke-width' => true,
                    'class'        => true,
                ),
                'g'      => array(
                    'fill'      => true,
                    'stroke'    => true,
                    'class'     => true,
                    'transform' => true,
                ),
            )
        );
    }

    /**
     * Ensure "show expertises" items are treated as having children so the
     * accordion toggle JS fires, while keeping the real child list empty.
     */
    public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ): void {
        if (
            $depth === 0
            && isset( $element->ID )
            && get_post_meta( (int) $element->ID, '_menu_item_show_expertises', true ) === '1'
        ) {
            if ( ! isset( $element->classes ) || ! is_array( $element->classes ) ) {
                $element->classes = array();
            }

            if ( ! in_array( 'menu-item-has-children', $element->classes, true ) ) {
                $element->classes[] = 'menu-item-has-children';
            }

            // Clear any manually attached children so the walker doesn't render them.
            $children_elements[ (int) $element->ID ] = array();
        }

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    /**
     * Render expertise items as a mobile accordion dropdown.
     */
    private function render_mobile_expertises_dropdown( int $item_id, string $indent ): string {
        $expertises = get_terms(
            array(
                'taxonomy'   => 'expertise',
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            )
        );

        $dropdown_id_attr = 'mobile-menu-item-' . $item_id;

        $out  = "\n$indent<div class=\"mobile-dropdown overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0\">\n";
        $out .= "$indent\t<div class=\"pt-2 pb-4\">\n";
        $out .= "$indent\t\t<ul class=\"space-y-3 max-h-[70vh] list-none! overflow-y-auto overscroll-contain\" data-mobile-dropdown-list data-mobile-dropdown-id=\"{$dropdown_id_attr}\">\n";

        if ( is_wp_error( $expertises ) || empty( $expertises ) ) {
            $out .= "$indent\t\t\t<li><span class=\"block px-[1.25rem] py-[1.25rem] text-[#161616]\">Er zijn momenteel geen expertises beschikbaar.</span></li>\n";
        } else {
            $visible = array_slice( $expertises, 0, 7 );

            foreach ( $visible as $expertise ) {
                $term_link = get_term_link( $expertise );
                if ( is_wp_error( $term_link ) ) {
                    continue;
                }

                $icon  = get_field( 'icoon', 'expertise_' . $expertise->term_id );
                $title = $expertise->name ?? '';

                $out .= "$indent\t\t\t<li class=\"list-none!\">\n";
                $out .= "$indent\t\t\t\t<a href=\"" . esc_url( $term_link ) . "\" class=\"mobile-dropdown-link mobile-dropdown-item relative block border border-[rgba(22,22,22,0.12)] bg-white px-[1.75rem] py-[1.75rem] transition-colors duration-300 hover:bg-[#FAFAFA] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EC663C] focus-visible:ring-offset-2\">\n";
                if ( ! empty( $icon ) ) {
                    $out .= "$indent\t\t\t\t\t<span class=\"\">" . $this->sanitize_expertise_icon_markup( (string) $icon ) . "</span>\n";
                }
                $out .= "$indent\t\t\t\t\t<span class=\"absolute right-[1.5rem] top-1/2 -translate-y-1/2\" aria-hidden=\"true\">\n";
                $out .= "$indent\t\t\t\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"12\" height=\"20\" viewBox=\"0 0 12 20\" fill=\"none\">\n";
                $out .= "$indent\t\t\t\t\t\t\t<rect width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
                $out .= "$indent\t\t\t\t\t\t\t<rect x=\"5.92578\" y=\"11.8521\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
                $out .= "$indent\t\t\t\t\t\t\t<rect x=\"2.96094\" y=\"14.8149\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
                $out .= "$indent\t\t\t\t\t\t\t<rect y=\"17.7778\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
                $out .= "$indent\t\t\t\t\t\t\t<rect x=\"2.96094\" y=\"2.96289\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
                $out .= "$indent\t\t\t\t\t\t\t<rect x=\"8.89062\" y=\"8.88916\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
                $out .= "$indent\t\t\t\t\t\t\t<rect x=\"5.92578\" y=\"5.92578\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
                $out .= "$indent\t\t\t\t\t\t</svg>\n";
                $out .= "$indent\t\t\t\t\t</span>\n";
                $out .= "$indent\t\t\t\t\t<span class=\"flex title-large mt-[1.25rem]\">" . esc_html( $title ) . "</span>\n";
                $out .= "$indent\t\t\t\t</a>\n";
                $out .= "$indent\t\t\t</li>\n";
            }

            // "Alle expertises" link
            $all_link = home_url( '/expertises' );
            $out .= "$indent\t\t\t<li class=\"list-none!\">\n";
            $out .= "$indent\t\t\t\t<a href=\"" . esc_url( $all_link ) . "\" class=\"mobile-dropdown-link mobile-dropdown-item relative block min-h-[7.5rem] border border-[rgba(22,22,22,0.12)] bg-white px-[1.5rem] py-[1.25rem] transition-colors duration-300 hover:bg-[#FAFAFA] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EC663C] focus-visible:ring-offset-2\">\n";
            $out .= "$indent\t\t\t\t\t<span class=\"absolute right-[1.5rem] top-1/2 -translate-y-1/2\" aria-hidden=\"true\">\n";
            $out .= "$indent\t\t\t\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"12\" height=\"20\" viewBox=\"0 0 12 20\" fill=\"none\">\n";
            $out .= "$indent\t\t\t\t\t\t\t<rect width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
            $out .= "$indent\t\t\t\t\t\t\t<rect x=\"5.92578\" y=\"11.8521\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
            $out .= "$indent\t\t\t\t\t\t\t<rect x=\"2.96094\" y=\"14.8149\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
            $out .= "$indent\t\t\t\t\t\t\t<rect y=\"17.7778\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
            $out .= "$indent\t\t\t\t\t\t\t<rect x=\"2.96094\" y=\"2.96289\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
            $out .= "$indent\t\t\t\t\t\t\t<rect x=\"8.89062\" y=\"8.88916\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
            $out .= "$indent\t\t\t\t\t\t\t<rect x=\"5.92578\" y=\"5.92578\" width=\"2.22222\" height=\"2.22222\" fill=\"#EC663C\"/>\n";
            $out .= "$indent\t\t\t\t\t\t</svg>\n";
            $out .= "$indent\t\t\t\t\t</span>\n";
            $out .= "$indent\t\t\t\t\t<span class=\"flex min-h-[5rem] items-end pr-10 text-[1.5rem] font-light leading-[1.2] text-[#161616]\" style=\"font-family: 'Fira Sans', sans-serif;\">Alle expertises</span>\n";
            $out .= "$indent\t\t\t\t</a>\n";
            $out .= "$indent\t\t\t</li>\n";
        }

        $out .= "$indent\t\t</ul>\n";
        $out .= "$indent\t</div>\n";
        $out .= "$indent</div>\n";

        return $out;
    }

    // Start Level - Add accordion container for mobile
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);

        if ($depth == 0 && $this->is_mobile_expertises) {
            // Expertise dropdown already rendered inline in start_el(); suppress walker output.
            return;
        }
        
        if ($depth == 0) {
			// Reset item count for this dropdown
			$this->mobile_dropdown_item_count = 0;
			// Mobile accordion dropdown container
			$output .= "\n$indent<div class=\"mobile-dropdown overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0\">\n";
			$output .= "$indent\t<div class=\"pt-2 pb-4\">\n";
			// Attach identifier to the list for JS/CSS targeting
			global $current_mobile_dropdown_id;
			$dropdown_id_attr = isset($current_mobile_dropdown_id) ? " data-mobile-dropdown-id=\"mobile-menu-item-{$current_mobile_dropdown_id}\"" : '';
			$output .= "$indent\t\t<ul class=\"space-y-3 max-h-[70vh] overflow-y-auto overscroll-contain\" data-mobile-dropdown-list$dropdown_id_attr>\n";
        } else {
            $output .= "\n$indent<ul class=\"mobile-submenu pl-4 mt-2 space-y-2\">\n";
        }
    }

    // End Level
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);

        if ($depth == 0 && $this->is_mobile_expertises) {
            // Reset flag and suppress walker output — already handled by start_el.
            $this->is_mobile_expertises = false;
            return;
        }
        
        if ($depth == 0) {
            $output .= "$indent\t\t</ul>\n";
            $output .= "$indent\t</div>\n";
			$output .= "$indent</div>\n";
			// Switch layout based on item count (<=4 => large image cards)
			global $current_mobile_dropdown_id;
			$dropdown_id = isset($current_mobile_dropdown_id) ? $current_mobile_dropdown_id : '';
			$output .= "<script>(function(){\n";
			$output .= "  var list = document.querySelector('[data-mobile-dropdown-list][data-mobile-dropdown-id=\"mobile-menu-item-{$dropdown_id}\"]');\n";
			$output .= "  if (!list) return;\n";
			$output .= "  var count = {$this->mobile_dropdown_item_count} || 0;\n";
			$output .= "  list.setAttribute('data-item-count', String(count));\n";
			$output .= "  if (count > 0 && count <= 4) { list.classList.add('layout-large'); } else { list.classList.remove('layout-large'); }\n";
			$output .= "})();</script>\n";
			// Scoped CSS for that specific dropdown id
			$output .= "<style>[data-mobile-dropdown-list][data-mobile-dropdown-id=\"mobile-menu-item-{$dropdown_id}\"].layout-large .mobile-card{flex-direction:column;gap:0.75rem}[data-mobile-dropdown-list][data-mobile-dropdown-id=\"mobile-menu-item-{$dropdown_id}\"].layout-large .mobile-thumb{width:100%;height:auto;aspect-ratio:16/9;border-radius:0.5rem}[data-mobile-dropdown-list][data-mobile-dropdown-id=\"mobile-menu-item-{$dropdown_id}\"].layout-large .mobile-content h3{font-size:1rem}</style>\n";
        } else {
            $output .= "$indent</ul>\n";
        }
    }

    // Start Element
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'mb-0! menu-item-' . $item->ID;

        // Mark archive menu item as active on single case posts
        if (is_singular('case') && !empty($item->url)) {
            $case_archive_url = get_post_type_archive_link('case');
            if ($case_archive_url) {
                $item_url = untrailingslashit($item->url);
                $archive_url = untrailingslashit($case_archive_url);
                
                if ($item_url === $archive_url) {
                    $classes[] = 'current-menu-item';
                    $classes[] = 'current_page_parent';
                }
            }
        }

        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);

        // Check if this item should auto-generate an expertises dropdown
        $show_expertises = false;
        if ($depth === 0) {
            $show_expertises = get_post_meta($item->ID, '_menu_item_show_expertises', true) === '1';
            if ($show_expertises) {
                $has_children = true;
                $this->is_mobile_expertises = true;
            }
        }
        
        // Apply filters
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        // Add mobile-specific classes
        if ($has_children && $depth == 0) {
            $class_names .= ' mobile-has-dropdown';
        }
        
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        // Add data attributes for mobile accordion functionality
        $data_attrs = '';
        if ($has_children && $depth == 0) {
			$data_attrs = ' data-mobile-dropdown="true" data-mobile-dropdown-id="mobile-menu-item-' . $item->ID . '"';
			// Store current dropdown id so start_lvl can add it to UL
			global $current_mobile_dropdown_id;
			$current_mobile_dropdown_id = $item->ID;
        }

        $output .= $indent . '<li' . $id . $class_names . $data_attrs .'>';

        // Determine if this item is current/active
        $is_current = false;
        foreach ($classes as $menu_class) {
            if (strpos($menu_class, 'current-') === 0) {
                $is_current = true;
                break;
            }
        }

        $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        
        // Different styles for different depths
        if ($depth == 0) {
            // Top level items - mobile style
            // First item has border-t
            $link_classes = 'mobile-nav-link block border-b border-[rgba(22,22,22,0.12)] py-[1.5rem] text-black transition-all duration-300 flex items-center justify-between';
            if ($depth == 0 && $item->menu_order == 1) {
                $link_classes .= ' border-t mt-[3.75rem]';
            }
            if ($has_children) {
                $link_classes .= ' cursor-pointer';
            }
		} else {
			// Count items and add a hook class for styling
			$this->mobile_dropdown_item_count++;
			// Dropdown items - mobile card style
			$link_classes = 'mobile-dropdown-link mobile-dropdown-item block  py-[1.75rem] border-b border-[rgba(22,22,22,0.12)] transition-all duration-300 hover:bg-light-blue/25';
        }

        // Add active class to the link if this item is current
        if ($is_current) {
            $link_classes .= ' is-active text-red';
        }

        $item_output .= '<a' . $attributes . ' class="' . $link_classes . '">';
        
        // Different content for different depths
        if ($depth == 0) {
            // Top level items
            $item_output .= (isset($args->link_before) ? $args->link_before : '') . '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>' . (isset($args->link_after) ? $args->link_after : '');
            
            // Add mobile dropdown arrow for parent items
            if ($has_children) {
                $item_output .= '<svg class="transition-transform duration-200 mobile-dropdown-arrow" xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="none">
<path d="M1.06055 -4.6358e-08L5.30371 4.24219L1.06055 8.48437L-4.6358e-08 7.42383L3.18164 4.24219L-3.24506e-07 1.06055L1.06055 -4.6358e-08Z" fill="#96ACC0"/>
</svg>';
            }
		} else {
			// Dropdown items with mobile-friendly layout
			$featured_image = get_the_post_thumbnail_url($item->object_id, 'medium');
            $description = get_post_meta($item->ID, '_menu_item_description', true);
            
            // Get icon settings
            $icon_type = get_post_meta($item->ID, '_menu_item_icon_type', true);
            $fa_icon = get_post_meta($item->ID, '_menu_item_fa_icon', true);
            $custom_icon = get_post_meta($item->ID, '_menu_item_custom_icon', true);
            
            // Mobile card content
			$item_output .= '<div class="flex items-center gap-3 mobile-card">';
            
            // Icon section (optional) - show icon next to text
            if ($icon_type === 'fontawesome' && !empty($fa_icon)) {
                $item_output .= '<span class="menu-item-icon flex-shrink-0"><i class="' . esc_attr($fa_icon) . '" style="font-size: 20px; color: #96ACC0;"></i></span>';
            } elseif ($icon_type === 'custom' && !empty($custom_icon)) {
                $item_output .= '<span class="menu-item-icon flex-shrink-0"><img src="' . esc_url($custom_icon) . '" alt="" style="width: 24px; height: 24px; object-fit: contain;" /></span>';
            }
            
            // Content section
			$item_output .= '<div class="mobile-content flex-1 min-w-0">';
            $item_output .= '<span class="text-black mb-1 flex items-center justify-between gap-2">'
                . '<span class="">' . apply_filters('the_title', $item->title, $item->ID) . '</span>'
                . '</span>';
            
            if ($description) {
                $item_output .= '<p class="text-xs text-gray-600 line-clamp-2">' . esc_html($description) . '</p>';
            }
            
            $item_output .= '</div>';
            $item_output .= '</div>';
        }
        
        $item_output .= '</a>';

        // Inject expertises accordion immediately after the link
        if ($depth === 0 && $show_expertises) {
            $item_output .= $this->render_mobile_expertises_dropdown((int) $item->ID, $indent . "\t");
        }
        
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // End Element
    function end_el(&$output, $item, $depth = 0, $args = null) {
        // Always reset expertise flag after each top-level item, regardless of whether
        // start_lvl/end_lvl were called (they are skipped when children array is empty).
        if ($depth === 0) {
            $this->is_mobile_expertises = false;
        }
        $output .= "</li>\n";
    }
}