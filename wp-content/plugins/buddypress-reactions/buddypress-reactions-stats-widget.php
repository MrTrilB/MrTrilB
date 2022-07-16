<?php
/**
 * BuddyPress Reactions Stats Widget
 *
 * @package Buddypress_Reactions
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Reactions Stats Widget.
 *
 * @since 1.0.0
 */


class BP_Reaction_Last_Three_Month_Stats_Widget extends WP_Widget {

	/**
	 * Working as a poll activity, we get things done better.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'description'                 => __( 'A Reactions last 3 Months Stats widget', 'buddypress-reactions' ),
			'classname'                   => 'widget_bp_reactions_statistics_widget buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, _x( '(BuddyPress) Reactions Last 3 Months', 'widget name', 'buddypress-reactions' ), $widget_ops );
	}

	/**
	 * Extends our front-end output method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Array of arguments for the widget.
	 * @param array $instance Widget instance data.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		global $wpdb;
		
		wp_enqueue_script( 'chart-js' );
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		$table_name    = $wpdb->prefix . 'bp_reactions_reacted_emoji';
		$reacted_query = 'SELECT reacted_date, count(*) as count FROM ' . $table_name . ' where reacted_date > now() - INTERVAL 3 MONTH GROUP BY MONTH(reacted_date) ORDER BY reacted_date ASC';
		$reacted_total = $wpdb->get_results( $reacted_query );

		if ( ! empty( $reacted_total ) ) {
			echo $before_widget;
			echo $before_title . esc_html( $title ) . $after_title;
			$monthly_reacted = array();
			foreach ( $reacted_total as $reacted ) {
				$monthly_reacted[ date_i18n( 'F-Y', strtotime( $reacted->reacted_date ) ) ] = $reacted->count;
			}
			?>
			<canvas id="wp-reaction-monthly-stats-<?php echo rand(); ?>" class="wp-reaction-monthly-stats" data-chart-info="<?php echo htmlspecialchars( wp_json_encode( $monthly_reacted, JSON_UNESCAPED_UNICODE ), ENT_QUOTES, 'UTF-8' ); ?>" data-text="<?php echo esc_html( 'Reacted Count', 'buddypress-reactions' ); ?>"></canvas>
			<?php

			echo $after_widget;

		}
	}

	/**
	 * Extends our update method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New instance data.
	 * @param array $old_instance Original instance data.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		return $new_instance;
	}

	/**
	 * Extends our form method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current instance.
	 * @return mixed
	 */
	public function form( $instance ) {

		$defaults = array(
			'title' => __( 'Reactions Statistics', 'buddypress-reactions' ),
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = strip_tags( $instance['title'] );
		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'buddypress-reactions' ); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" /></label></p>
		<?php

	}
}


add_action(
	'widgets_init',
	function() {
		register_widget( 'BP_Reaction_Last_Three_Month_Stats_Widget' );
	}
);
