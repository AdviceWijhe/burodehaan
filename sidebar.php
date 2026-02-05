<?php
/**
 * The sidebar containing the main widget area
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area w-full lg:w-1/3 lg:pl-8 mt-8 lg:mt-0">
    <div class="space-y-8">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside>
