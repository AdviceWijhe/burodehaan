<?php
/**
 * Import vacatures from Beter Bouwen Groep REST API into local `vacature` posts.
 *
 * @package advice2025
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

const ADVICE2025_BBG_VACATURE_API = 'https://www.beterbouwengroep.nl/wp-json/wp/v2/vacature';

/** Publieke website Beter Bouwen Groep (wordt op elke geïmporteerde vacature opgeslagen). */
const ADVICE2025_BBG_SITE_URL = 'https://www.beterbouwengroep.nl/';

const ADVICE2025_BBG_VACATURE_TAXONOMIES = array(
    'vacature_vakgebied',
    'vacature_organisatie',
    'vacature_locatie',
    'vacature_aantal_uren',
);

const ADVICE2025_BBG_ALLOWED_ORGANISATIE_SLUGS = array(
    'buro-de-haan',
);

/**
 * Register taxonomies matching the remote API (same slugs).
 */
function advice2025_register_vacature_import_taxonomies(): void
{
    $common = array(
        'public'            => true,
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_in_rest'      => true,
        'query_var'         => true,
    );

    register_taxonomy(
        'vacature_vakgebied',
        array('vacature'),
        array_merge(
            $common,
            array(
                'labels'       => array(
                    'name'          => __('Vakgebieden (vacature)', 'advice2025'),
                    'singular_name' => __('Vakgebied (vacature)', 'advice2025'),
                ),
                'rewrite'      => array('slug' => 'vacature-vakgebied'),
            )
        )
    );

    register_taxonomy(
        'vacature_organisatie',
        array('vacature'),
        array_merge(
            $common,
            array(
                'labels'       => array(
                    'name'          => __('Organisaties (vacature)', 'advice2025'),
                    'singular_name' => __('Organisatie (vacature)', 'advice2025'),
                ),
                'rewrite'      => array('slug' => 'vacature-organisatie'),
            )
        )
    );

    register_taxonomy(
        'vacature_locatie',
        array('vacature'),
        array_merge(
            $common,
            array(
                'labels'       => array(
                    'name'          => __('Locaties (vacature)', 'advice2025'),
                    'singular_name' => __('Locatie (vacature)', 'advice2025'),
                ),
                'rewrite'      => array('slug' => 'vacature-locatie'),
            )
        )
    );

    register_taxonomy(
        'vacature_aantal_uren',
        array('vacature'),
        array_merge(
            $common,
            array(
                'labels'       => array(
                    'name'          => __('Aantal uren (vacature)', 'advice2025'),
                    'singular_name' => __('Aantal uren (vacature)', 'advice2025'),
                ),
                'rewrite'      => array('slug' => 'vacature-aantal-uren'),
            )
        )
    );
}

add_action('init', 'advice2025_register_vacature_import_taxonomies', 5);

/**
 * Ensure a term exists locally; create from remote slug/name if missing.
 */
function advice2025_ensure_vacature_term(string $taxonomy, string $slug, string $name): ?int
{
    if (! taxonomy_exists($taxonomy)) {
        return null;
    }

    $slug = sanitize_title($slug);
    if ($slug === '') {
        return null;
    }

    $existing = term_exists($slug, $taxonomy);
    if ($existing) {
        if (is_array($existing)) {
            return (int) $existing['term_id'];
        }

        return (int) $existing;
    }

    $result = wp_insert_term(
        $name !== '' ? $name : $slug,
        $taxonomy,
        array('slug' => $slug)
    );

    if (is_wp_error($result)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[advice2025 vacature import] wp_insert_term failed: ' . $result->get_error_message());
        }

        return null;
    }

    return (int) $result['term_id'];
}

/**
 * Build local term ID lists per taxonomy from _embedded wp:term.
 *
 * @param array<string, mixed> $item Decoded REST post object.
 * @return array<string, int[]>
 */
