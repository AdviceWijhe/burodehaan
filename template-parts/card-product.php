<?php 

$ID = $args['post'] ?? get_the_ID();

?>

<a href="<?= get_the_permalink($ID) ?>" class="product group">
  <div class="product__image group-hover:scale-[0.95] overflow-hidden transition-transform duration-300 origin-center">
    <?php echo wp_get_attachment_image(get_post_thumbnail_id($ID), 'large', false, array(
      'class' => 'group-hover:scale-[1.2] w-full h-full object-cover transition-transform duration-300 origin-center',
      'alt' => get_the_title($ID),
      'loading' => 'lazy'
    )); ?>
  </div>
  <div class="product__content">
    <div class="pt-[1.5rem] lg:pt-[2rem] overflow-hidden js-title-container">
    <div class="span flex gap-[1.25rem] w-full items-center transition-transform duration-300 group-hover:-translate-x-[38px] with-arrows">
      <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
<path d="M18 7.88477V7.88574L10.1152 15.7705L8.53809 14.1934L13.8105 8.9209H0V6.69043H13.6514L8.53809 1.57715L10.1152 0L18 7.88477Z" fill="#E1322C"/>
</svg>  -->
<h6 class="headline-small no-after"><?= get_the_title($ID) ?></h6>
<!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
<path d="M18 7.88477V7.88574L10.1152 15.7705L8.53809 14.1934L13.8105 8.9209H0V6.69043H13.6514L8.53809 1.57715L10.1152 0L18 7.88477Z" fill="#E1322C"/>
</svg> -->
</div>
    </div>
  </div>
  <!-- <script>(function(){
    var root = document.currentScript && document.currentScript.closest('a.product');
    if(!root) return;
    var container = root.querySelector('.js-title-container');
    var title = root.querySelector('h6');
    if(!container || !title) return;
    function updateWidth(){
      var width = Math.ceil(title.getBoundingClientRect().width) + 38 - 1; // 1px marge om rechter pijl te verbergen
      container.style.width = width + 'px';
    }
    updateWidth();
    window.addEventListener('resize', updateWidth, { passive: true });
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(updateWidth).catch(function(){});
    }
  })();</script> -->
</a>