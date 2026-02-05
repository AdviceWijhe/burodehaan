<section class="proces <?php echo get_spacing_bottom_class(); ?> " data-proces-section>
    <div class="proces-scroll-wrapper overflow-hidden p-[20px] lg:p-0! pt-[40px] lg:pt-[80px]! ">
        <div class="flex flex-col lg:flex-row items-stretch flex-nowrap proces-slides-container" data-proces-slides>
            <div class="w-full lg:min-w-[600px]  lg:w-[600px]  lg:pl-[60px] lg:pr-[100px] lg:pb-[60px] flex flex-col justify-end proces-intro-slide text-pink mb-[60px] lg:mb-0!">
                <h4 class="mb-[24px]! lg:mb-[32px]! title-large"><?= get_sub_field('titel') ?></h4>
                <div class="mb-[24px]! lg:mb-[32px]! max-w-[333px] body-medium font-normal"><?= get_sub_field('content', null, null) ?></div>
                <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))) ?>
            </div>
            <?php 
            $count = 0;
            $processen = get_sub_field('processen');
            foreach($processen as $stap) { 
                $count++;
                ?>
                <div class="w-full lg:w-10/12 lg:min-w-[1200px] mr-[80px]! proces-slide mb-[24px] lg:mb-0!" data-slide-index="<?= $count ?>">
                    <div class="flex flex-col lg:flex-row flex-stretch">
                        <div class="w-full lg:w-5/12 aspect-square lg:aspect-auto lg:h-[calc(100vh-200px)]">
                            <img src="<?= $stap['afbeelding']['url'] ?>" alt="<?= $stap['afbeelding']['alt'] ?>" class="w-full h-full object-cover object-center">
                        </div>
                        <div class="w-full lg:w-7/12 bg-light-blue/25 p-[40px] lg:p-[60px] flex flex-col justify-between items-start">
                            <div class="badge mb-[12px] lg:mb-0! label-small">Niveau <?= $count ?></div>
                            <div>
                            <h4 class="title-large"><?= $stap['titel'] ?></h4>
                            <div class="mb-[32px]! body-medium font-normal"><?= $stap['content'] ?></div>
                            <?= get_template_part('template-parts/core/buttons', null, array('buttons' => $stap['buttons'])) ?>
                        </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- Blanko slide zodat laatste slide volledig in beeld komt -->
            <!-- <div class="w-full lg:w-10/12 lg:min-w-[1200px] proces-slide" ></div> -->
        </div>
    </div>

    <style>
        .step {
            cursor: pointer;
            flex-shrink: 0;
            transform-origin: center center;
            will-change: transform, opacity;
        }
        .step:hover {
            opacity: 0.9 !important;
        }
        .step:focus {
            outline: 2px solid rgba(150, 172, 192, 0.5);
            outline-offset: 2px;
        }
        .step.active {
            opacity: 1 !important;
        }
        .steps-wrapper {
            min-height: 40px;
        }
        .steps-prev,
        .steps-next {
            min-width: 0;
        }
        .steps-active {
            min-width: fit-content;
        }
    </style>

    <div class="py-[40px] px-[60px] steps-container overflow-hidden w-full hidden lg:block" data-steps-container>
        <div class="steps-wrapper flex items-center justify-between gap-2 w-full" data-steps-wrapper>
            <div class="steps-prev flex items-center gap-2 flex-1 justify-start" data-steps-prev></div>
            <div class="steps-active flex items-center justify-center flex-shrink-0" data-steps-active></div>
            <div class="steps-next flex items-center gap-2 flex-1 justify-end" data-steps-next></div>
        </div>
        <div class="steps-source hidden" data-steps-source>
            <?php 
            $count = 0;
            foreach($processen as $stap) { 
                $count++;
                ?>
                <div class="step border rounded-full border-light-blue/25 p-[4px] inline-flex items-center gap-2 transition-all duration-300" data-step-index="<?= $count ?>">
                    <span class="bg-light-blue aspect-square w-[20px] rounded-full flex items-center justify-center text-center text-white text-[12px] font-bold step-number"><?= $count ?></span>
                    <span class="step-text pr-[8px]"><?= $stap['proces_naam'] ?></span>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
    (function(){
        // Controleer of scherm groot genoeg is (tablet landscape en groter, 1024px+)
        const isTabletLandscapeOrLarger = () => {
            return window.matchMedia('(min-width: 1024px)').matches;
        };
        
        if (window.gsap && window.ScrollTrigger) {
            gsap.registerPlugin(ScrollTrigger);
            
            const section = document.querySelector('[data-proces-section]');
            if (!section) return;
            
            // Controleer schermgrootte voordat GSAP wordt geïnitialiseerd
            if (!isTabletLandscapeOrLarger()) {
                return; // Stop als scherm te klein is
            }
            
            const wrapper = section.querySelector('.proces-scroll-wrapper');
            const slidesContainer = section.querySelector('[data-proces-slides]');
            const slides = section.querySelectorAll('.proces-slide');
            const stepsSource = section.querySelector('[data-steps-source]');
            const stepsWrapper = section.querySelector('[data-steps-wrapper]');
            const stepsPrev = section.querySelector('[data-steps-prev]');
            const stepsActive = section.querySelector('[data-steps-active]');
            const stepsNext = section.querySelector('[data-steps-next]');
            
            if (slides.length === 0 || !stepsSource) return;
            
            // Haal alle steps uit de source
            const allSteps = Array.from(stepsSource.querySelectorAll('.step'));
            
            // Houd ScrollTrigger instance bij voor resize handling
            let scrollTriggerInstance = null;
            let scrollTween = null;
            
            // Functie om GSAP te killen
            const killGSAP = () => {
                if (scrollTriggerInstance) {
                    scrollTriggerInstance.kill();
                    scrollTriggerInstance = null;
                }
                if (scrollTween) {
                    scrollTween.kill();
                    scrollTween = null;
                }
                // Reset transform
                if (slidesContainer) {
                    gsap.set(slidesContainer, { x: 0, clearProps: 'all' });
                }
            };
            
            // Wacht tot alles geladen is
            const initScroll = () => {
                // Controleer opnieuw of scherm groot genoeg is
                if (!isTabletLandscapeOrLarger()) {
                    killGSAP();
                    return;
                }
                // Bereken de totale breedte voor horizontale scroll
                const introSlide = section.querySelector('.proces-intro-slide');
                const introWidth = introSlide ? introSlide.offsetWidth + parseInt(getComputedStyle(introSlide).marginRight) : 0;
                
                let totalWidth = introWidth;
                slides.forEach((slide) => {
                    totalWidth += slide.offsetWidth + parseInt(getComputedStyle(slide).marginRight);
                });
                
                // Set container width
                gsap.set(slidesContainer, {
                    width: totalWidth
                });
                
                // Houd bij welke step eerder actief was (voor animaties)
                let previousActiveIndex = 1;
                
                // Functie om te bepalen waar een step vandaan komt
                function getPreviousPosition(stepIndex, prevIndex, currentIndex) {
                    if (stepIndex === prevIndex) {
                        return 'center'; // Was actief (midden)
                    } else if (stepIndex === prevIndex - 1) {
                        return 'left-adjacent'; // Was direct links van actief
                    } else if (stepIndex === prevIndex + 1) {
                        return 'right-adjacent'; // Was direct rechts van actief
                    } else if (stepIndex < prevIndex) {
                        return 'left'; // Was links
                    } else {
                        return 'right'; // Was rechts
                    }
                }
                
                // Functie om actieve step te updaten
                function updateActiveStep(index, animate = true) {
                    // Leeg alle containers
                    stepsPrev.innerHTML = '';
                    stepsActive.innerHTML = '';
                    stepsNext.innerHTML = '';
                    
                    const stepsToAnimate = [];
                    const isFirstLoad = !animate || (previousActiveIndex === index && previousActiveIndex === 1);
                    
                    allSteps.forEach((stepTemplate, i) => {
                        const stepIndex = i + 1;
                        const step = stepTemplate.cloneNode(true);
                        const stepNumber = step.querySelector('.step-number');
                        const stepText = step.querySelector('.step-text');
                        
                        // Reset alle classes en styles
                        step.classList.remove('active', 'prev', 'next', 'prev-adjacent', 'next-adjacent');
                        step.style.opacity = '';
                        
                        // Bepaal waar deze step vandaan komt (alleen als niet eerste load)
                        const fromPosition = isFirstLoad ? null : getPreviousPosition(stepIndex, previousActiveIndex, index);
                        
                        // Set initial state gebaseerd op waar het vandaan komt
                        if (stepIndex === index) {
                            // Actieve step: volledig zichtbaar, opacity 1, in het midden
                            step.classList.add('active');
                            if (stepText) stepText.style.display = '';
                            if (stepNumber) stepNumber.style.display = 'flex';
                            stepsActive.appendChild(step);
                            
                            // Animate naar midden gebaseerd op waar het vandaan komt
                            if (!fromPosition) {
                                // Eerste load, geen animatie
                                gsap.set(step, { x: 0, opacity: 1, scale: 1 });
                            } else if (fromPosition === 'right-adjacent' || fromPosition === 'right') {
                                // Komt van rechts naar midden
                                gsap.set(step, { x: 200, opacity: 0.7, scale: 0.9 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 1,
                                        scale: 1,
                                        duration: 0.5,
                                        ease: "power2.out"
                                    }
                                });
                            } else if (fromPosition === 'left-adjacent' || fromPosition === 'left') {
                                // Komt van links naar midden
                                gsap.set(step, { x: -200, opacity: 0.7, scale: 0.9 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 1,
                                        scale: 1,
                                        duration: 0.5,
                                        ease: "power2.out"
                                    }
                                });
                            } else {
                                // Was al in midden
                                gsap.set(step, { x: 0, opacity: 1, scale: 1 });
                            }
                        } else if (stepIndex === index - 1) {
                            // Vorige stap: links, opacity 0.7, nummer + naam
                            step.classList.add('prev', 'prev-adjacent');
                            if (stepText) stepText.style.display = '';
                            if (stepNumber) stepNumber.style.display = 'flex';
                            stepsPrev.appendChild(step);
                            
                            // Animate naar links gebaseerd op waar het vandaan komt
                            if (!fromPosition) {
                                // Eerste load, geen animatie
                                gsap.set(step, { x: 0, opacity: 0.7, scale: 1 });
                            } else if (fromPosition === 'center') {
                                // Komt van midden naar links
                                gsap.set(step, { x: 200, opacity: 1, scale: 1 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 0.7,
                                        scale: 1,
                                        duration: 0.5,
                                        ease: "power2.out"
                                    }
                                });
                            } else {
                                // Was al links of komt van rechts
                                gsap.set(step, { x: fromPosition === 'right' || fromPosition === 'right-adjacent' ? 100 : 0, opacity: 0.7, scale: 1 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 0.7,
                                        duration: 0.4,
                                        ease: "power2.out"
                                    }
                                });
                            }
                        } else if (stepIndex === index + 1) {
                            // Volgende stap: rechts, opacity 0.7, nummer + naam
                            step.classList.add('next', 'next-adjacent');
                            if (stepText) stepText.style.display = '';
                            if (stepNumber) stepNumber.style.display = 'flex';
                            stepsNext.appendChild(step);
                            
                            // Animate naar rechts gebaseerd op waar het vandaan komt
                            if (!fromPosition) {
                                // Eerste load, geen animatie
                                gsap.set(step, { x: 0, opacity: 0.7, scale: 1 });
                            } else if (fromPosition === 'center') {
                                // Komt van midden naar rechts
                                gsap.set(step, { x: -200, opacity: 1, scale: 1 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 0.7,
                                        scale: 1,
                                        duration: 0.5,
                                        ease: "power2.out"
                                    }
                                });
                            } else {
                                // Was al rechts of komt van links
                                gsap.set(step, { x: fromPosition === 'left' || fromPosition === 'left-adjacent' ? -100 : 0, opacity: 0.7, scale: 1 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 0.7,
                                        duration: 0.4,
                                        ease: "power2.out"
                                    }
                                });
                            }
                        } else if (stepIndex < index) {
                            // Steps ervoor (meer dan 1 stap terug): links, opacity 0.7, alleen nummer
                            step.classList.add('prev');
                            if (stepText) stepText.style.display = 'none';
                            if (stepNumber) stepNumber.style.display = 'flex';
                            stepsPrev.appendChild(step);
                            
                            // Animate naar links
                            if (!fromPosition) {
                                gsap.set(step, { x: 0, opacity: 0.7 });
                            } else if (fromPosition === 'center' || fromPosition === 'right' || fromPosition === 'right-adjacent') {
                                gsap.set(step, { x: 50, opacity: 0.7 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 0.7,
                                        duration: 0.3,
                                        ease: "power2.out"
                                    }
                                });
                            } else {
                                gsap.set(step, { x: 0, opacity: 0.7 });
                            }
                        } else {
                            // Steps erna (meer dan 1 stap verder): rechts, opacity 0.7, alleen nummer
                            step.classList.add('next');
                            if (stepText) stepText.style.display = 'none';
                            if (stepNumber) stepNumber.style.display = 'flex';
                            stepsNext.appendChild(step);
                            
                            // Animate naar rechts
                            if (!fromPosition) {
                                gsap.set(step, { x: 0, opacity: 0.7 });
                            } else if (fromPosition === 'center' || fromPosition === 'left' || fromPosition === 'left-adjacent') {
                                gsap.set(step, { x: -50, opacity: 0.7 });
                                stepsToAnimate.push({
                                    element: step,
                                    animation: {
                                        x: 0,
                                        opacity: 0.7,
                                        duration: 0.3,
                                        ease: "power2.out"
                                    }
                                });
                            } else {
                                gsap.set(step, { x: 0, opacity: 0.7 });
                            }
                        }
                        
                        // Voeg accessibility attributen toe
                        step.setAttribute('role', 'button');
                        step.setAttribute('tabindex', '0');
                        step.setAttribute('aria-label', `Ga naar stap ${stepIndex}`);
                        
                        // Voeg click handler toe aan gekloonde step
                        step.addEventListener('click', () => {
                            const slideIndex = stepIndex - 1;
                            scrollToSlide(slideIndex);
                        });
                        
                        // Keyboard support
                        step.addEventListener('keydown', (e) => {
                            if (e.key === 'Enter' || e.key === ' ') {
                                e.preventDefault();
                                const slideIndex = stepIndex - 1;
                                scrollToSlide(slideIndex);
                            }
                        });
                    });
                    
                    // Animate all steps
                    stepsToAnimate.forEach(({ element, animation }) => {
                        gsap.to(element, animation);
                    });
                    
                    // Update previous active index
                    previousActiveIndex = index;
                }
                
                // Initialiseer eerste step als actief (zonder animatie)
                updateActiveStep(1, false);
                
                // Bereken snap points voor elke proces slide
                const snapPoints = slides.length;
                
                // Bereken de exacte scroll posities voor elke proces slide (zonder intro)
                const slidePositions = [];
                let currentPos = introWidth;
                slides.forEach((slide) => {
                    slidePositions.push(currentPos);
                    currentPos += slide.offsetWidth + parseInt(getComputedStyle(slide).marginRight);
                });
                
                // De scroll begint pas na de intro slide
                // Voeg extra ruimte toe aan het einde zodat laatste slide volledig in beeld komt
                const extraEndSpace = wrapper.offsetWidth * 0.3; // 30% van viewport breedte extra ruimte
                const scrollStartPos = introWidth;
                const scrollEndPos = totalWidth - wrapper.offsetWidth + extraEndSpace;
                const maxScroll = scrollEndPos - scrollStartPos;
                
                // Kill bestaande ScrollTrigger als deze al bestaat
                killGSAP();
                
                // GSAP ScrollTrigger voor horizontale scroll met snap
                let currentSlideIndex = -1;
                
                // Functie om naar een specifieke slide te scrollen
                function scrollToSlide(slideIndex) {
                    if (slideIndex < 0 || slideIndex >= slidePositions.length) return;
                    
                    // Voor de laatste slide, scroll naar het einde (met extra ruimte)
                    let targetPosition;
                    if (slideIndex === slidePositions.length - 1) {
                        // Laatste slide: scroll naar het einde zodat deze volledig in beeld komt
                        targetPosition = scrollEndPos;
                    } else {
                        targetPosition = slidePositions[slideIndex];
                    }
                    const targetProgress = targetPosition / scrollEndPos;
                    
                    if (scrollTriggerInstance) {
                        // Bereken de scroll positie waar we naartoe moeten
                        const scrollDistance = scrollTriggerInstance.end - scrollTriggerInstance.start;
                        const targetScrollY = scrollTriggerInstance.start + (targetProgress * scrollDistance);
                        const currentScrollY = window.scrollY;
                        
                        // Gebruik GSAP om te scrollen (met fallback naar window.scrollTo)
                        if (typeof gsap.to !== 'undefined' && gsap.plugins && gsap.plugins.scrollTo) {
                            // ScrollToPlugin is beschikbaar
                            gsap.to(window, {
                                duration: 0.6,
                                ease: "power2.out",
                                scrollTo: {
                                    y: targetScrollY,
                                    autoKill: false
                                },
                                onUpdate: function() {
                                    if (scrollTriggerInstance) {
                                        scrollTriggerInstance.update();
                                    }
                                }
                            });
                        } else {
                            // Fallback: gebruik window.scrollTo met smooth behavior
                            const startY = currentScrollY;
                            const distance = targetScrollY - startY;
                            const duration = 600;
                            const startTime = performance.now();
                            
                            function animateScroll(currentTime) {
                                const elapsed = currentTime - startTime;
                                const progress = Math.min(elapsed / duration, 1);
                                
                                // Ease functie (power2.out)
                                const eased = 1 - Math.pow(1 - progress, 2);
                                
                                window.scrollTo(0, startY + (distance * eased));
                                
                                if (scrollTriggerInstance) {
                                    scrollTriggerInstance.update();
                                }
                                
                                if (progress < 1) {
                                    requestAnimationFrame(animateScroll);
                                }
                            }
                            
                            requestAnimationFrame(animateScroll);
                        }
                    }
                }
                
                // Pin de hele section zodat zowel wrapper als steps container samen blijven
                scrollTween = gsap.to(slidesContainer, {
                    x: -scrollEndPos,
                    ease: "none",
                    scrollTrigger: {
                        trigger: section,
                        start: "top top",
                        end: () => `+=${scrollEndPos}`,
                        pin: section,
                        scrub: 1,
                        anticipatePin: 1,
                        invalidateOnRefresh: true,
                        onUpdate: (self) => {
                            // Update step tijdens scroll (zonder snap)
                            const currentScrollPos = self.progress * scrollEndPos;
                            
                            // Als we nog bij de intro slide zijn, toon eerste step
                            if (currentScrollPos < scrollStartPos) {
                                if (currentSlideIndex !== -1) {
                                    currentSlideIndex = -1;
                                    updateActiveStep(1);
                                }
                                return;
                            }
                            
                            // Bereken relatieve positie binnen de proces slides
                            const relativePos = currentScrollPos - scrollStartPos;
                            const maxRelativePos = maxScroll;
                            
                            // Als we bijna aan het einde zijn (laatste 15% van scroll), forceer laatste slide
                            const lastSlideThreshold = maxRelativePos * 0.85;
                            if (relativePos >= lastSlideThreshold) {
                                const lastIndex = slidePositions.length - 1;
                                if (currentSlideIndex !== lastIndex) {
                                    currentSlideIndex = lastIndex;
                                    updateActiveStep(lastIndex + 1);
                                }
                                return;
                            }
                            
                            // Vind huidige slide met verhoogde threshold voor betere detectie
                            let activeIndex = 0;
                            for (let i = 0; i < slidePositions.length; i++) {
                                const slideRelativePos = slidePositions[i] - scrollStartPos;
                                // Verhoog threshold naar 150px voor betere detectie
                                if (relativePos >= slideRelativePos - 150) {
                                    activeIndex = i;
                                } else {
                                    break;
                                }
                            }
                            
                            if (activeIndex !== currentSlideIndex) {
                                currentSlideIndex = activeIndex;
                                updateActiveStep(activeIndex + 1);
                            }
                        }
                    }
                });
                
                // Sla de scrollTrigger instance op na initialisatie
                scrollTriggerInstance = scrollTween.scrollTrigger;
            };
            
            // Wacht tot DOM en images geladen zijn
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(initScroll, 100);
                });
            } else {
                setTimeout(initScroll, 100);
            }
            
            // Herbereken bij resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (isTabletLandscapeOrLarger()) {
                        // Scherm is groot genoeg: refresh of herinitialiseer GSAP
                        if (scrollTriggerInstance) {
                            ScrollTrigger.refresh();
                        } else {
                            // Herinitialiseer als GSAP was uitgeschakeld
                            initScroll();
                        }
                    } else {
                        // Scherm is te klein: kill GSAP
                        killGSAP();
                    }
                }, 250);
            });
        }
    })();
    </script>
</section>