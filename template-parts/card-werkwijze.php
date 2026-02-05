<div class="card p-[28px] lg:p-10 bg-blue text-white h-full">
  <div class="label badge border-white! text-white! mb-4">Stap <?= $args['count'] ?></div>
  <h3 class="headline-small mb-5"><?= $args['item']['titel'] ?></h3>

  <?= esc_html(strip_tags($args['item']['content'], '<a><strong><em><b><i><ul><ol><li><span><br>')) ?>
</div>