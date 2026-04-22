<a href="<?php the_permalink(); ?>" class="block h-full group">
                    <div class="relative overflow-hidden aspect-[517/320]">
                      <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 w-full h-full object-cover')); ?>
                      <?php endif; ?>
                    </div>
                    <div class="bg-white p-[2.5rem] flex items-end justify-between gap-4" style="border: 1px solid rgba(22, 22, 22, 0.12);">
                      <div class="min-w-0">
                        <h3 class="title-medium text-black mb-3!"><?php the_title(); ?></h3>
                        <div class="body-small text-black/70 mb-0!">
                          <?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 20, '...')); ?>
                        </div>
                      </div>
                      <span class="shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                          <rect width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="2.96094" y="2.96289" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="#EC663C"/>
                          <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="#EC663C"/>
                        </svg>
                      </span>
                    </div>
                  </a>