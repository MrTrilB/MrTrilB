<?php
/**
 * Custom style
 */
function buddyx_get_custom_style() {

	if ( class_exists( 'GroovyMenuPreset' ) ) {
		$default            = GroovyMenuPreset::getDefaultPreset();
		$gm_preset_settings = json_decode( get_post_meta( $default, 'gm_preset_settings', true ), true );
		$mobile_width       = $gm_preset_settings['mobile_width'] . 'px';

		$style          = $gm_preset_settings['header']['style'];
		$menu_top_width = '';
		if ( $style == 4 ) {
			$menu_top_width = $gm_preset_settings['icon_menu_top_width'];
		} elseif ( $style == 3 ) {
			$menu_top_width = $gm_preset_settings['sidebar_menu_top_width'];
		} elseif ( $style == 5 ) {
			$menu_top_width = $gm_preset_settings['sidebar_expanding_menu_initial_width'];
		}

		return <<<CSS

		@media (min-width: $mobile_width) {
			body.gm-navbar-style-1 .site,
			body.gm-navbar-style-2 .site {
				padding-top: 0 !important;
			}
			body.gm-navbar-style-3 .site-header-wrapper>.container {
				max-width: 100%;
			}
			
			body.sticky-header.gm-navbar-style-3.gm-navbar-align-left .site-header-wrapper.has-sticky-header .site-header {
				margin-left: {$menu_top_width}px;
			}
			body.sticky-header.gm-navbar-style-3.gm-navbar-align-right .site-header-wrapper.has-sticky-header .site-header {
				margin-right: {$menu_top_width}px;
			}
			body.sticky-header.gm-navbar-style-4.gm-navbar-align-left .site-header-wrapper.has-sticky-header {
				padding-left: {$menu_top_width}px;
			}
			body.sticky-header.gm-navbar-style-4.gm-navbar-align-right .site-header-wrapper.has-sticky-header {
				padding-right: {$menu_top_width}px;
			}
			body.sticky-header.gm-navbar-style-5.gm-navbar-align-left .site-header-wrapper.has-sticky-header {
				padding-left: {$menu_top_width}px;
			}
			body.sticky-header.gm-navbar-style-5.gm-navbar-align-right .site-header-wrapper.has-sticky-header {
				padding-right: {$menu_top_width}px;
			}
			
			body.gm-navbar-style-3.gm-navbar-align-left .learndash-wrapper .ld-focus .ld-focus-sidebar {
				margin-left: {$menu_top_width}px;
			}
			body.gm-navbar-style-3.gm-navbar-align-right .learndash-wrapper .ld-focus .ld-focus-sidebar {
				margin-right: {$menu_top_width}px;
			}
			body.gm-navbar-style-4.gm-navbar-align-left .learndash-wrapper .ld-focus .ld-focus-sidebar {
				padding-left: {$menu_top_width}px;
			}
			body.gm-navbar-style-4.gm-navbar-align-right .learndash-wrapper .ld-focus .ld-focus-sidebar {
				padding-right: {$menu_top_width}px;
			}
			body.gm-navbar-style-5.gm-navbar-align-left .learndash-wrapper .ld-focus .ld-focus-sidebar {
				padding-left: {$menu_top_width}px;
			}
			body.gm-navbar-style-5.gm-navbar-align-right .learndash-wrapper .ld-focus .ld-focus-sidebar {
				padding-right: {$menu_top_width}px;
			}
		}

		@media (max-width: $mobile_width) {
			body.gm-navbar-style-1:not(.gm-hide--on-mobile) .site,
			body.gm-navbar-style-2:not(.gm-hide--on-mobile) .site {
				padding-top: 0 !important;
			}
			body.groovy-menu-enable:not(.gm-hide--on-mobile) .site-header-wrapper,
			body.groovy-menu-enable:not(.gm-hide--on-mobile) .top-bar {
				display: none;
			}
			body.gm-navbar-style-3:not(.gm-hide--on-mobile) .site,
			body.gm-navbar-style-4:not(.gm-hide--on-mobile) .site,
			body.gm-navbar-style-5:not(.gm-hide--on-mobile) .site {
				padding-top: 0 !important;
			}
		}

CSS;

	}

}
