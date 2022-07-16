<?php
class GoodGameTagCloud extends WP_Widget {

    var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;

	function __construct() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'goodgame_tag_cloud';
		$this->widget_description = esc_html__( 'Custom tag cloud for GoodGame', 'planetshine-goodgame' );
		$this->widget_idbase = 'goodgame_tag_cloud';
		$this->widget_name = esc_html__( 'GoodGame Tag Cloud', 'planetshine-goodgame' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		parent::__construct('goodgame_tag_cloud', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'clear_widget_cache' ) );
	}

	function widget($args, $instance)
    {
		$cache = wp_cache_get('goodgame_tag_cloud', 'widget');

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

		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('GoodGame Tag Cloud', 'planetshine-goodgame') : $instance['title'], $instance, $this->id_base);
        $count = isset( $instance['count'] ) ? $instance['count'] : 20;


        $term_args = array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => intval($count)
        );
        $post_tax_tags = get_terms('post_tag', $term_args);
		?>

		<?php echo $before_widget; ?>

			<?php echo $before_title .  esc_html($title) . $after_title; ?>

			<div class="post-block widget-tags">
				<div class="tags">
					<?php
						foreach($post_tax_tags as $pc)
						{
							echo '<a href="' . get_tag_link($pc->term_id) . '">' . $pc->name . '<span>' . $pc->count . '</span></a>';
						}
						?>
				</div>
			</div>

		<?php echo $after_widget; ?>

        <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('goodgame_tag_cloud', $cache, 'widget');
	}

	function update( $new_instance, $old_instance )
    {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = strip_tags($new_instance['count']);

		$this->clear_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['goodgame_tag_cloud']) )
			delete_option('goodgame_tag_cloud');

		return $instance;
	}

	function clear_widget_cache()
    {
		wp_cache_delete('goodgame_tag_cloud', 'widget');
	}

	function form( $instance )
    {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $count = isset( $instance['count'] ) ? esc_attr( $instance['count'] ) : 20;

        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'planetshine-goodgame' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'count' )); ?>"><?php esc_html_e( 'Max count:' , 'planetshine-goodgame'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'count' )); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
        </p>
        <?php
	}
}
