<?php
/**
 * Vacature card — Figma job row + BBG import fields (taxonomies, vacature_doel_url, thumbnail).
 *
 * @package advice2025
 */

declare(strict_types=1);

if (! isset($args) || ! is_array($args)) {
    $args = array();
}

$raw = null;
if (! empty($args['vacature'])) {
    $raw = $args['vacature'];
} elseif (! empty($args['post'])) {
    $raw = $args['post'];
}

$post_id = 0;
if ($raw instanceof WP_Post) {
    $post_id = (int) $raw->ID;
} elseif (is_numeric($raw)) {
    $post_id = (int) $raw;
}

if ($post_id <= 0 || get_post_status($post_id) !== 'publish') {
    return;
}

$background_class = isset($args['background_class']) ? trim((string) $args['background_class']) : '';

$header_group = function_exists('get_field') ? get_field('header', $post_id) : null;
if (! is_array($header_group)) {
    $header_group = array();
}

// BBG vacature URL (import stores remote permalink in vacature_doel_url).
$doel_raw = get_post_meta($post_id, 'vacature_doel_url', true);
$card_href = '';
if (is_string($doel_raw) && $doel_raw !== '') {
    $card_href = esc_url($doel_raw);
}
if ($card_href === '' && defined('ADVICE2025_BBG_SITE_URL')) {
    $card_href = esc_url((string) ADVICE2025_BBG_SITE_URL);
}
if ($card_href === '') {
    $card_href = esc_url(get_permalink($post_id));
}

$home_host = wp_parse_url(home_url(), PHP_URL_HOST);
$link_host = wp_parse_url($card_href, PHP_URL_HOST);
$is_external = is_string($link_host) && is_string($home_host)
    && strtolower((string) $link_host) !== strtolower((string) $home_host);

// Locatie: taxonomie (import), daarna args/ACF.
$locatie = '';
$loc_terms = get_the_terms($post_id, 'vacature_locatie');
if (! is_wp_error($loc_terms) && is_array($loc_terms) && $loc_terms !== array()) {
    $locatie = implode(
        ' / ',
        array_map(
            static function ($t) {
                return $t->name;
            },
            $loc_terms
        )
    );
}
if ($locatie === '' && ! empty($args['locatie'])) {
    $locatie = (string) $args['locatie'];
}
if ($locatie === '' && ! empty($header_group['locatie'])) {
    $locatie = (string) $header_group['locatie'];
}
if ($locatie === '' && function_exists('get_field')) {
    $locatie = (string) (get_field('header_locatie', $post_id) ?: '');
}
if ($locatie === '' && function_exists('get_field')) {
    $locatie = (string) (get_field('locatie', $post_id) ?: '');
}

// Uren: taxonomie (import), daarna args/ACF.
$uren_display = '';
$uren_terms = get_the_terms($post_id, 'vacature_aantal_uren');
if (! is_wp_error($uren_terms) && is_array($uren_terms) && $uren_terms !== array()) {
    $uren_display = implode(
        ' / ',
        array_map(
            static function ($t) {
                return $t->name;
            },
            $uren_terms
        )
    );
}
if ($uren_display === '' && ! empty($args['aantal_uren'])) {
    $a = (string) $args['aantal_uren'];
    $uren_display = $a !== '' ? $a . ' uur' : '';
}
if ($uren_display === '' && ! empty($header_group['aantal_uren'])) {
    $uren_display = (string) $header_group['aantal_uren'] . ' uur';
}
if ($uren_display === '' && function_exists('get_field')) {
    $h = get_field('header_aantal_uren', $post_id);
    $uren_display = $h !== null && $h !== '' ? (string) $h . ' uur' : '';
}
if ($uren_display === '' && function_exists('get_field')) {
    $h = get_field('aantal_uren', $post_id);
    $uren_display = $h !== null && $h !== '' ? (string) $h . ' uur' : '';
}

$title     = get_the_title($post_id);
$has_thumb = has_post_thumbnail($post_id);

$wrap_classes = array_filter(
    array(
        'vacature-card-wrap',
        $background_class,
        $background_class !== '' ? 'rounded-[4px] py-2 md:py-3' : '',
    )
);
?>

<div class="<?php echo esc_attr(implode(' ', $wrap_classes)); ?>">
    <a
        href="<?php echo esc_url($card_href); ?>"
        class="vacature-item group flex flex-col md:flex-row bg-white border border-black/12 overflow-hidden transition-shadow duration-300 hover:shadow-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
        <?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
    >
        <div class="relative w-full md:w-[245px] md:min-w-[245px] h-[200px] md:h-[169px] shrink-0 bg-secondary overflow-hidden">
            <?php if ($has_thumb) : ?>
                <?php
                echo get_the_post_thumbnail(
                    $post_id,
                    'large',
                    array(
                        'class'    => 'absolute inset-0 size-full object-cover',
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                        'alt'      => esc_attr(wp_strip_all_tags($title)),
                    )
                );
                ?>
            <?php endif; ?>
        </div>
        <div class="flex flex-1 flex-col md:flex-row md:items-center justify-between gap-4 min-w-0 px-5 py-5 md:pl-8 md:pr-6">
            <div class="min-w-0 flex-1">
                <h3 class="text-[1.875rem] font-light leading-[1.2] text-black mb-0">
                    <?php echo esc_html($title); ?>
                </h3>
                <?php if ($locatie !== '' || $uren_display !== '') : ?>
                    <div class="mt-4 flex flex-wrap items-center gap-x-8 gap-y-2 text-base font-light leading-normal text-black">
                        <?php if ($locatie !== '') : ?>
                            <span class="inline-flex items-center gap-2 whitespace-nowrap">
                                <span class="shrink-0 text-black" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M8 8.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" stroke="currentColor" stroke-width="1.2"/>
                                        <path d="M13 6.5c0 3.5-5 8.5-5 8.5S3 10 3 6.5a5 5 0 1 1 10 0Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <?php echo esc_html($locatie); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($uren_display !== '') : ?>
                            <span class="inline-flex items-center gap-2 whitespace-nowrap">
                                <span class="shrink-0 text-black" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <circle cx="8" cy="8" r="6.25" stroke="currentColor" stroke-width="1.2"/>
                                        <path d="M8 4.75V8l2.5 1.25" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <?php echo esc_html($uren_display); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <span class="shrink-0 self-end md:self-center text-primary" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                    <rect width="2.22222" height="2.22222" fill="currentColor"/>
                    <rect x="5.92578" y="11.8521" width="2.22222" height="2.22222" fill="currentColor"/>
                    <rect x="2.96094" y="14.8149" width="2.22222" height="2.22222" fill="currentColor"/>
                    <rect x="0.000183105" y="17.7778" width="2.22222" height="2.22222" fill="currentColor"/>
                    <rect x="2.96094" y="2.96289" width="2.22222" height="2.22222" fill="currentColor"/>
                    <rect x="8.89062" y="8.88916" width="2.22222" height="2.22222" fill="currentColor"/>
                    <rect x="5.92578" y="5.92578" width="2.22222" height="2.22222" fill="currentColor"/>
                </svg>
            </span>
        </div>
    </a>
</div>
