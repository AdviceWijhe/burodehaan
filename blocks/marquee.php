<?php
$zinnen = get_sub_field('zinnen');

if (!is_array($zinnen)) {
    $zinnen = array();
}

$zinnen = array_values(array_filter($zinnen, static function ($item) {
    return is_array($item) && !empty($item['zin']);
}));
?>

<?php if (!empty($zinnen)) : ?>
<div class="marquee py-[2rem]" data-marquee>
    <div class="marquee__viewport">
        <div class="marquee__track" data-marquee-track>
            <?php for ($group_index = 0; $group_index < 2; $group_index++) : ?>
                <div class="marquee__group" <?php echo $group_index === 1 ? 'aria-hidden="true"' : ''; ?>>
                    <?php foreach ($zinnen as $item) : ?>
                        <div class="marquee__item">
                            <span class="marquee__text display-medium"><?php echo esc_html($item['zin']); ?></span>
                            <span class="marquee__separator" aria-hidden="true"></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
<?php endif; ?>