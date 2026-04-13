<?php
/**
 * Reusable archive filter panel.
 *
 * @var array $args {
 *   @type string $panel_id
 *   @type string $label
 *   @type array  $taxonomies
 *   @type array  $active_filters
 * }
 */

$panel_id = isset($args['panel_id']) ? sanitize_html_class((string) $args['panel_id']) : 'archive-filter-panel';
$label = isset($args['label']) ? (string) $args['label'] : __('Filter', 'advice2025');
$taxonomies = isset($args['taxonomies']) && is_array($args['taxonomies']) ? $args['taxonomies'] : array();
$active_filters = isset($args['active_filters']) && is_array($args['active_filters']) ? $args['active_filters'] : array();

if (empty($taxonomies)) {
    return;
}
?>
<div
    class="filter_item"
    data-archive-filter-panel
    data-panel-id="<?php echo esc_attr($panel_id); ?>"
>
    <button
        type="button"
        class="filter_item_label btn border border-black text-black gap-[14px]"
        data-archive-filter-open
        aria-haspopup="dialog"
        aria-controls="<?php echo esc_attr($panel_id); ?>"
        aria-expanded="false"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="19" viewBox="0 0 26 19" fill="none" aria-hidden="true">
            <path d="M0.791667 18.2083H7.125V11.875H0.791667V18.2083ZM7.91667 15.4375V19H0V11.0833H7.91667V14.6458H25.3333V15.4375H7.91667ZM0.395833 4.35417H0V3.5625H17.4167V0H25.3333V7.91667H17.4167V4.35417H0.395833ZM18.2083 7.125H24.5417V0.791667H18.2083V7.125Z" fill="#161616"/>
        </svg>
        <?php echo esc_html($label); ?>
    </button>

    <div
        class="fixed inset-0 bg-black/30 z-[90] hidden"
        data-archive-filter-overlay
    ></div>

    <aside
        id="<?php echo esc_attr($panel_id); ?>"
        class="fixed top-0 right-0 h-full w-full sm:max-w-[540px] bg-white z-[100] translate-x-full transition-transform duration-300 ease-out shadow-2xl"
        data-archive-filter-drawer
        role="dialog"
        aria-modal="true"
        aria-labelledby="<?php echo esc_attr($panel_id); ?>-title"
    >
        <div class="h-full flex flex-col">
            <div class="flex items-center justify-between px-8 py-6 border-b border-black/10">
                <h2 id="<?php echo esc_attr($panel_id); ?>-title" class="headline-medium mb-0!">
                    <?php echo esc_html($label); ?>
                </h2>
                <button
                    type="button"
                    class="btn border border-black text-black"
                    data-archive-filter-close
                    aria-label="<?php esc_attr_e('Sluit filter', 'advice2025'); ?>"
                >
                    ×
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-8 py-6 space-y-8">
                <?php foreach ($taxonomies as $taxonomy_key => $taxonomy_config) : ?>
                    <?php
                    $taxonomy = is_string($taxonomy_key)
                        ? $taxonomy_key
                        : (isset($taxonomy_config['taxonomy']) ? (string) $taxonomy_config['taxonomy'] : '');

                    if (empty($taxonomy) || !taxonomy_exists($taxonomy)) {
                        continue;
                    }

                    $group_label = isset($taxonomy_config['label']) ? (string) $taxonomy_config['label'] : $taxonomy;
                    $terms = get_terms(array(
                        'taxonomy' => $taxonomy,
                        'hide_empty' => true,
                    ));

                    if (is_wp_error($terms) || empty($terms)) {
                        continue;
                    }

                    $active_term_ids = isset($active_filters[$taxonomy]) && is_array($active_filters[$taxonomy])
                        ? array_map('intval', $active_filters[$taxonomy])
                        : array();
                    ?>
                    <fieldset class="space-y-4" data-filter-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                        <legend class="font-bold text-[20px]"><?php echo esc_html($group_label); ?></legend>

                        <div class="space-y-3">
                            <?php foreach ($terms as $term) : ?>
                                <?php $is_checked = in_array((int) $term->term_id, $active_term_ids, true); ?>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        class="h-5 w-5 accent-[#EC663C]"
                                        data-filter-term
                                        data-taxonomy="<?php echo esc_attr($taxonomy); ?>"
                                        value="<?php echo esc_attr((string) $term->term_id); ?>"
                                        <?php checked($is_checked); ?>
                                    />
                                    <span class="text-[16px] leading-none">
                                        <?php echo esc_html($term->name); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
            </div>

            <div class="px-8 py-6 border-t border-black/10 flex items-center gap-4">
                <button
                    type="button"
                    class="btn border border-black text-black"
                    data-archive-filter-reset
                >
                    <?php esc_html_e('Reset', 'advice2025'); ?>
                </button>
                <button
                    type="button"
                    class="btn bg-pink text-white"
                    data-archive-filter-apply
                >
                    <?php esc_html_e('Toepassen', 'advice2025'); ?>
                </button>
            </div>
        </div>
    </aside>
</div>
