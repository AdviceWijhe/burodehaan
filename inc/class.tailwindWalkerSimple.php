<?php
declare(strict_types=1);

/**
 * Simple TailwindCSS 4 Navigation Walker with Dropdown Support
 * Based on Figma design: https://www.figma.com/design/ud6IaFO7RHbLZvOpfO0mC1/Management-Secrets-website?node-id=2449-12040&m=dev
 * 
 * Features:
 * - White text for normal menu items
 * - Light blue (#96ACC0) text and underline for active items
 * - Poppins Medium font, 15px, line-height 1.8
 * - Simple dropdown support with hover states
 */
class Advice2025_Simple_Nav_Walker extends Walker_Nav_Menu {
    
    private $is_mega_menu = false;
    private $mega_menu_cta_text = '';
    private $mega_menu_cta_url = '';
    
    /**
     * Start Level - Output dropdown container or submenu
     */
    public function start_lvl(&$output, $depth = 0, $args = null): void {
        $indent = str_repeat("\t", $depth);
        
        if ($depth == 0) {
            // Check if this is a mega menu
            if ($this->is_mega_menu) {
                // Mega Menu - Full width with 100% screen width (no padding-top to prevent gap)
                // Remove transition-all to prevent unwanted transforms
                $output .= "\n$indent<div class=\"dropdown-menu mega-menu fixed left-0 top-full w-screen bg-white shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 z-50 hover:opacity-100 hover:visible hover:delay-0\" style=\"transform: translateY(0);\">\n";
                $output .= "$indent\t<div class=\"container mx-auto px-10 py-10\">\n";
                $output .= "$indent\t\t<div class=\"flex gap-8\">\n";
                $output .= "$indent\t\t\t<div class=\"flex-1\">\n";
                $output .= "$indent\t\t\t\t<ul class=\"grid grid-cols-3 gap-6\">\n";
            } else {
                // Regular dropdown menu (pt-2 adds small gap for regular dropdowns)
                $output .= "\n$indent<ul class=\"absolute left-0 top-full pt-2 min-w-[200px] bg-white shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 transition-visibility delay-200 z-50 py-2 hover:opacity-100 hover:visible hover:delay-0\">\n";
            }
        } else {
            // Nested submenu
            $output .= "\n$indent<ul class=\"absolute left-full top-0 ml-2 min-w-[200px] bg-white shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 transition-visibility delay-200 z-50 py-2 hover:opacity-100 hover:visible hover:delay-0\">\n";
        }
    }

