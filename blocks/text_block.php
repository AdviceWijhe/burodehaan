<?php
/**
 * Block: Text Block
 * 
 * Een eenvoudig tekstblok met optionele heading
 */

 $soortBlock = get_sub_field('soort_content');
 $tekst_label = get_sub_field('tekst_label');
 if (empty($tekst_label)) {
     // Fallback voor eventuele alternatieve veldnaam.
     $tekst_label = get_sub_field('label');
 }
 $titel = (string) get_sub_field('titel', false, false);
 $tekst = (string) get_sub_field('tekst', false, false);
 if (trim($tekst) === '') {
     // Fallback voor oudere veldnaam.
     $tekst = (string) get_sub_field('content', false, false);
 }
 $achtergrondkleur = get_sub_field('achtergrondkleur');
 if (empty($soortBlock)) {
     // Zonder selectie standaard gedrag tonen.
     $soortBlock = 'default';
 }
 $has_background_color = is_string($achtergrondkleur) && trim($achtergrondkleur) !== '';
 $wrapper_spacing_class = $has_background_color
     ? 'py-[160px]'
     : (is_single() ? 'lg:pb-[100px] pb-[60px]' : get_spacing_bottom_class());

 // Achtergrondkleur kan slug (primary/secondary/black/white) of een hexkleur zijn.
 $bg_style = '';
 if (is_string($achtergrondkleur) && $achtergrondkleur !== '') {
     $theme_color_vars = array(
         'primary' => 'var(--color-primary)',
         'secondary' => 'var(--color-secondary)',
         'white' => 'var(--color-white)',
         'black' => 'var(--color-black)',
     );
     $resolved_color = $theme_color_vars[$achtergrondkleur] ?? $achtergrondkleur;
     $bg_style = ' style="background-color: ' . esc_attr($resolved_color) . ';"';
 }

 // Alleen bij zwarte achtergrond wordt alle tekst wit.
 $normalized_bg = strtolower(trim((string) $achtergrondkleur));
 $is_black_bg = in_array($normalized_bg, array('black', '#161616', '#000000', 'rgb(22,22,22)', 'rgb(0,0,0)', 'rgb(22 22 22)'), true);
 $text_color_class = $is_black_bg ? 'text-white' : 'text-black';
?>

<!-- Text Block -->
<div class="<?php echo esc_attr($wrapper_spacing_class); ?>"<?php echo $bg_style; ?>>
    <div class="container mx-auto px-0! lg:px-[20px]">
        <?php if (!empty($titel)) : ?>
            <div class="headline-medium mb-[24px] <?php echo esc_attr($text_color_class); ?>">
                <?php echo wp_kses_post($titel); ?>
            </div>
        <?php endif; ?>

        <?php 
        if($soortBlock === 'intro') { ?>
            <div class="w-full lg:w-7/12 <?php echo esc_attr($text_color_class); ?>">
                <?php if (!empty($tekst_label)) : ?>
                    <div class="label-large text-primary mb-[24px]"><?php echo esc_html($tekst_label); ?></div>
                <?php endif; ?>
                <?php if (!empty($tekst)) : ?>
                    <div class="title-large">
                        <?php echo wp_kses_post($tekst); ?>
                    </div>
                <?php endif; ?>
            </div>
       <?php  }
        if($soortBlock === 'default') { ?>
            <div class="w-full lg:w-6/12 mx-auto px-[20px] lg:px-[62px] default-content <?php echo esc_attr($text_color_class); ?>">
                <?php if (!empty($tekst_label)) : ?>
                    <div class="label-large text-primary mb-[24px]"><?php echo esc_html($tekst_label); ?></div>
                <?php endif; ?>
                <?php if (!empty($tekst)) : ?>
                    <div class="body-medium">
                        <?php echo wp_kses_post($tekst); ?>
                    </div>
                <?php endif; ?>
            </div>
       <?php  }
        if($soortBlock === 'columns') {
           ?>
           <div class="<?php echo esc_attr($text_color_class); ?>">
            <?php if (!empty($tekst_label)) : ?>
                <div class="label-large text-primary mb-[24px]"><?php echo esc_html($tekst_label); ?></div>
            <?php endif; ?>
            <?php if (!empty($tekst)) : ?>
                <!-- De content automatisch in 2 kolommen -->
                <div class="content-columns title-large columns-1 lg:columns-2 lg:gap-[40px]">
                    <?php echo wp_kses_post($tekst); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php  }
        ?>
        
    </div>
</div>