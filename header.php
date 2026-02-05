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
$top_banner = get_field('top_banner', 'option');
if($top_banner) : 
?>

    <div class="top-banner bg-[#D8D6D466] lg:py-[8px] text-black flex flex-col p-[20px] lg:flex-row lg:items-center justify-center lg:justify-end gap-4 relative overflow-hidden">
    <div class="relative text-center lg:text-right">
        <?= isset($top_banner['tekst']) ? $top_banner['tekst'] : '' ?>
    </div>

<?php if(isset($top_banner['buttons']) && $top_banner['buttons'] && !isset($top_banner['knoppen_of_popup'])) : ?>
    <div class="relative">
    <?= get_template_part('template-parts/core/buttons', null, array('buttons' => $top_banner['buttons'])) ?>
    </div>
<?php endif; ?>

<?php if(isset($top_banner['knoppen_of_popup']) && $top_banner['knoppen_of_popup']) : ?>
    <div class="flex justify-center lg:justify-end">
    <a class="btn bg-light-pink text-pink hover:bg-white relative w-full lg:w-auto justify-center lg:justify-start" onclick="openPopup(<?= isset($top_banner['popup']) && is_object($top_banner['popup']) ? $top_banner['popup']->ID : (isset($top_banner['popup']) ? $top_banner['popup'] : '') ?>)"><?= isset($top_banner['knop_tekst']) ? $top_banner['knop_tekst'] : '' ?> <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
      <g class="arrow-group">
        <rect x="7" y="11.7427" width="10" height="1" fill="currentColor"/>
        <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
        <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="currentColor"/>
      </g>
    </svg></a>
    </div>
<?php endif; ?>
    </div>

    <?php endif; ?>

    <header id="masthead" class="site-header bg-primary relative">
        <div class="w-full lg:pr-8">
            <div class="grid grid-cols-3 items-center gap-4">
                <!-- Left Column: Logo -->
                <div class="flex items-center">
                    <div class="site-branding z-10 py-[15px] px-[40px]">
                        <?php if (has_custom_logo()) : ?>
                            <div class="site-logo">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                                    <?php 
                                    $custom_logo_id = get_theme_mod('custom_logo');
                                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                                    if ($logo) {
                                        echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="h-[38px] lg:h-[38px] w-auto object-contain">';
                                    }
                                    ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="site-title-group">
                                <h1 class="site-title text-2xl font-bold text-gray-900">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-gray-700 transition-all duration-300 flex items-center space-x-2">
                                        <span class="text-gray-900">
                                            <?php bloginfo('name'); ?>
                                        </span>
                                    </a>
                                </h1>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Middle Column: Navigation -->
                <nav id="site-navigation" class="main-navigation hidden xl:flex items-center justify-center space-x-1 text-black">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'flex items-center space-x-1',
                        'fallback_cb'    => false,
                        'walker'         => new Advice2025_Simple_Nav_Walker(),
                        'depth'          => 3,
                    ));
                    ?>
                </nav>
                
                <!-- Right Column: Search, Secondary Button, Contact -->
                <div class="flex items-center justify-end gap-4 relative">
                    <!-- Search Container (button + expandable search bar) -->
                    <div class="flex items-center relative">
                        <!-- Search Button -->
                        <button id="header-search-toggle" class="p-2 text-black border border-black rounded hover:bg-gray-100 transition-colors flex-shrink-0" aria-label="Zoeken" aria-expanded="false">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- Search Bar (hidden by default, slides out to the left) -->
                        <div id="header-search-bar" class="hidden absolute right-full top-0 mr-2 bg-white border border-black rounded shadow-lg z-50 transition-all duration-300 overflow-hidden" style="width: 0; opacity: 0;">
                            <div class="w-[300px] p-2">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Secondary Button -->
                    <a href="#" class="px-4 py-2 bg-[#13161166] text-black rounded font-medium hover:opacity-80 transition-opacity">
                        Secondary button
                    </a>
                    
                    <!-- Contact Button -->
                    <a href="/contact" class="px-4 py-2 bg-[#FF5822] text-white rounded font-medium hover:opacity-90 transition-opacity">
                        Contact
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="xl:hidden col-span-3 flex justify-end pr-[20px]">
                    <button id="mobile-menu-button" class="relative py-[12px] px-[16px] border border-white text-white hover:bg-light-blue/25 hover:cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300" aria-expanded="false">
                    <span class="sr-only">Menu openen</span>
                    <div class="flex h-full flex-row items-center justify-center gap-2">
                        <div class="hamburger-lines flex flex-col items-center justify-center gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
