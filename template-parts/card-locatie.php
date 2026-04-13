<div class="block h-full group">
                    <div class="relative overflow-hidden aspect-[517/320]">
                      <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 w-full h-full object-cover')); ?>
                      <?php endif; ?>
                    </div>
                    <div class="bg-white p-[40px] flex items-end justify-between gap-4" style="border: 1px solid rgba(22, 22, 22, 0.12);">
                      <div class="min-w-0">
                        <h3 class="title-medium text-black mb-[28px]!"><?php the_title(); ?></h3>
                        <div class="">
                            <p class="mb-2! opacity-70"><?php the_field('adres'); ?></p>
                            <p class="opacity-70"><?php the_field('postcode'); ?>, <?php the_field('plaats'); ?></p>
                            <p class="mb-1! font-medium!"><a class="flex items-center gap-2" href="tel:<?php the_field('telefoonnummer'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path d="M5.47656 8.24219L8.125 5.625L5 0L0 2.5V3.125C0 12.4453 7.55469 20 16.875 20H17.5L20 15L14.375 11.875L11.7578 14.5234C9.00391 13.2148 6.78516 10.9961 5.47656 8.24219ZM14.4844 12.6523L19.1719 15.2578L17.1133 19.3789H16.875C7.90234 19.375 0.625 12.1016 0.625 3.125V2.88672L4.74219 0.828125L7.34766 5.51562L5.03516 7.80078L4.71875 8.11328L4.91016 8.51562C6.28125 11.3984 8.60156 13.7227 11.4844 15.0898L11.8867 15.2812L12.1992 14.9648L14.4844 12.6523Z" fill="#161616"/>
</svg> <?php the_field('telefoonnummer'); ?></a></p>
                            <p class="font-medium!"><a class="flex items-center gap-2" href="mailto:<?php the_field('e-mailadres'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="15" viewBox="0 0 20 15" fill="none">
  <path d="M0 0H20V15H0V0ZM19.375 0.625H0.625V2.66016L10 9.91797L19.375 2.66016V0.625ZM0.625 3.44922V14.375H19.375V3.44922L10.1914 10.5586L10 10.707L9.80859 10.5586L0.625 3.44922Z" fill="#161616"/>
</svg><?php the_field('e-mailadres'); ?></a></p>
                        </div>
                      </div>
                      
                    </div>
                      </div>