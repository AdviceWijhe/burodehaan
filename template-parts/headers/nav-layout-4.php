<?php
/**
 * Navigation Layout 4
 * Logo links + Menu gecentreerd + Buttons rechts (alles op één rij)
 */

$container_width = get_theme_mod('header_container_width', 'full-width');
$container_class = $container_width === 'contained' ? 'container mx-auto' : '';
?>

<div class="navigation-wrapper bg-white">
    <div class="<?php echo esc_attr($container_class); ?> px-10 py-5">
        <div class="grid grid-cols-3 items-center gap-8">
            <!-- Left: Logo -->
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                            <?php 
                            $custom_logo_id = get_theme_mod('custom_logo');
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            if ($logo) {
                                echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="h-[38px] w-auto object-contain">';
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

            <!-- Center: Navigation -->
            <nav class="main-navigation hidden xl:flex items-center justify-center gap-10">
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

            <!-- Right: Buttons -->
            <div class="flex items-center justify-end gap-4">
                <!-- Search Button -->
                <div class="relative">
                    <button id="header-search-toggle" class="w-[54px] h-[54px] flex items-center justify-center border border-[#131611] rounded hover:bg-gray-100 transition-colors" aria-label="Zoeken" aria-expanded="false">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    
                    <!-- Search Bar (expandable) -->
                    <div id="header-search-bar" class="hidden absolute right-full top-0 mr-2 bg-white border border-[#131611] rounded shadow-lg z-50 transition-all duration-300" style="width: 0; opacity: 0;">
                        <div class="w-[300px] p-2">
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
                <a href="<?php echo esc_url($secondary_url); ?>" class="hidden xl:inline-block px-5 py-4 bg-[rgba(19,22,17,0.4)] backdrop-blur-[7px] text-white rounded font-medium hover:opacity-80 transition-opacity whitespace-nowrap">
                    <?php echo esc_html($secondary_text); ?>
                </a>

                <!-- Contact Button -->
                <?php 
                $contact_button = get_field('header_contact_button', 'option');
                $contact_text = $contact_button && isset($contact_button['title']) ? $contact_button['title'] : 'Contact';
                $contact_url = $contact_button && isset($contact_button['url']) ? $contact_button['url'] : '/contact';
                ?>
                <a href="<?php echo esc_url($contact_url); ?>" class="hidden xl:inline-block px-5 py-4 bg-[#FF5822] text-[#131611] rounded font-medium hover:opacity-90 transition-opacity whitespace-nowrap">
                    <?php echo esc_html($contact_text); ?>
                </a>

                <!-- Mobile Menu Button -->
                <div class="xl:hidden">
                    <button id="mobile-menu-button" class="relative py-3 px-4 border border-[#131611] text-[#131611] hover:bg-gray-100 transition-colors" aria-expanded="false">
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
