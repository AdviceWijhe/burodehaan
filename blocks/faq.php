<?php 

// Haal alle FAQ posts op van het post type 'faq'
$faq_query = new WP_Query(array(
    'post_type' => 'faq',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC'
));

$faq_id = 'faq-' . uniqid();

?>

<section class="faq <?php echo get_spacing_bottom_class(); ?>">
    <div class="container mx-auto">
        <h2 class="headline-medium text-center mb-[2.25rem]! text-black"><?= get_sub_field('titel') ?></h2>

        <?php if($faq_query->have_posts()) { ?>
            <div class="faq-items max-w-[702px] mx-auto space-y-6">
                <?php 
                $index = 0;
                while($faq_query->have_posts()) : $faq_query->the_post();
                    $vraag = get_the_title();
                    $antwoord = apply_filters('the_content', get_the_content());
                    $item_id = $faq_id . '-item-' . $index;
                    ?>
                    <div class="faq-item border-b border-transparent">
                        <button 
                            class="faq-question w-full bg-light p-[1.25rem] lg:p-[1.75rem] flex items-center justify-between text-left transition-all duration-300 hover:opacity-90 hover:cursor-pointer rounded-t-[16px] rounded-b-[16px]"
                            aria-expanded="false"
                            aria-controls="<?php echo esc_attr($item_id); ?>"
                            data-faq-toggle
                        >
                            <span class="body-medium-bold pr-4 text-black">
                                <?php echo esc_html($vraag); ?>
                            </span>
                            <div class="faq-icon flex-shrink-0 lg:w-[20px] lg:h-[20px] w-[16px] h-[16px] flex items-center justify-center relative">
                                <div class="faq-icon-vertical absolute bg-black lg:w-[2px] w-[1px] lg:h-[20px] h-[16px] transition-transform duration-300 origin-center"></div>
                                <div class="faq-icon-horizontal absolute bg-black lg:w-[20px] w-[16px] lg:h-[2px] h-[2px] transition-opacity duration-300"></div>
                            </div>
                        </button>
                        <div 
                            id="<?php echo esc_attr($item_id); ?>"
                            class="faq-answer overflow-hidden transition-all duration-300 ease-in-out"
                            style="max-height: 0;"
                            aria-hidden="true"
                        >
                            <div class="bg-light px-[1.25rem] lg:px-[1.75rem] pb-[1.25rem] lg:pb-[1.75rem] pt-[0.625rem] lg:pt-[0.625rem] rounded-b-[16px]">
                                <div class="body-medium text-black">
                                    <?php echo wp_kses_post($antwoord); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                $index++;
                endwhile; 
                wp_reset_postdata();
                ?>
            </div>
        <?php } else { ?>
            <p class="text-center">Geen FAQ items gevonden.</p>
        <?php } ?>
    </div>
</section>