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

<form role="search" method="get" class="search-form w-full max-w-[14.375rem] lg:max-w-[26.25rem]" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="relative flex h-[3.6875rem] w-full items-stretch border border-[rgba(22,22,22,0.4)] bg-white">
        <label class="sr-only" for="search-field"><?php echo _x('Zoeken naar:', 'label', 'advice2025'); ?></label>
        <input
            type="search"
            id="search-field"
            class="search-field h-full min-w-0 flex-1 border-0 bg-transparent px-5 py-0 text-[1rem] font-light leading-[1.5] text-[#161616] placeholder:text-[#161616] placeholder:opacity-70 focus:outline-none"
            placeholder="Zoeken naar <?= $title; ?>..."
            value="<?php echo get_search_query(); ?>"
            name="s"
        />
        <button
            type="submit"
            class="flex h-full w-[3.5625rem] shrink-0 items-center justify-center bg-[#F7F5F0] text-[#161616] transition-colors hover:bg-[#f7f5f0] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#161616] focus-visible:ring-offset-0"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true">
                <path d="M17.0774 8.88026C17.0774 6.70624 16.2138 4.62126 14.6765 3.08399C13.1393 1.54672 11.0543 0.683097 8.88026 0.683097C6.70624 0.683097 4.62126 1.54672 3.08399 3.08399C1.54672 4.62126 0.683097 6.70624 0.683097 8.88026C0.683097 11.0543 1.54672 13.1393 3.08399 14.6765C4.62126 16.2138 6.70624 17.0774 8.88026 17.0774C11.0543 17.0774 13.1393 16.2138 14.6765 14.6765C16.2138 13.1393 17.0774 11.0543 17.0774 8.88026ZM14.9129 15.3953C13.3289 16.864 11.2113 17.7605 8.88026 17.7605C3.97477 17.7605 0 13.7858 0 8.88026C0 3.97477 3.97477 0 8.88026 0C13.7858 0 17.7605 3.97477 17.7605 8.88026C17.7605 11.2113 16.864 13.3289 15.3953 14.9129L22 21.5176L21.5176 22L14.9129 15.3953Z" fill="#161616"/>
            </svg>
            <span class="sr-only"><?php echo _x('Zoeken', 'submit button', 'advice2025'); ?></span>
        </button>
    </div>
</form>
