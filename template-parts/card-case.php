<a href="<?= get_the_permalink() ?>" class="relative min-h-[220px] lg:min-h-[400px] flex flex-col justify-end group">
  <div class="relative *:top-0 left-0 w-full  group-hover:scale-[0.95] overflow-hidden transition-transform duration-300 origin-center h-[220px] lg:h-[468px]">
    <?php echo wp_get_attachment_image(get_post_thumbnail_id(get_the_ID()), 'large', false, array(
      'class' => 'object-cover object-top w-full h-full group-hover:scale-[1.2] transition-transform duration-300 origin-center',
      'alt' => get_the_title(),
      'loading' => 'lazy'
    )); ?>
    <!-- <div class="absolute left-0 bottom-0 h-full w-full bg-gradient-to-t from-black to-transparent opacity-80"></div> -->
  </div>
  <!-- gradient overlay -->
  
  <div class="relative z-2 pt-[1.75rem]">
    <div class="flex flex-wrap gap-2">
      <?php $categories = get_the_category(); ?>
      <?php foreach($categories as $category) { ?>
        <div class="label-small text-white bg-primary border border-light-blue badge"><?= $category->name ?></div>
      <?php } ?>
      <div class="label-small text-primary border border-light-blue badge"><?= date('Y', strtotime(get_the_date())) ?></div>
    </div>
    <h5 class="mt-[0.75rem]"><?= get_the_title() ?></h5>
</div>
</a>