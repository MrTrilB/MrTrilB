<?php
/**
 * View: Default Template for Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/default-template.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 5.0.0
 */

use Tribe\Events\Views\V2\Template_Bootstrap;

get_header();

do_action( 'buddyx_sub_header' );

?>

<div class="buddyx-events-default-template">
    
    <?php
        do_action( 'buddyx_before_content' );

        echo tribe( Template_Bootstrap::class )->get_view_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        do_action( 'buddyx_after_content' );
    ?>

</div>

<?php

get_footer();
