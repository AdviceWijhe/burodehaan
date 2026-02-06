<?php
/**
 * Topbar Template
 * Displays different topbar styles based on customizer settings
 */

// Get customizer settings
$topbar_style = get_theme_mod('header_topbar_style', 'voordelen');
$container_width = get_theme_mod('header_container_width', 'full-width');
$container_class = $container_width === 'contained' ? 'container mx-auto' : '';
?>

<div class="topbar bg-[rgba(216,214,212,0.4)] py-3">
    <div class="<?php echo esc_attr($container_class); ?> px-10">
        <?php if ($topbar_style === 'voordelen') : ?>
            <!-- Topbar Style: Voordelen (Layout 1) -->
            <div class="flex items-center justify-between">
                <!-- Left: Voordelen -->
                <div class="flex items-center gap-8">
                    <?php 
                    // Get voordelen from ACF options or use defaults
                    $voordelen = get_field('topbar_voordelen', 'option');
                    if (!$voordelen || empty($voordelen)) {
                        $voordelen = array(
                            array('tekst' => 'Voordeel'),
                            array('tekst' => 'Voordeel'),
                        );
                    }
                    
                    foreach ($voordelen as $voordeel) :
                        if (!empty($voordeel['tekst'])) :
                    ?>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="12" viewBox="0 0 16 12" fill="none" class="flex-shrink-0">
                                <path d="M5.6001 11.8001L0.200098 6.4001L1.6001 5.0001L5.6001 9.0001L14.4001 0.200098L15.8001 1.6001L5.6001 11.8001Z" fill="#131611"/>
                            </svg>
                            <p class="text-sm leading-[1.6] font-['PP_Neue_Montreal'] text-[#131611]">
                                <?php echo esc_html($voordeel['tekst']); ?>
                            </p>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>

                <!-- Right: Menu Items -->
                <div class="flex items-center gap-8 text-sm font-medium leading-[1.4] text-[#131611]">
                    <?php
                    // Get topbar menu items from ACF or use WordPress menu
                    if (has_nav_menu('topbar-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'topbar-menu',
                            'container'      => false,
                            'menu_class'     => 'flex items-center gap-8',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ));
                    } else {
                        // Fallback menu items
                        ?>
                        <a href="#" class="hover:opacity-70 transition-opacity">Menu item</a>
                        <a href="#" class="hover:opacity-70 transition-opacity">Menu item</a>
                        <?php
                    }
                    ?>
                </div>
            </div>

        <?php elseif ($topbar_style === 'contact-info') : ?>
            <!-- Topbar Style: Contact Info (Layout 2) -->
            <div class="flex items-center justify-between">
                <!-- Left: Contact Information -->
                <div class="flex items-center gap-10">
                    <?php 
                    $phone = get_field('topbar_phone', 'option');
                    $email = get_field('topbar_email', 'option');
                    
                    if (!$phone) $phone = '+31 (0)123 456 789';
                    if (!$email) $email = 'info@advice.nl';
                    ?>
                    
                    <!-- Phone -->
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" class="flex-shrink-0">
                            <path d="M15.0399 11.2798C14.0599 11.2798 13.1066 11.1331 12.2132 10.8531C11.7999 10.7198 11.2932 10.8398 11.0132 11.1198L9.38656 12.4131C7.4799 11.4131 4.61323 8.57313 3.5999 6.63979L4.87323 4.9598C5.19323 4.63979 5.2999 4.17313 5.17323 3.7598C4.89323 2.87979 4.74656 1.91979 4.74656 0.93979C4.74656 0.419792 4.32656 -0.000205994 3.80656 -0.000205994H0.959896C0.439896 -0.000205994 -0.000103951 0.419792 -0.000103951 0.93979C-0.000103951 9.3598 6.66656 15.9998 15.0399 15.9998C15.5599 15.9998 15.9799 15.5798 15.9799 15.0598V12.2198C15.9799 11.6998 15.5599 11.2798 15.0399 11.2798Z" fill="#131611"/>
                        </svg>
                        <a href="tel:<?php echo esc_attr(str_replace(array(' ', '(', ')'), '', $phone)); ?>" class="text-sm font-medium leading-[1.4] text-[#131611] hover:opacity-70 transition-opacity">
                            <?php echo esc_html($phone); ?>
                        </a>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="12" viewBox="0 0 16 12" fill="none" class="flex-shrink-0">
                            <path d="M14.4 0H1.6C0.72 0 0.00799999 0.72 0.00799999 1.6L0 11.2C0 12.08 0.72 12.8 1.6 12.8H14.4C15.28 12.8 16 12.08 16 11.2V1.6C16 0.72 15.28 0 14.4 0ZM14.4 3.2L8 7.2L1.6 3.2V1.6L8 5.6L14.4 1.6V3.2Z" fill="#131611"/>
                        </svg>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="text-sm font-medium leading-[1.4] text-[#131611] hover:opacity-70 transition-opacity">
                            <?php echo esc_html($email); ?>
                        </a>
                    </div>
                </div>

                <!-- Right: Menu Items + Language Selector -->
                <div class="flex items-center gap-8">
                    <!-- Menu Items -->
                    <div class="flex items-center gap-8 text-sm font-medium leading-[1.4] text-[#131611]">
                        <?php
                        if (has_nav_menu('topbar-menu')) {
                            wp_nav_menu(array(
                                'theme_location' => 'topbar-menu',
                                'container'      => false,
                                'menu_class'     => 'flex items-center gap-8',
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ));
                        } else {
                            ?>
                            <a href="#" class="hover:opacity-70 transition-opacity">Menu item</a>
                            <a href="#" class="hover:opacity-70 transition-opacity">Menu item</a>
                            <?php
                        }
                        ?>
                    </div>

                    <!-- Language Selector -->
                    <?php if (function_exists('pll_the_languages')) : ?>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" class="flex-shrink-0">
                                <path d="M8 0C3.58 0 0 3.58 0 8C0 12.42 3.58 16 8 16C12.42 16 16 12.42 16 8C16 3.58 12.42 0 8 0ZM13.92 5.6H11.92C11.7 4.58 11.36 3.6 10.9 2.68C12.14 3.24 13.16 4.28 13.92 5.6ZM8 1.64C8.68 2.64 9.22 3.78 9.52 5.6H6.48C6.78 3.78 7.32 2.64 8 1.64ZM1.74 9.6C1.58 9.08 1.5 8.54 1.5 8C1.5 7.46 1.58 6.92 1.74 6.4H4.08C4.02 6.92 3.98 7.46 3.98 8C3.98 8.54 4.02 9.08 4.08 9.6H1.74ZM2.08 10.4H4.08C4.3 11.42 4.64 12.4 5.1 13.32C3.86 12.76 2.84 11.72 2.08 10.4ZM4.08 5.6H2.08C2.84 4.28 3.86 3.24 5.1 2.68C4.64 3.6 4.3 4.58 4.08 5.6ZM8 14.36C7.32 13.36 6.78 12.22 6.48 10.4H9.52C9.22 12.22 8.68 13.36 8 14.36ZM9.92 9.6H6.08C6 9.08 5.98 8.54 5.98 8C5.98 7.46 6 6.92 6.08 6.4H9.92C10 6.92 10.02 7.46 10.02 8C10.02 8.54 10 9.08 9.92 9.6ZM10.9 13.32C11.36 12.4 11.7 11.42 11.92 10.4H13.92C13.16 11.72 12.14 12.76 10.9 13.32ZM11.92 9.6C11.98 9.08 12.02 8.54 12.02 8C12.02 7.46 11.98 6.92 11.92 6.4H14.26C14.42 6.92 14.5 7.46 14.5 8C14.5 8.54 14.42 9.08 14.26 9.6H11.92Z" fill="#131611"/>
                            </svg>
                            <div class="flex items-center gap-1">
                                <?php 
                                pll_the_languages(array(
                                    'dropdown'           => 1,
                                    'show_names'         => 1,
                                    'display_names_as'   => 'name',
                                    'show_flags'         => 0,
                                )); 
                                ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Fallback language selector -->
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" class="flex-shrink-0">
                                <path d="M8 0C3.58 0 0 3.58 0 8C0 12.42 3.58 16 8 16C12.42 16 16 12.42 16 8C16 3.58 12.42 0 8 0ZM13.92 5.6H11.92C11.7 4.58 11.36 3.6 10.9 2.68C12.14 3.24 13.16 4.28 13.92 5.6ZM8 1.64C8.68 2.64 9.22 3.78 9.52 5.6H6.48C6.78 3.78 7.32 2.64 8 1.64ZM1.74 9.6C1.58 9.08 1.5 8.54 1.5 8C1.5 7.46 1.58 6.92 1.74 6.4H4.08C4.02 6.92 3.98 7.46 3.98 8C3.98 8.54 4.02 9.08 4.08 9.6H1.74ZM2.08 10.4H4.08C4.3 11.42 4.64 12.4 5.1 13.32C3.86 12.76 2.84 11.72 2.08 10.4ZM4.08 5.6H2.08C2.84 4.28 3.86 3.24 5.1 2.68C4.64 3.6 4.3 4.58 4.08 5.6ZM8 14.36C7.32 13.36 6.78 12.22 6.48 10.4H9.52C9.22 12.22 8.68 13.36 8 14.36ZM9.92 9.6H6.08C6 9.08 5.98 8.54 5.98 8C5.98 7.46 6 6.92 6.08 6.4H9.92C10 6.92 10.02 7.46 10.02 8C10.02 8.54 10 9.08 9.92 9.6ZM10.9 13.32C11.36 12.4 11.7 11.42 11.92 10.4H13.92C13.16 11.72 12.14 12.76 10.9 13.32ZM11.92 9.6C11.98 9.08 12.02 8.54 12.02 8C12.02 7.46 11.98 6.92 11.92 6.4H14.26C14.42 6.92 14.5 7.46 14.5 8C14.5 8.54 14.42 9.08 14.26 9.6H11.92Z" fill="#131611"/>
                            </svg>
                            <button class="flex items-center gap-1 text-sm font-medium leading-[1.4] text-[#131611] hover:opacity-70 transition-opacity">
                                <span>Nederlands</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6" fill="none" class="flex-shrink-0">
                                    <path d="M0 0L5 6L10 0H0Z" fill="#131611"/>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
