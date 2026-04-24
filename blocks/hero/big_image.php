<section class="hero hero__big_image overflow-hidden relative w-full max-w-full">
    <div class="relative flex items-end w-full p-0 h-[37.5rem] md:h-auto md:aspect-[1728/750]">
        <div class="absolute inset-0 h-full w-full">
            <div class="absolute top-0 left-0 w-1/2 h-full" style="background: linear-gradient(90deg, rgba(22, 22, 22, 0.5) 0%, rgba(22, 22, 22, 0) 100%); z-index: 2;"></div>
<div class="absolute bottom-0 left-0 w-full h-[18.75rem]" style="opacity: 0.5;
background: linear-gradient(0deg, #0A2031 0%, rgba(10, 32, 49, 0.00) 100%); z-index: 2;"></div>
            <?php
            $video_input = trim((string) get_sub_field('video_id'));
            $video_type = '';
            $video_id = '';
            $video_embed_url = '';
            $afbeelding = get_sub_field('afbeelding');

            if ($video_input) {
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_input, $matches)) {
                    $video_type = 'youtube';
                    $video_id = $matches[1];
                } elseif (preg_match('/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/', $video_input, $matches)) {
                    $video_type = 'vimeo';
                    $video_id = $matches[1];
                } elseif (preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_input)) {
                    $video_type = 'youtube';
                    $video_id = $video_input;
                } elseif (preg_match('/^\d+$/', $video_input)) {
                    $video_type = 'vimeo';
                    $video_id = $video_input;
                }

                if ($video_type === 'youtube' && $video_id !== '') {
                    $video_embed_url = 'https://www.youtube.com/embed/' . rawurlencode($video_id) . '?autoplay=1&mute=1&controls=0&rel=0&playsinline=1&loop=1&playlist=' . rawurlencode($video_id);
                } elseif ($video_type === 'vimeo' && $video_id !== '') {
                    $video_embed_url = 'https://player.vimeo.com/video/' . rawurlencode($video_id) . '?autoplay=1&muted=1&background=1&loop=1';
                }
            }

            if ($video_embed_url !== '') :
            ?>
                <div class="hero-big-image-video">
                    <iframe
                        src="<?= esc_url($video_embed_url) ?>"
                        title="<?= esc_attr(get_sub_field('titel') ?: 'Hero video') ?>"
                        class="hero-big-image-video-frame"
                        frameborder="0"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen
                        loading="eager"
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            <?php
            elseif ($afbeelding && isset($afbeelding['ID'])) :
                $image_srcset = wp_get_attachment_image_srcset($afbeelding['ID'], 'full');
                $image_sizes = wp_get_attachment_image_sizes($afbeelding['ID'], 'full');
                $image_src = wp_get_attachment_image_url($afbeelding['ID'], 'full');
            ?>
                <img src="<?= esc_url($image_src) ?>"
                     <?php if ($image_srcset) : ?>srcset="<?= esc_attr($image_srcset) ?>" sizes="<?= esc_attr($image_sizes ?: '100vw') ?>"<?php endif; ?>
                     alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>"
                     loading="eager" class="w-full h-full object-cover object-center">
            <?php elseif ($afbeelding && isset($afbeelding['url'])) : ?>
                <img src="<?= esc_url($afbeelding['url']) ?>"
                     alt="<?= esc_attr($afbeelding['alt'] ?? '') ?>"
                     loading="eager" class="w-full h-full object-cover object-center">
            <?php endif; ?>
        </div>
        <div class="w-full relative z-2">
            <div class="w-full lg:w-6/12 p-[1rem] lg:px-[3.75rem] lg:py-[3.75rem]">
                <?php
                $hero_title = (string) get_sub_field('titel', false, false);
                $hero_title = preg_replace('/^\s*<p>(.*)<\/p>\s*$/si', '$1', $hero_title);
                ?>
                <div class="headline-large text-white !text-white [&_*]:!text-white"><?php echo wp_kses_post($hero_title); ?></div>
                <div class="mt-[1.75rem] lg:mt-[2.5rem] hero-big-image-buttons">
                <?= get_template_part('template-parts/core/buttons', null, array('buttons' => get_sub_field('buttons'), 'align_items' => 'start', 'white_hover_primary' => true)) ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hero__big_image .hero-big-image-buttons .btn {
            border-radius: 0 !important;
        }

        @media (max-width: 1023px) {
            .hero__big_image .hero-big-image-buttons > div {
                flex-direction: row !important;
                gap: 12px !important;
                flex-wrap: wrap;
            }

            .hero__big_image .hero-big-image-buttons .btn {
                padding: 20px !important;
            }
        }

        .hero__big_image .hero-big-image-video {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .hero__big_image .hero-big-image-video-frame {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 129.6%;
            max-width: none;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }
    </style>
</section>