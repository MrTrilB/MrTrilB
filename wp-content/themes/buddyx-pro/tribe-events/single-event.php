<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = get_the_ID();
?>

<div id="tribe-events-content" class="tribe-events-single">

	<p class="tribe-events-back">
		<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( '&laquo; ' . esc_html_x( 'All %s', '%s Events plural label', 'buddyxpro' ), $events_label_plural ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	</p>

	<!-- Notices -->
	<?php tribe_the_notices(); ?>

	<div class="buddyx-event-heading">
		<div class="tribe-event-schedule-short">
			<div class="buddyx-schedule-short-date">
				<span class="buddyx-schedule-short-m"><?php echo tribe_get_start_date( null, true, 'M' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="buddyx-schedule-short-d"><?php echo tribe_get_start_date( null, true, 'j' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
		</div>
		<div class="tribe-event-schedule-long">
			<div class="buddyx-tribe-events-single-heading">
				<?php the_title( '<h1 class="tribe-events-single-event-title">', '</h1>' ); ?>
				<?php echo tribe_events_event_schedule_details( $event_id, '<h2>', '</h2>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="tribe-events-schedule tribe-clearfix">
				<?php if ( tribe_get_cost() ) : ?>
					<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes(); ?>>
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'buddyxpro' ), $events_label_singular ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ); ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ); ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>
	<!-- #tribe-events-header -->

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="buddyx-single-body">
				<!-- Event featured image, but exclude link -->
				<?php echo tribe_event_featured_image( $event_id, 'full', false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<div class="buddyx-single-content">
					<!-- Event content -->
					<?php do_action( 'tribe_events_single_event_before_the_content' ); ?>
					<div class="tribe-events-single-event-description tribe-events-content">
						<?php the_content(); ?>
					</div>
					<!-- .tribe-events-single-event-description -->
					<?php do_action( 'tribe_events_single_event_after_the_content' ); ?>
				</div>

				<div class="buddyx-single-pri-meta">
					<!-- Event meta -->
					<?php do_action( 'tribe_events_single_event_before_the_meta' ); ?>
					<div class="buddyx-single-meta-section-wrapper">
						<?php tribe_get_template_part( 'modules/meta' ); ?>
					</div>
					<?php do_action( 'tribe_events_single_event_after_the_meta' ); ?>
				</div> <!-- #post-x -->
			</div>
		</div>
		<?php

		do_action( 'buddyx_pro_post_comment_before' );

		if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) {
			comments_template();}
		?>
	<?php endwhile; ?>

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'buddyxpro' ), $events_label_singular ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ); ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ); ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
