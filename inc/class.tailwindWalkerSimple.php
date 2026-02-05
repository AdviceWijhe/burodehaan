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
    
    /**
     * Start Level - Output dropdown container or submenu
     */
    public function start_lvl(&$output, $depth = 0, $args = null): void {
        $indent = str_repeat("\t", $depth);
        
        if ($depth == 0) {
            // Top-level dropdown menu
            // Uses group-hover on parent <li> to show/hide dropdown (scoped to that li)
            // Small delay on hiding (200ms) to prevent accidental close
            $output .= "\n$indent<ul class=\"absolute left-0 top-full pt-2 min-w-[200px] bg-white  shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 transition-visibility delay-200 z-50 py-2 hover:opacity-100 hover:visible hover:delay-0\">\n";
        } else {
            // Nested submenu
            $output .= "\n$indent<ul class=\"absolute left-full top-0 ml-2 min-w-[200px] bg-white  shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 transition-visibility delay-200 z-50 py-2 hover:opacity-100 hover:visible hover:delay-0\">\n";
        }
    }

    /**
     * End Level - Output closing </ul> tag
     */
    public function end_lvl(&$output, $depth = 0, $args = null): void {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
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

        // Apply filters
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        // Add dropdown classes for top-level items with children
        if ($has_children && $depth == 0) {
            $class_names .= ' relative group';
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
            // Dropdown items - simpler styling
            $link_classes = 'block px-4 py-2 text-[15px] leading-[1.8] font-medium transition-colors duration-300';
            
            if ($is_current) {
                $link_classes .= ' text-light-blue bg-light-blue/10';
            } else {
                $link_classes .= ' text-gray-800 hover:text-light-blue hover:bg-light-blue/5';
            }
        }

        $item_output .= '<a' . $attributes . ' class="' . $link_classes . '">';
        
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

