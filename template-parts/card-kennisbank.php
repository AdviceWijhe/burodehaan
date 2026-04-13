<?php
$item = $args['item'];
$id = 0;
$thumbnail = '';
$link = '#';
$name = '';
$description = '';



if ($item instanceof WP_Term) {
    $id = (int) $item->term_id;
    $link = get_term_link($item);
    $thumbnail = advice2025_get_term_thumbnail_url($id);
    $name = $item->name;
    $description = $item->description;
} elseif ($item instanceof WP_Post) {
    $id = (int) $item->ID;
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
}

?>

<a href="<?php echo esc_url($link); ?>" class="card border border-[rgba(22,22,22,0.12)] flex flex-col lg:flex-row">
                    <div class="card-image w-full lg:w-2/8 h-full">
                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($name); ?>" class="w-full h-full object-cover">

                    </div>
                    <div class="card-body p-[40px] w-full lg:w-4/8">
                        <h3 class="card-title mb-[28px]!"><?php echo esc_html($name); ?></h3>
                        <div class="card-description"><?php echo wp_kses_post($description); ?></div>
                    </div>
                    <div class="w-full lg:w-1/8 lg:ml-auto flex items-center justify-center">
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