    /**
     * End Level - Output closing </ul> tag
     */
    public function end_lvl(&$output, $depth = 0, $args = null): void {
        $indent = str_repeat("\t", $depth);
        
        if ($depth == 0 && $this->is_mega_menu) {
            // Close mega menu structure
            $output .= "$indent\t\t\t\t</ul>\n";
            $output .= "$indent\t\t\t</div>\n";
            
            // Add CTA section if text and URL are provided
            if (!empty($this->mega_menu_cta_text) && !empty($this->mega_menu_cta_url)) {
                $output .= "$indent\t\t\t<div class=\"w-[300px] flex-shrink-0\">\n";
                $output .= "$indent\t\t\t\t<div class=\"bg-gray-50 rounded-lg p-6 h-full flex flex-col justify-center items-start\">\n";
                $output .= "$indent\t\t\t\t\t<h3 class=\"text-xl font-semibold text-[#131611] mb-4\">Ontdek meer</h3>\n";
                $output .= "$indent\t\t\t\t\t<a href=\"" . esc_url($this->mega_menu_cta_url) . "\" class=\"inline-flex items-center gap-2 px-6 py-3 bg-[#FF5822] text-[#131611] rounded font-medium hover:opacity-90 transition-opacity\">\n";
                $output .= "$indent\t\t\t\t\t\t<span>" . esc_html($this->mega_menu_cta_text) . "</span>\n";
                $output .= "$indent\t\t\t\t\t\t<svg width=\"16\" height=\"16\" viewBox=\"0 0 16 16\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n";
                $output .= "$indent\t\t\t\t\t\t\t<path d=\"M3 8H13M13 8L8 3M13 8L8 13\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>\n";
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
            $output .= "$indent</ul>\n";
        }
    }

    /**
     * Start Element - Output opening <li> and <a> tags
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Manually add current-menu-item class for archive pages
        if (is_post_type_archive() && !empty($item->url)) {
            $current_url = home_url($_SERVER['REQUEST_URI']);
            $item_url = trailingslashit($item->url);
            $current_url_normalized = trailingslashit($current_url);
            
            if ($item_url === $current_url_normalized) {
                $classes[] = 'current-menu-item';
            }
        }
        
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
        
        // Add dropdown classes for top-level items with children
        if ($has_children && $depth == 0) {
            $class_names .= ' relative group';
            if ($enable_mega_menu) {
                $class_names .= ' has-mega-menu';
            } else {
                $class_names .= ' has-dropdown';
            }
        }
        
        // Add relative positioning for dropdown items with nested children
        if ($has_children && $depth > 0) {
            $class_names .= ' relative group';
        }
        
        // Add min-height for top-level items to match logo height
        if ($depth == 0) {
            $class_names .= ' min-h-[80px] flex items-center';
        }
        
        $class_names = $class_names ? ' class="' . esc_attr(trim($class_names)) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        // Determine if this item is current/active
        $is_current = false;
        foreach ($classes as $menu_class) {
            if (strpos($menu_class, 'current-') === 0) {
                $is_current = true;
                break;
            }
        }

        // Build link attributes
        $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        
        // Different styling for top-level vs dropdown items
        if ($depth == 0) {
            // Top level items - matching Figma design
            // Font: Poppins Medium (font-medium), 15px (text-[15px]), line-height 1.8 (leading-[1.8])
            $link_classes = 'relative px-4 py-2 text-[15px] leading-[1.8] font-medium transition-colors duration-300 flex items-center gap-1';
            
            // Active state: light blue text (#96ACC0) and underline
            if ($is_current) {
                $link_classes .= ' text-light-blue';
            } else {
                $link_classes .= ' text-white';
            }
        } else {
            // Dropdown items - check if in mega menu
            if ($this->is_mega_menu && $depth == 1) {
                // Mega menu items - card style
                $link_classes = 'block p-4 rounded-lg transition-all duration-300 hover:bg-gray-50 group/mega-item';
            } else {
                // Regular dropdown items
                $link_classes = 'block px-4 py-2 text-[15px] leading-[1.8] font-medium transition-colors duration-300';
            }
            
            if ($is_current) {
                $link_classes .= ' text-light-blue bg-light-blue/10';
            } else {
                $link_classes .= ' text-gray-800 hover:text-light-blue';
            }
        }

        $item_output .= '<a' . $attributes . ' class="' . $link_classes . '">';
        
        // Get icon settings
        $icon_type = get_post_meta($item->ID, '_menu_item_icon_type', true);
        $fa_icon = get_post_meta($item->ID, '_menu_item_fa_icon', true);
        $custom_icon = get_post_meta($item->ID, '_menu_item_custom_icon', true);
        
        // Add icon if set (only for dropdown items)
        if ($depth > 0 && $icon_type && $icon_type !== 'none') {
            if ($icon_type === 'fontawesome' && !empty($fa_icon)) {
                $item_output .= '<span class="menu-item-icon mr-3"><i class="' . esc_attr($fa_icon) . '" style="font-size: 18px;"></i></span>';
            } elseif ($icon_type === 'custom' && !empty($custom_icon)) {
                $item_output .= '<span class="menu-item-icon mr-3"><img src="' . esc_url($custom_icon) . '" alt="" style="width: 24px; height: 24px; object-fit: contain;" /></span>';
            }
        }
        
        // Menu item title
        $item_output .= (isset($args->link_before) ? $args->link_before : '') 
            . '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>' 
            . (isset($args->link_after) ? $args->link_after : '');
        
        // Add dropdown arrow for items with children (top level only)
        if ($has_children && $depth == 0) {
            $item_output .= '<svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * End Element - Output closing </li> tag
     */
    public function end_el(&$output, $item, $depth = 0, $args = null): void {
        $output .= "</li>\n";
    }
}

