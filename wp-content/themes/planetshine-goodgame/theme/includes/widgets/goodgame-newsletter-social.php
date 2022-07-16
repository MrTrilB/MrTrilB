<?php
class GoodGameSocialNewsletter extends WP_Widget {

    var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;

	function __construct() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'newsletter';
		$this->widget_description = esc_html__( 'Display a newsletter signup form', 'planetshine-goodgame' );
		$this->widget_idbase = 'goodgame_social_newsletter';
		$this->widget_name = esc_html__( 'GoodGame Newsletter', 'planetshine-goodgame' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		parent::__construct('goodgame_social_newsletter', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'clear_widget_cache' ) );
	}

	function widget($args, $instance)
    {
		$cache = wp_cache_get('goodgame_social_newsletter', 'widget');

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
        global $post;

		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Follow us', 'planetshine-goodgame') : $instance['title'], $instance, $this->id_base);
        $description = isset( $instance['description'] ) ? $instance['description'] : '';
		?>

		<?php echo $before_widget;  ?>

			<?php echo $before_title .  esc_html($title) . $after_title; ?>

			<?php echo wpautop($description); ?>

			<form action="<?php echo goodgame_gs('newsletter_form_action'); ?>" method="<?php echo goodgame_gs('newsletter_form_method'); ?>">
				<p class="input-wrapper"><input type="text" name="<?php echo goodgame_gs('newsletter_email_field'); ?>" placeholder="<?php esc_html_e('E-mail address', 'planetshine-goodgame'); ?>"><input type="submit" value="<?php esc_html_e('Subscribe', 'planetshine-goodgame'); ?>" /></p>
			</form>

		<?php echo $after_widget; ?>

        <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('goodgame_social_newsletter', $cache, 'widget');
	}

	function update( $new_instance, $old_instance )
    {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = $new_instance['description'];
        $instance['show_newsletter'] = isset( $new_instance['show_newsletter'] ) ? $new_instance['show_newsletter'] : false;
		$instance['show_social'] = isset( $new_instance['show_social'] ) ? $new_instance['show_social'] : false;

		$this->clear_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['goodgame_social_newsletter']) )
			delete_option('goodgame_social_newsletter');

		return $instance;
	}

	function clear_widget_cache()
    {
		wp_cache_delete('goodgame_social_newsletter', 'widget');
	}

	function form( $instance )
    {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $description   = isset( $instance['description'] ) ? $instance['description'] : '';

        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'planetshine-goodgame'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'description' )); ?>"><?php esc_html_e( 'Description:' , 'planetshine-goodgame'); ?></label>
                <textarea class="widefat" name="<?php echo esc_attr($this->get_field_name( 'description' )); ?>"><?php echo esc_html($description); ?></textarea>
            </p>
        <?php
	}
}
