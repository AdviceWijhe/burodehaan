<?php
/**
 * Block: Hero Banner
 * 
 * Een grote hero banner met achtergrondafbeelding
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


while (have_rows('hero_variant')) : the_row();

get_template_part('blocks/hero/' . get_row_layout());


endwhile;


?>

