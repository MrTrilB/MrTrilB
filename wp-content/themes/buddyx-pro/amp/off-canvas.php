<?php
/**
 * The template part for displaying the off-canvas area of AMP.
 *
 * @package buddyxpro
 */
namespace BuddyxPro\BuddyxPro;

if (!buddyxpro()->is_primary_nav_menu_active()) {
    return;
}

?>
<amp-sidebar id="buddyx-amp-canvas" layout="nodisplay" side="left">
    <div role="button" aria-label="close sidebar" on="tap:buddyx-amp-canvas.toggle" tabindex="0" class="close-sidebar">✕</div>
    <?php buddyxpro()->display_primary_nav_menu(['menu_id' => 'primary-menu']); ?>
</amp-sidebar>