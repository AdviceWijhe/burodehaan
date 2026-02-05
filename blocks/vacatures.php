<?php
/**
 * Vacatures Block
 * Toont een lijst van vacatures met filter functionaliteit
 */

// Haal vacatures op (dit kan aangepast worden naar de juiste post type)
$vacatures = get_posts(array(
    'post_type' => 'vacature', // Pas aan naar het juiste post type
    'posts_per_page' => -1,
    'post_status' => 'publish'
));

// Filter opties
$filter_options = array(
    'alle' => 'Alle',
    'development' => 'Development',
    'seo' => 'SEO',
    'design' => 'Design',
    'management' => 'Management',
    'marketing' => 'Marketing'
);

if ($vacatures) : ?>
    <section id="vacatures" class="vacatures-section <?php echo get_spacing_bottom_class(); ?>">
        <div class="container mx-auto py-[60px]! lg:py-[100px]! relative overflow-hidden">
            <div class="lg:px-[100px] relative">
                <div class="mb-7 lg:mb-12">
                    <h2 class=""><?= get_sub_field('titel') ?></h2>
                </div>
                
                <!-- Filter knoppen -->
                <div class="mb-8 lg:mb-12 flex flex-wrap gap-3">
                    <?php foreach ($filter_options as $value => $label) : ?>
                        <button 
                            class="vacature-filter-btn px-6 py-3 rounded-full border-2 border-black/30 text-black hover:border-black hover:bg-black/5 transition-all duration-300 <?php echo $value === 'alle' ? 'active bg-black/10 border-black' : ''; ?>"
                            data-filter="<?php echo esc_attr($value); ?>"
                            type="button">
                            <?php echo esc_html($label); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <div class="w-full">
                    <div class="vacatures-lijst flex flex-col gap-4">
                        <?php foreach ($vacatures as $index => $vacature) : 
                            // Haal genest veld op uit Header group
                            $header_group = get_field('header', $vacature->ID);
                            $soort_vacature = '';
                            if (is_array($header_group) && isset($header_group['soort_vacature'])) {
                                $soort_vacature = $header_group['soort_vacature'];
                            } else {
                                $soort_vacature = get_field('header_soort_vacature', $vacature->ID);
                                if (empty($soort_vacature)) {
                                    $soort_vacature = get_field('soort_vacature', $vacature->ID);
                                }
                            }
                            // Converteer array naar string indien nodig
                            if (is_array($soort_vacature)) {
                                $soort_vacature = !empty($soort_vacature) ? $soort_vacature[0] : '';
                            }
                            $filter_value = $soort_vacature ? strtolower($soort_vacature) : '';
                        ?>
                            <div class="vacature-item-wrapper" data-soort-vacature="<?php echo esc_attr($filter_value); ?>">
                                <?php get_template_part('template-parts/card-vacature', null, array(
                                    'vacature' => $vacature,
                                    'soort_vacature' => $soort_vacature
                                )) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.vacature-filter-btn');
            const vacatureItems = document.querySelectorAll('.vacature-item-wrapper');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Update active state
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-black/10', 'border-black');
                        btn.classList.add('border-black/30');
                    });
                    this.classList.add('active', 'bg-black/10', 'border-black');
                    this.classList.remove('border-black/30');
                    
                    // Filter vacatures
                    vacatureItems.forEach(item => {
                        const itemSoort = item.getAttribute('data-soort-vacature');
                        
                        if (filterValue === 'alle' || itemSoort === filterValue) {
                            item.style.display = 'block';
                            // Fade in animation
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.style.transition = 'opacity 0.3s ease-in-out';
                                item.style.opacity = '1';
                            }, 10);
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
<?php else : ?>
    <section class="vacatures-section <?php echo get_spacing_bottom_class(); ?>">
        <div class="container mx-auto px-4">
            <div class="w-full lg:w-8/12 mx-auto">
                <div class="text-center">
                    <p class="text-gray-600">Er zijn momenteel geen vacatures beschikbaar.</p>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
