<?php
/**
 * Helper om CTA data op te halen
 * Gebruikt door verschillende CTA template parts
 * 
 * @return array met cta_afbeelding, cta_titel, cta_buttons
 */

$met_cta = get_sub_field('met_cta') === true;
$aangepaste_cta = get_sub_field('aangepaste_cta') === true;

if ($aangepaste_cta) {
    $cta_groep = get_sub_field('cta_groep');
    
    if (is_array($cta_groep) && !empty($cta_groep)) {
        $cta_afbeelding = $cta_groep['cta_afbeelding'] ?? null;
        $cta_titel     = $cta_groep['cta_titel'] ?? null;
        $cta_content   = $cta_groep['cta_content'] ?? null;
        $cta_buttons   = $cta_groep['cta_buttons'] ?? null;
        $cta_contactpersoon   = $cta_groep['cta_contactpersoon'] ?? null;
        $cta_background_color   = $cta_groep['cta_achtergrond_kleur'] ?? null;
    } else {
        $cta_afbeelding = get_sub_field('cta_afbeelding');
        $cta_titel     = get_sub_field('cta_titel');
        $cta_content   = get_sub_field('cta_content');
        $cta_buttons   = get_sub_field('cta_buttons');
        $cta_contactpersoon   = get_sub_field('cta_contactpersoon');
        $cta_background_color   = get_sub_field('achtergrond_kleur');
    }
} else {
    $cta_groep_option = get_field('cta_groep', 'option');
    
    if (is_array($cta_groep_option) && !empty($cta_groep_option)) {
        $cta_afbeelding = $cta_groep_option['cta_afbeelding'] ?? null;
        $cta_titel     = $cta_groep_option['cta_titel'] ?? null;
        $cta_content   = $cta_groep_option['cta_content'] ?? null;
        $cta_buttons   = $cta_groep_option['cta_buttons'] ?? null;
        $cta_contactpersoon   = $cta_groep_option['cta_contactpersoon'] ?? null;
        $cta_background_color   = $cta_groep_option['achtergrond_kleur'] ?? null;
    } else {
        $cta_afbeelding = get_field('cta_afbeelding', 'option');
        $cta_titel     = get_field('cta_titel', 'option');
        $cta_content   = get_field('cta_content', 'option');
        $cta_buttons   = get_field('cta_buttons', 'option');
        $cta_contactpersoon   = get_field('cta_contactpersoon', 'option');
        $cta_background_color   = get_field('achtergrond_kleur', 'option');
    }
}

$cta_background_color   = get_sub_field('achtergrond_kleur');

if (isset($args['cta_afbeelding'])) {
    $cta_afbeelding = $args['cta_afbeelding'];
}
if (isset($args['cta_titel'])) {
    $cta_titel = $args['cta_titel'];
}
if (isset($args['cta_content'])) {
    $cta_content = $args['cta_content'];
}
if (isset($args['cta_buttons'])) {
    $cta_buttons = $args['cta_buttons'];
}
if (isset($args['cta_contactpersoon'])) {
    $cta_contactpersoon = $args['cta_contactpersoon'];
}
if (isset($args['cta_background_color'])) {
    $cta_background_color = $args['achtergrond_kleur'];
}