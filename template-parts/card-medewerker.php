<div class="medewerker">

  <div class="medewerker__image  aspect-[9/13] overflow-hidden">
    <?php echo wp_get_attachment_image(get_post_thumbnail_id(get_the_ID()), 'large', false, array(
      'class' => 'object-cover object-top w-full h-full',
      'alt' => get_the_title(),
      'loading' => 'lazy'
    )); ?>
  </div>

  <div class="medewerker__content mt-5">
    <h5 class="mb-2"><?= get_the_title() ?></h5>
    <p><?= get_field('functie', get_the_ID()) ?></p>

    <?php if(get_field('telefoonnummer', get_the_ID()) || get_field('emailadres', get_the_ID())) : ?>
    <div class="flex gap-5">
        <?php if(get_field('telefoonnummer', get_the_ID())) : ?>  
        <a href="tel:<?= get_field('telefoonnummer', get_the_ID()) ?>" class="btn btn-outline-gray w-[calc(50%-12px)] py-4 flex items-center   text-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
<path d="M4.36467 1.07707C4.18421 0.641147 3.70845 0.409126 3.25378 0.533339L1.19138 1.09581C0.783581 1.20831 0.5 1.57861 0.5 2.00046C0.5 7.79864 5.20136 12.5 10.9995 12.5C11.4214 12.5 11.7917 12.2164 11.9042 11.8086L12.4667 9.74621C12.5909 9.29155 12.3589 8.81579 11.9229 8.63533L9.67303 7.69787C9.29102 7.5385 8.84807 7.64865 8.58792 7.96973L7.64109 9.12515C5.99116 8.34471 4.65529 7.00884 3.87485 5.35891L5.03027 4.41442C5.35135 4.15193 5.4615 3.71132 5.30213 3.32931L4.36467 1.07941V1.07707Z" fill="#092354"/>
</svg>
    </a>
    <?php endif; ?>
    <?php if(get_field('emailadres', get_the_ID())) : ?>
        <a href="mailto:<?= get_field('emailadres', get_the_ID()) ?>" class="btn btn-outline-gray w-[calc(50%-12px)] flex py-4 items-center  text-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="9" viewBox="0 0 13 9" fill="none">
<path d="M1.625 0C1.00391 0 0.5 0.503906 0.5 1.125C0.5 1.47891 0.666406 1.81172 0.95 2.025L6.05 5.85C6.31719 6.04922 6.68281 6.04922 6.95 5.85L12.05 2.025C12.3336 1.81172 12.5 1.47891 12.5 1.125C12.5 0.503906 11.9961 0 11.375 0H1.625ZM0.5 2.625V7.5C0.5 8.32734 1.17266 9 2 9H11C11.8273 9 12.5 8.32734 12.5 7.5V2.625L7.4 6.45C6.86562 6.85078 6.13438 6.85078 5.6 6.45L0.5 2.625Z" fill="#092354"/>
</svg>
        <?php endif; ?>
        </a>
    </div>
    <?php endif; ?>
  </div>

</div>