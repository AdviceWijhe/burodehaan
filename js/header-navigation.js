/**
 * Header Navigation & Mobile Menu
 * Handles all header functionality including:
 * - Search toggle
 * - Header scroll behavior
 * - Mobile menu
 * - Desktop dropdown overlays
 */

class HeaderNavigation {
    constructor() {
        this.header = document.getElementById('masthead');
        this.body = document.body;
        this.searchToggle = document.getElementById('header-search-toggle');
        this.searchBar = document.getElementById('header-search-bar');
        this.mobileMenuButton = document.getElementById('mobile-menu-button');
        this.mobileNavigation = document.getElementById('mobile-navigation');
        this.overlay = document.getElementById('dropdown-overlay');
        this.primaryMenu = document.getElementById('primary-menu');
        
        // State
        this.lastScrollTop = 0;
        this.headerHeight = 0;
        this.isHeaderVisible = true;
        this.scrollDirection = 'down';
        this.scrollTimeout = null;
        this.ticking = false;
        this.resizeObserver = null;
        
        this.init();
    }
    
    init() {
        if (!this.header) return;
        
        this.initSearch();
        this.initScrollBehavior();
        this.initMobileMenu();
        this.initDesktopDropdowns();
        this.initMobileDrilldown();
        this.initFloatingNav();
        this.setupEventListeners();
        
        // Initial setup
        this.updateHeaderHeight();
        this.updateMobileMenuMaxHeight();
        
        // Window load
        window.addEventListener('load', () => {
            setTimeout(() => this.updateHeaderHeight(), 100);
        });
        
        // Expose reset function globally
        window.resetHeader = () => this.resetHeader();
    }
    