function advice2025_parse_embedded_vacature_terms(array $item): array
{
    $out = array(
        'vacature_vakgebied'    => array(),
        'vacature_organisatie'  => array(),
        'vacature_locatie'      => array(),
        'vacature_aantal_uren'  => array(),
    );

    if (empty($item['_embedded']['wp:term']) || ! is_array($item['_embedded']['wp:term'])) {
        return $out;
    }

    foreach ($item['_embedded']['wp:term'] as $group) {
        if (! is_array($group)) {
            continue;
        }
        foreach ($group as $term) {
            if (! is_array($term) || empty($term['taxonomy'])) {
                continue;
            }
            $tax = (string) $term['taxonomy'];
            if (! in_array($tax, ADVICE2025_BBG_VACATURE_TAXONOMIES, true)) {
                continue;
            }
            $slug = isset($term['slug']) ? (string) $term['slug'] : '';
            $name = isset($term['name']) ? wp_strip_all_tags((string) $term['name']) : $slug;
            $tid  = advice2025_ensure_vacature_term($tax, $slug, $name);
            if ($tid !== null) {
                $out[ $tax ][] = $tid;
            }
        }
    }

    foreach ($out as $tax => $ids) {
        $out[ $tax ] = array_values(array_unique(array_map('intval', $ids)));
    }

    return $out;
}

/**
 * Check whether remote vacature belongs to allowed organisaties.
 *
 * @param array<string, mixed> $item Decoded REST post object.
 */
