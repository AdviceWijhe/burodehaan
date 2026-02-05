<?php 

$contactpersoon = $args['medewerker'];

$telefoon = get_field('telefoonnummer', $contactpersoon) ?: get_field('telefoonnummer', 'option');
$email = get_field('emailadres', $contactpersoon) ?: get_field('emailadres', 'option');

$variant = $args['variant'] ?? 'default';

$text_color = $args['text-color'] ?? 'blue';



?>

<div class="flex <?php if($variant == 'big') {echo 'lg:gap-20  flex-col lg:items-start items-center lg:flex-row';}else {echo 'gap-5 items-center';} ?>   text-<?= $text_color ?> ">
  <div class="<?php if($variant == 'default') { echo 'w-[60px] h-[60px]';} else if($variant == 'big') {echo 'w-[180px] h-[180px] lg:w-[250px] lg:h-[250px]';} ?> overflow-hidden">
    <?php echo wp_get_attachment_image(get_post_thumbnail_id($contactpersoon), 'large', false, array(
      'class' => 'object-cover object-top',
      'alt' => get_the_title($contactpersoon),
      'loading' => 'lazy'
    )); ?>
  </div>
  <div class="">
    <h3 class="mb-3 <?php if($variant == 'big') {echo 'headline-medium mt-[49px]';} else { echo 'headline-small';} ?>"><?= get_the_title($contactpersoon) ?></h3>
    <p class="mb-0! lead"><?= get_field('functie', $contactpersoon) ?></p>

    <?php if($variant == 'big') : ?>
    <div class="flex flex-wrap gap-4 mt-[16px] lg:mt-[28px]">
  <a class="btn flex items-center justify-center lg:justify-start max-lg:flex-grow gap-2" href="tel:"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
<path d="M3.86467 1.07707C3.68421 0.641147 3.20845 0.409126 2.75378 0.533339L0.691376 1.09581C0.283581 1.20831 0 1.57861 0 2.00046C0 7.79864 4.70136 12.5 10.4995 12.5C10.9214 12.5 11.2917 12.2164 11.4042 11.8086L11.9667 9.74621C12.0909 9.29155 11.8589 8.81579 11.4229 8.63533L9.17303 7.69787C8.79102 7.5385 8.34807 7.64865 8.08792 7.96973L7.14109 9.12515C5.49116 8.34471 4.15529 7.00884 3.37485 5.35891L4.53027 4.41442C4.85135 4.15193 4.9615 3.71132 4.80213 3.32931L3.86467 1.07941V1.07707Z" fill="white"/>
</svg> Neem contact op</a>
  <a class="btn btn-outline-blue  flex items-center justify-center lg:justify-start max-lg:flex-grow gap-2" href="mailto:"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="9" viewBox="0 0 12 9" fill="none">
<path d="M1.125 0C0.503906 0 0 0.503906 0 1.125C0 1.47891 0.166406 1.81172 0.45 2.025L5.55 5.85C5.81719 6.04922 6.18281 6.04922 6.45 5.85L11.55 2.025C11.8336 1.81172 12 1.47891 12 1.125C12 0.503906 11.4961 0 10.875 0H1.125ZM0 2.625V7.5C0 8.32734 0.672656 9 1.5 9H10.5C11.3273 9 12 8.32734 12 7.5V2.625L6.9 6.45C6.36562 6.85078 5.63438 6.85078 5.1 6.45L0 2.625Z" fill="#092354"/>
</svg>Stuur een mail</a>
</div>
<?php endif; ?>
  </div>
</div>
<?php if($variant == 'default') : ?>
<div class="flex flex-wrap gap-4 mt-[16px] lg:mt-[28px]">
  <a class="btn btn-red flex items-center justify-center md:justify-start gap-2 max-md:flex-grow" href="tel:<?= $telefoon ?>"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
<path d="M3.86467 1.07707C3.68421 0.641147 3.20845 0.409126 2.75378 0.533339L0.691376 1.09581C0.283581 1.20831 0 1.57861 0 2.00046C0 7.79864 4.70136 12.5 10.4995 12.5C10.9214 12.5 11.2917 12.2164 11.4042 11.8086L11.9667 9.74621C12.0909 9.29155 11.8589 8.81579 11.4229 8.63533L9.17303 7.69787C8.79102 7.5385 8.34807 7.64865 8.08792 7.96973L7.14109 9.12515C5.49116 8.34471 4.15529 7.00884 3.37485 5.35891L4.53027 4.41442C4.85135 4.15193 4.9615 3.71132 4.80213 3.32931L3.86467 1.07941V1.07707Z" fill="white"/>
</svg> Neem contact op</a>
  <a class="btn btn-outline-<?= $text_color ?>  flex max-md:flex-grow items-center justify-center md:justify-start gap-2" href="mailto:<?= $email ?>"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="9" viewBox="0 0 12 9" fill="none">
