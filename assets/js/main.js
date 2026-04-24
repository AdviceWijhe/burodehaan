/**
 * Main JavaScript file for Advice 2025 theme
 */

(function () {
  "use strict";

  // DOM ready
  document.addEventListener("DOMContentLoaded", function () {
    // Top banner functionality
    initTopBanner();

    // Modern navbar functionality
    initModernNavbar();

    // Mobile menu functionality
    initMobileMenu();

    // Smooth scrolling for anchor links
    initSmoothScrolling();

    // Back to top button
    //initBackToTop();

    // Newsletter form
    initNewsletterForm();

    // Enhanced accessibility
    initAccessibility();

    // Image text block animations
    initImageTextAnimations();

    // Marquee block animation
    initMarquee();

    // Image text block layout (align and sticky)
    initImageTextLayout();

    // FAQ accordion functionality
    initFaqAccordion();

    // Archive load more button
    initArchiveLoadMore();
    initPostsArchiveSearch();
    initArchiveFilterPanels();

    // Gravity Forms floating labels (guarded call)
    if (typeof initGravityFormsFloatingLabels === "function") {
      initGravityFormsFloatingLabels();
    } else if (typeof window !== "undefined" && typeof window.initGravityFormsFloatingLabels === "function") {
      window.initGravityFormsFloatingLabels();
    }

    // Gravity Forms custom selects
    if (typeof initGravityFormsCustomSelects === "function") {
      initGravityFormsCustomSelects();
    } else if (typeof window !== "undefined" && typeof window.initGravityFormsCustomSelects === "function") {
      window.initGravityFormsCustomSelects();
    }
  });

  

  /**
   * Top Banner Functionality
   * Banner is now always visible when filled in, no close functionality
   */
  function initTopBanner() {
    // No functionality needed - banner is always visible when filled in
  }

  /**
   * Modern Navbar Functionality
   */
  function initModernNavbar() {
    const navbar = document.getElementById("masthead");

    if (!navbar) return;

    // Scroll effect for navbar
    let lastScrollTop = 0;
    let scrollTimeout;

    window.addEventListener("scroll", function () {
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop;

      // Add scrolled class for glassmorphism effect
      if (scrollTop > 50) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }

      // Hide/show navbar on scroll
      if (scrollTop > lastScrollTop && scrollTop > 100) {
        // Scrolling down
        navbar.style.transform = "translateY(-100%)";
      } else {
        // Scrolling up
        navbar.style.transform = "translateY(0)";
      }

      lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

      // Clear timeout and set new one
      // clearTimeout(scrollTimeout);
      // scrollTimeout = setTimeout(function () {
      //   navbar.style.transform = "translateY(0)";
      // }, 1000);
    });

    // Smooth reveal animation on page load
    setTimeout(function () {
      navbar.style.opacity = "1";
      navbar.style.transform = "translateY(0)";
    }, 100);

    // Add hover effects to navigation links
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach(function (link) {
      link.addEventListener("mouseenter", function () {
       // this.style.transform = "translateY(-2px)";
      });

      link.addEventListener("mouseleave", function () {
       // this.style.transform = "translateY(0)";
      });
    });

    // Active page indicator
    const currentUrl = window.location.pathname;
    navLinks.forEach(function (link) {
      if (link.getAttribute("href") === currentUrl) {
        link.classList.add("active");
        const indicator = link.querySelector(".nav-indicator");
        if (indicator) {
          indicator.style.width = "100%";
        }
      }
    });
  }

  /**
   * Mobile Menu Functionality
   */
  function initMobileMenu() {
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu-1");

    if (!mobileMenuButton || !mobileMenu) {
      return;
    }

    // Set initial state
    mobileMenuButton.setAttribute("aria-expanded", "false");

    mobileMenuButton.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const isExpanded =
        mobileMenuButton.getAttribute("aria-expanded") === "true";

      // Toggle aria-expanded
      mobileMenuButton.setAttribute("aria-expanded", !isExpanded);

      // Toggle menu visibility
      if (!isExpanded) {
        // Opening menu
        mobileMenu.classList.remove("mobile-menu-hidden");
        mobileMenu.classList.remove("hidden"); // Remove Tailwind hidden class
        mobileMenu.classList.add("mobile-menu-visible");
        mobileMenuButton.classList.add("active");

        // Animate menu items
        setTimeout(function () {
          const menuItems = mobileMenu.querySelectorAll(".mobile-nav-link");
          menuItems.forEach(function (item, index) {
            item.style.opacity = "0";
            item.style.transform = "translateX(-20px)";

            setTimeout(function () {
              item.style.transition = "all 0.3s ease";
              item.style.opacity = "1";
              item.style.transform = "translateX(0)";
            }, index * 50 + 100);
          });
        }, 50);
      } else {
        // Closing menu
        mobileMenu.classList.remove("mobile-menu-visible");
        mobileMenu.classList.add("mobile-menu-hidden");
        mobileMenuButton.classList.remove("active");

        // Reset menu items
        const menuItems = mobileMenu.querySelectorAll(".mobile-nav-link");
        menuItems.forEach(function (item) {
          item.style.opacity = "";
          item.style.transform = "";
          item.style.transition = "";
        });
      }
    });

    // Close mobile menu when clicking outside
    document.addEventListener("click", function (event) {
      if (
        !mobileMenuButton.contains(event.target) &&
        !mobileMenu.contains(event.target)
      ) {
        if (mobileMenuButton.getAttribute("aria-expanded") === "true") {
          mobileMenuButton.setAttribute("aria-expanded", "false");
          mobileMenu.classList.remove("mobile-menu-visible");
          mobileMenu.classList.add("mobile-menu-hidden");
          mobileMenuButton.classList.remove("active");
        }
      }
    });

    // Close mobile menu on escape key
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        if (mobileMenuButton.getAttribute("aria-expanded") === "true") {
          mobileMenuButton.setAttribute("aria-expanded", "false");
          mobileMenu.classList.remove("mobile-menu-visible");
          mobileMenu.classList.add("mobile-menu-hidden");
          mobileMenuButton.classList.remove("active");
        }
      }
    });

    // Initialize dropdown functionality after menu is loaded
    function initDropdowns() {
      const mobileDropdownToggles = mobileMenu.querySelectorAll(
        ".menu-item-has-children > .mobile-nav-link"
      );
      mobileDropdownToggles.forEach(function (toggle) {
        toggle.addEventListener("click", function (e) {
          // Only prevent default if this is a dropdown toggle (has children)
          const parentLi = this.closest(".menu-item-has-children");
          if (parentLi) {
            e.preventDefault();

            const dropdown = parentLi.querySelector(".mobile-dropdown");
            const arrow = this.querySelector("svg");

            if (dropdown) {
              dropdown.classList.toggle("open");
              this.classList.toggle("mobile-dropdown-toggle");
              this.classList.toggle("active");

              if (arrow) {
                arrow.style.transform = dropdown.classList.contains("open")
                  ? "rotate(180deg)"
                  : "rotate(0deg)";
              }
            }
          }
        });
      });

      // Close menu when clicking on non-dropdown links
      const mobileNavLinks = mobileMenu.querySelectorAll(
        ".mobile-nav-link:not(.menu-item-has-children > .mobile-nav-link)"
      );
      mobileNavLinks.forEach(function (link) {
        link.addEventListener("click", function () {
          mobileMenuButton.setAttribute("aria-expanded", "false");
          mobileMenu.classList.remove("mobile-menu-visible");
          mobileMenu.classList.add("mobile-menu-hidden");
          mobileMenuButton.classList.remove("active");
        });
      });
    }

    // Initialize dropdowns
    initDropdowns();
  }

  /**
   * Smooth scrolling for anchor links
   */
  function initSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(function (link) {
      link.addEventListener("click", function (event) {
        const targetId = this.getAttribute("href").substring(1);
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
          event.preventDefault();

          targetElement.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });

          // Update URL without jumping
          if (history.pushState) {
            history.pushState(null, null, "#" + targetId);
          }
        }
      });
    });
  }

  /**
   * Back to top button
   */
  // function initBackToTop() {
  //   // Create back to top button
  //   const backToTopButton = document.createElement("button");
  //   backToTopButton.innerHTML = `
  //           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  //               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
  //           </svg>
  //       `;
  //   backToTopButton.className =
  //     "fixed bottom-8 right-8 bg-red text-white p-3 rounded-full shadow-lg hover:bg-red-800 transition-all duration-300 opacity-0 invisible z-50";
  //   backToTopButton.setAttribute("aria-label", "Terug naar boven");
  //   backToTopButton.id = "back-to-top";

  //   document.body.appendChild(backToTopButton);

  //   // Show/hide button based on scroll position
  //   window.addEventListener("scroll", function () {
  //     if (window.pageYOffset > 300) {
  //       backToTopButton.classList.remove("opacity-0", "invisible");
  //       backToTopButton.classList.add("opacity-100", "visible");
  //     } else {
  //       backToTopButton.classList.add("opacity-0", "invisible");
  //       backToTopButton.classList.remove("opacity-100", "visible");
  //     }
  //   });

  //   // Scroll to top functionality
  //   backToTopButton.addEventListener("click", function () {
  //     window.scrollTo({
  //       top: 0,
  //       behavior: "smooth",
  //     });
  //   });
  // }

  /**
   * Newsletter form functionality
   */
  function initNewsletterForm() {
    const newsletterForm = document.querySelector(".newsletter-form");

    if (!newsletterForm) return;

    newsletterForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const emailInput = this.querySelector('input[type="email"]');
      const submitButton = this.querySelector('button[type="submit"]');

      if (!emailInput || !submitButton) return;

      const email = emailInput.value.trim();

      // Basic email validation
      if (!isValidEmail(email)) {
        showNotification("Voer een geldig email adres in.", "error");
        return;
      }

      // Show loading state
      const originalButtonText = submitButton.textContent;
      submitButton.textContent = "Bezig...";
      submitButton.disabled = true;

      // Simulate API call (replace with actual implementation)
      setTimeout(function () {
        showNotification("Bedankt voor je aanmelding!", "success");
        emailInput.value = "";
        submitButton.textContent = originalButtonText;
        submitButton.disabled = false;
      }, 1000);
    });
  }

  /**
   * Enhanced accessibility features
   */
  function initAccessibility() {
    // Add skip link
    const skipLink = document.createElement("a");
    skipLink.href = "#main";
    skipLink.textContent = "Ga naar hoofdinhoud";
    skipLink.className = "skip-link";
    document.body.insertBefore(skipLink, document.body.firstChild);

    // Keyboard navigation for dropdowns
    const menuItems = document.querySelectorAll(".main-navigation a");
    menuItems.forEach(function (item, index) {
      item.addEventListener("keydown", function (event) {
        if (event.key === "ArrowDown" && menuItems[index + 1]) {
          event.preventDefault();
          menuItems[index + 1].focus();
        } else if (event.key === "ArrowUp" && menuItems[index - 1]) {
          event.preventDefault();
          menuItems[index - 1].focus();
        }
      });
    });

    // Focus management for modals/overlays
    document.addEventListener("keydown", function (event) {
      if (event.key === "Tab") {
        trapFocus(event);
      }
    });
  }

  /**
   * Utility Functions
   */

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function showNotification(message, type) {
    const notification = document.createElement("div");
    notification.textContent = message;
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transition-all duration-300 ${
      type === "success" ? "bg-green-600" : "bg-red-600"
    }`;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(function () {
      notification.style.transform = "translateX(0)";
    }, 100);

    // Remove after 3 seconds
    setTimeout(function () {
      notification.style.transform = "translateX(100%)";
      setTimeout(function () {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification);
        }
      }, 300);
    }, 3000);
  }

  function trapFocus(event) {
    const modal = document.querySelector('[aria-modal="true"]');
    if (!modal) return;

    const focusableElements = modal.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );

    const firstFocusableElement = focusableElements[0];
    const lastFocusableElement =
      focusableElements[focusableElements.length - 1];

    if (event.shiftKey) {
      if (document.activeElement === firstFocusableElement) {
        lastFocusableElement.focus();
        event.preventDefault();
      }
    } else {
      if (document.activeElement === lastFocusableElement) {
        firstFocusableElement.focus();
        event.preventDefault();
      }
    }
  }

  // Lazy loading for images (if IntersectionObserver is supported)
  if ("IntersectionObserver" in window) {
    const imageObserver = new IntersectionObserver(function (
      entries,
      observer
    ) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove("lazy");
          observer.unobserve(img);
        }
      });
    });

    document.querySelectorAll("img[data-src]").forEach(function (img) {
      imageObserver.observe(img);
    });
  }

  /**
   * Image Text Block Animations
   * Animate images sequentially (scale 0 to 1) when block enters viewport
   */
  function initImageTextAnimations() {
    const imageTextBlocks = document.querySelectorAll(".js-image-text-animate");

    if (imageTextBlocks.length === 0) return;

    // Check if IntersectionObserver is supported
    if (!("IntersectionObserver" in window)) {
      // Fallback: animate immediately if IntersectionObserver is not supported
      imageTextBlocks.forEach(function (block) {
        animateImages(block);
      });
      return;
    }

    const observerOptions = {
      root: null,
      rootMargin: "0px",
      threshold: 0.2, // Trigger when 20% of the block is visible
    };

    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          const block = entry.target;
          animateImages(block);
          // Unobserve after animation is triggered
          observer.unobserve(block);
        }
      });
    }, observerOptions);

    // Observe all image text blocks
    imageTextBlocks.forEach(function (block) {
      observer.observe(block);
    });
  }

  /**
   * Animate images sequentially
   */
  function animateImages(block) {
    const images = block.querySelectorAll(".js-image-animate");

    if (images.length === 0) return;

    // Animate each image sequentially with random delays
    // First image goes to scale 1.2, others go to scale 1
    images.forEach(function (image, index) {
      // Base delay with random variation (between -100ms and +200ms)
      const baseDelay = index * 150;
      const randomVariation = Math.random() * 300 - 100; // Random between -100 and 200
      const delay = Math.max(0, baseDelay + randomVariation); // Ensure delay is never negative
      
      
        if (image.classList.contains("js-image-animate-first")) {
          image.classList.add("animate-first");
        } else {
          image.classList.add("animate");
        }
     
    });
  }

  /**
   * FAQ Accordion Functionality
   */
  function initFaqAccordion() {
    const faqToggles = document.querySelectorAll("[data-faq-toggle]");

    // Helper function to close an accordion item
    function closeAccordionItem(toggle) {
      const answerId = toggle.getAttribute("aria-controls");
      const answer = document.getElementById(answerId);
      const icon = toggle.querySelector(".faq-icon");
      
      if (!answer || !icon) return;

      const verticalLine = icon.querySelector(".faq-icon-vertical");
      const horizontalLine = icon.querySelector(".faq-icon-horizontal");

      toggle.setAttribute("aria-expanded", "false");
      answer.setAttribute("aria-hidden", "true");
      answer.style.maxHeight = "0";
      
      // Herstel border-radius: verwijder rounded-b-none en voeg rounded-b-[16px] toe
      toggle.classList.remove("rounded-b-none");
      toggle.classList.add("rounded-b-[16px]");
      
      if (verticalLine) verticalLine.style.transform = "rotate(0deg)";
      if (horizontalLine) horizontalLine.style.opacity = "1";
    }

    // Helper function to open an accordion item
    function openAccordionItem(toggle) {
      const answerId = toggle.getAttribute("aria-controls");
      const answer = document.getElementById(answerId);
      const icon = toggle.querySelector(".faq-icon");
      
      if (!answer || !icon) return;

      const verticalLine = icon.querySelector(".faq-icon-vertical");
      const horizontalLine = icon.querySelector(".faq-icon-horizontal");

      toggle.setAttribute("aria-expanded", "true");
      answer.setAttribute("aria-hidden", "false");
      
      // Verwijder border-radius onderkant van button: verwijder rounded-b-[16px] en voeg rounded-b-none toe
      toggle.classList.remove("rounded-b-[16px]");
      toggle.classList.add("rounded-b-none");
      
      // Tijdelijk max-height verwijderen om correcte scrollHeight te krijgen
      const currentMaxHeight = answer.style.maxHeight;
      answer.style.maxHeight = "none";
      const height = answer.scrollHeight;
      answer.style.maxHeight = currentMaxHeight;
      
      // Force reflow
      answer.offsetHeight;
      
      // Nu de animatie starten met de correcte hoogte
      answer.style.maxHeight = height + "px";
      
      if (verticalLine) verticalLine.style.transform = "rotate(90deg)";
      if (horizontalLine) horizontalLine.style.opacity = "1";
    }

    faqToggles.forEach(function (toggle) {
      toggle.addEventListener("click", function (e) {
        e.preventDefault();

        const isExpanded = this.getAttribute("aria-expanded") === "true";

        if (isExpanded) {
          // Closing the clicked item
          closeAccordionItem(this);
        } else {
          // Opening the clicked item - close all others first
          faqToggles.forEach(function (otherToggle) {
            if (otherToggle !== toggle) {
              const otherIsExpanded = otherToggle.getAttribute("aria-expanded") === "true";
              if (otherIsExpanded) {
                closeAccordionItem(otherToggle);
              }
            }
          });
          
          // Open the clicked item
          openAccordionItem(this);
        }
      });
    });
  }

  /**
   * Archive Load More
   */
  function initArchiveLoadMore() {
    const button = document.getElementById("archive-load-more");
    const grid = document.getElementById("archive-post-grid");

    if (!button || !grid || typeof window.advice2025ArchiveLoadMore === "undefined") {
      return;
    }

    const ajaxUrl = window.advice2025ArchiveLoadMore.ajaxUrl;
    const nonce = window.advice2025ArchiveLoadMore.nonce;
    const maxPages = parseInt(button.dataset.maxPages || "1", 10);
    const originalLabel = button.textContent;
    let currentPage = parseInt(button.dataset.currentPage || "1", 10);
    let isLoading = false;

    function setLoadingState(loading) {
      isLoading = loading;
      button.disabled = loading;
      button.textContent = loading ? "Bezig met laden..." : originalLabel;
    }

    function hideButton() {
      button.classList.add("hidden");
      button.disabled = true;
    }

    button.addEventListener("click", function () {
      if (isLoading) return;
      if (currentPage >= maxPages) {
        hideButton();
        return;
      }

      const nextPage = currentPage + 1;
      const queryVars = button.dataset.queryVars || "";
      const payload = new URLSearchParams();
      payload.append("action", "archive_load_more");
      payload.append("nonce", nonce);
      payload.append("page", String(nextPage));
      payload.append("query_vars", queryVars);

      setLoadingState(true);

      fetch(ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: payload.toString(),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (result) {
          if (!result || !result.success || !result.data) {
            throw new Error("Invalid AJAX response");
          }

          if (result.data.html) {
            grid.insertAdjacentHTML("beforeend", result.data.html);
          }

          currentPage = nextPage;
          button.dataset.currentPage = String(currentPage);

          if (!result.data.hasMore || currentPage >= maxPages) {
            hideButton();
            return;
          }

          setLoadingState(false);
        })
        .catch(function () {
          setLoadingState(false);
        });
    });
  }

  /**
   * Posts archive search (AJAX)
   */
  function initPostsArchiveSearch() {
    const archiveContainer = document.querySelector("[data-posts-archive-search]");
    const grid = document.getElementById("archive-post-grid");
    const searchForm = document.querySelector(".search_filter .search .search-form");
    const searchInput = searchForm ? searchForm.querySelector('input[name="s"]') : null;
    const loadMoreButton = document.getElementById("archive-load-more");

    if (!archiveContainer || !grid || !searchForm || !searchInput || typeof window.advice2025ArchiveLoadMore === "undefined") {
      return;
    }

    const ajaxUrl = window.advice2025ArchiveLoadMore.ajaxUrl;
    const nonce = window.advice2025ArchiveLoadMore.nonce;
    let baseQueryVars = {};
    let debounceTimer = null;
    let activeRequestId = 0;

    try {
      baseQueryVars = JSON.parse(archiveContainer.dataset.queryVars || "{}");
    } catch (error) {
      baseQueryVars = {};
    }

    function setButtonVisibility(shouldShow) {
      if (!loadMoreButton) return;

      if (shouldShow) {
        loadMoreButton.classList.remove("hidden");
        loadMoreButton.disabled = false;
      } else {
        loadMoreButton.classList.add("hidden");
        loadMoreButton.disabled = true;
      }
    }

    function fetchResults(searchTerm, activeFilters) {
      activeRequestId += 1;
      const requestId = activeRequestId;

      const queryVars = Object.assign({}, baseQueryVars, {
        s: searchTerm,
        advice2025_filters: activeFilters || {},
      });

      const payload = new URLSearchParams();
      payload.append("action", "archive_load_more");
      payload.append("nonce", nonce);
      payload.append("page", "1");
      payload.append("query_vars", JSON.stringify(queryVars));

      grid.classList.add("opacity-60");

      fetch(ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: payload.toString(),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (result) {
          if (requestId !== activeRequestId) {
            return;
          }

          if (!result || !result.success || !result.data) {
            throw new Error("Invalid AJAX response");
          }

          if (result.data.html) {
            grid.innerHTML = result.data.html;
          } else {
            grid.innerHTML = '<p class="col-span-full text-[18px]">Geen resultaten gevonden.</p>';
          }

          if (loadMoreButton) {
            loadMoreButton.dataset.currentPage = "1";
            loadMoreButton.dataset.queryVars = JSON.stringify(queryVars);
          }

          setButtonVisibility(Boolean(result.data.hasMore));
        })
        .catch(function () {
          if (requestId === activeRequestId) {
            grid.innerHTML = '<p class="col-span-full text-[18px]">Geen resultaten gevonden.</p>';
            setButtonVisibility(false);
          }
        })
        .finally(function () {
          if (requestId === activeRequestId) {
            grid.classList.remove("opacity-60");
          }
        });
    }

    function scheduleSearch() {
      if (debounceTimer) {
        window.clearTimeout(debounceTimer);
      }

      debounceTimer = window.setTimeout(function () {
        const event = new CustomEvent("advice2025:archive-search", {
          detail: {
            searchTerm: searchInput.value.trim(),
          },
        });
        document.dispatchEvent(event);
      }, 300);
    }

    searchForm.addEventListener("submit", function (submitEvent) {
      submitEvent.preventDefault();
      if (debounceTimer) {
        window.clearTimeout(debounceTimer);
      }

      const searchEvent = new CustomEvent("advice2025:archive-search", {
        detail: {
          searchTerm: searchInput.value.trim(),
        },
      });
      document.dispatchEvent(searchEvent);
    });

    searchInput.addEventListener("input", scheduleSearch);

    document.addEventListener("advice2025:archive-search", function (event) {
      function collectFiltersFromUI() {
        const activeFilters = {};
        const activeCheckboxes = document.querySelectorAll("[data-archive-filter-panel] [data-filter-term]:checked");

        activeCheckboxes.forEach(function (checkbox) {
          const taxonomy = checkbox.getAttribute("data-taxonomy");
          const value = parseInt(checkbox.value || "0", 10);

          if (!taxonomy || !value) {
            return;
          }

          if (!activeFilters[taxonomy]) {
            activeFilters[taxonomy] = [];
          }

          activeFilters[taxonomy].push(value);
        });

        return activeFilters;
      }

      const detail = event && event.detail ? event.detail : {};
      const searchTerm = typeof detail.searchTerm === "string" ? detail.searchTerm : searchInput.value.trim();
      const activeFilters = detail.activeFilters && typeof detail.activeFilters === "object"
        ? detail.activeFilters
        : collectFiltersFromUI();

      fetchResults(searchTerm, activeFilters);
    });
  }

  /**
   * Reusable archive filter panels
   */
  function initArchiveFilterPanels() {
    const panels = document.querySelectorAll("[data-archive-filter-panel]");
    const searchInput = document.querySelector(".search_filter .search .search-form input[name='s']");

    if (panels.length === 0) {
      return;
    }

    panels.forEach(function (panel) {
      const openButton = panel.querySelector("[data-archive-filter-open]");
      const closeButton = panel.querySelector("[data-archive-filter-close]");
      const overlay = panel.querySelector("[data-archive-filter-overlay]");
      const drawer = panel.querySelector("[data-archive-filter-drawer]");
      const applyButton = panel.querySelector("[data-archive-filter-apply]");
      const resetButton = panel.querySelector("[data-archive-filter-reset]");
      const checkboxes = panel.querySelectorAll("[data-filter-term]");
      const filterIndicator = panel.querySelector("[data-archive-filter-indicator]");

      if (!openButton || !closeButton || !overlay || !drawer || !applyButton || !resetButton) {
        return;
      }

      function getActiveFilters() {
        const activeFilters = {};

        checkboxes.forEach(function (checkbox) {
          if (!checkbox.checked) {
            return;
          }

          const taxonomy = checkbox.getAttribute("data-taxonomy");
          const value = parseInt(checkbox.value || "0", 10);

          if (!taxonomy || !value) {
            return;
          }

          if (!activeFilters[taxonomy]) {
            activeFilters[taxonomy] = [];
          }

          activeFilters[taxonomy].push(value);
        });

        return activeFilters;
      }

      function updateFilterIndicator() {
        if (!filterIndicator) return;
        const hasActiveFilters = Array.from(checkboxes).some(function (checkbox) {
          return checkbox.checked;
        });
        filterIndicator.classList.toggle("hidden", !hasActiveFilters);
      }

      // Moet exact overeenkomen met template-parts/archive-filter-panel.php (drawer translate off-screen).
      const drawerHiddenClass = "translate-x-[calc(100%+2.5rem)]";

      function openDrawer() {
        overlay.classList.remove("hidden");
        drawer.classList.remove(drawerHiddenClass);
        openButton.setAttribute("aria-expanded", "true");
        document.body.classList.add("overflow-hidden");
      }

      function closeDrawer() {
        overlay.classList.add("hidden");
        drawer.classList.add(drawerHiddenClass);
        openButton.setAttribute("aria-expanded", "false");
        document.body.classList.remove("overflow-hidden");
      }

      function resetFilters() {
        checkboxes.forEach(function (checkbox) {
          checkbox.checked = false;
        });
        updateFilterIndicator();
      }

      function applyFilters() {
        updateFilterIndicator();
        const event = new CustomEvent("advice2025:archive-search", {
          detail: {
            searchTerm: searchInput ? searchInput.value.trim() : "",
            activeFilters: getActiveFilters(),
          },
        });
        document.dispatchEvent(event);
        closeDrawer();
      }

      openButton.addEventListener("click", openDrawer);
      closeButton.addEventListener("click", closeDrawer);
      overlay.addEventListener("click", closeDrawer);
      applyButton.addEventListener("click", applyFilters);
      checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", updateFilterIndicator);
      });
      resetButton.addEventListener("click", function () {
        resetFilters();
        applyFilters();
      });

      document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && !overlay.classList.contains("hidden")) {
          closeDrawer();
        }
      });

      updateFilterIndicator();
    });
  }

  /**
   * Marquee block animations
   */
  function initMarquee() {
    const marquees = document.querySelectorAll("[data-marquee]");
    if (marquees.length === 0) return;

    marquees.forEach(function (marquee) {
      const track = marquee.querySelector("[data-marquee-track]");
      const groups = marquee.querySelectorAll(".marquee__group");

      if (!track || groups.length < 2) return;
      if (typeof window.gsap === "undefined") return;

      const motionPreference = window.matchMedia("(prefers-reduced-motion: reduce)");
      const speedPxPerSecond = 80;
      let tween = null;
      let resizeTimeout = null;

      function killTween() {
        if (tween) {
          tween.kill();
          tween = null;
        }
      }

      function createTween() {
        killTween();
        window.gsap.set(track, { x: 0 });

        if (motionPreference.matches) {
          return;
        }

        const loopDistance = groups[0].offsetWidth;
        if (!loopDistance || loopDistance <= 0) {
          return;
        }

        const duration = loopDistance / speedPxPerSecond;

        tween = window.gsap.to(track, {
          x: -loopDistance,
          duration: duration,
          ease: "none",
          repeat: -1,
        });
      }

      function pauseTween() {
        if (tween) tween.pause();
      }

      function playTween() {
        if (tween && !motionPreference.matches) tween.play();
      }

    //   marquee.addEventListener("mouseenter", pauseTween);
    //   marquee.addEventListener("mouseleave", playTween);
    //   marquee.addEventListener("focusin", pauseTween);
    //   marquee.addEventListener("focusout", playTween);

      window.addEventListener("resize", function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(createTween, 150);
      });

      if (typeof motionPreference.addEventListener === "function") {
        motionPreference.addEventListener("change", createTween);
      } else if (typeof motionPreference.addListener === "function") {
        motionPreference.addListener(createTween);
      }

      createTween();
    });
  }
})();

// Image + Text layout logic
(function () {
  "use strict";

  function initImageTextLayout() {
    const blocks = document.querySelectorAll(".js-image-text.js-image-text--bg-empty");
    if (blocks.length === 0) return;

    const applyLayout = () => {
      const isLarge = window.matchMedia("(min-width: 1024px)").matches; // lg breakpoint

      blocks.forEach((block) => {
        const row = block.querySelector(".js-it-row");
        const contentEl = block.querySelector(".js-it-content");
        const imageEl = block.querySelector(".js-it-image");
        if (!row || !contentEl || !imageEl) return;

        // Reset for non-lg: center items, no sticky
        if (!isLarge) {
          row.classList.remove("items-start");
          row.classList.add("items-center");
          imageEl.classList.remove("lg:sticky", "top-24");
          imageEl.classList.add("relative");
          return;
        }

        const contentRect = contentEl.getBoundingClientRect();
        const imageRect = imageEl.getBoundingClientRect();

        if (contentRect.height > imageRect.height + 1) {
          // Content taller -> align top and make image sticky
          row.classList.remove("items-center");
          row.classList.add("items-start");
          imageEl.classList.remove("relative");
          imageEl.classList.add("lg:sticky", "top-24");
        } else {
          // Content not taller -> center and remove sticky
          row.classList.remove("items-start");
          row.classList.add("items-center");
          imageEl.classList.add("relative");
          imageEl.classList.remove("lg:sticky", "top-24");
        }
      });
    };

    // Initial run and on resize
    applyLayout();
    window.addEventListener("resize", debounce(applyLayout, 150));
  }

  function debounce(fn, delay) {
    let t;
    return function () {
      clearTimeout(t);
      t = setTimeout(() => fn.apply(this, arguments), delay);
    };
  }

  /**
   * Gravity Forms Floating Labels
   * Handles floating label effect for Gravity Forms inputs
   */
  function initGravityFormsFloatingLabels() {
    // Function to check if input has value and update field state
    function updateFieldState(field, input) {
      const hasValue = input.value && input.value.trim() !== "";
      const isFocused = document.activeElement === input;

      if (hasValue || isFocused) {
        field.classList.add("gfield--has-value");
        if (isFocused) {
          field.classList.add("gfield--focused");
        } else {
          field.classList.remove("gfield--focused");
        }
      } else {
        field.classList.remove("gfield--has-value", "gfield--focused");
      }
    }

    // Function to setup floating labels for a form
    function setupFloatingLabels(form) {
      // Get all input fields (text, email, tel, url, number, password)
      const textInputs = form.querySelectorAll(
        'input[type="text"], input[type="email"], input[type="tel"], input[type="url"], input[type="number"], input[type="password"]'
      );
      
      // Get all textareas
      const textareas = form.querySelectorAll("textarea");

      // Combine inputs and textareas
      const allInputs = [...textInputs, ...textareas];

      allInputs.forEach((input) => {
        // Skip if input is radio, checkbox, or file
        if (
          input.type === "radio" ||
          input.type === "checkbox" ||
          input.type === "file"
        ) {
          return;
        }

        // Find the parent field
        const field = input.closest(".gfield");
        if (!field) return;

        // Skip certain field types
        if (
          field.classList.contains("gfield--input-type-fileupload") ||
          field.classList.contains("gfield--type-radio") ||
          field.classList.contains("gfield--type-checkbox") ||
          field.classList.contains("gfield--type-select")
        ) {
          return;
        }

        // Find the label and input container
        const label = field.querySelector(".gfield_label");
        const inputContainer = field.querySelector(".ginput_container");

        // Move label inside input container if it exists and hasn't been moved yet
        if (label && inputContainer && !inputContainer.contains(label)) {
          // Check if label is already moved (has a data attribute)
          if (!label.hasAttribute("data-floating-label-moved")) {
            inputContainer.insertBefore(label, inputContainer.firstChild);
            label.setAttribute("data-floating-label-moved", "true");
          }
        }

        // Check initial state
        updateFieldState(field, input);

        // Handle focus
        input.addEventListener("focus", function () {
          field.classList.add("gfield--focused");
          field.classList.add("gfield--has-value");
        });

        // Handle blur
        input.addEventListener("blur", function () {
          field.classList.remove("gfield--focused");
          updateFieldState(field, input);
        });

        // Handle input (for real-time updates)
        input.addEventListener("input", function () {
          updateFieldState(field, input);
        });

        // Handle change (for select-like behaviors)
        input.addEventListener("change", function () {
          updateFieldState(field, input);
        });
      });
    }

    // Setup for existing forms
    const forms = document.querySelectorAll(".gform_wrapper");
    forms.forEach((form) => {
      setupFloatingLabels(form);
    });

    // Setup for dynamically loaded forms (Gravity Forms AJAX)
    if (typeof jQuery !== "undefined") {
      jQuery(document).on("gform_post_render", function (event, formId, currentPage) {
        const form = document.querySelector(`.gform_wrapper[data-formid="${formId}"]`);
        if (form) {
          setupFloatingLabels(form);
        }
      });
    }
  }

  // Expose initializer to the main scope if needed
  window.initImageTextLayout = initImageTextLayout;
  window.initGravityFormsFloatingLabels = initGravityFormsFloatingLabels;

  /**
   * Gravity Forms Custom Selects
   * Replaces native <select> with accessible custom dropdown matching design
   */
  function initGravityFormsCustomSelects() {
    function buildCustomSelect(nativeSelect) {
      if (nativeSelect.dataset.msEnhanced === "true") return; // prevent double init
      if (nativeSelect.multiple) return; // skip multiselects

      const field = nativeSelect.closest(".gfield");
      const container = nativeSelect.closest(".ginput_container");
      if (!field || !container) return;

      // Wrapper
      const wrapper = document.createElement("div");
      wrapper.className = "ms-select";
      wrapper.setAttribute("data-name", nativeSelect.name || "");

      // Trigger/button
      const trigger = document.createElement("button");
      trigger.type = "button";
      trigger.className = "ms-select__trigger";
      trigger.setAttribute("aria-haspopup", "listbox");
      trigger.setAttribute("aria-expanded", "false");
      trigger.innerHTML = `<span class="ms-select__label"></span><span class="ms-select__icon" aria-hidden="true"></span>`;

      // Listbox
      const listbox = document.createElement("div");
      listbox.className = "ms-select__dropdown";
      listbox.setAttribute("role", "listbox");
      listbox.tabIndex = -1;

      // Options
      const options = Array.from(nativeSelect.options);
      let selectedIndex = nativeSelect.selectedIndex;
      options.forEach((opt, idx) => {
        const optionEl = document.createElement("div");
        optionEl.className = "ms-select__option";
        optionEl.setAttribute("role", "option");
        optionEl.setAttribute("data-value", opt.value);
        optionEl.textContent = opt.textContent;
        if (opt.disabled) optionEl.setAttribute("aria-disabled", "true");
        if (idx === selectedIndex && !opt.disabled) {
          optionEl.setAttribute("aria-selected", "true");
          optionEl.classList.add("is-selected");
        }
        listbox.appendChild(optionEl);
      });

      // Insert into DOM before native select
      nativeSelect.style.position = "absolute";
      nativeSelect.style.pointerEvents = "none";
      nativeSelect.style.opacity = "0";
      nativeSelect.style.width = "100%";
      nativeSelect.style.height = "100%";
      nativeSelect.tabIndex = -1;
      nativeSelect.dataset.msEnhanced = "true";

      wrapper.appendChild(trigger);
      wrapper.appendChild(listbox);
      container.appendChild(wrapper);

      // Helpers
      function closeDropdown() {
        wrapper.classList.remove("is-open");
        trigger.setAttribute("aria-expanded", "false");
      }
      function openDropdown() {
        wrapper.classList.add("is-open");
        trigger.setAttribute("aria-expanded", "true");
        listbox.focus();
      }
      function setSelectedByIndex(index, emitChange = true) {
        const opts = Array.from(listbox.querySelectorAll(".ms-select__option"));
        if (index < 0 || index >= opts.length) return;
        const optEl = opts[index];
        if (optEl.getAttribute("aria-disabled") === "true") return;
        opts.forEach(o => {
          o.classList.remove("is-selected");
          o.removeAttribute("aria-selected");
        });
        optEl.classList.add("is-selected");
        optEl.setAttribute("aria-selected", "true");
        const value = optEl.getAttribute("data-value");
        const label = optEl.textContent || "";
        trigger.querySelector(".ms-select__label").textContent = label;
        nativeSelect.value = value;
        selectedIndex = index;
        // Update floating label state on field
        field.classList.add("gfield--has-value");
        if (emitChange) {
          nativeSelect.dispatchEvent(new Event("change", { bubbles: true }));
          nativeSelect.dispatchEvent(new Event("input", { bubbles: true }));
        }
      }

      // Initialize label/value
      if (selectedIndex >= 0 && !options[selectedIndex].disabled) {
        setSelectedByIndex(selectedIndex, false);
      } else {
        trigger.querySelector(".ms-select__label").textContent = options[0] ? options[0].textContent : "";
        field.classList.remove("gfield--has-value");
      }

      // Events
      trigger.addEventListener("click", (e) => {
        e.preventDefault();
        if (wrapper.classList.contains("is-open")) {
          closeDropdown();
        } else {
          openDropdown();
        }
      });

      listbox.addEventListener("click", (e) => {
        const optionEl = e.target.closest(".ms-select__option");
        if (!optionEl || optionEl.getAttribute("aria-disabled") === "true") return;
        const idx = Array.from(listbox.children).indexOf(optionEl);
        setSelectedByIndex(idx);
        closeDropdown();
        trigger.focus();
      });

      // Keyboard navigation
      function handleKeyNav(e) {
        const opts = Array.from(listbox.querySelectorAll(".ms-select__option"));
        let nextIndex = selectedIndex;
        switch (e.key) {
          case "ArrowDown":
            e.preventDefault();
            do {
              nextIndex = Math.min(opts.length - 1, nextIndex + 1);
            } while (opts[nextIndex] && opts[nextIndex].getAttribute("aria-disabled") === "true" && nextIndex < opts.length - 1);
            setSelectedByIndex(nextIndex);
            break;
          case "ArrowUp":
            e.preventDefault();
            do {
              nextIndex = Math.max(0, nextIndex - 1);
            } while (opts[nextIndex] && opts[nextIndex].getAttribute("aria-disabled") === "true" && nextIndex > 0);
            setSelectedByIndex(nextIndex);
            break;
          case "Enter":
          case " ":
            e.preventDefault();
            if (wrapper.classList.contains("is-open")) {
              closeDropdown();
              trigger.focus();
            } else {
              openDropdown();
            }
            break;
          case "Escape":
            e.preventDefault();
            closeDropdown();
            trigger.focus();
            break;
          default:
            break;
        }
      }
      trigger.addEventListener("keydown", (e) => {
        if (e.key === "ArrowDown" || e.key === "ArrowUp" || e.key === " " || e.key === "Enter") {
          e.preventDefault();
          openDropdown();
        }
      });
      listbox.addEventListener("keydown", handleKeyNav);

      // Close on outside click
      document.addEventListener("click", (e) => {
        if (!wrapper.contains(e.target)) closeDropdown();
      });

      // Sync from native select if it changes externally
      nativeSelect.addEventListener("change", () => {
        const idx = nativeSelect.selectedIndex;
        setSelectedByIndex(idx, false);
      });
    }

    // Initialize all selects within Gravity Forms
    const allSelects = document.querySelectorAll(".gform_wrapper .gfield--type-select select");
    allSelects.forEach(buildCustomSelect);

    // Re-init on Gravity Forms AJAX render
    if (typeof jQuery !== "undefined") {
      jQuery(document).on("gform_post_render", function () {
        const newSelects = document.querySelectorAll(".gform_wrapper .gfield--type-select select");
        newSelects.forEach(buildCustomSelect);
      });
    }
  }

  window.initGravityFormsCustomSelects = initGravityFormsCustomSelects;



  document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
    }
});
})();

