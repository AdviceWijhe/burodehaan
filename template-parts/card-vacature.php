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
        $background_class !== '' ? 'rounded-[0.25rem] py-2 md:py-3' : '',
    )
);
?>

<div class="<?php echo esc_attr(implode(' ', $wrap_classes)); ?> h-full">
    <a
        href="<?php echo esc_url($card_href); ?>"
        class="vacature-item group flex flex-col md:flex-row bg-white border border-black/12 overflow-hidden transition-shadow duration-300 hover:border-black/25 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary h-full"
        <?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
    >
        <div class="relative w-full md:w-[15.3125rem] md:min-w-[15.3125rem] h-[12.5rem] md:h-[10.5625rem] shrink-0 bg-secondary overflow-hidden">
            <?php if ($has_thumb) : ?>
                <?php
                echo get_the_post_thumbnail(
                    $post_id,
                    'large',
                    array(
                        'class'    => 'absolute inset-0 size-full object-cover transition-transform duration-300 ease-out group-hover:scale-110',
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                        'alt'      => esc_attr(wp_strip_all_tags($title)),
                    )
                );
                ?>
            <?php endif; ?>
        </div>
        <div class="flex flex-1 flex-col md:flex-row md:items-stretch justify-between gap-4 min-w-0 px-5 py-5 md:pl-8 md:pr-6 md:py-6 min-h-[10rem] md:min-h-0">
            <div class="min-w-0 flex-1 flex flex-col justify-between">
                <h3 class="text-[1.875rem] font-light leading-[1.2] text-black mb-0">
                    <?php echo esc_html($title); ?>
                </h3>
                <?php if ($locatie !== '' || $uren_display !== '') : ?>
                    <div class="mt-4 flex flex-col md:flex-row md:flex-wrap md:items-center gap-2 md:gap-x-8 md:gap-y-2 text-base font-light leading-normal text-black">
                        <?php if ($locatie !== '') : ?>
                            <span class="inline-flex items-center gap-2 whitespace-nowrap">
                                <span class="shrink-0 text-black" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 15 20" fill="none">
  <path d="M0.605946 7.14259C0.605946 8.44916 1.02253 9.87692 1.68529 11.3085C2.34425 12.7324 3.23045 14.1261 4.12422 15.3569C5.01799 16.584 5.91176 17.6406 6.58587 18.3867C6.85476 18.6859 7.08957 18.9358 7.27135 19.129C7.45313 18.9358 7.68794 18.6859 7.95683 18.3867C9.29748 16.8983 11.5433 14.1526 12.8574 11.3047C13.5202 9.87313 13.9368 8.44916 13.9368 7.1388C13.9368 3.541 10.9638 0.602159 7.27135 0.602159C3.57887 0.602159 0.605946 3.541 0.605946 7.14259ZM14.5427 7.14259C14.5427 12.0167 9.24825 17.9322 7.69551 19.5645C7.65385 19.6061 7.61977 19.644 7.5819 19.6819C7.38497 19.8864 7.27135 20 7.27135 20C7.27135 20 7.15774 19.8902 6.9608 19.6819C6.92672 19.644 6.88885 19.6061 6.84719 19.5645C5.29445 17.936 0 12.0205 0 7.14259C0 3.20015 3.25696 0 7.27135 0C11.2857 0 14.5427 3.19636 14.5427 7.14259ZM7.27135 4.24162C8.07488 4.24162 8.84551 4.56082 9.41369 5.12901C9.98188 5.69719 10.3011 6.46782 10.3011 7.27135C10.3011 8.07488 9.98188 8.84551 9.41369 9.41369C8.84551 9.98188 8.07488 10.3011 7.27135 10.3011C6.46782 10.3011 5.69719 9.98188 5.12901 9.41369C4.56082 8.84551 4.24162 8.07488 4.24162 7.27135C4.24162 6.46782 4.56082 5.69719 5.12901 5.12901C5.69719 4.56082 6.46782 4.24162 7.27135 4.24162ZM9.69513 7.27135C9.69513 6.62852 9.43977 6.01202 8.98522 5.55748C8.53068 5.10293 7.91418 4.84757 7.27135 4.84757C6.62852 4.84757 6.01202 5.10293 5.55748 5.55748C5.10293 6.01202 4.84757 6.62852 4.84757 7.27135C4.84757 7.91418 5.10293 8.53068 5.55748 8.98522C6.01202 9.43977 6.62852 9.69513 7.27135 9.69513C7.91418 9.69513 8.53068 9.43977 8.98522 8.98522C9.43977 8.53068 9.69513 7.91418 9.69513 7.27135Z" fill="#EC663C"/>
</svg>
                                </span>
                                <?php echo esc_html($locatie); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($uren_display !== '') : ?>
                            <span class="inline-flex items-center gap-2 whitespace-nowrap">
                                <span class="shrink-0 text-black" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path d="M19.375 10C19.375 11.2311 19.1325 12.4502 18.6614 13.5877C18.1902 14.7251 17.4997 15.7586 16.6291 16.6291C15.7586 17.4997 14.7251 18.1902 13.5877 18.6614C12.4502 19.1325 11.2311 19.375 10 19.375C8.76886 19.375 7.54977 19.1325 6.41234 18.6614C5.27492 18.1902 4.24142 17.4997 3.37087 16.6291C2.50032 15.7586 1.80977 14.7251 1.33863 13.5877C0.867491 12.4502 0.625 11.2311 0.625 10C0.625 8.76886 0.867491 7.54977 1.33863 6.41234C1.80977 5.27492 2.50032 4.24142 3.37087 3.37087C4.24142 2.50032 5.27492 1.80977 6.41234 1.33863C7.54977 0.867491 8.76886 0.625 10 0.625C11.2311 0.625 12.4502 0.867491 13.5877 1.33863C14.7251 1.80977 15.7586 2.50032 16.6291 3.37087C17.4997 4.24142 18.1902 5.27492 18.6614 6.41234C19.1325 7.54977 19.375 8.76886 19.375 10ZM0 10C0 12.6522 1.05357 15.1957 2.92893 17.0711C4.8043 18.9464 7.34784 20 10 20C12.6522 20 15.1957 18.9464 17.0711 17.0711C18.9464 15.1957 20 12.6522 20 10C20 7.34784 18.9464 4.8043 17.0711 2.92893C15.1957 1.05357 12.6522 0 10 0C7.34784 0 4.8043 1.05357 2.92893 2.92893C1.05357 4.8043 0 7.34784 0 10ZM9.6875 4.0625V10.168L9.82812 10.2617L13.5781 12.7617L13.8398 12.9336L14.1875 12.4141L13.9258 12.2422L10.3164 9.83594V3.75391H9.69141V4.06641L9.6875 4.0625Z" fill="#EC663C"/>
</svg>
                                </span>
                                <?php echo esc_html($uren_display); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <span class="shrink-0 self-end md:self-center text-primary transition-transform duration-300 ease-out group-hover:translate-x-[6px]" aria-hidden="true">
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
