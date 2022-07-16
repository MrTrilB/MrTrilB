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


class BP_Reaction_Stats_Widget extends WP_Widget {

	/**
	 * Working as a poll activity, we get things done better.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'description'                 => __( 'A Reactions list widget', 'buddypress-reactions' ),
			'classname'                   => 'widget_bp_reactions_list_widget buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, _x( '(BuddyPress) Reactions List', 'widget name', 'buddypress-reactions' ), $widget_ops );

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
		$title 					= apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$reaction_statistic 	= apply_filters( 'widget_reaction_statistic', $instance['reaction_statistic'], $instance, $this->id_base );
		$reactions_shortcodes 	= apply_filters( 'widget_reactions_shortcodes', $instance['reactions_shortcodes'], $instance, $this->id_base );
		$max_reactions 			= apply_filters( 'widget_max_reactions', $instance['max_reactions'], $instance, $this->id_base );
		
		$start_date = '';
		$end_date = '';
		$where_search = " where bprs_id = " . $reactions_shortcodes;
		if ( $reaction_statistic == 'last_month') {
			$start_date = Date("Y-m-d", strtotime("first day of previous month") );
			$end_date = Date("Y-m-d", strtotime("last day of last month") );
			
			$where_search .= " AND ( reacted_date >= '". $start_date ." 00:00:00' AND reacted_date <= '". $end_date ." 00:00:00' ) ";
		} else if ( $reaction_statistic == 'last_week') {
			$start_date = date("Y-m-d", strtotime("last week monday"));
			$end_date = date("Y-m-d", strtotime("last week sunday"));
			$where_search .= " AND ( reacted_date >= '". $start_date ." 00:00:00' AND reacted_date <= '". $end_date ." 00:00:00' ) ";
		}		
		
		
		$table_name     = $wpdb->prefix . 'bp_reactions_reacted_emoji';
		$reacted_query  = 'SELECT count(*) as count, emoji_id, bprs_id FROM ' . $table_name . " {$where_search} group by emoji_id ORDER BY count DESC LIMIT 0, $max_reactions";
		$reacted_total  = $wpdb->get_results( $reacted_query );
		$total_reacted_count = count($reacted_total);
		
		/* total count is odd then we will display even reaction to manage row */
		if ($total_reacted_count % 2 != 0) {
			unset($reacted_total[$total_reacted_count - 1]);		  
		}
				
		if ( !empty( $reacted_total )) {
			echo $before_widget;
			echo $before_title . esc_html( $title ) . $after_title;
			
			?>
			<ul class="wp-reactions-lists">
				<?php foreach( $reacted_total as $emoji ): ?>
					<li>
						<img class="reaction-image" src="<?php echo esc_url( get_buddypress_reaction_emoji( $emoji->emoji_id, 'svg' ) ); ?>"  alt="">
						<span class="count"><?php echo esc_html(bp_reactions_emoji_count_format($emoji->count));?></span>
						<span class="wp-emoji-name"><?php echo esc_html(get_buddypress_reaction_emoji_name($emoji->emoji_id));?></span>
					</li>
				<?php endforeach; ?>
			</ul>
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
		global $wpdb;
		$defaults = array(
			'title'            		=> __( 'Reactions List', 'buddypress-reactions' ),
			'reaction_statistic'    => 'forever',
			'reactions_shortcodes'  => '',
			'max_reactions'     	=> 4,
		);
		$table_name     = $wpdb->prefix . 'bp_reactions_shortcodes';
		$query          = 'SELECT * FROM ' . $table_name . " ORDER BY ID DESC";
		$result         = $wpdb->get_results( $query );

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title            		= strip_tags( $instance['title'] );
		$reaction_statistic 	= strip_tags( $instance['reaction_statistic'] );
		$reactions_shortcodes 	= strip_tags( $instance['reactions_shortcodes'] );
		$max_reactions 	= strip_tags( $instance['max_reactions'] );
		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'buddypress-reactions' ); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" /></label></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'reaction_statistic' ) ); ?>"><?php esc_html_e( 'Statistic', 'buddypress-reactions' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'reaction_statistic' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'reaction_statistic' ) ); ?>">
				<option value="forever"  <?php selected( $reaction_statistic, 'forever' ); ?>><?php esc_html_e('Forever', 'buddypress-reactions');?></option>
				<option value="last_month"  <?php selected( $reaction_statistic, 'last_month' ); ?>><?php esc_html_e('Last Month', 'buddypress-reactions');?></option>
				<option value="last_week"  <?php selected( $reaction_statistic, 'last_week' ); ?>><?php esc_html_e('Last Week', 'buddypress-reactions');?></option>

			</select>
		</p>

		<?php  if ( $result ) :?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'reactions_shortcodes' ) ); ?>"><?php esc_html_e( 'Reaction Shotcode', 'buddypress-reactions' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'reactions_shortcodes' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'reactions_shortcodes' ) ); ?>">
				<?php foreach ( $result as $res ) : ?>
					<option value="<?php echo esc_attr($res->id);?>" <?php selected( $reactions_shortcodes, $res->id ); ?>><?php echo esc_html($res->name);?></option>
				<?php endforeach; ?>
				</select>
			</p>
		<?php endif; ?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'max_reactions' ) ); ?>"><?php esc_html_e( 'Max reactions show:', 'buddypress-reactions' ); ?> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'max_reactions' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max_reactions' ) ); ?>" type="number" value="<?php echo esc_attr( $max_reactions ); ?>" style="width: 30%"  min="2" step="2"/>
			</label>
		</p>
		<?php

	}
}


add_action(
	'widgets_init',
	function() {
		register_widget( 'BP_Reaction_Stats_Widget' );
	}
);