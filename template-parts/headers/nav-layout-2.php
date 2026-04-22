<?php
/**
 * Navigation Layout 2
 * Logo + Menu links + Contact Button rechts
 */

$container_width = get_theme_mod('header_container_width', 'full-width');
$container_class = $container_width === 'contained' ? 'container mx-auto' : '';
?>

<div class="navigation-wrapper bg-white">
    <div class="<?php echo esc_attr($container_class); ?> px-10 py-5">
        <div class="flex items-center justify-between gap-10">
            <!-- Logo + Navigation -->
            <div class="flex items-center gap-10 flex-1">
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
                <nav class="main-navigation hidden xl:flex items-center gap-10">
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
            </div>

            <!-- Contact Button -->
            <div class="flex items-center gap-4">
                <?php 
                $contact_button = get_field('header_contact_button', 'option');
                $contact_text = $contact_button && isset($contact_button['title']) ? $contact_button['title'] : 'Contact';
                $contact_url = $contact_button && isset($contact_button['url']) ? $contact_button['url'] : '/contact';
                ?>
                <a href="<?php echo esc_url($contact_url); ?>" class="px-5 py-4 bg-[#FF5822] text-[#131611] rounded font-medium hover:opacity-90 transition-opacity whitespace-nowrap">
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
