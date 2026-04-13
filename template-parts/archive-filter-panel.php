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
        class="filter_item_label h-[59px] px-[24px] flex items-center group justify-center font-medium! gap-[8px] border border-black text-black body-small hover:cursor-pointer hover:bg-black hover:text-white"
        data-archive-filter-open
        aria-haspopup="dialog"
        aria-controls="<?php echo esc_attr($panel_id); ?>"
        aria-expanded="false"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="19" viewBox="0 0 26 19" fill="none" aria-hidden="true">
            <path class="group-hover:fill-white" d="M0.791667 18.2083H7.125V11.875H0.791667V18.2083ZM7.91667 15.4375V19H0V11.0833H7.91667V14.6458H25.3333V15.4375H7.91667ZM0.395833 4.35417H0V3.5625H17.4167V0H25.3333V7.91667H17.4167V4.35417H0.395833ZM18.2083 7.125H24.5417V0.791667H18.2083V7.125Z" fill="#161616"/>
        </svg>
        <?php echo esc_html($label); ?>
    </button>

    <div
        class="fixed inset-0 bg-black/30 z-90 hidden"
        data-archive-filter-overlay
    ></div>

    <aside
        id="<?php echo esc_attr($panel_id); ?>"
        class="fixed top-0 right-0 h-full w-full lg:top-10 lg:right-10 lg:h-[calc(100%-80px)] lg:w-[600px] bg-secondary z-100 translate-x-full transition-transform duration-300 ease-out shadow-2xl"
        data-archive-filter-drawer
        role="dialog"
        aria-modal="true"
        aria-labelledby="<?php echo esc_attr($panel_id); ?>-title"
    >
        <div class="h-full flex flex-col">
            <div class="flex items-start justify-between px-6 pt-6 lg:px-[60px] lg:pt-[60px]">
                <h4 id="<?php echo esc_attr($panel_id); ?>-title" class="mb-0! text-black">
                    <?php esc_html_e('Selecteer je ', 'advice2025'); ?><span class="font-semibold"><?php esc_html_e('filters', 'advice2025'); ?></span>
                </h4>
                <button
                    type="button"
                    class="w-[66px] h-[66px] flex items-center justify-center text-secondary text-[34px] leading-none absolute top-0 right-0 hover:cursor-pointer"
                    data-archive-filter-close
                    aria-label="<?php esc_attr_e('Sluit filter', 'advice2025'); ?>"
                >
                <svg xmlns="http://www.w3.org/2000/svg" width="66" height="66" viewBox="0 0 66 66" fill="none">
  <rect width="66" height="66" fill="#161616"/>
  <rect x="23" y="23.0001" width="2.22222" height="2.22222" fill="#F7F5F0"/>
  <rect x="28.9258" y="34.8521" width="2.22222" height="2.22222" fill="#F7F5F0"/>
  <rect x="25.9609" y="37.8149" width="2.22222" height="2.22222" fill="#F7F5F0"/>
  <rect x="23" y="40.7778" width="2.22222" height="2.22222" fill="#F7F5F0"/>
  <rect x="25.9609" y="25.9632" width="2.22222" height="2.22222" fill="#F7F5F0"/>
  <rect x="31.8906" y="31.8892" width="2.22222" height="2.22222" fill="#F7F5F0"/>
  <rect x="28.9258" y="28.9258" width="2.22222" height="2.22222" fill="#F7F5F0"/>
  <rect x="43.0029" y="43" width="2.22222" height="2.22222" transform="rotate(-180 43.0029 43)" fill="#F7F5F0"/>
  <rect x="37.0771" y="31.1479" width="2.22222" height="2.22222" transform="rotate(-180 37.0771 31.1479)" fill="#F7F5F0"/>
  <rect x="40.042" y="28.1852" width="2.22222" height="2.22222" transform="rotate(-180 40.042 28.1852)" fill="#F7F5F0"/>
  <rect x="43.0029" y="25.2222" width="2.22222" height="2.22222" transform="rotate(-180 43.0029 25.2222)" fill="#F7F5F0"/>
  <rect x="40.042" y="40.0369" width="2.22222" height="2.22222" transform="rotate(-180 40.042 40.0369)" fill="#F7F5F0"/>
  <rect x="34.1123" y="34.1108" width="2.22222" height="2.22222" transform="rotate(-180 34.1123 34.1108)" fill="#F7F5F0"/>
  <rect x="37.0771" y="37.0742" width="2.22222" height="2.22222" transform="rotate(-180 37.0771 37.0742)" fill="#F7F5F0"/>
</svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 pt-8 pb-6 lg:px-[60px] lg:pt-[44px] lg:pb-[24px] space-y-[44px]">
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
                    $group_class = $taxonomy === 'expertise' ? 'grid grid-cols-1 md:grid-cols-2 gap-x-[42px] gap-y-[10px]' : 'space-y-[10px]';
                    ?>
                    <fieldset class="space-y-[14px]" data-filter-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                        <legend class="font-normal text-[18px] leading-[1.2] text-black"><?php echo esc_html($group_label); ?></legend>

                        <div class="<?php echo esc_attr($group_class); ?>">
                            <?php foreach ($terms as $term) : ?>
                                <?php $is_checked = in_array((int) $term->term_id, $active_term_ids, true); ?>
                                <label class="flex items-center gap-[12px] cursor-pointer">
                                    <input
                                        type="checkbox"
                                        class="peer sr-only"
                                        data-filter-term
                                        data-taxonomy="<?php echo esc_attr($taxonomy); ?>"
                                        value="<?php echo esc_attr((string) $term->term_id); ?>"
                                        <?php checked($is_checked); ?>
                                    />
                                    <span class="w-[23px] h-[23px] border border-black/50 peer-checked:border-black flex items-center justify-center transition-colors">
                                        <span class="w-[11px] h-[11px] bg-black opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                                    </span>
                                    <span class="text-[16px] leading-normal font-light text-black opacity-70 peer-checked:opacity-100 transition-opacity">
                                        <?php echo esc_html($term->name); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
            </div>

            <div class="px-6 pb-6 pt-4 lg:px-[60px] lg:pb-[40px] lg:pt-[20px] flex flex-wrap items-center gap-[16px]">
                <button
                    type="button"
                    class="bg-primary text-secondary text-[16px] leading-normal font-normal py-[24px] px-[34px]"
                    data-archive-filter-apply
                >
                    <?php esc_html_e('Filters toepassen', 'advice2025'); ?>
                </button>
                <button
                    type="button"
                    class="bg-transparent border border-black text-black text-[16px] leading-normal font-normal py-[24px] px-[34px]"
                    data-archive-filter-reset
                >
                    <?php esc_html_e('Wis alle filters', 'advice2025'); ?>
                </button>
            </div>
        </div>
    </aside>
</div>
