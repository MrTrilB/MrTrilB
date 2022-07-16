<?php

/**
 * Get Members Directory Class
 */
function youzify_blogs_directory_class() {

    // New Array
    $directory_class = array( 'youzify-directory youzify-page youzify-members-directory-page' );

    // Add Scheme Class
    $directory_class[] = youzify_option( 'youzify_profile_scheme', 'youzify-blue-scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    return youzify_generate_class( $directory_class );
}
