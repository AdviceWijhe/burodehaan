
    <div class="grid grid-cols-1 md:grid-cols-12">
        <div class="w-full md:col-span-8 md:col-start-3 flex flex-col gap-6">
 
            <?php 
            $counter = 0;
            foreach($args['items'] as $item) : 
                 
                if(is_object($item)) {
                    $link = get_term_link($item->term_id);
                    $name = $item->name;
                    $description = $item->description;
                }else {
                    $link = get_the_permalink($item['ID']);
                    $name = $item['name'];
                    $description = $item['description'];
                }
                $counter++;
                if($counter == 1) { ?>
                    <a href="<?php echo $link; ?>" class="card border border-[rgba(22,22,22,0.12)] flex flex-col lg:flex-row">
                    <div class="card-image w-full lg:w-3/8 h-full">
                        <img src="<?= advice2025_get_term_thumbnail_url($item->term_id) ?>" alt="<?php echo $name; ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="card-body p-[60px] w-full lg:w-5/8">
                        <h3 class="card-title mb-[28px]!"><?php echo $name; ?></h3>
                        <div class="card-description"><?php echo $description; ?></div>
                        <span class="btn bg-primary mt-[40px]"><?php echo $name; ?></span>
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
