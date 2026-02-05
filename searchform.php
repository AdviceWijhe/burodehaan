<?php
/**
 * Template for displaying search forms
 */
?>

<form role="search" method="get" class="search-form relative" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="relative">
        <label class="sr-only" for="search-field"><?php echo _x('Zoeken naar:', 'label', 'advice2025'); ?></label>
        <input 
            type="search" 
            id="search-field"
            class="search-field w-full h-16 px-4 pr-12 bg-white border border-[#d6dde1] rounded-[4px] text-[#092354] text-[17px] font-medium placeholder:text-[#092354] placeholder:opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
            placeholder="Zoeken op product, titel of probleem" 
            value="<?php echo get_search_query(); ?>" 
            name="s"
        />
        <button 
            type="submit" 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-[#092354] hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="sr-only"><?php echo _x('Zoeken', 'submit button', 'advice2025'); ?></span>
        </button>
    </div>
</form>
