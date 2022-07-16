<?php

function buddyx_defaults( $key = '' ) {
	$defaults = array();

	// site layout
    $defaults[ 'site-layout' ] = 'wide';
    
    // site loader
    $defaults[ 'site-loader' ] = '2';

    // site loader text
    $defaults[ 'site-loader-text' ] = 'Loading';

    // site topbar
    $defaults[ 'site-topbar-enable' ] = '1';

    // site topbar left
    $defaults[ 'site-topbar-left-area' ] = '<i class="fas fa-phone-alt"></i> 011 322 44 56 | <i class="fas fa-envelope"></i> <a href="mailto:mail@example.com">mail@example.com</a>';

    // site header
    $defaults[ 'site-sticky-header' ] = '1';  
    $defaults[ 'site-header-layout' ] = 'default';  
    $defaults[ 'site-header-menu-effect' ] = 'default';  

    // site search
    $defaults[ 'site-search' ] = '1';

    // site cart
    $defaults[ 'site-cart' ] = '1';

    // site breadcrumbs
    $defaults[ 'site-breadcrumbs' ] = 'on';

    // site sidebar
    $defaults[ 'sidebar-option' ] = 'right';
    $defaults[ 'buddypress-sidebar-option' ] = 'both';
    $defaults[ 'buddypress-members-sidebar-option' ] = 'right';
    $defaults[ 'buddypress-groups-sidebar-option' ] = 'right';
    $defaults[ 'bbpress-sidebar-option' ] = 'right';
    $defaults[ 'woocommerce-sidebar-option' ] = 'right';
    $defaults[ 'ld-sidebar-option' ] = 'right';
    $defaults[ 'lp-sidebar-option' ] = 'right';

    // sticky sidebar
    $defaults[ 'sticky-sidebar' ] = '1';

    // blog layout option
    $defaults[ 'blog-layout-option' ] = 'default-layout';

    // blog image position
    $defaults[ 'blog-image-position' ] = 'thumb-left';

    // blog grid columns
    $defaults[ 'blog-grid-columns' ] = 'one-column';

    // single post blog layout option
    $defaults[ 'single-post-sidebar-option' ] = 'none';

    // blog masonry view
    $defaults[ 'blog-masonry-view' ] = 'without-masonry';

    // post per view
    $defaults[ 'post-per-row' ] = 'buddyx-masonry-2';

    // single post content width
    $defaults[ 'single-post-content-width' ] = 'small';

    // single post title layout
    $defaults[ 'single-post-title-layout' ] = 'buddyx-section-title-above';

    // single post social box
    $defaults[ 'single-post-social-box' ] = 'on';

    // single post social link
    $defaults[ 'single-post-social-link' ] = array ('facebook', 'twitter', 'pinterest', 'linkedin', 'whatsapp' );

    // single post social box
    $defaults[ 'single-post-progressbar' ] = 'on';

    // buddypress members directory view
    $defaults[ 'buddypress-members-directory-view' ] = 'card';

    // buddypress groups directory view
    $defaults[ 'buddypress-groups-directory-view' ] = 'card';

    // learndash
    $defaults[ 'ld-category-filter' ] = 'on';
    $defaults[ 'ld-instructors-filter' ] = 'off';

    // woocommerce product style
    $defaults[ 'woo-product-style' ] = 'default';

    // sign-in popup
    $defaults[ 'buddyx-signin-popup' ] = '';

	if ( !empty( $key ) && array_key_exists( $key, $defaults ) ) {
		return $defaults[ $key ];
	}

	return '';
}
