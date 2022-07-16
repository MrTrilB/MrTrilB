<?php
/**
 * View: Photo Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/v2/photo/event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 5.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 * @var string $placeholder_url The url for the placeholder image if a featured image does not exist.
 *
 * @see tribe_get_event() For the format of the event object.
 */
$classes = get_post_class( array( 'tribe-common-g-col', 'tribe-events-pro-photo__event' ), $event->ID );

if ( ! empty( $event->featured ) ) {
	$classes[] = 'tribe-events-pro-photo__event--featured';
}
?>
<article <?php tribe_classes( $classes ); ?>>
	<div class="buddyx-tribe-events-calendar-event-wrapper buddyx-tribe-events-calendar-photo-event-wrapper">

		<?php $this->template( 'photo/event/featured-image', array( 'event' => $event ) ); ?>

		<div class="tribe-events-pro-photo__event-details-wrapper">
			<?php $this->template( 'photo/event/date-tag', array( 'event' => $event ) ); ?>
			<div class="tribe-events-pro-photo__event-details">
				<?php $this->template( 'photo/event/date-time', array( 'event' => $event ) ); ?>
				<?php $this->template( 'photo/event/title', array( 'event' => $event ) ); ?>
				<?php $this->template( 'photo/event/cost', array( 'event' => $event ) ); ?>
			</div>
		</div>
	</div>
</article>
