<?php
/**
 * Formulier Block
 * Toont een formulier
 */

 $formulier = get_sub_field('formulier')['id'];
 $medewerker = get_sub_field('contactpersoon');
 $label = get_sub_field('label');
 $titel = get_sub_field('titel');
 $content = get_sub_field('content');
 $image = get_sub_field('afbeelding');
 if(isset($args['formulier'])) {
  $formulier = $args['formulier'];
 }

 if(isset($args['medewerker'])) {
  $medewerker = $args['medewerker'];
 }

 if(isset($args['label'])) {
  $label = $args['label'];
 }

 if(isset($args['content'])) {
  $content = $args['content'];
 }

 if(isset($args['titel'])) {
  $titel = $args['titel'];
 }

 if(isset($args['image'])) {
  $image = $args['image'];
 }
?>

<section id="formulier" class="<?php echo get_spacing_bottom_class(); ?>">
  <div class="formulier pt-[1.25rem] pb-[3.75rem] lg:py-[10rem] bg-light-blue/25">
  <div class="container mx-auto">
    <div class="flex flex-col lg:flex-row lg:gap-10 items-center">

    <div class="w-full lg:w-6/12  ">
   
    <div class="aspect-auto lg:aspect-square relative overflow-hidden js-it-image js-image-text-animate">
      <div class="first-image js-image-animate w-full h-full">
        <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" class="w-full h-full object-cover object-center">
      </div>
      <div class="second-image js-image-animate w-full h-full absolute top-0 left-0">
        <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" class="w-full h-full object-cover object-center">
      </div>
      <div class="third-image js-image-animate w-full h-full absolute top-0 left-0">
        <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" class="w-full h-full object-cover object-center">
      </div>
    </div>
 
   </div>


    <div class="w-full lg:w-5/12 ml-auto mt-[2.5rem] lg:mt-0">
      <!-- <div class="absolute h-full w-[100vw] right-0 top-0 bg-gray-100"></div> -->
      <div class="relative lg:pe-5">
        <div class="">
          <div class="w-full">
            <h2 class="mb-[2rem]!"><?= $titel ?></h2>

            <?php if($content) { ?>
              <div class="content mb-[2.5rem]">
                <?php echo $content; ?>
              </div>
            <?php } ?>
          </div>

        </div>
       

      <?php echo do_shortcode('[gravityform id='.$formulier.' title="false"]') ?>
      </div>
    </div>

              
  </div>
  </div>
</section>