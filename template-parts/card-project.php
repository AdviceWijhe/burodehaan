<a href="<?php the_permalink(); ?>" class="block relative overflow-hidden aspect-[5/7] group">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 w-full h-full object-cover')); ?>
                    <?php endif; ?>
                    <div class="absolute left-3 right-3 bottom-3 rounded-[12px] border border-white/20 bg-white/5 backdrop-blur-[20px] lg:p-[32px] p-[20px] text-white flex items-end justify-between gap-3">
                      <div>
                        <?php
                        $term_label = '';
                        $taxonomies = get_object_taxonomies(get_post_type(), 'names');
                        if (!empty($taxonomies)) {
                          foreach ($taxonomies as $tax) {
                            $terms = get_the_terms(get_the_ID(), $tax);
                            if (!empty($terms) && !is_wp_error($terms)) {
                              $term_label = $terms[0]->name;
                              break;
                            }
                          }
                        }
                        ?>
                        <?php if ($term_label) : ?>
                          <div class="label-medium text-white lg:mb-[16px] mb-[12px]"><?php echo esc_html($term_label); ?></div>
                        <?php endif; ?>
                        <h3 class="headline-small text-white mb-0! w-2/3"><?php the_title(); ?></h3>
                      </div>
                      <span class="shrink-0 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                          <rect width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="2.96094" y="2.96313" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                          <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#F7F5F0"/>
                        </svg>
                      </span>
                    </div>
                  </a>