<?php
/**
 * Navigation Layout 5 - Floating
 * Floating menubalk binnen container met 40px ruimte bovenaan
 * Logo links + Menu items midden + Buttons rechts
 * Gebaseerd op Figma: https://www.figma.com/design/EnnctIvaE527aCXNcA9xjX/Standaard-website?node-id=2099-2
 */

$container_width = get_theme_mod('header_container_width', 'full-width');
?>

<div class="navigation-wrapper-floating pt-10">
    <div class="container mx-auto px-10">
        <div class="navigation-floating-inner bg-white rounded-[1.25rem] shadow-md px-10 py-5 border border-gray-100">
            <div class="flex items-center justify-between gap-10">
                <!-- Logo -->
                <div class="site-branding flex-shrink-0">
                    <?php if (has_custom_logo()) : ?>
                        <div class="site-logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                                <?php 
                                $custom_logo_id = get_theme_mod('custom_logo');
                                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                                if ($logo) {
                                    echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="h-[2.375rem] w-auto object-contain">';
                                }
                                ?>
                            </a>
                        </div>
                    <?php else : ?>
                        <h1 class="text-2xl font-bold">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-[#131611]">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

                <!-- Main Navigation -->
                <nav class="main-navigation hidden xl:flex items-center gap-10 flex-1 justify-center">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'flex items-center gap-10',
                        'fallback_cb'    => false,
                        'walker'         => new Advice2025_Simple_Nav_Walker(),
                        'depth'          => 3,
                    ));
                    ?>
                </nav>

                <!-- Buttons Group -->
                <div class="flex items-center justify-end gap-4 flex-shrink-0">
                    <!-- Search Button -->
                    <div class="relative">
                        <button id="header-search-toggle" class="w-[3.375rem] h-[3.375rem] flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded transition-colors" aria-label="Zoeken" aria-expanded="false">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- Search Bar (expandable) -->
                        <div id="header-search-bar" class="hidden absolute right-full top-0 mr-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50 transition-all duration-300" style="width: 0; opacity: 0;">
                            <div class="w-[18.75rem] p-2">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Secondary Button -->
                    <?php 
                    $secondary_button = get_field('header_secondary_button', 'option');
                    $secondary_text = $secondary_button && isset($secondary_button['title']) ? $secondary_button['title'] : 'Secondary button';
                    $secondary_url = $secondary_button && isset($secondary_button['url']) ? $secondary_button['url'] : '#';
                    ?>
                    <a href="<?php echo esc_url($secondary_url); ?>" class="hidden xl:inline-flex items-center justify-center px-5 py-4 bg-gray-200 text-[#131611] rounded font-medium hover:bg-gray-300 transition-colors whitespace-nowrap">
                        <?php echo esc_html($secondary_text); ?>
                    </a>

                    <!-- Contact Button -->
                    <?php 
                    $contact_button = get_field('header_contact_button', 'option');
                    $contact_text = $contact_button && isset($contact_button['title']) ? $contact_button['title'] : 'Contact';
                    $contact_url = $contact_button && isset($contact_button['url']) ? $contact_button['url'] : '/contact';
                    ?>
                    <a href="<?php echo esc_url($contact_url); ?>" class="hidden xl:inline-flex items-center justify-center px-5 py-4 bg-[#FF5822] text-[#131611] rounded font-medium hover:opacity-90 transition-opacity whitespace-nowrap">
                        <?php echo esc_html($contact_text); ?>
                    </a>

                    <!-- Mobile Menu Button -->
                    <div class="xl:hidden">
                        <button id="mobile-menu-button" class="relative py-3 px-4 border border-[#131611] text-[#131611] hover:bg-gray-100 transition-colors rounded" aria-expanded="false">
                            <span class="sr-only">Menu openen</span>
                            <div class="flex items-center gap-2">
                                <div class="hamburger-lines">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                        <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
                                        <rect x="8.25" y="9.25" width="9.5" height="0.5" fill="currentColor"/>
                                        <rect x="8.25" y="12.5074" width="9.5" height="0.5" fill="currentColor"/>
                                        <rect x="8.25" y="15.7647" width="9.5" height="0.5" fill="currentColor"/>
                                    </svg>
                                </div>
                                <span class="mobile-menu-label font-semibold">Menu</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Floating navigation specific styles - Based on Figma design */
.navigation-wrapper-floating {
    padding-top: 40px;
}

.navigation-floating-inner {
    /* Subtle shadow matching Figma */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

/* Menu items in floating nav have dark text */
.navigation-wrapper-floating .main-navigation a {
    color: #131611 !important;
}

.navigation-wrapper-floating .main-navigation a:hover {
    opacity: 0.7;
}

/* Active menu items */
.navigation-wrapper-floating .main-navigation a.is-active,
.navigation-wrapper-floating .main-navigation .current-menu-item > a {
    color: #131611 !important;
    font-weight: 600;
}

/* Dropdowns positioned correctly below floating container */
.navigation-wrapper-floating .main-navigation li.group {
    position: relative;
}

.navigation-wrapper-floating .dropdown-menu,
.navigation-wrapper-floating .mega-menu {
    top: calc(100% + 0.75rem);
    border-radius: 12px;
}

/* Sticky floating nav on scroll */
@media (min-width: 1024px) {
    .header-floating-sticky {
        position: sticky;
        top: 0;
        z-index: 100;
        background: transparent;
        padding-top: 40px;
        transition: padding-top 0.3s ease;
    }
    
    .header-floating-sticky.is-scrolled {
        padding-top: 20px;
    }
    
    .header-floating-sticky.is-scrolled .navigation-floating-inner {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
}
</style>

