<?php
/**
 * Custom functions for dokan
 * @link    https://wbcomdesigns.com/
 * @package buddyxpro
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Store Container
if ( ! function_exists( 'buddyx_store_container_open' ) ) {

    function buddyx_store_container_open() { ?>
        <div class="container">
    <?php }

}

add_action( 'buddyx_store_container_open', 'buddyx_store_container_open' );

if ( ! function_exists( 'buddyx_store_container_close' ) ) {

    function buddyx_store_container_close() { ?>
        </div>
    <?php }

}

add_action( 'buddyx_store_container_close', 'buddyx_store_container_close' );

// render_store_header_on_top
if ( ! function_exists( 'render_store_header_on_top' ) ) {

    function render_store_header_on_top() { ?>
        <div class="dokan-single-store dokan-single-store-top">
            <div class="store-page-wrap" role="main">
                <?php dokan_get_template_part( 'store-header' ); ?>
            </div>
        </div>
        <?php
    }

}