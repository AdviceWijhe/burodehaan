<?php
/**
 * Navigation Layout 1
 * Logo links + Menu rechts met dropdown + Search + Secondary Button + Contact Button
 */

$container_width = get_theme_mod('header_container_width', 'full-width');
$container_class = $container_width === 'contained' ? 'container mx-auto' : 'container';

$header_background_color = 'bg-secondary';

if(is_archive() || is_page('expertises') || is_home()) {
    $header_background_color = 'bg-white';
}
if(is_single()) {
    $header_background_color = 'bg-black';
}
if(is_tax()) {
    $header_background_color = 'bg-black';
}
if(is_page()) {
    $variant = null;
    if (have_rows('hero_banner')) :
        while (have_rows('hero_banner')) :
            the_row();

            $variant = get_row_layout();
        endwhile;
        if($variant == 'big_image') {
            $header_background_color = 'bg-black';
        }else {
            $header_background_color = 'bg-secondary';
        }
        
    endif;    
    // $header_background_color = 'bg-white';
}
?>

<?php $default_logo_theme = ($header_background_color === 'bg-black') ? 'light' : 'dark'; ?>
<div class="navigation-wrapper <?php echo esc_attr($header_background_color); ?>" data-default-logo-theme="<?php echo esc_attr($default_logo_theme); ?>">
    <div class="<?php echo esc_attr($container_class); ?> pt-[1rem] pb-[1rem] xl:pt-[1.75rem] <?php if(is_front_page()) { echo 'xl:pb-[1.75rem]'; } ?>">
        <div class="flex items-center justify-between gap-10 xl:pl-0 xl:pr-0">
            <!-- Logo -->
            <div class="site-branding flex-shrink-0">
                <?php 
                $logos = get_field('logos', 'option');

                $logo_light = $logos['logo_wit'] ?? null;
                $logo_dark = $logos['logo_donker'] ?? null;
                $logo = ($header_background_color == 'bg-black') ? $logo_light : $logo_dark;

                if ($logo) : ?>
                    <div class="site-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                            <?php if (!empty($logo_dark['url']) && !empty($logo_light['url'])) : ?>
                                <?php echo '<img src="' . esc_url($logo_dark['url']) . '" alt="' . get_bloginfo('name') . '" class="site-logo-dark h-[43px] w-auto object-contain">'; ?>
                                <?php echo '<img src="' . esc_url($logo_light['url']) . '" alt="' . get_bloginfo('name') . '" class="site-logo-light h-[43px] w-auto object-contain">'; ?>
                            <?php else : ?>
                                <?php echo '<img src="' . esc_url($logo['url']) . '" alt="' . get_bloginfo('name') . '" class="h-[43px] w-auto object-contain">'; ?>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php else : ?>
                    <h1 class="text-2xl font-bold">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-[#131611]">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                <?php endif; ?>
            </div>

            <!-- Navigation + Buttons -->
            <div class="flex items-center gap-10 flex-1 justify-end">
                <!-- Main Navigation -->
                <nav class="main-navigation hidden xl:flex items-center gap-10">
                    <?php
                    $menu_text_color = 'text-black';
                    

                    if($header_background_color == 'bg-black') {
                        $menu_text_color = 'text-white';
                    }

                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'flex items-center ' . $menu_text_color,
                        'fallback_cb'    => false,
                        'walker'         => new Advice2025_Simple_Nav_Walker(),
                        'depth'          => 3,
                    ));
                    ?>
                </nav>

                <!-- Buttons Group -->
                <div class="hidden xl:flex items-center gap-4">
                    <!-- Search Button -->
                    <!-- <div class="relative">
                        <button id="header-search-toggle" class="w-[54px] h-[54px] flex items-center justify-center border border-[#131611] rounded hover:bg-gray-100 transition-colors" aria-label="Zoeken" aria-expanded="false">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button> -->
                        
                        <!-- Search Bar (expandable) -->
                        <!-- <div id="header-search-bar" class="hidden absolute right-full top-0 mr-2 bg-white border border-[#131611] rounded shadow-lg z-50 transition-all duration-300" style="width: 0; opacity: 0;">
                            <div class="w-[300px] p-2">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div> -->

                    <!-- Secondary Button -->
                    <!-- <?php 
                    $secondary_button = get_field('header_secondary_button', 'option');
                    $secondary_text = $secondary_button && isset($secondary_button['title']) ? $secondary_button['title'] : 'Secondary button';
                    $secondary_url = $secondary_button && isset($secondary_button['url']) ? $secondary_button['url'] : '#';
                    ?>
                    <a href="<?php echo esc_url($secondary_url); ?>" class="px-5 py-4 bg-[rgba(19,22,17,0.4)] backdrop-blur-[7px] text-white rounded font-medium hover:opacity-80 transition-opacity whitespace-nowrap">
                        <?php echo esc_html($secondary_text); ?>
                    </a> -->

                    <!-- Contact Button -->
                    <?php 
                    $contact_button = get_field('header_contact_button', 'option');
                    $contact_text = $contact_button && isset($contact_button['title']) ? $contact_button['title'] : 'Plan een afspraak';
                    $contact_url = $contact_button && isset($contact_button['url']) ? $contact_button['url'] : '/plan-een-afspraak';

                    $contact_button = array(
                        array(
                            'link' => array(
                                'url' => $contact_url,
                                'title' => $contact_text,
                            ),
                            'knop_kleur' => 'primary',
                            'knop_icon' => 'none',
                        ),
                    );
                    ?>
                    <?php get_template_part('template-parts/core/buttons', null, array('buttons' => $contact_button, 'no_margin' => true, 'align_items' => 'stretch', 'full_width' => false)); ?>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="xl:hidden">
                <button id="mobile-menu-button" class="relative inline-flex items-center gap-2 py-3 pl-[1.25rem] pr-[0.8125rem] bg-primary border border-primary text-white hover:opacity-90 transition-opacity" aria-expanded="false">
                    <span class="sr-only">Menu openen</span>
                    <div class="hamburger-lines">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" viewBox="0 0 14 10" fill="none">
                            <rect width="14" height="1" fill="white"/>
                            <rect y="4.5" width="14" height="1" fill="white"/>
                            <rect y="9" width="14" height="1" fill="white"/>
                        </svg>
                    </div>
                    <span class="mobile-menu-label font-medium">Menu</span>
                </button>
            </div>
        </div>
    </div>
</div>
