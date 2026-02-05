<?php 

$faqItems = get_sub_field('faq');
$faq_id = 'faq-' . uniqid();

?>

<section class="faq <?php echo get_spacing_bottom_class(); ?> ">
    <div class="container mx-auto">
        <h2 class="headline-medium text-center mb-[36px]!"><?= get_sub_field('titel') ?></h2>

        <?php if($faqItems) { ?>
            <div class="faq-items max-w-[702px] mx-auto space-y-6">
                <?php foreach($faqItems as $index => $faq) { 
                    // Als het een post object is
                    if (is_object($faq)) {
                        $vraag = get_the_title($faq->ID);
                        $antwoord = apply_filters('the_content', $faq->post_content);
                    } 
                    // Als het een array is met directe velden
                    else {
                        $vraag = $faq['vraag'];
                        $antwoord = $faq['antwoord'];
                    }
                    $item_id = $faq_id . '-item-' . $index;
                    ?>
                    <div class="faq-item border-b border-transparent">
                        <button 
                            class="faq-question w-full bg-light-blue/25 p-[20px] lg:p-[28px] flex items-center justify-between text-left transition-all duration-300 hover:opacity-90  hover:cursor-pointer"
                            aria-expanded="false"
                            aria-controls="<?php echo esc_attr($item_id); ?>"
                            data-faq-toggle
                        >
                            <span class="body-large font-medium pr-4">
                                <?php echo esc_html($vraag); ?>
                            </span>
                            <div class="faq-icon flex-shrink-0 lg:w-[20px] lg:h-[20px] w-[16px] h-[16px] flex items-center justify-center relative">
                                <div class="faq-icon-vertical absolute bg-[#00344C] lg:w-[2px] w-[1px] lg:h-[20px] h-[16px] transition-transform duration-300 origin-center"></div>
                                <div class="faq-icon-horizontal absolute bg-[#00344C] lg:w-[20px] w-[16px] lg:h-[2px] h-[2px] transition-opacity duration-300"></div>
                            </div>
                        </button>
                        <div 
                            id="<?php echo esc_attr($item_id); ?>"
                            class="faq-answer overflow-hidden transition-all duration-300 ease-in-out max-h-0"
                            aria-hidden="true"
                        >
                            <div class="bg-light-blue/25 p-[20px] lg:p-[28px] pb-[10px] lg:pb-[28px]  pt-0! lg:pt-0!">
                                <div class="body-medium">
                                    <?php echo wp_kses_post($antwoord); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>