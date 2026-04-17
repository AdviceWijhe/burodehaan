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
 $tekst = (string) get_sub_field('tekst');
 if (trim($tekst) === '') {
     // Fallback voor oudere veldnaam.
     $tekst = (string) get_sub_field('content');
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

$is_single_or_tax = is_single() || is_tax();
$show_sidebar = $is_single_or_tax && (
    get_sub_field('sidebar') ||
    get_sub_field('toon_sidebar') ||
    get_sub_field('met_sidebar')
);

$show_sidebar_author = (bool) (
    get_sub_field('sidebar_auteur') ||
    get_sub_field('auteur_sidebar') ||
    get_sub_field('auteur')
);
$show_sidebar_date = (bool) (
    get_sub_field('sidebar_datum') ||
    get_sub_field('datum_sidebar') ||
    get_sub_field('datum')
);
$show_sidebar_expertises = (bool) (
    get_sub_field('sidebar_expertises') ||
    get_sub_field('expertises_sidebar') ||
    get_sub_field('expertises')
);
$show_sidebar_themas = (bool) (
    get_sub_field('sidebar_themas') ||
    get_sub_field('themas_sidebar') ||
    get_sub_field('themas')
);
$show_sidebar_articles = (bool) (
    get_sub_field('sidebar_artikelen') ||
    get_sub_field('artikelen_sidebar') ||
    get_sub_field('artikelen')
);

$sidebar_author_post = get_sub_field('sidebar_auteur_medewerker');
if (empty($sidebar_author_post)) {
    $sidebar_author_post = get_sub_field('auteur_medewerker');
}
if (empty($sidebar_author_post)) {
    $sidebar_author_post = get_sub_field('medewerker');
}
if (is_array($sidebar_author_post) && isset($sidebar_author_post['ID'])) {
    $sidebar_author_post = (int) $sidebar_author_post['ID'];
} elseif ($sidebar_author_post instanceof WP_Post) {
    $sidebar_author_post = (int) $sidebar_author_post->ID;
} else {
    $sidebar_author_post = (int) $sidebar_author_post;
}

$queried_term = is_tax() ? get_queried_object() : null;
$current_post_id = is_single() ? get_the_ID() : 0;

$expertise_terms = array();
$thema_terms = array();
if ($is_single_or_tax) {
    if ($current_post_id) {
        $expertise_terms = get_the_terms($current_post_id, 'expertise');
        $thema_terms = get_the_terms($current_post_id, 'thema');
    }

    if ($queried_term instanceof WP_Term) {
        if ($queried_term->taxonomy === 'expertise') {
            $expertise_terms = array($queried_term);
        }
        if ($queried_term->taxonomy === 'thema') {
            $thema_terms = array($queried_term);
        }
    }

    if (is_wp_error($expertise_terms) || !is_array($expertise_terms)) {
        $expertise_terms = array();
    }
    if (is_wp_error($thema_terms) || !is_array($thema_terms)) {
        $thema_terms = array();
    }
}

$related_posts = array();
if ($show_sidebar_articles && $is_single_or_tax) {
    $tax_query = array();
    $expertise_ids = wp_list_pluck($expertise_terms, 'term_id');
    $thema_ids = wp_list_pluck($thema_terms, 'term_id');

    if (!empty($expertise_ids)) {
        $tax_query[] = array(
            'taxonomy' => 'expertise',
            'field' => 'term_id',
            'terms' => $expertise_ids,
        );
    }
    if (!empty($thema_ids)) {
        $tax_query[] = array(
            'taxonomy' => 'thema',
            'field' => 'term_id',
            'terms' => $thema_ids,
        );
    }
    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'OR';
    }

    if (!empty($tax_query)) {
        $related_query = new WP_Query(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => $current_post_id ? array($current_post_id) : array(),
            'tax_query' => $tax_query,
        ));
        $related_posts = $related_query->posts;
        wp_reset_postdata();
    }
}
?>

