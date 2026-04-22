<section class="stappenplan <?php echo get_spacing_bottom_class(); ?> relative text-white pt-[3.75rem] lg:pt-0 mb-[3.75rem] lg:mb-0" data-stappenplan-section>
  <div class="absolute lg:sticky inset-0 lg:top-0 lg:left-0 w-full h-full lg:h-screen overflow-hidden pointer-events-none -z-10">
  </div>
  <div class="container mx-auto relative pt-[3.75rem] lg:pt-[6.25rem]">
    <div class="flex flex-col lg:flex-row items-start lg:gap-[3.75rem] lg:-mt-[calc(100vh-100px)]">
      <div class="w-full lg:w-5/12 lg:sticky lg:top-[6.25rem] lg:self-start lg:h-[calc(100vh-260px)] mb-0 lg:mb-[10rem] flex flex-col justify-between order-1" data-stappenplan-content>
        <div class="mb-[2.5rem] lg:mb-0 text-center lg:text-left">
          <h3 class="headline-medium"><?= get_sub_field('titel') ?></h3>
          <p><?= get_sub_field('content') ?></p>
        </div>
        <div class="hidden lg:block">
          <?php get_template_part('template-parts/core/cta-compact'); ?>
        </div>
      </div>
      <div class="w-full lg:w-6/12 lg:ml-auto mb-0 lg:mb-[6.25rem] lg:mt-0 order-2 h-auto lg:min-h-0">
        <div class="sticky lg:static top-[1.25rem] lg:translate-y-0 lg:top-auto" data-stappenplan-steps>
          <div class="flex lg:flex-col gap-[1.25rem] lg:gap-10 overflow-x-scroll lg:overflow-visible snap-x snap-mandatory lg:snap-none scrollbar-hide" data-stappenplan-inner>
            <?php
            $stappen = get_field('werkwijze', 'option');

            if(get_sub_field('aangepaste_stappen', get_the_ID())) {
              $stappen = get_sub_field('werkwijze');
            }
            $count = 0;
            foreach ($stappen as $stap) {
              $count++;

              $title = $stap['titel'];
              $content = $stap['content'];
              ?>

              <div class=" w-full py-[1.75rem] px-[1.75rem] lg:py-[3.75rem] lg:px-[3.75rem] bg-white text-primary relative snap-center lg:snap-align-none flex-shrink-0 lg:flex-shrink" data-stap="<?= $count ?>">
            <div class="flex items-start flex-col gap-5">
              <div class="badge border-light-blue!">Stap <?= $count ?></div>
              <h6 class="title-large"><?= $title ?></h6>
            </div>
            <div class="mt-5 text-primary/70"><?= $content ?></div>

            <div class="absolute top-0 lg:top-[50%] lg:translate-y-[-50%] opacity-15 flex items-center justify-center">

            </div>
              </div>

            <?php } ?>
          </div>
        </div>
      </div>
      <div class="w-full lg:hidden order-3 mt-[2.5rem] mb-0 lg:mb-[3.75rem]">
        <?php get_template_part('template-parts/core/cta-compact'); ?>
      </div>
    </div>
  </div>
</section>

