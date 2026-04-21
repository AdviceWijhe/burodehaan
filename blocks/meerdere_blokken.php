<?php
/**
 * Block: Meerdere Blokken
 * 
 * Een flexibel blok met 1, 2 of 3 tekstblokken met afbeelding, titel en link
 */

$tekstblokken = get_sub_field('tekstblokken');
$block_id = 'meerdere-blokken-' . uniqid();

if ($tekstblokken && is_array($tekstblokken) && !empty($tekstblokken)) : 
    $aantal = count($tekstblokken);
    ?>
    
    <div class="meerdere-blokken <?php echo get_spacing_bottom_class(); ?>">
        <div class="container mx-auto px-[20px] lg:px-0!">
            <?php if ($aantal == 3) : ?>
                <!-- Speciale layout voor 3 blokken: flex container, eerste links, 2e en 3e rechts onder elkaar -->
                <div class="flex flex-col lg:flex-row lg:items-stretch gap-4 lg:gap-6">
                    <?php 
                    $index = 0;
                    foreach ($tekstblokken as $blok) : 
                        $index++;
                        $titel = $blok['titel'] ?? '';
                        $afbeelding = $blok['afbeelding'] ?? '';
                        $link = $blok['link'] ?? '';
                        $positie_afbeelding = $blok['positie_afbeelding'] ?? 'rechts'; // Default: rechts
                        $afbeelding_zwart_wit = $blok['afbeelding_zwart_wit'] ?? false;
                        // Check of checkbox is aangevinkt (ondersteunt verschillende ACF formaten)
                        $is_zwart_wit = false;
                        if ($afbeelding_zwart_wit) {
                            if (is_array($afbeelding_zwart_wit)) {
                                $is_zwart_wit = in_array('ja', $afbeelding_zwart_wit) || in_array(1, $afbeelding_zwart_wit);
                            } else {
                                $is_zwart_wit = ($afbeelding_zwart_wit === 'ja' || $afbeelding_zwart_wit === true || $afbeelding_zwart_wit === 1 || $afbeelding_zwart_wit === '1');
                            }
                        }
                        
                        if ($index == 1) {
                            // Eerste blok: links, 6 kolommen (50%)
                            echo '<div class="w-full lg:w-6/12 flex">';
                        } elseif ($index == 2) {
                            // Start flex container voor 2e en 3e blok
                            echo '<div class="w-full lg:w-6/12 flex flex-col gap-4 lg:gap-6">';
                        }
                    ?>
                        <?php 
                        // Voor 2e en 3e blok: horizontale layout
                        $is_horizontal = ($index == 2 || $index == 3);
                        $is_first = ($index == 1);
                        $container_class = $is_horizontal ? 'bg-[#D8D6D466] rounded-lg overflow-hidden flex flex-col lg:flex-row h-full' : 'bg-[#D8D6D466] rounded-lg overflow-hidden flex flex-col h-full';
                        // Voor 1e blok: padding van 60px
                        $padding_class = $is_first ? 'p-[60px]' : '';
                        ?>
                        <div class="<?php echo esc_attr($container_class); ?> <?php echo esc_attr($padding_class); ?>">
                            <?php if ($afbeelding) : 
                                // Get image ID if it's an array
                                $image_id = is_array($afbeelding) ? $afbeelding['ID'] : attachment_url_to_postid($afbeelding);
                                
                                if ($image_id) {
                                    $image_src = wp_get_attachment_image_url($image_id, 'large');
                                    $image_srcset = wp_get_attachment_image_srcset($image_id, 'large');
                                    $image_sizes = wp_get_attachment_image_sizes($image_id, 'large');
                                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: ($titel ?: 'Afbeelding');
                                } else {
                                    $image_src = is_array($afbeelding) ? $afbeelding['url'] : $afbeelding;
                                    $image_srcset = '';
                                    $image_sizes = '';
                                    $image_alt = is_array($afbeelding) ? ($afbeelding['alt'] ?? '') : '';
                                }
                                
                                // Bepaal aspect ratio en layout
                                if ($is_horizontal) {
                                    // Voor horizontale layout: kleinere afbeelding
                                    $aspect_class = 'aspect-video lg:aspect-square lg:w-2/5';
                                    // Bepaal positie op basis van selecteerveld
                                    if ($positie_afbeelding === 'links') {
                                        $image_order = 'order-1 lg:order-1';
                                    } else {
                                        $image_order = 'order-1 lg:order-2';
                                    }
                                    $image_spacing = '';
                                } else {
                                    // Voor 1e blok: afbeelding boven met meer ruimte
                                    $aspect_class = 'aspect-video lg:aspect-[4/3]';
                                    $image_order = 'order-1';
                                    $image_spacing = 'mb-6 lg:mb-8';
                                }
                            ?>
                                <div class="w-full <?php echo esc_attr($aspect_class); ?> <?php echo esc_attr($image_order); ?> <?php echo esc_attr($image_spacing); ?> overflow-hidden flex-shrink-0">
                                    <img src="<?php echo esc_url($image_src); ?>" 
                                         <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 50vw'); ?>"<?php endif; ?>
                                         alt="<?php echo esc_attr($image_alt); ?>" 
                                         loading="lazy"
                                         class="w-full h-full <?php echo $is_first ? 'object-contain' : 'object-cover'; ?> <?php echo $is_zwart_wit ? 'grayscale' : ''; ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="<?php echo $is_first ? '' : 'p-6 lg:p-8'; ?> flex flex-col flex-grow <?php 
                                if ($is_horizontal) {
                                    // Bepaal tekst positie op basis van afbeelding positie
                                    if ($positie_afbeelding === 'links') {
                                        echo 'order-2 lg:order-2 lg:w-3/5 justify-between';
                                    } else {
                                        echo 'order-2 lg:order-1 lg:w-3/5 justify-between';
                                    }
                                } else {
                                    echo 'order-2';
                                }
                            ?>">
                                <?php if ($is_horizontal) : ?>
                                    <!-- Spacer voor 2e en 3e kolom om titel naar beneden te duwen -->
                                    <div class="flex-grow"></div>
                                <?php endif; ?>
                                
                                <?php if ($titel) : ?>
                                    <h2 class="mb-4 lg:mb-6 mt-0!"><?php echo esc_html($titel); ?></h2>
                                <?php endif; ?>
                                
                                <?php if ($link && is_array($link)) : 
                                    $link_url = $link['url'] ?? '#';
                                    $link_title = $link['title'] ?? 'Lees meer';
                                    $link_target = $link['target'] ?? '_self';
                                ?>
                                    <div class="<?php echo $is_horizontal ? '' : 'mt-auto'; ?>">
                                        <a href="<?php echo esc_url($link_url); ?>" 
                                           target="<?php echo esc_attr($link_target); ?>"
                                           class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-medium">
                                            <?php echo esc_html($link_title); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-3 h-3 fill-current">
                                                <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z"/>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php 
                        // Sluit containers
                        if ($index == 1) {
                            echo '</div>';
                        } elseif ($index == 3) {
                            echo '</div>';
                        }
                    endforeach; 
                    ?>
                </div>
            <?php else : ?>
                <!-- Standaard layout voor 1 of 2 blokken -->
                <div class="grid grid-cols-1 <?php echo $aantal == 1 ? 'lg:grid-cols-12' : 'lg:grid-cols-12'; ?> gap-4 lg:gap-6">
                    <?php 
                    foreach ($tekstblokken as $blok) : 
                        $titel = $blok['titel'] ?? '';
                        $afbeelding = $blok['afbeelding'] ?? '';
                        $link = $blok['link'] ?? '';
                        $afbeelding_zwart_wit = $blok['afbeelding_zwart_wit'] ?? false;
                        // Check of checkbox is aangevinkt (ondersteunt verschillende ACF formaten)
                        $is_zwart_wit = false;
                        if ($afbeelding_zwart_wit) {
                            if (is_array($afbeelding_zwart_wit)) {
                                $is_zwart_wit = in_array('ja', $afbeelding_zwart_wit) || in_array(1, $afbeelding_zwart_wit);
                            } else {
                                $is_zwart_wit = ($afbeelding_zwart_wit === 'ja' || $afbeelding_zwart_wit === true || $afbeelding_zwart_wit === 1 || $afbeelding_zwart_wit === '1');
                            }
                        }
                        
                        // Bepaal de breedte op basis van aantal blokken
                        if ($aantal == 1) {
                            $col_span = 'lg:col-span-12';
                            $block_class = 'lg:mx-auto lg:w-fit';
                        } else {
                            $col_span = 'lg:col-span-6';
                            $block_class = '';
                        }
                    ?>
                        <div class="<?php echo esc_attr($col_span); ?> <?php echo esc_attr($block_class); ?> bg-[#D8D6D466] rounded-lg overflow-hidden flex flex-col h-full <?php echo $aantal == 1 ? 'p-[60px]' : ''; ?>">
                            <?php if ($afbeelding) : 
                                // Get image ID if it's an array
                                $image_id = is_array($afbeelding) ? $afbeelding['ID'] : attachment_url_to_postid($afbeelding);
                                
                                if ($image_id) {
                                    $image_src = wp_get_attachment_image_url($image_id, 'large');
                                    $image_srcset = wp_get_attachment_image_srcset($image_id, 'large');
                                    $image_sizes = wp_get_attachment_image_sizes($image_id, 'large');
                                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: ($titel ?: 'Afbeelding');
                                } else {
                                    $image_src = is_array($afbeelding) ? $afbeelding['url'] : $afbeelding;
                                    $image_srcset = '';
                                    $image_sizes = '';
                                    $image_alt = is_array($afbeelding) ? ($afbeelding['alt'] ?? '') : '';
                                }
                                
                                // Voor 1 kolom: max-height van 350px
                                $image_container_class = $aantal == 1 
                                    ? 'w-full mb-6 lg:mb-8 overflow-hidden lg:max-h-[350px]' 
                                    : 'w-full aspect-video lg:aspect-[4/3] mb-6 lg:mb-8 overflow-hidden';
                            ?>
                                <div class="<?php echo esc_attr($image_container_class); ?>">
                                    <img src="<?php echo esc_url($image_src); ?>" 
                                         <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes ?: '(max-width: 1024px) 100vw, 50vw'); ?>"<?php endif; ?>
                                         alt="<?php echo esc_attr($image_alt); ?>" 
                                         loading="lazy"
                                         class="w-full h-full object-contain <?php echo $is_zwart_wit ? 'grayscale' : ''; ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="<?php echo $aantal == 1 ? '' : 'p-6 lg:p-8'; ?> flex flex-col flex-grow">
                                <?php if ($titel) : ?>
                                    <h3 class="mb-4 lg:mb-6 mt-0!"><?php echo esc_html($titel); ?></h3>
                                <?php endif; ?>
                                
                                <?php if ($link && is_array($link)) : 
                                    $link_url = $link['url'] ?? '#';
                                    $link_title = $link['title'] ?? 'Lees meer';
                                    $link_target = $link['target'] ?? '_self';
                                ?>
                                    <div class="mt-auto">
                                        <a href="<?php echo esc_url($link_url); ?>" 
                                           target="<?php echo esc_attr($link_target); ?>"
                                           class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-medium">
                                            <?php echo esc_html($link_title); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 fill-current">
                                                <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z"/>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>