<!-- Text Block -->
<div class="<?php echo get_spacing_bottom_class(); ?>">
    <?php if($has_background_color) : ?>
        <div class="bg-<?= $achtergrondkleur ?> lg:pb-[160px] lg:pt-[160px] pb-[100px] pt-[100px]"<?php echo $bg_style; ?>>
    <?php endif; ?>
    <div class="container">
 

        <?php 
        if($soortBlock === 'intro') { ?>
            <div class="w-11/12 ml-[calc(100%/12*1)] lg:w-6/12 lg:ml-[calc(100%/12*2)] <?php echo esc_attr($text_color_class); ?>">
                <?php if (!empty($tekst_label)) : ?>
                    <div class="label-large text-primary mb-[24px]"><?php echo esc_html($tekst_label); ?></div>
                <?php endif; ?>
                <?php if (!empty($tekst)) : ?>
                    <div class="body-large">
                        <?php echo wp_kses_post($tekst); ?>
                    </div>
                <?php endif; 
                
                if(get_sub_field('buttons')) { ?>
                    <div class="mt-[24px]! lg:mt-[32px]!">
                      <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'))); ?>
                    </div>
                <?php } ?>
               
                
            </div>
       <?php  }
        if($soortBlock === 'default') { 
            $width = 'lg:w-6/12';
            if($is_single_or_tax) {
                $width = 'lg:w-5/12';
            }
            ?>
            <div class="<?php echo $is_single_or_tax ? 'grid grid-cols-1 lg:grid-cols-12 gap-[40px]' : ''; ?>">
                <div class="w-full <?php echo $is_single_or_tax ? 'lg:col-span-5 lg:col-start-3' : $width . ' ml-[calc(100%/12*2)]'; ?> default-content <?php echo esc_attr($text_color_class); ?>">
                <?php if (!empty($tekst_label)) : ?>
                        <div class="label-large text-primary mb-[24px]"><?php echo esc_html($tekst_label); ?></div>
                    <?php endif; ?>
                <?php if (!empty($titel)) : ?>
                <div class="headline-medium mb-[28px] <?php echo esc_attr($text_color_class); ?>">
                    <?php echo wp_kses_post($titel); ?>
                </div>
            <?php endif; ?>    
                
                    <?php if (!empty($tekst)) : ?>
                        <div class="body-medium">
                            <?php echo $tekst; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($show_sidebar) : ?>
                    <aside class="w-full lg:mt-0 lg:col-span-3 lg:col-start-9 text-black order-first lg:order-last">
                        <?php if ($show_sidebar_author && $sidebar_author_post > 0) : ?>
                            <div class="mb-[32px]">
                                <p class="label-medium mb-[16px]!">Geschreven door</p>
                                <?php get_template_part('template-parts/card-contactpersoon', null, array('variant' => 'default', 'text-color' => 'black', 'medewerker' => $sidebar_author_post)); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_sidebar_date) : ?>
                            <div class="mb-[32px]">
                                <p class="label-medium mb-[12px]!">Datum</p>
                                <p class="body-medium mb-0!"><?php echo esc_html(get_the_date('j F Y', $current_post_id ?: null)); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_sidebar_expertises && !empty($expertise_terms)) : ?>
                            <div class="mb-[32px]">
                                <p class="label-medium mb-[12px]!">Expertises</p>
                                <div class="flex flex-wrap gap-[8px]">
                                    <?php foreach ($expertise_terms as $expertise_term) : ?>
                                        <?php $expertise_link = get_term_link($expertise_term); ?>
                                        <?php if (!is_wp_error($expertise_link)) : ?>
                                            <a href="<?php echo esc_url($expertise_link); ?>" class="body-small font-normal! border border-black text-black badge">
                                                <?php echo esc_html($expertise_term->name); ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_sidebar_themas && !empty($thema_terms)) : ?>
                            <div class="mb-[32px]">
                                <p class="label-medium mb-[12px]!">Thema's</p>
                                <div class="flex flex-wrap gap-[8px]">
                                    <?php foreach ($thema_terms as $thema_term) : ?>
                                        <?php $thema_link = get_term_link($thema_term); ?>
                                        <?php if (!is_wp_error($thema_link)) : ?>
                                            <a href="<?php echo esc_url($thema_link); ?>" class="body-small font-normal! border border-black text-black badge">
                                                <?php echo esc_html($thema_term->name); ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_sidebar_articles && !empty($related_posts)) : ?>
                            <div>
                                <p class="label-medium mb-[16px]!">Gerelateerde artikelen</p>
                                <div class="flex flex-col gap-[16px]">
                                    <?php foreach ($related_posts as $related_post) : ?>
                                        <?php
                                        $related_id = (int) $related_post->ID;
                                        $related_permalink = get_permalink($related_id);
                                        $related_title = get_the_title($related_id);
                                        $related_thumb = get_the_post_thumbnail_url($related_id, 'medium');
                                        $related_taxonomy_label = '';

                                        $related_themas = get_the_terms($related_id, 'thema');
                                        $related_expertises = get_the_terms($related_id, 'expertise');
                                        if (is_array($related_themas) && !empty($related_themas)) {
                                            $related_taxonomy_label = $related_themas[0]->name;
                                        } elseif (is_array($related_expertises) && !empty($related_expertises)) {
                                            $related_taxonomy_label = $related_expertises[0]->name;
                                        }
                                        ?>
                                        <a href="<?php echo esc_url($related_permalink); ?>" class="flex items-stretch border border-[rgba(22,22,22,0.12)]">
                                            <?php if ($related_thumb) : ?>
                                                <div class="w-[72px] min-w-[72px] self-stretch">
                                                    <img src="<?php echo esc_url($related_thumb); ?>" alt="<?php echo esc_attr($related_title); ?>" class="w-full h-full object-cover" loading="lazy">
                                                </div>
                                            <?php endif; ?>
                                            <div class="flex-1 min-w-0 p-[20px]">
                                                <?php if (!empty($related_taxonomy_label)) : ?>
                                                    <span class="inline-flex body-small font-normal! border border-black text-black badge mb-[16px]"><?php echo esc_html($related_taxonomy_label); ?></span>
                                                <?php endif; ?>
                                                <p class="body-medium font-normal! mb-0!"><?php echo esc_html($related_title); ?></p>
                                            </div>
                                            <div class="shrink-0 flex items-center pr-[20px]">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="16" viewBox="0 0 9 16" fill="none">
                                                    <rect width="1.77777" height="1.77777" fill="#EC663C"/>
                                                    <rect x="4.74072" y="9.48169" width="1.77777" height="1.77777" fill="#EC663C"/>
                                                    <rect x="2.36865" y="11.8521" width="1.77777" height="1.77777" fill="#EC663C"/>
                                                    <rect y="14.2222" width="1.77777" height="1.77777" fill="#EC663C"/>
                                                    <rect x="2.36865" y="2.37036" width="1.77777" height="1.77777" fill="#EC663C"/>
                                                    <rect x="7.1123" y="7.11133" width="1.77777" height="1.77777" fill="#EC663C"/>
                                                    <rect x="4.74072" y="4.74072" width="1.77777" height="1.77777" fill="#EC663C"/>
                                                </svg>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </aside>
                <?php endif; ?>
            </div>
       <?php  }
        if($soortBlock === 'columns') {
           ?>
           <div class="<?php echo esc_attr($text_color_class); ?>">
            <div class="w-full lg:w-8/12 lg:ml-[calc(100%/12*2)]">
            <?php if (!empty($tekst_label)) : ?>
                <div class="label-large text-primary mb-[24px]"><?php echo esc_html($tekst_label); ?></div>
            <?php endif; ?>
            <?php if (!empty($titel)) : ?>
                <div class="mb-[40px] max-w-[790px] <?php echo esc_attr($text_color_class); ?>">
                    <?php echo wp_kses_post($titel); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($tekst)) : ?>
                <!-- De content automatisch in 2 kolommen -->
                <div class="content-columns title-large columns-1 lg:columns-2 lg:gap-[45px]">
                    <?php echo wp_kses_post($tekst); ?>
                </div>
            <?php endif; ?>
        </div>
        </div>
        <?php  }
        ?>
        
    </div>
    <?php if($has_background_color) : ?>
        </div>
    <?php endif; ?>
</div>