<style>
  /* Hide scrollbar */
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  
  /* Mobiel: stappen zichtbaar en smooth scrolling */
  @media (max-width: 1023px) {
    .stappenplan [data-stap] {
      opacity: 1 !important;
      visibility: visible !important;
      transform: none !important;
    }
    
    .stappenplan [data-stappenplan-inner] {
      scroll-behavior: smooth;
      -webkit-overflow-scrolling: touch;
    }
    
    .stappenplan [data-stappenplan-steps] {
      position: sticky;
      top: 20px;
      z-index: 10;
    }
  }
  
  /* Desktop: GSAP zal de animaties afhandelen */
  @media (min-width: 1024px) {
    .stappenplan [data-stap] {
      /* GSAP zal deze properties setten */
      will-change: opacity, transform;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (!window.gsap || !window.ScrollTrigger) {
      console.log('Stappenplan: GSAP of ScrollTrigger niet gevonden');
      return;
    }
    
    gsap.registerPlugin(ScrollTrigger);
    
    const section = document.querySelector('[data-stappenplan-section]');
    const stepsContainer = document.querySelector('[data-stappenplan-steps]');
    const innerScroll = document.querySelector('[data-stappenplan-inner]');
    
    if (!section || !stepsContainer || !innerScroll) {
      console.log('Stappenplan: elements niet gevonden');
      return;
    }
    
    const stappen = innerScroll.querySelectorAll('[data-stap]');
    let scrollTriggerInstance = null;
    let horizontalScrollTween = null;
    let isScrolling = false;
    let wheelHandler = null;
    let touchStartHandler = null;
    let touchMoveHandler = null;
    let touchEndHandler = null;
    let desktopScrollTriggers = [];
    
    // Functie om GSAP te killen
    const killGSAP = () => {
      if (scrollTriggerInstance) {
        scrollTriggerInstance.kill();
        scrollTriggerInstance = null;
      }
      if (horizontalScrollTween) {
        horizontalScrollTween.kill();
        horizontalScrollTween = null;
      }
      
      // Verwijder event listeners
      if (wheelHandler) {
        window.removeEventListener('wheel', wheelHandler);
        wheelHandler = null;
      }
      if (touchStartHandler) {
        section.removeEventListener('touchstart', touchStartHandler);
        touchStartHandler = null;
      }
      if (touchMoveHandler) {
        section.removeEventListener('touchmove', touchMoveHandler);
        touchMoveHandler = null;
      }
      if (touchEndHandler) {
        section.removeEventListener('touchend', touchEndHandler);
        touchEndHandler = null;
      }
      
      // Kill alle desktop ScrollTriggers
      desktopScrollTriggers.forEach(trigger => {
        if (trigger) trigger.kill();
      });
      desktopScrollTriggers = [];
      
      // Kill alle ScrollTriggers voor deze sectie
      ScrollTrigger.getAll().forEach(trigger => {
        if (trigger.vars && (trigger.vars.trigger === section || trigger.vars.trigger === stepsContainer)) {
          trigger.kill();
        }
      });
    };
    
    // Functie om te checken of we op mobiel zijn
    const isMobile = () => window.innerWidth < 1024;
    
    // Functie om naar een specifieke stap te scrollen
    const scrollToStep = (index) => {
      if (isScrolling || index < 0 || index >= stappen.length) return;
      
      const targetStap = stappen[index];
      const targetScroll = targetStap.offsetLeft;
      
      isScrolling = true;
      gsap.to(innerScroll, {
        scrollLeft: targetScroll,
        duration: 0.6,
        ease: 'power2.out',
        onComplete: () => {
          isScrolling = false;
        }
      });
    };
    
    // Functie om de volgende/vorige stap te vinden
    const findStepIndex = (direction) => {
      const scrollLeft = innerScroll.scrollLeft;
      const containerWidth = innerScroll.clientWidth;
      
      for (let i = 0; i < stappen.length; i++) {
        const stapLeft = stappen[i].offsetLeft;
        const stapRight = stapLeft + stappen[i].offsetWidth;
        const viewportRight = scrollLeft + containerWidth;
        
        if (direction === 'next') {
          if (stapRight > viewportRight + 10) {
            return i;
          }
        } else {
          if (stapLeft < scrollLeft - 10) {
            return i;
          }
        }
      }
      
      return direction === 'next' ? stappen.length - 1 : 0;
    };
    
    // Initialisatie functie
    const initStappenplan = () => {
      killGSAP();
      
      if (isMobile()) {
        // Mobiel: Horizontaal scrollen met GSAP ScrollTrigger
        console.log('Stappenplan mobiel geïnitialiseerd met GSAP');
        
        // Maak stappen zichtbaar
        gsap.set(stappen, { opacity: 1, visibility: 'visible', transform: 'none' });
        
        let touchStartY = 0;
        let touchStartX = 0;
        let touchStartScrollLeft = 0;
        let isTouching = false;
        let touchDirection = null;
        let wheelBlocked = false;
        let isPinned = false;
        
        // Bereken de totale breedte van alle stappen
        const totalStepsWidth = Array.from(stappen).reduce((sum, stap) => {
          return sum + stap.offsetWidth + parseInt(getComputedStyle(stap).marginRight || 0);
        }, 0);
        
        const containerWidth = innerScroll.clientWidth;
        const maxScrollLeft = Math.max(0, totalStepsWidth - containerWidth);
        
        // Bereken hoeveel verticale scroll ruimte we nodig hebben
        // Gebruik het aantal stappen maal viewport hoogte voor een natuurlijke scroll ervaring
        // Of gebruik de horizontale scroll afstand als basis
        const scrollDistance = maxScrollLeft > 0 
          ? Math.max(maxScrollLeft * 1.5, window.innerHeight * stappen.length * 0.8)
          : window.innerHeight;
        
        // Koppel horizontale scroll aan verticale scroll progress en pin de sectie
        horizontalScrollTween = gsap.to(innerScroll, {
          scrollLeft: maxScrollLeft,
          ease: 'none',
          scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: () => `+=${scrollDistance}`,
            pin: true,
            anticipatePin: 1,
            scrub: 0.5,
            invalidateOnRefresh: true,
            onEnter: () => {
              wheelBlocked = false;
              isPinned = true;
            },
            onLeave: () => {
              wheelBlocked = true;
              isPinned = false;
            },
            onEnterBack: () => {
              wheelBlocked = false;
              isPinned = true;
            },
            onLeaveBack: () => {
              wheelBlocked = true;
              isPinned = false;
            }
          }
        });
        
        // Sla de ScrollTrigger instance op
        scrollTriggerInstance = horizontalScrollTween.scrollTrigger;
        
        // Wheel event is optioneel - ScrollTrigger handelt de scroll af
        // We kunnen dit gebruiken voor extra snap-to-step functionaliteit indien gewenst
        // Voor nu laten we ScrollTrigger het werk doen
        
        // Touch support voor directe horizontale swipe (optioneel)
        // ScrollTrigger handelt de verticale scroll naar horizontale scroll conversie af
        // Deze handlers kunnen gebruikt worden voor directe horizontale swipe functionaliteit
        
      } else {
        // Desktop: GSAP ScrollTrigger animaties voor stappen
        console.log('Stappenplan desktop geïnitialiseerd met GSAP');
        
        // Reset stappen naar initiële staat
        gsap.set(stappen, {
          opacity: 0,
          visibility: 'hidden',
          y: 30
        });
        
        // Animeer elke stap wanneer deze in view komt
        stappen.forEach((stap, index) => {
          const trigger = ScrollTrigger.create({
            trigger: stap,
            start: 'top 80%',
            end: 'top 20%',
            onEnter: () => {
              gsap.to(stap, {
                opacity: 1,
                visibility: 'visible',
                y: 0,
                duration: 0.8,
                ease: 'power2.out',
                delay: index * 0.1
              });
            },
            once: true
          });
          desktopScrollTriggers.push(trigger);
        });
      }
    };
    
    // Initialiseer
    initStappenplan();
    
    // Herinitialiseer bij resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        ScrollTrigger.refresh();
        initStappenplan();
      }, 250);
    });
  });
</script>