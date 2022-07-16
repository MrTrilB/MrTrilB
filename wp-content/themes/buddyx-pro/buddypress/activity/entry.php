<?php
/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @since 3.0.0
 * @version 3.0.0
 */

bp_nouveau_activity_hook( 'before', 'entry' ); ?>

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>" data-bp-activity-id="<?php bp_activity_id(); ?>" data-bp-timestamp="<?php bp_nouveau_activity_timestamp(); ?>" data-bp-activity="<?php if ( function_exists( 'bp_nouveau_edit_activity_data' ) ) { bp_nouveau_edit_activity_data(); } ?>">

	<?php
	if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
		bb_nouveau_activity_entry_bubble_buttons();
	} else {
		bp_nouveau_activity_entry_dropdown_toggle_buttons();
	}
	?>

	<div class="activity-card-head">
		<h6 class="card-head-content-type">
			<?php echo buddyx_bp_get_activity_css_first_class(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</h6>
    </div>    

    <div class="activity-item-head">

        <div class="activity-avatar item-avatar">

            <a href="<?php bp_activity_user_link(); ?>">

                <?php bp_activity_avatar( array( 'type' => 'full' ) ); ?>

            </a>

        </div>

        <div class="activity-header">

            <?php bp_activity_action(); ?>

			<?php if ( function_exists( 'BuddyPress' ) && isset( buddypress()->buddyboss ) ) { ?>
			<p class="activity-date">
				<a href="<?php echo esc_url( bp_activity_get_permalink( bp_get_activity_id() ) ); ?>"><?php echo bp_core_time_since( bp_get_activity_date_recorded() ); ?></a>
				<?php
				if ( function_exists( 'bp_nouveau_activity_is_edited' ) ) {
						bp_nouveau_activity_is_edited();
				}
				?>
			</p>
			<?php } ?>

			<?php
			if ( function_exists( 'bp_nouveau_activity_privacy' ) ) {
				bp_nouveau_activity_privacy();
			}
			?>

        </div>

	</div>

	<div class="activity-content">

		<?php if ( bp_nouveau_activity_has_content() ) : ?>

			<div class="activity-inner">

				<?php
				if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) || ! function_exists( 'bp_activity_type_part' ) ) {
					echo bp_nouveau_activity_content();
				} else {
					bp_get_template_part( 'activity/type-parts/content',  bp_activity_type_part() );
				}
				?>

			</div>

		<?php endif; ?>

		<?php
		if ( function_exists( 'bp_nouveau_activity_state' ) ) {
			bp_nouveau_activity_state();
		}
		?>
		<div class="bp-activity-post-footer bp-activity-content-actions">
			<?php do_action( 'bp_activity_before_post_footer_content' ); ?>

			<div class="content-action">
				<div class="meta-line">
					<p class="meta-line-text">						
						<a href="javascript:void(0)">
							<span class="comment-count">
								<?php echo bp_activity_get_comment_count() ?>
							</span>
							<span>
								<?php esc_html_e( 'Comment', 'buddyxpro' ); ?>
							</span>
						</a>
					</p>
				</div>
				<?php if ( class_exists( 'Buddypress_Share_Public' ) ) { ?>
				<div class="meta-line">
					<p class="meta-line-text" id="bp-activity-share-<?php echo bp_get_activity_id(); ?>">
						<span class="share-count">
							<?php echo bp_activity_get_share_count( bp_get_activity_id(), true ) ?>
						</span>
						<span>
							<?php esc_html_e( 'Share', 'buddyxpro' ); ?>
						</span>						
					</p>
				</div>
				<?php } ?>
			</div>

			<?php do_action( 'bp_activity_after_post_footer_content' ); ?>
		</div>

		<?php bp_nouveau_activity_entry_buttons(); ?>

	</div>

	<?php bp_nouveau_activity_hook( 'before', 'entry_comments' ); ?>

	<?php if ( bp_activity_get_comment_count() || ( is_user_logged_in() && ( bp_activity_can_comment() || bp_is_single_activity() ) ) ) : ?>

		<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php bp_nouveau_activity_comment_form(); ?>

		</div>

	<?php endif; ?>

	<?php bp_nouveau_activity_hook( 'after', 'entry_comments' ); ?>

</li>

<?php
bp_nouveau_activity_hook( 'after', 'entry' );
