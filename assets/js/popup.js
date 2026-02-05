/**
 * Popup Management Script
 * Handles opening/closing popups and lazy loading forms
 */

(function() {
    'use strict';

    // === UTILITY FUNCTIES ===
    
    // Helper functie voor HTML escaping
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // === TEMPLATE FUNCTIES VOOR POPUP HTML ===
    
    // Functie voor checkmark icon SVG
    function getCheckmarkIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
            <circle cx="12.5" cy="12.5" r="12.5" fill="#96ACC0"/>
            <rect x="7.92969" y="12.0708" width="6" height="1" transform="rotate(45 7.92969 12.0708)" fill="#0A2031"/>
            <rect x="18.5352" y="8.53564" width="1" height="11" transform="rotate(45 18.5352 8.53564)" fill="#0A2031"/>
        </svg>`;
    }
    
    // Functie voor backgrounds HTML generatie
    function buildBackgroundsHTML(color, scale, scaleLg) {
        const bgColors = {
            'primary': {
                'gradient-1': 'conic-gradient(from 180deg at 50% 50%, #0F2030 0deg, #08334A 180deg, #0F2030 360deg)',
                'gradient-2': 'conic-gradient(from 180deg at 50% 50%, #08334A 0deg, #0F2030 180deg, #08334A 360deg)',
            },
            'blue': {
                'gradient-1': 'conic-gradient(from 180deg at 50% 50%, #0F2030 0deg, #08334A 180deg, #0F2030 360deg)',
                'gradient-2': 'conic-gradient(from 180deg at 50% 50%, #08334A 0deg, #0F2030 180deg, #08334A 360deg)',
            },
            'secondary': {
                'gradient-1': 'conic-gradient(from 180deg at 50% 50%, #480E25 0deg, #6C0733 180deg, #480E25 360deg)',
                'gradient-2': 'conic-gradient(from 180deg at 50% 50%, #6C0733 0deg, #480E25 180deg, #6C0733 360deg)',
            },
            'pink': {
                'gradient-1': 'conic-gradient(from 180deg at 50% 50%, #480E25 0deg, #6C0733 180deg, #480E25 360deg)',
                'gradient-2': 'conic-gradient(from 180deg at 50% 50%, #6C0733 0deg, #480E25 180deg, #6C0733 360deg)',
            },
        };
        
        const selectedColor = bgColors[color] || bgColors['primary'];
        const scaleValue = scale || '80';
        const scaleLgValue = scaleLg || '80';
        
        let html = `<div class="backgrounds w-[3535px] h-[3535px] rotate-[-45deg] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 scale-${scaleValue} lg:scale-${scaleLgValue}">`;
        html += `<div class="background absolute top-0 left-0 w-full h-full" style="background: ${selectedColor['gradient-1']}"></div>`;
        html += `<div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[3093px] h-[3093px]" style="background: ${selectedColor['gradient-2']}"></div>`;
        html += `<div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[2649px] h-[2649px]" style="background: ${selectedColor['gradient-1']}"></div>`;
        html += `<div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[2205px] h-[2205px]" style="background: ${selectedColor['gradient-2']}"></div>`;
        html += `<div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1761px] h-[1761px]" style="background: ${selectedColor['gradient-1']}"></div>`;
        html += `<div class="background absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1317px] h-[1317px] bg-${color}"></div>`;
        html += `</div>`;
        
        return html;
    }
    
    // Template voor linker sectie (afbeelding + content + USPs)
    function buildPopupLeftSection(popup) {
        let html = '<div class="relative w-full h-full lg:w-1/2">';
        html += '<div class="popup-scroll-section popup-left bg-[#0a2031] lg:overflow-y-auto lg:max-h-[90vh] flex flex-col">';
        
        // Afbeelding
        if (popup.image) {
            html += `<div class="w-full h-[200px] order-2 lg:order-1 lg:h-[415px] flex-shrink-0 overflow-hidden">`;
            html += `<img src="${escapeHtml(popup.image.url)}" alt="${escapeHtml(popup.image.alt || popup.title)}" class="w-full h-full object-cover object-center">`;
            html += `</div>`;
        }
        
        // Content sectie
        html += '<div class="flex-1 bg-[#0a2031] relative overflow-hidden order-1 lg:order-2 pt-[60px] xl:pt-[80px] px-[20px] lg:px-[60px] xl:px-[80px] pb-[40px] lg:pb-[80px] xl:pb-[100px]">';
        // Backgrounds element
        const backgroundColor = popup.background_color || 'primary';
        html += buildBackgroundsHTML(backgroundColor, '43', '40');
        // Content tekst
        if (popup.content) {
            html += `<div class="text-white text-[13px] lg:text-[15px] leading-[1.8] font-[\'Poppins\',sans-serif] font-normal mb-[40px] relative z-10">`;
            html += popup.content;
            html += `</div>`;
        }
        
        // USPs
        if (popup.usps && popup.usps.length > 0) {
            html += '<div class="relative z-10">';
            popup.usps.forEach(usp => {
                html += `<div class="flex items-start gap-[12px]">`;
                html += getCheckmarkIcon();
                html += `<p class="text-white text-[13px] lg:text-[15px] leading-[1.8] font-[\'Poppins\',sans-serif] font-medium">${escapeHtml(usp)}</p>`;
                html += `</div>`;
            });
            html += '</div>';
        }
        
        html += '</div>'; // Sluit content sectie
        html += '</div>'; // Sluit scrollbare sectie
        html += '<div class="scroll-gradient-overlay scroll-gradient-left"></div>';
        html += '</div>'; // Sluit wrapper
        
        return html;
    }
    
    // Template voor rechter sectie (labels + titel + formulier placeholder)
    function buildPopupRightSection(popup) {
        let html = '<div class="relative w-full h-full lg:w-1/2">';
        html += '<div class="popup-scroll-section popup-right bg-[#e5e8eb] lg:overflow-y-auto lg:max-h-[90vh] px-[20px] lg:px-[60px] xl:px-[80px] py-[60px] xl:py-[80px] xl:pr-[120px]">';
        
        // Labels/badges
        if (popup.labels && popup.labels.length > 0) {
            html += '<div class="flex flex-wrap gap-[10px] mb-[20px] lg:mb-[32px]">';
            popup.labels.forEach((label, index) => {
                if (index === 0) {
                    html += `<span class="label-medium text-white bg-blue border border-light-blue badge">${escapeHtml(label)}</span>`;
                } else {
                    html += `<span class="label-medium text-blue border border-light-blue badge">${escapeHtml(label)}</span>`;
                }
            });
            html += '</div>';
        }
        
        // Titel
        html += `<h2 id="popup-title" class="headline-medium mb-[40px]">${escapeHtml(popup.title)}</h2>`;
        
        // Formulier placeholder (wordt via AJAX geladen)
        if (popup.formulier_id) {
            html += '<div class="popup-form-wrapper" data-form-id="' + popup.formulier_id + '" style="min-height: 600px;">';
            html += '<div class="form-loading" style="padding: 40px; text-align: center;">';
            html += '<div class="spinner" style="border: 3px solid #e5e8eb; border-top: 3px solid #00344c; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>';
            html += '<p style="margin-top: 20px; color: #00344c;">Formulier laden...</p>';
            html += '</div>';
            html += '</div>';
        }
        
        html += '</div>'; // Sluit scrollbare sectie
        html += '<div class="scroll-gradient-overlay scroll-gradient-right"></div>';
        html += '</div>'; // Sluit wrapper
        
        return html;
    }
    
    // Functie om formulier via AJAX te laden
    function loadFormViaAjax(formId, wrapperElement, onLoadCallback) {
        // Check of ajaxurl beschikbaar is
        if (typeof popupData === 'undefined' || !popupData.ajaxUrl) {
            console.error('AJAX URL niet beschikbaar');
            wrapperElement.innerHTML = '<p style="color: #d32f2f; padding: 20px;">Fout bij laden van formulier.</p>';
            return;
        }
        
        // AJAX request naar WordPress
        fetch(popupData.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=load_popup_form&form_id=' + formId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                wrapperElement.innerHTML = data.data.html;

                // Zorg dat het formulier zichtbaar is (soms wordt het met display:none gerenderd)
                try {
                    const visibilityTarget = wrapperElement.querySelector('.gform_wrapper') || wrapperElement.querySelector('form');
                    if (visibilityTarget) {
                        // Verwijder inline display:none
                        visibilityTarget.style.removeProperty('display');
                        // Verwijder eventuele "onzichtbaar" klassen van Gravity Forms
                        if (visibilityTarget.classList) {
                            visibilityTarget.classList.remove('gf_invisible', 'gform_hidden');
                        }
                    }
                } catch (e) {
                    console.warn('Kon formulier zichtbaarheid niet aanpassen:', e);
                }
                
                // Trigger Gravity Forms events
                if (typeof jQuery !== 'undefined') {
                    setTimeout(() => {
                        jQuery(document).trigger('gform_post_render', [formId, 1]);
                        
                        const formWrapper = wrapperElement.querySelector('.gform_wrapper');
                        if (formWrapper) {
                            jQuery(formWrapper).trigger('gform_post_render');
                            
                            if (typeof window.initGravityFormsCustomSelects === 'function') {
                                window.initGravityFormsCustomSelects();
                            }
                            if (typeof window.initGravityFormsFloatingLabels === 'function') {
                                window.initGravityFormsFloatingLabels();
                            }
                        }
                        
                        // Callback uitvoeren na form init
                        if (onLoadCallback) {
                            onLoadCallback();
                        }
                    }, 100);
                } else if (onLoadCallback) {
                    onLoadCallback();
                }
            } else {
                wrapperElement.innerHTML = '<p style="color: #d32f2f; padding: 20px;">Fout bij laden van formulier.</p>';
            }
        })
        .catch(error => {
            console.error('Formulier laden mislukt:', error);
            wrapperElement.innerHTML = '<p style="color: #d32f2f; padding: 20px;">Fout bij laden van formulier.</p>';
        });
    }
    
    // Functie om scroll indicators te initialiseren
    function initScrollIndicators() {
        // Op mobiel: hele popup container
        if (window.innerWidth < 1024) {
            const container = document.getElementById('popup-container');
            const gradient = document.getElementById('mobile-scroll-gradient');
            
            if (!container || !gradient) {
                return;
            }
            
            function updateMobileScrollState() {
                const scrollTop = container.scrollTop;
                const scrollHeight = container.scrollHeight;
                const clientHeight = container.clientHeight;
                const scrollBottom = scrollHeight - scrollTop - clientHeight;
                
                // Check of er genoeg content is om te scrollen
                if (scrollHeight > clientHeight) {
                    gradient.classList.add('has-scroll');
                } else {
                    gradient.classList.remove('has-scroll');
                }
                
                // Check of gebruiker onderaan is
                if (scrollBottom < 20) {
                    gradient.classList.add('at-bottom');
                } else {
                    gradient.classList.remove('at-bottom');
                }
                
                // Detecteer welke sectie onderaan zichtbaar is voor gradient kleur
                const rightSection = container.querySelector('.popup-right');
                if (rightSection) {
                    const rightRect = rightSection.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();
                    const containerBottom = containerRect.bottom;
                    
                    // Als de rechter sectie zichtbaar is in de viewport (ook maar een deel), gebruik lichte gradient
                    // Check of de bovenkant van de rechter sectie zichtbaar is OF de onderkant nog in beeld is
                    const isRightVisible = rightRect.top < containerBottom && rightRect.bottom > containerRect.top;
                    
                    if (isRightVisible) {
                        gradient.classList.add('showing-right');
                    } else {
                        gradient.classList.remove('showing-right');
                    }
                }
            }
            
            container.addEventListener('scroll', updateMobileScrollState);
            setTimeout(updateMobileScrollState, 100);
            window.addEventListener('resize', updateMobileScrollState);
            
            return;
        }
        
        // Desktop: individuele scroll secties
        const scrollSections = document.querySelectorAll('.popup-scroll-section');
        
        scrollSections.forEach(section => {
            // Vind de bijbehorende gradient overlay (sibling van parent)
            const gradientOverlay = section.parentElement.querySelector('.scroll-gradient-overlay');
            
            if (!gradientOverlay) return;
            
            // Functie om scroll state te updaten
            function updateScrollState() {
                const scrollTop = section.scrollTop;
                const scrollHeight = section.scrollHeight;
                const clientHeight = section.clientHeight;
                const scrollBottom = scrollHeight - scrollTop - clientHeight;
                
                // Check of er genoeg content is om te scrollen
                if (scrollHeight > clientHeight) {
                    gradientOverlay.classList.add('has-scroll');
                } else {
                    gradientOverlay.classList.remove('has-scroll');
                }
                
                // Check of gebruiker heeft gescrolled (niet meer bovenaan)
                if (scrollTop > 20) {
                    section.classList.add('is-scrolled');
                } else {
                    section.classList.remove('is-scrolled');
                }
                
                // Check of gebruiker onderaan is (binnen 20px van de bodem)
                if (scrollBottom < 20) {
                    gradientOverlay.classList.add('at-bottom');
                } else {
                    gradientOverlay.classList.remove('at-bottom');
                }
            }
            
            // Update bij scroll
            section.addEventListener('scroll', updateScrollState);
            
            // Update direct bij laden - meerdere keren voor snelheid
            updateScrollState();
            setTimeout(updateScrollState, 50);
            setTimeout(updateScrollState, 100);
            
            // Update bij resize (voor responsive gedrag)
            window.addEventListener('resize', updateScrollState);
        });
    }
    
    // Popup management functie
    function openPopup(popupID) {
        // Check of popup data beschikbaar is
        if (typeof window.popupsData === 'undefined') {
            console.error('Popup data niet beschikbaar');
            return;
        }
        
        // Zoek de popup data
        const popup = window.popupsData.find(p => p.id == popupID);
        
        if (!popup) {
            console.error('Popup niet gevonden met ID:', popupID);
            return;
        }
        
        let overlay = document.getElementById('popup-overlay');
        const container = document.getElementById('popup-container');
        let content = document.getElementById('popup-content');
        let closeBtn = document.getElementById('popup-close');
        
        if (!overlay || !container || !content) {
            console.error('Popup elementen niet gevonden');
            return;
        }
        
        // Bouw popup HTML met template functies
        const html = buildPopupLeftSection(popup) + buildPopupRightSection(popup);
        
        // Vul content in
        content.innerHTML = html;
        
        // Toon overlay (eerst zonder fade)
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        
        // Trigger fade-in na kort delay (voor smooth animatie)
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                overlay.classList.add('popup-open');
            });
        });
        
        // Voorkom body scroll wanneer popup open is
        document.body.style.overflow = 'hidden';
        
        // Verwijder oude event listeners eerst (als die er zijn)
        const oldOverlayHandler = overlay._popupClickHandler;
        const oldEscapeHandler = overlay._popupEscapeHandler;
        
        if (oldOverlayHandler) {
            overlay.removeEventListener('click', oldOverlayHandler);
        }
        if (oldEscapeHandler) {
            document.removeEventListener('keydown', oldEscapeHandler);
        }
        
        // Voeg event listeners toe voor sluiten
        const closePopup = () => {
            // Start fade-out animatie
            overlay.classList.remove('popup-open');
            
            // Wacht tot animatie klaar is voordat we popup verbergen
            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                content.innerHTML = '';
                // Herstel body scroll
                document.body.style.overflow = '';
                // Verwijder event listeners
                if (overlay._popupClickHandler) {
                    overlay.removeEventListener('click', overlay._popupClickHandler);
                    delete overlay._popupClickHandler;
                }
                if (overlay._popupEscapeHandler) {
                    document.removeEventListener('keydown', overlay._popupEscapeHandler);
                    delete overlay._popupEscapeHandler;
                }
            }, 300); // Match met CSS transition duration
        };
        
        // Sluit bij klik op overlay (buiten popup)
        const overlayClickHandler = function(e) {
            if (e.target === overlay) {
                closePopup();
            }
        };
        overlay.addEventListener('click', overlayClickHandler);
        overlay._popupClickHandler = overlayClickHandler;
        
        // Sluit bij klik op close button
        if (closeBtn) {
            closeBtn.onclick = closePopup;
        }
        
        // Sluit bij Escape toets
        const escapeHandler = (e) => {
            if (e.key === 'Escape' && !overlay.classList.contains('hidden')) {
                closePopup();
            }
        };
        document.addEventListener('keydown', escapeHandler);
        overlay._popupEscapeHandler = escapeHandler;
        
        // Focus op close button
        setTimeout(() => {
            if (closeBtn) {
                closeBtn.focus();
            }
        }, 100);
        
        // Initialiseer scroll indicators
        initScrollIndicators();
        
        // Laad formulier via AJAX (lazy loading)
        if (popup.formulier_id) {
            const formWrapper = content.querySelector('.popup-form-wrapper[data-form-id="' + popup.formulier_id + '"]');
            if (formWrapper) {
                loadFormViaAjax(popup.formulier_id, formWrapper, function() {
                    // Herinitialiseer scroll indicators direct na form load
                    if (window.innerWidth >= 1024) {
                        initScrollIndicators();
                        // Extra checks voor zekerheid
                        setTimeout(() => initScrollIndicators(), 100);
                        setTimeout(() => initScrollIndicators(), 200);
                    }
                });
            }
        }
    }
    
    // Maak openPopup globaal beschikbaar
    window.openPopup = openPopup;
    
})();

