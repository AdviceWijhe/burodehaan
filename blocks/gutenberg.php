<?php 

// Haal blokken op van ACF Extended Block Editor field
$blocks = get_sub_field('blocks');

?>

<div class="gutenberg-content acf-gutenberg">
<div class="container mx-auto">

                        <div class="flex flex-col xl:flex-row">
                        <!-- Enhanced Gutenberg Blocks -->
                        <div class="blocks-container max-w-none w-full lg:w-7/12">
                            <?php echo $blocks; ?>
               
                        </div>

                        <!-- <div class="w-full lg:w-4/12 lg:ml-auto">
                        <div class="bg-blue p-10 lg:sticky lg:top-[100px]">
          <?= get_template_part('template-parts/card-contactpersoon', null, array('medewerker' => get_field('contactpersoon'), 'text-color' => 'white')) ?>
        </div>
                        </div> -->
                        </div>
                    </div>
                    </div>