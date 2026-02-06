<?php 
// CTA blok met verschillende types
// Data logica zit in de template parts zelf
?>
<?php if(get_sub_field('cta_type') == 'default' || isset($args['cta_type']) && $args['cta_type'] == 'default') : ?>
<?php get_template_part('template-parts/core/cta-default', null, $args); ?>

<?php elseif(get_sub_field('cta_type') == 'big') : ?>
<section class="cta <?php echo get_spacing_bottom_class(); ?>">
  
  <div class=" mx-auto bg-blue relative overflow-hidden">
    <div class="flex flex-col lg:items-center justify-center relative py-[60px] lg:py-[160px] ">
      <div class="w-full max-w-[700px]  text-white text-center mx-auto px-[20px] lg:px-0">
        <h2 class="headline-medium text-white mb-[32px]! max-w-[579px] mx-auto"><?= get_sub_field('titel') ?></h2>

        <?php if(get_sub_field('usps')) { ?>
          <div class="usps relative flex flex-col lg:flex-row flex-wrap items-center justify-center gap-[16px] lg:gap-[28px] mb-[34px]">
            <?php foreach(get_sub_field('usps') as $usp) { ?>
              <div class="usp w-full lg:w-auto flex gap-2 items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
  <circle cx="12.5" cy="12.5" r="12.5" fill="#96ACC0"/>
  <rect x="7.92969" y="12.0708" width="6" height="1" transform="rotate(45 7.92969 12.0708)" fill="#0A2031"/>
  <rect x="18.5352" y="8.53564" width="1" height="11" transform="rotate(45 18.5352 8.53564)" fill="#0A2031"/>
</svg>
                <?php echo $usp['usp']; ?>
              </div>
            <?php } ?>
          </div>
        <?php } ?>

        <?php if(get_sub_field('content')) { ?>
          <div class="relative max-w-[579px] mx-auto body-medium font-normal">
            <?php echo get_sub_field('content', null, null); ?>
          </div>
        <?php } ?>
      </div>
      <div class="w-full lg:w-6/12 flex mt-[32px] justify-center align-center">
        <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'no_margin' => true, 'align_items' => 'start')); ?>
      </div>
    </div>
  </div>
</section>


<?php endif; ?>