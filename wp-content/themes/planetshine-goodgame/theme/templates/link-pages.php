<?php

$args = array (
    'before'           => '<div class="post-pages pages clearfix">',
    'after'            => '</div>',
    'link_before'      => '<span></span>',
    'link_after'       => '',
    'next_or_number'   => 'next',
    'nextpagelink'     => esc_html__('Next page', 'planetshine-goodgame'),
    'previouspagelink' => esc_html__('Previous page', 'planetshine-goodgame'),
    'pagelink'         => '%',
    'echo'             => 1
);
wp_link_pages($args);