function advice2025_has_allowed_vacature_organisatie(array $item): bool
{
    if (empty($item['_embedded']['wp:term']) || ! is_array($item['_embedded']['wp:term'])) {
        return false;
    }

    foreach ($item['_embedded']['wp:term'] as $group) {
        if (! is_array($group)) {
            continue;
        }

        foreach ($group as $term) {
            if (
                ! is_array($term)
                || ($term['taxonomy'] ?? '') !== 'vacature_organisatie'
                || empty($term['slug'])
            ) {
                continue;
            }

            $organisatie_slug = sanitize_title((string) $term['slug']);
            if (in_array($organisatie_slug, ADVICE2025_BBG_ALLOWED_ORGANISATIE_SLUGS, true)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Resolve a unique post_name for vacature CPT.
 *
 * @param string               $desired_slug Remote slug.
 * @param int                  $remote_id    Remote post ID (for suffix).
 * @param int                  $local_id     Local post ID when updating (0 when inserting).
 */
function advice2025_vacature_unique_slug(string $desired_slug, int $remote_id, int $local_id = 0): string
{
    $base = sanitize_title($desired_slug);
    if ($base === '') {
        $base = 'vacature-' . $remote_id;
    }

    $query = new WP_Query(
        array(
            'post_type'              => 'vacature',
            'name'                   => $base,
            'posts_per_page'         => 1,
            'post_status'            => 'any',
            'fields'                 => 'ids',
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        )
    );

    if (empty($query->posts)) {
        return $base;
    }

    $found = (int) $query->posts[0];
    if ($local_id > 0 && $found === $local_id) {
        return $base;
    }

    return $base . '-' . $remote_id;
}

/**
 * Find local vacature post ID by remote BBG id.
 */
function advice2025_find_vacature_by_remote_id(int $remote_id): int
{
    if ($remote_id <= 0) {
        return 0;
    }

    $posts = get_posts(
        array(
            'post_type'              => 'vacature',
            'post_status'            => 'any',
            'posts_per_page'         => 1,
            'fields'                 => 'ids',
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'meta_key'               => '_bbg_vacature_id',
            'meta_value'             => (string) $remote_id,
        )
    );

    return ! empty($posts) ? (int) $posts[0] : 0;
}

/**
 * Featured image URL from embedded REST response.
 *
 * @param array<string, mixed> $item
 */
function advice2025_get_bbg_embedded_featured_media_url(array $item): ?string
{
    $embed = $item['_embedded']['wp:featuredmedia'][0] ?? null;
    if (! is_array($embed)) {
        return null;
    }

    if (! empty($embed['source_url']) && is_string($embed['source_url'])) {
        return esc_url_raw($embed['source_url']);
    }

    $full = $embed['media_details']['sizes']['full']['source_url'] ?? '';
    if (is_string($full) && $full !== '') {
        return esc_url_raw($full);
    }

    return null;
}

/**
 * Fetch featured image URL from remote media endpoint (fallback if embed missing).
 */
function advice2025_fetch_bbg_media_source_url(int $media_id): ?string
{
    if ($media_id <= 0) {
        return null;
    }

    $url = sprintf(
        'https://www.beterbouwengroep.nl/wp-json/wp/v2/media/%d',
        $media_id
    );

    $response = wp_remote_get(
        $url,
        array(
            'timeout' => 20,
            'headers' => array(
                'User-Agent' => 'Advice2025-Vacature-Import; ' . home_url('/'),
            ),
        )
    );

    if (is_wp_error($response)) {
        return null;
    }

    if ((int) wp_remote_retrieve_response_code($response) !== 200) {
        return null;
    }

    $body = json_decode((string) wp_remote_retrieve_body($response), true);
    if (! is_array($body) || empty($body['source_url']) || ! is_string($body['source_url'])) {
        return null;
    }

    return esc_url_raw($body['source_url']);
}

/**
 * Resolve public image URL for a vacature (embed first, then media API).
 *
 * @param array<string, mixed> $item
 */
function advice2025_get_bbg_featured_image_url(array $item): ?string
{
    $embedded = advice2025_get_bbg_embedded_featured_media_url($item);
    if ($embedded !== null && $embedded !== '') {
        return $embedded;
    }

    $media_id = isset($item['featured_media']) ? (int) $item['featured_media'] : 0;

    return advice2025_fetch_bbg_media_source_url($media_id);
}

/**
 * Download remote featured image and set as post thumbnail when needed.
 *
 * @param array<string, mixed> $item
 */
function advice2025_sync_bbg_featured_image(int $local_post_id, array $item): void
{
    $remote_media = isset($item['featured_media']) ? (int) $item['featured_media'] : 0;

    if ($remote_media === 0) {
        update_post_meta($local_post_id, '_bbg_featured_media_id', 0);
        $thumb = (int) get_post_thumbnail_id($local_post_id);
        if (
            $thumb > 0
            && get_post_meta($thumb, '_bbg_sideload_source', true) === 'vacature_import'
        ) {
            wp_delete_attachment($thumb, true);
            delete_post_thumbnail($local_post_id);
        }

        return;
    }

    $stored = (int) get_post_meta($local_post_id, '_bbg_featured_media_id', true);
    if ($stored === $remote_media && has_post_thumbnail($local_post_id)) {
        return;
    }

    $image_url = advice2025_get_bbg_featured_image_url($item);
    if ($image_url === null || $image_url === '') {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[advice2025 vacature import] download_url failed: ' . $tmp->get_error_message());
        }

        return;
    }

    $path     = wp_parse_url($image_url, PHP_URL_PATH);
    $filename = is_string($path) ? basename($path) : '';
    if ($filename === '' || ! preg_match('/\.(jpe?g|png|gif|webp)$/i', $filename)) {
        $filename = 'bbg-vacature-' . $remote_media . '.jpg';
    }

    $file_array = array(
        'name'     => sanitize_file_name($filename),
        'tmp_name' => $tmp,
    );

    $old_thumb = (int) get_post_thumbnail_id($local_post_id);

    $attachment_id = media_handle_sideload($file_array, $local_post_id);
    if (is_wp_error($attachment_id)) {
        if (is_string($tmp) && is_file($tmp)) {
            unlink($tmp);
        }
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[advice2025 vacature import] media_handle_sideload failed: ' . $attachment_id->get_error_message());
        }

        return;
    }

    $attachment_id = (int) $attachment_id;
    update_post_meta($attachment_id, '_bbg_sideload_source', 'vacature_import');

    if ($old_thumb > 0 && $old_thumb !== $attachment_id) {
        if (get_post_meta($old_thumb, '_bbg_sideload_source', true) === 'vacature_import') {
            wp_delete_attachment($old_thumb, true);
        }
    }

    set_post_thumbnail($local_post_id, $attachment_id);
    update_post_meta($local_post_id, '_bbg_featured_media_id', $remote_media);
}

/**
 * Fetch one page from the remote API.
 *
 * @return array{items: array<int, array<string, mixed>>, total_pages: int}|WP_Error
 */
function advice2025_fetch_bbg_vacature_page(int $page)
{
    $url = add_query_arg(
        array(
            'per_page' => 100,
            'page'     => max(1, $page),
            '_embed'   => 1,
        ),
        ADVICE2025_BBG_VACATURE_API
    );

    $response = wp_remote_get(
        $url,
        array(
            'timeout' => 30,
            'headers' => array(
                'User-Agent' => 'Advice2025-Vacature-Import; ' . home_url('/'),
            ),
        )
    );

    if (is_wp_error($response)) {
        return $response;
    }

    $code = (int) wp_remote_retrieve_response_code($response);
    if ($code !== 200) {
        return new WP_Error(
            'bbg_http_error',
            sprintf('HTTP %d from vacature API', $code)
        );
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (! is_array($data)) {
        return new WP_Error('bbg_json_error', 'Invalid JSON from vacature API');
    }

    $total_pages = (int) wp_remote_retrieve_header($response, 'x-wp-totalpages');
    if ($total_pages < 1) {
        $total_pages = 1;
    }

    return array(
        'items'       => $data,
        'total_pages' => $total_pages,
    );
}

/**
 * Import or update a single remote vacature item.
 *
 * @param array<string, mixed> $item
 * @return array{created: bool, updated: bool, skipped: bool}
 */
function advice2025_import_single_bbg_vacature(array $item): array
{
    $result = array(
        'created' => false,
        'updated' => false,
        'skipped' => false,
    );

    if (empty($item['id']) || ($item['status'] ?? '') !== 'publish') {
        $result['skipped'] = true;

        return $result;
    }

    if (! advice2025_has_allowed_vacature_organisatie($item)) {
        $result['skipped'] = true;

        return $result;
    }

    $remote_id = (int) $item['id'];
    $title     = isset($item['title']['rendered'])
        ? wp_strip_all_tags((string) $item['title']['rendered'])
        : '';

    if ($title === '') {
        $result['skipped'] = true;

        return $result;
    }

    $link = isset($item['link']) ? esc_url_raw((string) $item['link']) : '';
    $slug = isset($item['slug']) ? (string) $item['slug'] : '';

    $local_id = advice2025_find_vacature_by_remote_id($remote_id);
    $post_name = advice2025_vacature_unique_slug($slug, $remote_id, $local_id);

    $post_data = array(
        'post_type'    => 'vacature',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_name'    => $post_name,
        'post_content' => '',
        'post_excerpt' => '',
    );

    if ($local_id > 0) {
        $post_data['ID'] = $local_id;
        $updated_id      = wp_update_post(wp_slash($post_data), true);
        if (is_wp_error($updated_id)) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[advice2025 vacature import] wp_update_post failed: ' . $updated_id->get_error_message());
            }
            $result['skipped'] = true;

            return $result;
        }
        $local_id = (int) $updated_id;
        $result['updated'] = true;
    } else {
        $new_id = wp_insert_post(wp_slash($post_data), true);
        if (is_wp_error($new_id)) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[advice2025 vacature import] wp_insert_post failed: ' . $new_id->get_error_message());
            }
            $result['skipped'] = true;

            return $result;
        }
        $local_id = (int) $new_id;
        $result['created'] = true;
    }

    update_post_meta($local_id, '_bbg_vacature_id', $remote_id);
    if ($link !== '') {
        update_post_meta($local_id, 'vacature_doel_url', $link);
    } else {
        delete_post_meta($local_id, 'vacature_doel_url');
    }

    update_post_meta(
        $local_id,
        'vacature_bbg_website_url',
        esc_url_raw(ADVICE2025_BBG_SITE_URL)
    );

    $terms_by_tax = advice2025_parse_embedded_vacature_terms($item);
    foreach (ADVICE2025_BBG_VACATURE_TAXONOMIES as $taxonomy) {
        $ids = $terms_by_tax[ $taxonomy ] ?? array();
        if ($ids === array()) {
            wp_set_object_terms($local_id, array(), $taxonomy, false);
        } else {
            wp_set_object_terms($local_id, $ids, $taxonomy, false);
        }
    }

    advice2025_sync_bbg_featured_image($local_id, $item);

    return $result;
}