<path d="M1.125 0C0.503906 0 0 0.503906 0 1.125C0 1.47891 0.166406 1.81172 0.45 2.025L5.55 5.85C5.81719 6.04922 6.18281 6.04922 6.45 5.85L11.55 2.025C11.8336 1.81172 12 1.47891 12 1.125C12 0.503906 11.4961 0 10.875 0H1.125ZM0 2.625V7.5C0 8.32734 0.672656 9 1.5 9H10.5C11.3273 9 12 8.32734 12 7.5V2.625L6.9 6.45C6.36562 6.85078 5.63438 6.85078 5.1 6.45L0 2.625Z" fill="#092354"/>
</svg>Stuur een mail</a>
</div>
<?php endif; ?>


<?php if(is_single() && get_post_type() == 'vacature' && $variant == 'default' || is_single() && get_post_type() == 'excursie' && $variant == 'default') : ?>

  <div class="lg:pr-24">
          <div class="flex items-center gap-4 text-gray-300 my-3">
            <hr class="flex-1 border-gray-700">
            <span>of</span>
            <hr class="flex-1 border-gray-700">
          </div>
<!-- Binnen de a href whatsapp url plaatsen -->
          <a href="https://wa.me/<?= get_field('whatsapp', 'option') ?>" target="_blank" class="btn btn-outline-white flex text-center items-center justify-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
  <path d="M10.2517 2.22364C9.12357 1.09383 7.62368 0.471069 6.0251 0.471069C2.7318 0.471069 0.0510457 3.15097 0.050195 6.44597C0.050195 7.49922 0.324991 8.52694 0.84821 9.433L0 12.5289L3.16739 11.6977C4.04027 12.1742 5.0229 12.4243 6.02255 12.4251H6.0251C9.3184 12.4251 11.9991 9.74438 12 6.45023C12 4.85334 11.3798 3.3526 10.2517 2.22364ZM6.0251 11.417H6.0234C5.13265 11.417 4.25806 11.1771 3.49578 10.7245L3.31457 10.6164L1.43524 11.1098L1.93719 9.27731L1.81893 9.08929C1.32208 8.29893 1.0592 7.38436 1.06005 6.44597C1.0609 3.70822 3.28905 1.48007 6.0285 1.48007C7.35484 1.48007 8.60206 1.99819 9.5396 2.93658C10.4771 3.87497 10.9935 5.12304 10.9927 6.45023C10.9918 9.18883 8.7637 11.4161 6.0268 11.4161L6.0251 11.417ZM8.74924 7.69744C8.60035 7.62258 7.86615 7.26185 7.72917 7.21166C7.5922 7.16146 7.49266 7.13679 7.39312 7.28652C7.29358 7.43626 7.00773 7.77231 6.9201 7.87185C6.83332 7.97139 6.74569 7.98415 6.59681 7.90928C6.44793 7.83442 5.96639 7.67702 5.39638 7.16827C4.95229 6.77266 4.65282 6.28348 4.56604 6.13374C4.47926 5.98401 4.55668 5.90319 4.63155 5.82917C4.69876 5.76196 4.78043 5.65476 4.8553 5.56799C4.93017 5.48121 4.95484 5.41825 5.00418 5.31871C5.05438 5.21917 5.02885 5.13154 4.99227 5.05753C4.95484 4.98266 4.65622 4.2476 4.53201 3.94899C4.4112 3.65802 4.28784 3.69716 4.19596 3.69291C4.10918 3.68865 4.00964 3.6878 3.9101 3.6878C3.81056 3.6878 3.64892 3.72523 3.51195 3.87497C3.37497 4.0247 2.98958 4.38543 2.98958 5.12048C2.98958 5.85554 3.52471 6.56508 3.59957 6.66462C3.67444 6.76416 4.65282 8.27256 6.15016 8.91914C6.50663 9.07313 6.78483 9.16501 7.00092 9.23392C7.35824 9.34792 7.68408 9.33176 7.94101 9.29347C8.22772 9.25008 8.82411 8.9319 8.94832 8.58394C9.07253 8.23512 9.07253 7.93651 9.03509 7.8744C8.99766 7.8123 8.89812 7.77486 8.74924 7.7V7.69744Z" fill="white"/>
</svg> Start een chat via WhatsApp</a>
          <p class="small text-gray-300 mt-3 text-center mb-0!">Beschikbaar opwerkdagen van 08:00 tot 17:00</p>
        </div>

  <?php endif; ?>