<?php
$item = $args['item'];
$id = 0;
$thumbnail = '';
$link = '#';
$name = '';
$description = '';
$post_type = '';


if ($item instanceof WP_Term) {
    $id = (int) $item->term_id;
    $link = get_term_link($item);
    $thumbnail = advice2025_get_term_thumbnail_url($id);
    $name = $item->name;
    $description = $item->description;
} elseif ($item instanceof WP_Post) {
    $id = (int) $item->ID;
    $post_type = get_post_type($id);
    $thumbnail = get_the_post_thumbnail_url($id, 'large');
    $link = get_permalink($id);
    $name = get_the_title($id);
    $description = get_the_excerpt($id);
} elseif (is_array($item)) {
    if (isset($item['term_id'])) {
        $id = (int) $item['term_id'];
        $term = get_term($id);
        $link = !is_wp_error($term) ? get_term_link($term) : '#';
        $thumbnail = advice2025_get_term_thumbnail_url($id);
        $name = $item['name'] ?? '';
        $description = $item['description'] ?? '';
    } elseif (isset($item['ID'])) {
        $id = (int) $item['ID'];
        $thumbnail = get_the_post_thumbnail_url($id, 'large');
        $link = get_permalink($id);
        $name = $item['name'] ?? get_the_title($id);
        $description = $item['description'] ?? get_the_excerpt($id);
    }
} else {
    $id = (int) $item;
    $thumbnail = get_the_post_thumbnail_url($id, 'large');
    $link = get_permalink($id);
    $name = get_the_title($id);
    $description = get_the_excerpt($id);
    $post_type = get_post_type($id);
}

?>

<a href="<?php echo esc_url($link); ?>" class="card border border-[rgba(22,22,22,0.12)] flex flex-col md:flex-row md:items-stretch">
                    <div class="card-image w-full md:w-2/8">
                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($name); ?>" class="w-full h-full object-cover">

                    </div>
                    <div class="card-body p-[28px] lg:p-[40px] relative w-full md:w-6/8">
                        <?php 
                        if($post_type == 'post') {
                            $category = get_the_category($id);
                            $thema = get_the_terms($id, 'thema');
                            $expertise = get_the_terms($id, 'expertise');
                            $category_name = $category[0]->name;
                            $thema_name = $thema[0]->name;
                            $expertise_name = $expertise[0]->name;
                      ?>
                        <div class="badges flex gap-[8px] flex-wrap mb-[20px]">
                            <?php if($category_name && $category_name != 'Niet gecategoriseerd') { ?>
                                <div class="badge bg-black border font-medium! border-black text-white body-small"><?php echo esc_html($category_name); ?></div>
                            <?php } ?>
                            <?php if($thema_name && $thema_name != 'Niet gecategoriseerd') { ?>
                                <div class="badge border! border-black! font-medium! text-black body-small"><?php echo esc_html($thema_name); ?></div>
                            <?php } ?>
                            <?php if($expertise_name && $expertise_name != 'Niet gecategoriseerd') { ?>
                                <div class="badge border! border-black! font-medium! text-black body-small"><?php echo esc_html($expertise_name); ?></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    
                        <h3 class="card-title mb-[28px]! mt-0!"><?php echo esc_html($name); ?></h3>
                        <div class="card-description"><?php echo wp_kses_post($description); ?></div>
                    </div>
                    <div class="shrink-0 max-md:absolute max-md:bottom-0 max-md:w-[12px] max-md:h-[20px] max-md:right-0 flex items-center justify-center pr-[20px]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
<rect width="2.22222" height="2.22222" fill="#EC663C"/>
<rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#EC663C"/>
<rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#EC663C"/>
<rect y="17.7778" width="2.22222" height="2.22222" fill="#EC663C"/>
<rect x="2.96094" y="2.96289" width="2.22222" height="2.22222" fill="#EC663C"/>
<rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#EC663C"/>
<rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#EC663C"/>
</svg>
                    </div>
                </a>