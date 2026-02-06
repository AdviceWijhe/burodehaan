<?php 
$vacature = $args['vacature'];
$background_class = isset($args['background_class']) ? $args['background_class'] : '';

// Haal Header group op
$header_group = get_field('header', $vacature->ID);

// Haal aantal_uren op - probeer eerst uit args, anders uit Header group
$aantal_uren = '';
if (isset($args['aantal_uren']) && !empty($args['aantal_uren'])) {
    $aantal_uren = $args['aantal_uren'];
} else {
    if (is_array($header_group) && isset($header_group['aantal_uren'])) {
        $aantal_uren = $header_group['aantal_uren'];
    } else {
        $aantal_uren = get_field('header_aantal_uren', $vacature->ID);
        if (empty($aantal_uren)) {
            $aantal_uren = get_field('aantal_uren', $vacature->ID);
        }
    }
}

// Haal locatie op - probeer eerst uit args, anders uit Header group
$locatie = '';
if (isset($args['locatie']) && !empty($args['locatie'])) {
    $locatie = $args['locatie'];
} else {
    if (is_array($header_group) && isset($header_group['locatie'])) {
        $locatie = $header_group['locatie'];
    } else {
        $locatie = get_field('header_locatie', $vacature->ID);
        if (empty($locatie)) {
            $locatie = get_field('locatie', $vacature->ID);
        }
    }
}

// Haal soort_vacature op - probeer eerst uit args, anders uit Header group
$soort_vacature = '';
if (isset($args['soort_vacature']) && !empty($args['soort_vacature'])) {
    $soort_vacature = $args['soort_vacature'];
} else {
    if (is_array($header_group) && isset($header_group['soort_vacature'])) {
        $soort_vacature = $header_group['soort_vacature'];
    } else {
        $soort_vacature = get_field('header_soort_vacature', $vacature->ID);
        if (empty($soort_vacature)) {
            $soort_vacature = get_field('soort_vacature', $vacature->ID);
        }
    }
}

// ACF select velden kunnen arrays teruggeven, converteer naar string indien nodig
if (is_array($soort_vacature)) {
    $soort_vacature = !empty($soort_vacature) ? $soort_vacature[0] : '';
}

// Haal andere ACF velden op (voor eventueel later gebruik)
$uren = get_field('uren_per_week', $vacature->ID);
$salaris = get_field('salaris', $vacature->ID);
$type = get_field('type_contract', $vacature->ID);

// Map soort_vacature waarden naar labels
$soort_labels = array(
    'development' => 'Development',
    'seo' => 'SEO',
    'design' => 'Design',
    'management' => 'Management',
    'marketing' => 'Marketing'
);
$soort_label = '';
if (!empty($soort_vacature)) {
    $soort_vacature_clean = is_string($soort_vacature) ? strtolower($soort_vacature) : '';
    $soort_label = isset($soort_labels[$soort_vacature_clean]) ? $soort_labels[$soort_vacature_clean] : ucfirst($soort_vacature);
}
?>

<a href="<?php echo get_permalink($vacature->ID); ?>" class="vacature-item group bg-primary p-[20px]! transition-all duration-300 flex gap-6 justify-between items-center shadow-md hover:shadow-lg rounded-[16px]">
                          
    <!-- Linker kant: Titel -->
    <div class="flex-1">
        <h3 class="title-large text-black transition-colors mb-0">
            <span class="block">
                <?php echo get_the_title($vacature->ID); ?>
            </span>
        </h3>
    </div>
    
    <!-- Midden: Badges met aantal_uren, locatie en soort_vacature -->
    <div class="flex-shrink-0 flex items-center gap-3">
        <?php if (!empty($aantal_uren)) : ?>
            <span class="inline-block px-4 py-2 border-2 border-black text-black rounded-[4px] text-sm font-medium whitespace-nowrap">
                <?php echo esc_html($aantal_uren . ' uur'); ?>
            </span>
        <?php endif; ?>
        
        <?php if (!empty($locatie)) : ?>
            <span class="inline-block px-4 py-2 border-2 border-black text-black rounded-[4px] text-sm font-medium whitespace-nowrap">
                <?php echo esc_html($locatie); ?>
            </span>
        <?php endif; ?>
        
        <?php if (!empty($soort_label)) : ?>
            <span class="inline-block px-4 py-2 border-2 border-black text-black rounded-[4px] text-sm font-medium whitespace-nowrap">
                <?php echo esc_html($soort_label); ?>
            </span>
        <?php elseif (!empty($soort_vacature)) : ?>
            <span class="inline-block px-4 py-2 border-2 border-black text-black rounded-[4px] text-sm font-medium whitespace-nowrap">
                <?php echo esc_html(ucfirst($soort_vacature)); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Rechter kant: Chevron button -->
    <div class="flex-shrink-0">
        <span class="inline-flex items-center justify-center">
            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-transform duration-300 group-hover:translate-x-1">
                <circle cx="12.5" cy="12.5" r="12" stroke="#480E25"/>
                <g class="arrow-group">
                    <rect x="7" y="11.7427" width="10" height="1" fill="#480E25"/>
                    <rect x="17.4854" y="12.2427" width="1" height="6" transform="rotate(135 17.4854 12.2427)" fill="#480E25"/>
                    <rect x="17.4854" y="12.2427" width="6" height="1" transform="rotate(135 17.4854 12.2427)" fill="#480E25"/>
                </g>
            </svg>
        </span>
    </div>
</a>