/**
 * Run full import; returns aggregate counts.
 *
 * @return array{created: int, updated: int, skipped: int, pages: int}|WP_Error
 */
function advice2025_run_bbg_vacature_import()
{
    $created = 0;
    $updated = 0;
    $skipped = 0;
    $pages   = 0;

    $first = advice2025_fetch_bbg_vacature_page(1);
    if (is_wp_error($first)) {
        return $first;
    }

    $total_pages = (int) $first['total_pages'];
    $pages       = $total_pages;

    $page = 1;
    while ($page <= $total_pages) {
        $batch = $page === 1 ? $first : advice2025_fetch_bbg_vacature_page($page);
        if (is_wp_error($batch)) {
            return $batch;
        }

        foreach ($batch['items'] as $item) {
            if (! is_array($item)) {
                ++$skipped;
                continue;
            }
            $r = advice2025_import_single_bbg_vacature($item);
            if ($r['created']) {
                ++$created;
            } elseif ($r['updated']) {
                ++$updated;
            } else {
                ++$skipped;
            }
        }

        ++$page;
    }

    return array(
        'created' => $created,
        'updated' => $updated,
        'skipped' => $skipped,
        'pages'   => $pages,
    );
}

/**
 * Admin: Tools submenu + handler.
 */
function advice2025_vacature_import_admin_menu(): void
{
    add_management_page(
        __('Import vacatures (BBG)', 'advice2025'),
        __('Import vacatures (BBG)', 'advice2025'),
        'manage_options',
        'advice2025-import-vacatures',
        'advice2025_vacature_import_tools_page'
    );
}