<circle cx="12.5" cy="12.5" r="12" stroke="white"/>
<rect x="8.25" y="9.25" width="9.5" height="0.5" fill="#00344C" stroke="white" stroke-width="0.5"/>
<rect x="8.25" y="12.5074" width="9.5" height="0.5" fill="#00344C" stroke="white" stroke-width="0.5"/>
<rect x="8.25" y="15.7647" width="9.5" height="0.5" fill="#00344C" stroke="white" stroke-width="0.5"/>
</svg>
</div>
                        <span class="mobile-menu-label  leading-none font-semibold text-white">Menu</span>
                    </div>
                    </button>
                </div>
                
            </div>
            
            <!-- Mobile Navigation (dropdown under header) -->
            <div id="mobile-navigation" class="mobile-navigation absolute left-0 right-0 top-full z-40 bg-primary  xl:hidden overflow-hidden max-h-0 opacity-0 invisible transition-all duration-300 ease-out flex flex-col h-full">
                <nav class="mobile-menu-nav flex-1 overflow-y-auto">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'mobile-primary-menu',
                        'container'      => false,
                        'menu_class'     => 'mobile-menu-list pt-[100px]',
                        'fallback_cb'    => false,
                        'walker'         => new Advice2025_Mobile_Nav_Walker(),
                        'depth'          => 3, // Allow up to 3 levels of nesting
                    ));
                    ?>
                </nav>
                <div class="p-[20px] pb-[60px] pl-[40px]  mt-auto">
                <div class="w-full mt-10 lg:mt-0">
                <?php $header_cta_mobile = get_field('header_cta', 'option'); ?>
                <?php if($header_cta_mobile && isset($header_cta_mobile['link']) && isset($header_cta_mobile['link']['url'])) : ?>
                <a href="<?= esc_url($header_cta_mobile['link']['url']) ?>" class="flex gap-3">
                <div class="relative w-[90px] rotate-[-7deg]">
                  <?php if(isset($header_cta_mobile['afbeelding']) && isset($header_cta_mobile['afbeelding']['url'])) : ?>
                  <img src="<?= esc_url($header_cta_mobile['afbeelding']['url']) ?>" alt="<?= esc_attr($header_cta_mobile['afbeelding']['alt'] ?? '') ?>" class="w-[90px] h-[120px] shadow-xl absolute top-1/2 -translate-y-1/2 left-[-10px]">
                  <?php endif; ?>
                </div>
                  <div class="text-white flex-1">
                    <h6 class="mb-5 text-white">Raising Giants</h6>
                    <span class="w-full flex flex items-center justify-between gap-2">
                        <div class="span">
                      <p class="mb-0! font-bold text-white"><?= isset($header_cta_mobile['titel']) ? esc_html($header_cta_mobile['titel']) : '' ?></p>
                      <p class="mb-0! text-white/70"><?= isset($header_cta_mobile['subtitel']) ? esc_html($header_cta_mobile['subtitel']) : '' ?></p>
                      </div>
                      <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12" stroke="#96ACC0"/>
                            <g class="arrow-group">
                                <rect x="7" y="11.7427" width="10" height="1" fill="#96ACC0"/>
                                <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="#96ACC0"/>
                                <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="#96ACC0"/>
                            </g>
                            </svg>
                </span>
                  </div>
                </a>
                <?php endif; ?>
              </div>
                </div>

                <!-- Drilldown subpanel -->
                <div id="mobile-subpanel" class="absolute inset-0 bg-primary z-50 opacity-0 invisible translate-x-full transition-all duration-300 ease-out overflow-y-auto">
                    <div class="flex items-center gap-3 p-4 bg-primary mt-[24px]">
                        <button id="mobile-subpanel-back" type="button" class="btn border border-white text-white px-[16px]! py-[12px]! flex gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-0!" width="25" height="25" viewBox="0 0 25 25" fill="none">
