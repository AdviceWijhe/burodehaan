<?php
/**
 * Template for displaying search forms
 */
$title = get_the_title();
if(get_post_type() == 'project') {
    $title = 'projecten';
} elseif(get_post_type() == 'post') {
    $title = 'artikelen';
} elseif(get_post_type() == 'kennisbank') {
    $title = 'downloads';
} elseif(get_post_type() == 'page') {
    $title = 'pagina';
} else {
    $title = 'artikelen';
}
?>

<form role="search" method="get" class="search-form w-full max-w-[230px] lg:max-w-[420px]" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="relative flex h-[59px] w-full items-stretch border border-[rgba(22,22,22,0.4)] bg-white">
        <label class="sr-only" for="search-field"><?php echo _x('Zoeken naar:', 'label', 'advice2025'); ?></label>
        <input
            type="search"
            id="search-field"
            class="search-field h-full min-w-0 flex-1 border-0 bg-transparent px-5 py-0 text-[16px] font-light leading-[1.5] text-[#161616] placeholder:text-[#161616] placeholder:opacity-70 focus:outline-none"
            placeholder="Zoeken naar <?= $title; ?>..."
            value="<?php echo get_search_query(); ?>"
            name="s"
        />
        <button
            type="submit"
            class="flex h-full w-[57px] shrink-0 items-center justify-center border-l border-[rgba(22,22,22,0.4)] text-[#161616] transition-colors hover:bg-[#f7f5f0] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#161616] focus-visible:ring-offset-0"
        >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="sr-only"><?php echo _x('Zoeken', 'submit button', 'advice2025'); ?></span>
        </button>
    </div>
</form>
