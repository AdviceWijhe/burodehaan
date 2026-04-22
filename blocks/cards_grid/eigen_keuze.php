
    <div class="grid grid-cols-1 md:grid-cols-12">
        <div class="w-full md:col-span-8 md:col-start-3 flex flex-col gap-6">
 
            <?php 
            $counter = 0;
         
            foreach($args['items'] as $item) : 
                 
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
                $counter++;
                if($counter == 1 && count($args['items']) > 1) { ?>
                    <a href="<?php echo $link; ?>" class="card border border-[rgba(22,22,22,0.12)] flex flex-col lg:flex-row">
                    <div class="card-image w-full lg:w-3/8 h-full">
                        <img src="<?= $thumbnail ?>" alt="<?php echo $name; ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="card-body p-[3.75rem] w-full lg:w-5/8">
                        <h3 class="card-title mb-[1.75rem]!"><?php echo $name; ?></h3>
                        <div class="card-description"><?php echo $description; ?></div>
                        <span class="btn bg-primary mt-[2.5rem]"><?php echo $name; ?></span>
                    </div>
                </a>
                <?php }else {
                ?>
                <?php get_template_part('template-parts/card-kennisbank', null, array('item' => $item)); ?>
            <?php 
        }
        endforeach; ?>
        </div>
    </div>
