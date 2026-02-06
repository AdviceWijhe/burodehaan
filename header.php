<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('min-h-screen flex flex-col'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site flex flex-col min-h-screen">
    <?php

 // Get customizer settings
 $header_layout = get_theme_mod('header_layout', 'layout-1');
 $enable_topbar = get_theme_mod('header_enable_topbar', true);

 $header_class .= 'relative bg-white';
 if ($header_layout === 'layout-5') {
    
    $header_class = 'site-header fixed! left-1/2 -translate-x-1/2 w-full top-0 z-50';
 }
?>


    <header id="masthead" class="<?= $header_class ?>" style="z-index: 50;">
        <?php
       
        
        // Display Topbar if enabled
        if ($enable_topbar) {
            get_template_part('template-parts/headers/topbar');
        }
        
        // Display selected navigation layout
        get_template_part('template-parts/headers/nav-' . $header_layout);
        ?>
        
        <!-- Mobile Navigation (dropdown under header) -->
        <div id="mobile-navigation" class="mobile-navigation absolute left-0 right-0 top-full z-40 bg-white xl:hidden overflow-hidden max-h-0 opacity-0 invisible transition-all duration-300 ease-out flex flex-col h-full border-t border-gray-200">
            <nav class="mobile-menu-nav flex-1 overflow-y-auto">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-primary-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-list pt-[100px]',
                    'fallback_cb'    => false,
                    'walker'         => new Advice2025_Mobile_Nav_Walker(),
                    'depth'          => 3,
                ));
                ?>
            </nav>
            <div class="p-[20px] pb-[60px] pl-[40px] mt-auto">
                <div class="w-full mt-10 lg:mt-0">
                    <?php $header_cta_mobile = get_field('header_cta', 'option'); ?>
                    <?php if($header_cta_mobile && isset($header_cta_mobile['link']) && isset($header_cta_mobile['link']['url'])) : ?>
                        <a href="<?= esc_url($header_cta_mobile['link']['url']) ?>" class="flex gap-3">
                            <div class="relative w-[90px] rotate-[-7deg]">
                                <?php if(isset($header_cta_mobile['afbeelding']) && isset($header_cta_mobile['afbeelding']['url'])) : ?>
                                    <img src="<?= esc_url($header_cta_mobile['afbeelding']['url']) ?>" alt="<?= esc_attr($header_cta_mobile['afbeelding']['alt'] ?? '') ?>" class="w-[90px] h-[120px] shadow-xl absolute top-1/2 -translate-y-1/2 left-[-10px]">
                                <?php endif; ?>
                            </div>
                            <div class="text-[#131611] flex-1">
                                <h6 class="mb-5 text-[#131611]">Raising Giants</h6>
                                <span class="w-full flex items-center justify-between gap-2">
                                    <div class="span">
                                        <p class="mb-0! font-bold text-[#131611]"><?= isset($header_cta_mobile['titel']) ? esc_html($header_cta_mobile['titel']) : '' ?></p>
                                        <p class="mb-0! text-[#131611]/70"><?= isset($header_cta_mobile['subtitel']) ? esc_html($header_cta_mobile['subtitel']) : '' ?></p>
                                    </div>
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12.5" cy="12.5" r="12" stroke="#131611"/>
                                        <g class="arrow-group">
                                            <rect x="7" y="11.7427" width="10" height="1" fill="#131611"/>
                                            <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="#131611"/>
                                            <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="#131611"/>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Drilldown subpanel -->
            <div id="mobile-subpanel" class="absolute inset-0 bg-white z-50 opacity-0 invisible translate-x-full transition-all duration-300 ease-out overflow-y-auto">
                <div class="flex items-center gap-3 p-4 bg-white mt-[24px]">
                    <button id="mobile-subpanel-back" type="button" class="btn border border-[#131611] text-[#131611] px-[16px]! py-[12px]! flex gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-0!" width="25" height="25" viewBox="0 0 25 25" fill="none">
                            <circle cx="12.5" cy="12.5" r="12" transform="rotate(-180 12.5 12.5)" stroke="currentColor"/>
                            <rect x="9.6182" y="12.7574" width="0.5" height="5.5" transform="rotate(-45 9.6182 12.7574)" fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
                            <rect x="9.6182" y="12.7574" width="5.5" height="0.5" transform="rotate(-45 9.6182 12.7574)" fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
                        </svg>
                        <span>Terug</span>
                    </button>
                </div>
                <div class="px-[20px] pt-[20px]">
                    <div class="border-b border-[#131611]/10">
                        <a id="mobile-subpanel-title" href="#" class="title-medium text-[#131611] mb-[20px] hover:text-[#131611]/80 transition-colors flex items-center w-full justify-between"></a>
                    </div>
                </div>
                <div id="mobile-subpanel-content" class="px-[20px] space-y-3"></div>
            </div>
        </div>
    </header>
    
<!-- Desktop dropdown overlay -->
<div id="dropdown-overlay" class="fixed inset-0 bg-[#131611]/50 opacity-0 invisible transition-opacity duration-300 z-40 hidden lg:block pointer-events-none"></div>
