<?php
// CTA blok met verschillende types
// Data logica zit in de template parts zelf
$cta_type = $args['cta_type'] ?? get_sub_field('cta_type');
?>
<?php if($cta_type == 'default') : ?>
<?php get_template_part('template-parts/core/cta-default', null, $args); ?>

<?php elseif($cta_type == 'small') : ?>
<?php
include locate_template('template-parts/core/cta-get-data.php');
?>
<section class="cta cta--small <?php echo get_spacing_bottom_class(); ?>">
  <div class="container">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-[2.5rem]">
      <div class="w-full lg:col-span-5 lg:col-start-3 text-black border border-[rgba(22,22,22,0.12)] rounded-[1.25rem] p-[2.25rem] lg:p-[2.5rem]">
        <?php if (!empty($cta_titel)) : ?>
          <div class="mb-[1.5rem] lg:mb-[2rem]"><?php echo $cta_titel; ?></div>
        <?php endif; ?>

        <?php if (get_sub_field('contactpersoon_tonen') && !empty($cta_contactpersoon)) : ?>
          <div class="mb-[1.5rem] lg:mb-[2rem]">
            <?php get_template_part('template-parts/card-contactpersoon', null, array(
              'variant'    => 'default',
              'medewerker' => $cta_contactpersoon,
            )); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($cta_buttons)) : ?>
          <div>
            <?php get_template_part('template-parts/core/buttons', null, array(
              'buttons'     => $cta_buttons,
              'no_margin'   => true,
              'align_items' => 'start',
              'full_width'  => false,
            )); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php elseif($cta_type == 'center') : ?>
<?php get_template_part('template-parts/core/cta-center', null, $args); ?>

<?php endif; ?>