    /**
     * Floating Navigation Sticky Behavior
     */
    initFloatingNav() {
        const floatingNav = document.querySelector('.navigation-wrapper-floating');
        if (!floatingNav) return;
        
        // Add class for sticky behavior
        floatingNav.classList.add('header-floating-sticky');
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 50) {
                floatingNav.classList.add('is-scrolled');
            } else {
                floatingNav.classList.remove('is-scrolled');
            }
        }, { passive: true });
    }
    
    /**
     * Search Toggle Functionality
     */
    initSearch() {
        if (!this.searchToggle || !this.searchBar) return;
        
        this.searchToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleSearch();
        });
        
        // Close search when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.searchToggle.contains(e.target) && !this.searchBar.contains(e.target)) {
                this.closeSearch();
            }
        });
    }
    
    toggleSearch() {
        const isExpanded = this.searchToggle.getAttribute('aria-expanded') === 'true';
        
        if (isExpanded) {
            this.closeSearch();
        } else {
            this.openSearch();
        }
    }
    
    openSearch() {
        this.searchBar.classList.remove('hidden');
        setTimeout(() => {
            this.searchBar.style.width = '300px';
            this.searchBar.style.opacity = '1';
        }, 10);
        this.searchToggle.setAttribute('aria-expanded', 'true');
        
        // Focus on search input
        const searchInput = this.searchBar.querySelector('input[type="search"]');
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 350);
        }
    }
    
    closeSearch() {
        this.searchBar.style.width = '0';
        this.searchBar.style.opacity = '0';
        setTimeout(() => {
            this.searchBar.classList.add('hidden');
        }, 300);
        this.searchToggle.setAttribute('aria-expanded', 'false');
    }
    
    /**
     * Header Scroll Behavior
     */
    initScrollBehavior() {
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
        
        // Setup resize observer for header height changes
        if (window.ResizeObserver) {
            this.resizeObserver = new ResizeObserver((entries) => {
                for (let entry of entries) {
                    if (entry.target === this.header) {
                        this.updateHeaderHeight();
                        this.updateMobileMenuMaxHeight();
                    }
                }
            });
            this.resizeObserver.observe(this.header);
        }
    }
    
    onScroll() {
        if (!this.ticking) {
            requestAnimationFrame(() => {
                this.handleScroll();
                this.ticking = false;
            });
            this.ticking = true;
        }
    }
    
    handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollDelta = scrollTop - this.lastScrollTop;
        
        clearTimeout(this.scrollTimeout);
        
        // Determine scroll direction
        if (scrollDelta > 0) {
            this.scrollDirection = 'down';
        } else if (scrollDelta < 0) {
            this.scrollDirection = 'up';
        }
        
        // Always show header at top
        if (scrollTop <= 10) {
            this.showHeader();
            this.lastScrollTop = scrollTop;
            return;
        }
        
        // Hide header when scrolling down
        if (this.scrollDirection === 'down' && scrollDelta > 5 && scrollTop > 100) {
            this.hideHeader();
        } 
        // Show header when scrolling up
        else if (this.scrollDirection === 'up' && scrollDelta < -5) {
            this.showHeader();
        }
        
        this.lastScrollTop = scrollTop;
    }
    
    showHeader() {
        if (!this.isHeaderVisible) {
            this.header.classList.remove('notVisible');
            this.isHeaderVisible = true;
        }
    }
    
    hideHeader() {
        if (this.isHeaderVisible) {
            this.header.classList.add('notVisible');
            this.isHeaderVisible = false;
        }
    }
    
    updateHeaderHeight() {
        const currentHeight = this.header.offsetHeight;
        if (currentHeight !== this.headerHeight) {
            this.headerHeight = currentHeight;
            this.body.style.paddingTop = this.headerHeight + 'px';
            this.updateMobileMenuMaxHeight();
        }
    }
    
    resetHeader() {
        this.isHeaderVisible = true;
        this.header.classList.remove('notVisible');
        this.lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    }
    
    /**
     * Mobile Menu Functionality
     */
    initMobileMenu() {
        if (!this.mobileMenuButton || !this.mobileNavigation) return;
        
        this.mobileMenuButton.addEventListener('click', () => {
            const isOpen = this.mobileNavigation.classList.contains('visible');
            if (isOpen) {
                this.closeMobileMenu();
            } else {
                this.openMobileMenu();
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.mobileNavigation || !this.mobileMenuButton) return;
            const clickInsideMenu = this.mobileNavigation.contains(e.target);
            const clickOnButton = this.mobileMenuButton.contains(e.target);
            if (!clickInsideMenu && !clickOnButton && this.mobileNavigation.classList.contains('visible')) {
                this.closeMobileMenu();
            }
        });
        
        // Close mobile menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.mobileNavigation.classList.contains('visible')) {
                this.closeMobileMenu();
            }
        });
    }
    
    updateMobileMenuMaxHeight() {
        const nav = document.getElementById('mobile-navigation');
        if (!nav) return;
        
        let h = 0;
        if (typeof this.headerHeight === 'number' && this.headerHeight > 0) {
            h = this.headerHeight;
        } else if (this.header) {
            const rect = this.header.getBoundingClientRect();
            h = Math.round(rect.height || this.header.offsetHeight || 0);
        }
        
        const value = (h && h > 0) ? `calc(100vh - ${h}px)` : '100vh';
        nav.style.maxHeight = value;
        nav.style.height = value;
        
        const subpanel = document.getElementById('mobile-subpanel');
        if (subpanel) {
            subpanel.style.maxHeight = value;
            subpanel.style.height = value;
        }
    }
    
    openMobileMenu() {
        this.mobileNavigation.classList.remove('opacity-0', 'invisible');
        this.mobileNavigation.classList.add('opacity-100', 'visible');
        this.updateMobileMenuMaxHeight();

        const navWrapper = document.querySelector('.navigation-wrapper');
        if (this.header) {
            this.header.style.backgroundColor = '#ffffff';
        }
        if (navWrapper) {
            navWrapper.classList.add('is-dropdown-open');
            navWrapper.style.backgroundColor = '#ffffff';
        }
        
        this.mobileMenuButton.setAttribute('aria-expanded', 'true');
        this.mobileMenuButton.style.backgroundColor = '#f3f4f6';
        
        const mobileMenuLabel = this.mobileMenuButton.querySelector('.mobile-menu-label');
        if (mobileMenuLabel) {
            mobileMenuLabel.textContent = 'SLUIT';
        }
        
        // Update icon to close (X)
        const hamburgerContainer = this.mobileMenuButton.querySelector('.hamburger-lines');
        if (hamburgerContainer) {
            hamburgerContainer.innerHTML = this.getCloseIcon();
        }
    }
    
    closeMobileMenu() {
        this.mobileNavigation.classList.remove('opacity-100', 'visible');
        this.mobileNavigation.classList.add('opacity-0', 'invisible');
        this.mobileNavigation.style.maxHeight = '0px';

        const navWrapper = document.querySelector('.navigation-wrapper');
        if (this.header) {
            this.header.style.backgroundColor = '';
        }
        if (navWrapper) {
            navWrapper.classList.remove('is-dropdown-open');
            navWrapper.style.backgroundColor = '';
        }
        
        this.mobileMenuButton.setAttribute('aria-expanded', 'false');
        this.mobileMenuButton.style.backgroundColor = '';
        
        const mobileMenuLabel = this.mobileMenuButton.querySelector('.mobile-menu-label');
        if (mobileMenuLabel) {
            mobileMenuLabel.textContent = 'MENU';
        }
        
        // Update icon to hamburger
        const hamburgerContainer = this.mobileMenuButton.querySelector('.hamburger-lines');
        if (hamburgerContainer) {
            hamburgerContainer.innerHTML = this.getHamburgerIcon();
        }
        
        // Reset all dropdowns
        const mobileDropdowns = this.mobileNavigation.querySelectorAll('.mobile-dropdown');
        const mobileArrows = this.mobileNavigation.querySelectorAll('.mobile-dropdown-arrow');
        
        mobileDropdowns.forEach(dropdown => {
            dropdown.style.maxHeight = '0';
            dropdown.style.opacity = '0';
        });
        
        mobileArrows.forEach(arrow => {
            arrow.style.transform = '';
        });
    }
    
    getHamburgerIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" viewBox="0 0 14 10" fill="none">
            <rect width="14" height="1" fill="white"/>
            <rect y="4.5" width="14" height="1" fill="white"/>
            <rect y="9" width="14" height="1" fill="white"/>
        </svg>`;
    }
    
    getCloseIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
            <circle cx="12.5" cy="12.5" r="12" stroke="currentColor"/>
            <rect x="9.35355" y="16.0711" width="9.5" height="0.5" transform="rotate(-45 9.35355 16.0711)" fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
            <rect x="9.70703" y="9.35355" width="9.5" height="0.5" transform="rotate(45 9.70703 9.35355)" fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
        </svg>`;
    }
    
    /**
     * Mobile Drilldown Navigation
     */
    initMobileDrilldown() {
        const mobileMenuRoot = document.getElementById('mobile-primary-menu');
        if (!mobileMenuRoot) return;
        
        const parentLinks = mobileMenuRoot.querySelectorAll('li.mobile-has-dropdown > a');
        const subpanel = document.getElementById('mobile-subpanel');
        const subpanelTitle = document.getElementById('mobile-subpanel-title');
        const subpanelContent = document.getElementById('mobile-subpanel-content');
        const subpanelBack = document.getElementById('mobile-subpanel-back');
        
        if (!subpanel || !subpanelTitle || !subpanelContent) return;
        
        // Back button handler
        if (subpanelBack) {
            subpanelBack.addEventListener('click', (e) => {
                e.preventDefault();
                this.hideSubpanel(subpanel);
            });
        }
        
        // Parent link handlers
        parentLinks.forEach(anchor => {
            const handler = (e) => {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof e.stopImmediatePropagation === 'function') {
                        e.stopImmediatePropagation();
                    }
                }
                
                const li = anchor.parentElement;
                const dropdown = li.querySelector('.mobile-dropdown');
                const arrow = anchor.querySelector('.mobile-dropdown-arrow');
                
                if (dropdown) {
                    const titleText = anchor.querySelector('span') 
                        ? anchor.querySelector('span').textContent 
                        : anchor.textContent;
                    const titleUrl = anchor.href || '#';
                    this.showSubpanel(subpanel, subpanelTitle, subpanelContent, titleText, dropdown, titleUrl);
                }
                
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }
                
                // Reset other arrows
                parentLinks.forEach(otherAnchor => {
                    if (otherAnchor !== anchor) {
                        const otherArrow = otherAnchor.querySelector('.mobile-dropdown-arrow');
                        if (otherArrow) {
                            otherArrow.style.transform = '';
                        }
                    }
                });
                
                return false;
            };
            
            anchor.addEventListener('click', handler, false);
            anchor.addEventListener('touchstart', handler, { passive: false });
        });
    }
    
    showSubpanel(subpanel, subpanelTitle, subpanelContent, titleText, contentNode, titleUrl) {
        const arrowSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="none" class="shrink-0 ml-2">
            <path d="M1.06055 -4.6358e-08L5.30371 4.24219L1.06055 8.48437L-4.6358e-08 7.42383L3.18164 4.24219L-3.24506e-07 1.06055L1.06055 -4.6358e-08Z" fill="#131611"/>
        </svg>`;
        
        subpanelTitle.innerHTML = `<span class="truncate flex-1 min-w-0">${titleText || ''}</span>${arrowSvg}`;
        subpanelTitle.href = titleUrl || '#';
        
        // Clone content
        subpanelContent.innerHTML = '';
        if (contentNode) {
            const list = contentNode.querySelector('ul, .mobile-submenu, .space-y-3');
            const toClone = list ? Array.from(list.children) : Array.from(contentNode.children);
            toClone.forEach(child => {
                subpanelContent.appendChild(child.cloneNode(true));
            });
        }
        
        // Show subpanel
        subpanel.classList.remove('opacity-0', 'invisible', 'translate-x-full');
        subpanel.classList.add('opacity-100', 'visible', 'translate-x-0');
    }
    
    hideSubpanel(subpanel) {
        subpanel.classList.remove('opacity-100', 'visible', 'translate-x-0');
        subpanel.classList.add('opacity-0', 'invisible', 'translate-x-full');

        // Reset all dropdown arrows when going back to the main menu
        const mobileMenuRoot = document.getElementById('mobile-primary-menu');
        if (mobileMenuRoot) {
            mobileMenuRoot.querySelectorAll('.mobile-dropdown-arrow').forEach(arrow => {
                arrow.style.transform = '';
            });
        }
    }
    
    /**
     * Desktop Dropdown Overlays
     */
    initDesktopDropdowns() {
        if (!this.overlay || !this.primaryMenu) return;
        
        let hoverCount = 0;
        let closeTimer = null;
        const parents = this.primaryMenu.querySelectorAll(':scope > li.has-dropdown, :scope > li.has-mega-menu, :scope > li[data-has-dropdown="true"]');
        const navWrapper = document.querySelector('.navigation-wrapper');
        
        const setHeaderDropdownState = (isOpen) => {
            if (!navWrapper) return;
            navWrapper.classList.toggle('is-dropdown-open', isOpen);
        };

        const setActiveParent = (activeLi) => {
            parents.forEach((item) => {
                if (item === activeLi) {
                    item.classList.add('dropdown-active');
                } else {
                    item.classList.remove('dropdown-active');
                }
            });
        };

        const clearCloseTimer = () => {
            if (!closeTimer) return;
            clearTimeout(closeTimer);
            closeTimer = null;
        };
        
        const showOverlay = () => {
            clearCloseTimer();
            this.overlay.classList.remove('opacity-0', 'invisible');
            this.overlay.classList.add('opacity-100', 'visible');
            // Enable pointer events when visible
            this.overlay.style.pointerEvents = 'auto';
            setHeaderDropdownState(true);
        };
        
        const hideOverlay = () => {
            this.overlay.classList.remove('opacity-100', 'visible');
            this.overlay.classList.add('opacity-0', 'invisible');
            setHeaderDropdownState(false);
            // Disable pointer events when hidden to prevent interference
            setTimeout(() => {
                if (this.overlay.classList.contains('invisible')) {
                    this.overlay.style.pointerEvents = 'none';
                }
            }, 300); // Wait for transition to complete
        };

        const scheduleCloseIfNotHovered = () => {
            clearCloseTimer();
            closeTimer = setTimeout(() => {
                const isAnyParentHovered = Array.from(parents).some((item) => {
                    const dropdown = item.querySelector(':scope > .dropdown-menu');
                    const parentHovered = item.matches(':hover');
                    const dropdownHovered = dropdown ? dropdown.matches(':hover') : false;
                    return parentHovered || dropdownHovered;
                });

                if (!isAnyParentHovered) {
                    hideOverlay();
                    hoverCount = 0;
                    parents.forEach((item) => item.classList.remove('dropdown-active'));
                }
            }, 250);
        };
        
        parents.forEach((li) => {
            // Mouse enter on parent
            li.addEventListener('mouseenter', () => {
                if (window.innerWidth < 1024) return;
                clearCloseTimer();
                hoverCount++;
                setActiveParent(li);
                showOverlay();
            });
            
            // Mouse leave on parent
            li.addEventListener('mouseleave', () => {
                if (window.innerWidth < 1024) return;
                scheduleCloseIfNotHovered();
            });
            
            // Focus events for keyboard navigation
            li.addEventListener('focusin', () => {
                if (window.innerWidth < 1024) return;
                setActiveParent(li);
                showOverlay();
            });
            
            li.addEventListener('focusout', (e) => {
                if (window.innerWidth < 1024) return;
                if (!li.contains(e.relatedTarget)) {
                    hideOverlay();
                    li.classList.remove('dropdown-active');
                }
            });
            
            // Handle dropdown hover separately (including mega menus)
            const dropdown = li.querySelector(':scope > .dropdown-menu');
            if (dropdown) {
                dropdown.addEventListener('mouseenter', () => {
                    if (window.innerWidth < 1024) return;
                    clearCloseTimer();
                    hoverCount++;
                    setActiveParent(li);
                    showOverlay();
                });
                
                dropdown.addEventListener('mouseleave', () => {
                    if (window.innerWidth < 1024) return;
                    scheduleCloseIfNotHovered();
                });
            }
        });
        
        // Click overlay to close - but don't interfere with menu hovers
        this.overlay.addEventListener('click', () => {
            hideOverlay();
            hoverCount = 0;
            parents.forEach(li => li.classList.remove('dropdown-active'));
        });
        
        // Allow real parent links on desktop; only block dummy "#" style links.
        document.addEventListener('click', (e) => {
            const anchor = e.target.closest('a');
            if (!anchor) return;
            
            // Allow clicks inside dropdown/mega menu content
            if (anchor.closest('.dropdown-menu')) return;
            
            const li = anchor.closest('li');
            const isTopLevelParent = li && li.parentElement && li.parentElement.id === 'primary-menu' && (
                li.classList.contains('has-dropdown') || 
                li.classList.contains('has-mega-menu') ||
                (li.dataset && li.dataset.hasDropdown === 'true')
            );
            
            const href = (anchor.getAttribute('href') || '').trim();
            const isDummyLink = href === '' || href === '#' || href.toLowerCase().startsWith('javascript:');

            // Only prevent navigation for non-navigable parent links.
            if (isTopLevelParent && window.innerWidth >= 1024) {
                if (isDummyLink) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof e.stopImmediatePropagation === 'function') {
                        e.stopImmediatePropagation();
                    }
                }
            }
        }, true);
        
        // Ensure hover still works after clicks elsewhere on page
        document.addEventListener('click', (e) => {
            // Don't interfere if clicking on menu items or dropdowns
            if (e.target.closest('.main-navigation') || 
                e.target.closest('.dropdown-menu') ||
                e.target.closest('#dropdown-overlay')) {
                return;
            }
            
            // Click outside menu - just hide overlay, don't block future hovers
            if (hoverCount > 0) {
                hideOverlay();
                hoverCount = 0;
                parents.forEach(li => li.classList.remove('dropdown-active'));
            }
        });
    }
    
    /**
     * Setup Event Listeners
     */
    setupEventListeners() {
        // Window resize
        window.addEventListener('resize', () => {
            this.updateHeaderHeight();
            this.updateMobileMenuMaxHeight();
            
            // Close mobile menu on desktop
            if (window.innerWidth >= 1024 && this.mobileNavigation) {
                this.closeMobileMenu();
            }
            
            // Hide desktop overlay on mobile
            if (window.innerWidth < 1024 && this.overlay && this.primaryMenu) {
                this.overlay.classList.remove('opacity-100', 'visible');
                this.overlay.classList.add('opacity-0', 'invisible');
                const parents = this.primaryMenu.querySelectorAll(':scope > li.has-dropdown, :scope > li[data-has-dropdown="true"]');
                parents.forEach(li => li.classList.remove('dropdown-active'));
            }
        });
        
        // Orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.updateHeaderHeight();
                this.updateMobileMenuMaxHeight();
            }, 100);
        });
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new HeaderNavigation();
});
