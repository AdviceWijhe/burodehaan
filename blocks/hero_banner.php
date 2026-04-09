<?php
/**
 * Block: Hero Banner
 * 
 * Een grote hero banner met achtergrondafbeelding
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// Nieuwe structuur: hero_banner (flexible) met layout "hero_variant",
// waarbinnen opnieuw layouts zoals big_image/content_afbeelding gekozen worden.
if (have_rows('hero_banner')) :
    while (have_rows('hero_banner')) :
        the_row();

        if (get_row_layout() === 'hero_variant' && have_rows('hero_variant')) {
            while (have_rows('hero_variant')) {
                the_row();
                get_template_part('blocks/hero/' . get_row_layout());
            }
        } else {
            // Fallback: als de layout direct big_image/content_afbeelding is.
            get_template_part('blocks/hero/' . get_row_layout());
        }
    endwhile;
// Fallback voor oude opzet (direct hero_variant als flexible field).
elseif (have_rows('hero_variant')) :
    while (have_rows('hero_variant')) :
        the_row();
        get_template_part('blocks/hero/' . get_row_layout());
    endwhile;
endif;


?>