add_action('admin_menu', 'advice2025_vacature_import_admin_menu');

function advice2025_vacature_import_tools_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $err = isset($_GET['advice2025_vacature_import_err']) ? sanitize_text_field((string) wp_unslash($_GET['advice2025_vacature_import_err'])) : '';
    if ($err !== '') {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($err) . '</p></div>';
    }

    $run = isset($_GET['advice2025_vacature_import_done']) ? sanitize_text_field((string) wp_unslash($_GET['advice2025_vacature_import_done'])) : '';
    if ($run === '1') {
        $c = isset($_GET['c']) ? (int) $_GET['c'] : 0;
        $u = isset($_GET['u']) ? (int) $_GET['u'] : 0;
        $s = isset($_GET['s']) ? (int) $_GET['s'] : 0;
        echo '<div class="notice notice-success is-dismissible"><p>';
        echo esc_html(
            sprintf(
                /* translators: 1: created count, 2: updated count, 3: skipped count */
                __('Vacature-import voltooid: %1$d nieuw, %2$d bijgewerkt, %3$d overgeslagen.', 'advice2025'),
                $c,
                $u,
                $s
            )
        );
        echo '</p></div>';
    }

    $url = wp_nonce_url(
        admin_url('admin.php?action=advice2025_import_vacatures'),
        'advice2025_import_vacatures'
    );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Import vacatures (Beter Bouwen Groep)', 'advice2025'); ?></h1>
        <p><?php echo esc_html__('Haalt gepubliceerde vacatures op via de REST API en slaat titel, doel-URL en taxonomieën lokaal op.', 'advice2025'); ?></p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url($url); ?>">
                <?php echo esc_html__('Nu importeren', 'advice2025'); ?>
            </a>
        </p>
        <p class="description">
            <?php echo esc_html__('WP-CLI: wp advice2025 import-vacatures', 'advice2025'); ?>
        </p>
    </div>
    <?php
}

function advice2025_handle_admin_vacature_import(): void
{
    if (! isset($_GET['action']) || $_GET['action'] !== 'advice2025_import_vacatures') {
        return;
    }

    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Onvoldoende rechten.', 'advice2025'));
    }

    check_admin_referer('advice2025_import_vacatures');

    $stats = advice2025_run_bbg_vacature_import();
    if (is_wp_error($stats)) {
        wp_safe_redirect(
            add_query_arg(
                array(
                    'page'                          => 'advice2025-import-vacatures',
                    'advice2025_vacature_import_err' => rawurlencode($stats->get_error_message()),
                ),
                admin_url('tools.php')
            )
        );
        exit;
    }

    wp_safe_redirect(
        add_query_arg(
            array(
                'page'                             => 'advice2025-import-vacatures',
                'advice2025_vacature_import_done'  => '1',
                'c'                                => $stats['created'],
                'u'                                => $stats['updated'],
                's'                                => $stats['skipped'],
            ),
            admin_url('tools.php')
        )
    );
    exit;
}

add_action('admin_init', 'advice2025_handle_admin_vacature_import');

/**
 * WP-CLI command.
 */
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command(
        'advice2025 import-vacatures',
        static function (): void {
            WP_CLI::log('Starting BBG vacature import...');
            $stats = advice2025_run_bbg_vacature_import();
            if (is_wp_error($stats)) {
                WP_CLI::error($stats->get_error_message());
            }
            WP_CLI::success(
                sprintf(
                    'Done. Created: %d, updated: %d, skipped: %d (pages: %d).',
                    $stats['created'],
                    $stats['updated'],
                    $stats['skipped'],
                    $stats['pages']
                )
            );
        }
    );
}
