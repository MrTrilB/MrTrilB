<?php
class GoodGameTwitchStream extends WP_Widget {

    var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;

	function __construct() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'goodgame_twitch_stream';
		$this->widget_description = esc_html__( 'Twitch.tv Live Stream and streamer\'s details', 'planetshine-goodgame' );
		$this->widget_idbase = 'goodgame_twitch_stream';
		$this->widget_name = esc_html__( 'GoodGame Twitch Live Stream', 'planetshine-goodgame' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		parent::__construct('goodgame_twitch_stream', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'clear_widget_cache' ) );
	}

	function widget($args, $instance)
    {
		$cache = wp_cache_get('goodgame_twitch_stream', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Twitch', 'planetshine-goodgame') : $instance['title'], $instance, $this->id_base);
        $username = isset( $instance['username'] ) ? $instance['username'] : '';

		$unique_id = uniqid();
		$stream = GoodGameInstance()->get_twitch_data('streams', $username);
		$channel = GoodGameInstance()->get_twitch_data('channels', $username);


		echo $before_widget;  ?>

			<?php echo $before_title .  esc_html($title) . $after_title; ?>

			<div class="post-block" data-username="<?php echo esc_attr($username); ?>"></div>

		<?php echo $after_widget; ?>

        <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('goodgame_twitch_stream', $cache, 'widget');
	}

	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);

		$this->clear_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['goodgame_twitch_stream']) )
			delete_option('goodgame_twitch_stream');

		return $instance;
	}

	function clear_widget_cache()
    {
		wp_cache_delete('goodgame_twitch_stream', 'widget');
	}

	function form( $instance )
    {
		$title = isset( $instance['title'] ) ? esc_sql( $instance['title'] ) : 'Twitch';
        $username = isset( $instance['username'] ) ? esc_sql( $instance['username'] ) : '';
		?>
			<p>
                <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'planetshine-goodgame'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'username' )); ?>"><?php esc_html_e( 'Streamer username:' , 'planetshine-goodgame'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'username' )); ?>" type="text" value="<?php echo esc_attr($username); ?>" />
            </p>
		<?php
	}
}
