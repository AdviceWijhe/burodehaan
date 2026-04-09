<?php 
$variant = get_sub_field('variant') ?: 'standard';
get_template_part('blocks/switch/' . $variant);