<circle cx="12.5" cy="12.5" r="12" transform="rotate(-180 12.5 12.5)" stroke="white"/>
<rect x="9.6182" y="12.7574" width="0.5" height="5.5" transform="rotate(-45 9.6182 12.7574)" fill="white" stroke="white" stroke-width="0.5"/>
<rect x="9.6182" y="12.7574" width="5.5" height="0.5" transform="rotate(-45 9.6182 12.7574)" fill="white" stroke="white" stroke-width="0.5"/>
</svg>
                            <span class="">Terug</span>
                       </button>
                    </div>
                    <div class="px-[20px] pt-[20px]">
                        <div class="border-b border-white/10">
                    <a id="mobile-subpanel-title" href="#" class="title-medium text-white mb-[20px] hover:text-white/80 transition-colors flex items-center w-full justify-between"></a>
                    </div>
                    </div>
                    <div id="mobile-subpanel-content" class="px-[20px] space-y-3">
                    
                    </div>
                </div>
            </div>
            
        </div>
    </header>
    <!-- Desktop dropdown overlay -->
    <div id="dropdown-overlay" class="fixed inset-0 bg-blue/50 opacity-0 invisible transition-opacity duration-300 z-40 hidden lg:block"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Header Search Toggle
    const searchToggle = document.getElementById('header-search-toggle');
    const searchBar = document.getElementById('header-search-bar');
    
    if (searchToggle && searchBar) {
        searchToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isExpanded = searchToggle.getAttribute('aria-expanded') === 'true';
            if (isExpanded) {
                // Close: slide in
                searchBar.style.width = '0';
                searchBar.style.opacity = '0';
                setTimeout(() => {
                    searchBar.classList.add('hidden');
                }, 300);
                searchToggle.setAttribute('aria-expanded', 'false');
            } else {
                // Open: slide out
                searchBar.classList.remove('hidden');
                setTimeout(() => {
                    searchBar.style.width = '300px';
                    searchBar.style.opacity = '1';
                }, 10);
                searchToggle.setAttribute('aria-expanded', 'true');
                // Focus on search input when opened
                const searchInput = searchBar.querySelector('input[type="search"]');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 350);
                }
            }
        });
        
        // Close search bar when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchToggle.contains(e.target) && !searchBar.contains(e.target)) {
                searchBar.style.width = '0';
                searchBar.style.opacity = '0';
                setTimeout(() => {
                    searchBar.classList.add('hidden');
                }, 300);
                searchToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
    
    const header = document.getElementById('masthead');
    const body = document.body;
    let lastScrollTop = 0;
    let headerHeight = 0;
    let isHeaderVisible = true;
    let scrollDirection = 'down';
    let scrollTimeout;
    let ticking = false;
    
    // Function to update header height and body padding
    function updateHeaderHeight() {
        // Only update if height actually changed
        const currentHeight = header.offsetHeight;
        
        if (currentHeight !== headerHeight) {
            headerHeight = currentHeight;
            body.style.paddingTop = headerHeight + 'px';
            // Also keep mobile menu height in sync with header height
            updateMobileMenuMaxHeight();
        }
    }
    
    // Function to show header
    function showHeader() {
        if (!isHeaderVisible) {
            header.classList.remove('notVisible');
            isHeaderVisible = true;
        }
    }
    
    // Function to hide header
    function hideHeader() {
        if (isHeaderVisible) {
            header.classList.add('notVisible');
            isHeaderVisible = false;
        }
    }
    
    // Function to handle scroll behavior
    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollDelta = scrollTop - lastScrollTop;
        
        // Clear any existing timeout
        clearTimeout(scrollTimeout);
        
        // Determine scroll direction
        if (scrollDelta > 0) {
            scrollDirection = 'down';
        } else if (scrollDelta < 0) {
            scrollDirection = 'up';
        }
        
        // If we're at the top, always show header
        if (scrollTop <= 10) {
            showHeader();
            lastScrollTop = scrollTop;
            return;
        }
        
        // If scrolling down and past threshold, hide header
        if (scrollDirection === 'down' && scrollDelta > 5 && scrollTop > 100) {
            hideHeader();
        }
        // If scrolling up, show header
        else if (scrollDirection === 'up' && scrollDelta < -5) {
            showHeader();
        }
        
        lastScrollTop = scrollTop;
        
        // Set timeout to reset scroll state
        scrollTimeout = setTimeout(function() {
            // Optional: Add any cleanup logic here
        }, 100);
    }
    
    // Throttled scroll handler
    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(function() {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    }
    
    // Add scroll listener
    window.addEventListener('scroll', onScroll, { passive: true });
    
    // Handle resize to recalculate header height
    window.addEventListener('resize', function() {
        updateHeaderHeight();
        updateMobileMenuMaxHeight();
    });
    
    // Handle orientation change (mobile)
    window.addEventListener('orientationchange', function() {
        setTimeout(function(){
            updateHeaderHeight();
            updateMobileMenuMaxHeight();
        }, 100);
    });
    
    // Initial setup
    updateHeaderHeight();
    updateMobileMenuMaxHeight();
    
    // Recalculate height after images load (in case logo affects height)
    window.addEventListener('load', function() {
        setTimeout(updateHeaderHeight, 100);
    });
    
    // Simple resize observer for header height changes
    let resizeObserver;
    if (window.ResizeObserver) {
        resizeObserver = new ResizeObserver(function(entries) {
            for (let entry of entries) {
                if (entry.target === header) {
                    updateHeaderHeight();
                    updateMobileMenuMaxHeight();
                }
            }
        });
        resizeObserver.observe(header);
    }
    
    // Debug function to reset header state (can be called from console)
    window.resetHeader = function() {
        isHeaderVisible = true;
        header.classList.remove('notVisible');
        lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    };
    
    // Mobile Menu Functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileNavigation = document.getElementById('mobile-navigation');
    const mobileMenuLabel = mobileMenuButton ? mobileMenuButton.querySelector('.mobile-menu-label') : null;
    
    // Compute mobile menu max-height = 100vh - headerHeight
    function updateMobileMenuMaxHeight() {
        const nav = document.getElementById('mobile-navigation');
        if (!nav) return;
        let h = 0;
        if (typeof headerHeight === 'number' && headerHeight > 0) {
            h = headerHeight;
        } else if (header) {
            const rect = header.getBoundingClientRect();
            h = Math.round(rect.height || header.offsetHeight || 0);
        }
        const value = (h && h > 0) ? ('calc(100vh - ' + h + 'px)') : '100vh';
        nav.style.maxHeight = value;
        nav.style.height = value;
        // Keep subpanel within same viewport height
        const subpanel = document.getElementById('mobile-subpanel');
        if (subpanel) {
            subpanel.style.maxHeight = value;
            subpanel.style.height = value;
        }
    }
    
    // Function to open mobile menu
    function openMobileMenu() {
        // Dropdown open under header
        mobileNavigation.classList.remove('opacity-0', 'invisible');
        mobileNavigation.classList.add('opacity-100', 'visible');
        updateMobileMenuMaxHeight();
        
        // Update button state
        mobileMenuButton.setAttribute('aria-expanded', 'true');
        // Update button UI to open state (Figma: bg #EBEEF0, label SLUIT)
        mobileMenuButton.style.backgroundColor = '#0a2031';
        if (mobileMenuLabel) { mobileMenuLabel.textContent = 'SLUIT'; }
        
        // Replace hamburger SVG with X SVG
        const hamburgerContainer = mobileMenuButton.querySelector('.hamburger-lines');
        if (hamburgerContainer) {
            const closeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none"><circle cx="12.5" cy="12.5" r="12" stroke="white"/><rect x="9.35355" y="16.0711" width="9.5" height="0.5" transform="rotate(-45 9.35355 16.0711)" fill="#00344C" stroke="white" stroke-width="0.5"/><rect x="9.70703" y="9.35355" width="9.5" height="0.5" transform="rotate(45 9.70703 9.35355)" fill="#00344C" stroke="white" stroke-width="0.5"/></svg>';
            hamburgerContainer.innerHTML = closeSvg;
        }
    }
    
    // Function to close mobile menu
    function closeMobileMenu() {
        mobileNavigation.classList.remove('opacity-100', 'visible');
        mobileNavigation.classList.add('opacity-0', 'invisible');
        mobileNavigation.style.maxHeight = '0px';
        
        // Update button state
        mobileMenuButton.setAttribute('aria-expanded', 'false');
        // Reset button UI to closed state (Figma: bg white, label MENU)
        mobileMenuButton.style.backgroundColor = '';
        if (mobileMenuLabel) { mobileMenuLabel.textContent = 'MENU'; }
        
        // Replace X SVG back to hamburger SVG
        const hamburgerContainer = mobileMenuButton.querySelector('.hamburger-lines');
        if (hamburgerContainer) {
            const hamburgerSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none"><circle cx="12.5" cy="12.5" r="12" stroke="white"/><rect x="8.25" y="9.25" width="9.5" height="0.5" fill="#00344C" stroke="white" stroke-width="0.5"/><rect x="8.25" y="12.5074" width="9.5" height="0.5" fill="#00344C" stroke="white" stroke-width="0.5"/><rect x="8.25" y="15.7647" width="9.5" height="0.5" fill="#00344C" stroke="white" stroke-width="0.5"/></svg>';
            hamburgerContainer.innerHTML = hamburgerSvg;
        }
        
        // Close all mobile dropdowns
        const mobileDropdowns = mobileNavigation.querySelectorAll('.mobile-dropdown');
        const mobileArrows = mobileNavigation.querySelectorAll('.mobile-dropdown-arrow');
        
        mobileDropdowns.forEach(dropdown => {
            dropdown.style.maxHeight = '0';
            dropdown.style.opacity = '0';
        });
        
        mobileArrows.forEach(arrow => {
            arrow.style.transform = '';
        });
    }
    
    // Event listeners for mobile menu
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            const isOpen = mobileNavigation && mobileNavigation.classList.contains('visible');
            if (isOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }
    
    // Close mobile menu when clicking outside
    // Click outside to close
    document.addEventListener('click', function(e) {
        if (!mobileNavigation || !mobileMenuButton) return;
        const clickInsideMenu = mobileNavigation.contains(e.target);
        const clickOnButton = mobileMenuButton.contains(e.target);
        if (!clickInsideMenu && !clickOnButton && mobileNavigation.classList.contains('visible')) {
            closeMobileMenu();
        }
    });
    
    // Close mobile menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileNavigation.classList.contains('visible')) {
            closeMobileMenu();
        }
    });
    
    // Mobile dropdown drilldown behavior
    const mobileMenuRoot = document.getElementById('mobile-primary-menu');
    if (mobileMenuRoot) {
        const parentLinks = mobileMenuRoot.querySelectorAll('li.mobile-has-dropdown > a');
        const subpanel = document.getElementById('mobile-subpanel');
        const subpanelTitle = document.getElementById('mobile-subpanel-title');
        const subpanelContent = document.getElementById('mobile-subpanel-content');
        const subpanelBack = document.getElementById('mobile-subpanel-back');

        function showSubpanel(titleText, contentNode, titleUrl) {
            if (!subpanel || !subpanelTitle || !subpanelContent) return;
            // Fill title and URL with SVG arrow
            const arrowSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="none" class="flex-shrink-0 ml-2"><path d="M1.06055 -4.6358e-08L5.30371 4.24219L1.06055 8.48437L-4.6358e-08 7.42383L3.18164 4.24219L-3.24506e-07 1.06055L1.06055 -4.6358e-08Z" fill="#96ACC0"/></svg>';
            subpanelTitle.innerHTML = '<span class="truncate flex-1 min-w-0">' + (titleText || '') + '</span>' + arrowSvg;
            if (titleUrl) {
                subpanelTitle.href = titleUrl;
            } else {
                subpanelTitle.href = '#';
            }
            // Clear and append cloned children from dropdown list
            subpanelContent.innerHTML = '';
            if (contentNode) {
                // If the dropdown container wraps a UL, copy its LI children
                const list = contentNode.querySelector('ul, .mobile-submenu, .space-y-3');
                const toClone = list ? Array.from(list.children) : Array.from(contentNode.children);
                toClone.forEach(child => {
                    subpanelContent.appendChild(child.cloneNode(true));
                });
            }
            // Show
            subpanel.classList.remove('opacity-0','invisible','translate-x-full');
            subpanel.classList.add('opacity-100','visible','translate-x-0');
        }

        function hideSubpanel() {
            if (!subpanel) return;
            subpanel.classList.remove('opacity-100','visible','translate-x-0');
            subpanel.classList.add('opacity-0','invisible','translate-x-full');
        }

        if (subpanelBack) {
            subpanelBack.addEventListener('click', function(e){
                e.preventDefault();
                hideSubpanel();
            });
        }
        parentLinks.forEach(anchor => {
            const handler = function(e) {
                // Guard against other listeners (e.g., page transitions)
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof e.stopImmediatePropagation === 'function') {
                        e.stopImmediatePropagation();
                    }
                }
                const li = this.parentElement;
                const dropdown = li.querySelector('.mobile-dropdown');
                const arrow = this.querySelector('.mobile-dropdown-arrow');
                const isOpen = li.classList.contains('is-open');

                // Drilldown: always show subpanel instead of expanding inline
                if (dropdown) {
                    const titleText = this.querySelector('span') ? this.querySelector('span').textContent : this.textContent;
                    const titleUrl = this.href || '#';
                    showSubpanel(titleText, dropdown, titleUrl);
                }
                // Optional: mark state for styling arrow
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }
                // Ensure others visually reset
                parentLinks.forEach(otherA => {
                    const otherLi = otherA.parentElement;
                    if (otherLi !== li) {
                        const oa = otherA.querySelector('.mobile-dropdown-arrow');
                        if (oa) { oa.style.transform = ''; }
                    }
                });
                return false;
            };
            // Click handler (bubbling phase)
            anchor.addEventListener('click', handler, false);
            // Touchstart handler to catch iOS behaviors early
            anchor.addEventListener('touchstart', handler, { passive: false });
        });
    }
    
    // Handle window resize - close mobile menu on desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) { // lg breakpoint
            closeMobileMenu();
        }
        updateMobileMenuMaxHeight();
    });

    // Guard against page-transition on parent links (desktop only)
    // Use capture phase so we pre-empt other click handlers
    document.addEventListener('click', function(e) {
        const anchor = e.target.closest('a');
        if (!anchor) return;
        // If click happens inside an open dropdown menu, do nothing (allow click)
        if (anchor.closest('.dropdown-menu')) {
            return;
        }
        // Match only top-level parent links that own a dropdown
        const li = anchor.closest('li');
        const isTopLevelParent = li && li.parentElement && li.parentElement.id === 'primary-menu' && (
            li.classList.contains('has-dropdown') ||
            (li.dataset && li.dataset.hasDropdown === 'true')
        );
        if (isTopLevelParent && window.innerWidth >= 1024) {
            // Prevent navigation and other listeners (e.g., page transition) from firing
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
        }
    }, true);

    // Desktop dropdown overlay behavior
    const overlay = document.getElementById('dropdown-overlay');
    const primaryMenu = document.getElementById('primary-menu');
    if (overlay && primaryMenu) {
        let hoverCount = 0;
        const parents = primaryMenu.querySelectorAll(':scope > li.has-dropdown, :scope > li[data-has-dropdown="true"]');

        function showOverlay() {
            overlay.classList.remove('opacity-0', 'invisible');
            overlay.classList.add('opacity-100', 'visible');
        }

        function hideOverlay() {
            overlay.classList.remove('opacity-100', 'visible');
            overlay.classList.add('opacity-0', 'invisible');
        }

        parents.forEach((li) => {
            li.addEventListener('mouseenter', () => {
                if (window.innerWidth < 1024) return;
                hoverCount++;
                li.classList.add('dropdown-active');
                showOverlay();
            });
            li.addEventListener('mouseleave', (e) => {
                if (window.innerWidth < 1024) return;
                const dropdown = li.querySelector(':scope > .dropdown-menu');
                const toEl = e.relatedTarget;
                // Als we naar de dropdown bewegen, actief laten
                if (dropdown && toEl && dropdown.contains(toEl)) {
                    return;
                }
                hoverCount = Math.max(0, hoverCount - 1);
                if (hoverCount === 0) hideOverlay();
                li.classList.remove('dropdown-active');
            });
            // Keyboard accessibility
            li.addEventListener('focusin', () => {
                if (window.innerWidth < 1024) return;
                li.classList.add('dropdown-active');
                showOverlay();
            });
            li.addEventListener('focusout', (e) => {
                if (window.innerWidth < 1024) return;
                // Only hide if focus moved outside of this parent entirely
                if (!li.contains(e.relatedTarget)) {
                    hideOverlay();
                    li.classList.remove('dropdown-active');
                }
            });

            // Events op de dropdown zelf (fixed element) om active staat vast te houden
            const dropdown = li.querySelector(':scope > .dropdown-menu');
            if (dropdown) {
                dropdown.addEventListener('mouseenter', () => {
                    if (window.innerWidth < 1024) return;
                    hoverCount++;
                    li.classList.add('dropdown-active');
                    showOverlay();
                });
                dropdown.addEventListener('mouseleave', (e) => {
                    if (window.innerWidth < 1024) return;
                    // Als we teruggaan naar li, nog niet sluiten
                    const toEl = e.relatedTarget;
                    if (toEl && li.contains(toEl)) {
                        return;
                    }
                    hoverCount = Math.max(0, hoverCount - 1);
                    if (hoverCount === 0) hideOverlay();
                    li.classList.remove('dropdown-active');
                });
            }
        });

        // Clicking the overlay should hide it (and allows CSS hover to naturally close when leaving)
        overlay.addEventListener('click', () => {
            hideOverlay();
            parents.forEach(li => li.classList.remove('dropdown-active'));
        });

        // Hide overlay when resizing to mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth < 1024) {
                hideOverlay();
                parents.forEach(li => li.classList.remove('dropdown-active'));
            }
        });
    }
});
</script>
