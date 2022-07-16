<?php

class GoodGameTopReviewsByPlatforms extends WP_Widget {
	var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;

	function __construct() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'goodgame_top_reviews_by_platforms';
		$this->widget_description = esc_html__( 'Top 5 posts filtered by platform and period of time', 'planetshine-goodgame' );
		$this->widget_idbase = 'goodgame_top_reviews_by_platforms';
		$this->widget_name = esc_html__( 'GoodGame Top Reviews By Platforms', 'planetshine-goodgame' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		parent::__construct('goodgame_top_reviews_by_platforms', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'clear_widget_cache' ) );
	}

	function widget($args, $instance)
    {
		$cache = wp_cache_get('goodgame_top_reviews_by_platforms', 'widget');

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

        global $post, $post_list_order;
		$post_list_order = NULL;

		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Top 5 rated games', 'planetshine-goodgame') : $instance['title'], $instance, $this->id_base);
        $count = isset( $instance['count'] ) ? intval($instance['count']) : false;

		$platforms = GoodGameInstance()->get_all_platforms();

		$items = goodgame_get_posts_by_platform('all', 'all', $count);

		if(!empty($items)) : ?>

			<?php echo $before_widget;  ?>

				<?php echo $before_title .  esc_html($title) . $after_title; ?>

				<div class="post-block">
					<form action="load_top_reviews_widget_items" class="form-top-reviews">
						<div class="select-wrapper">
							<input type="hidden" name="action" value="load_top_reviews_widget_items" />
							<input type="hidden" name="count" value="<?php echo esc_attr($count); ?>" />
							<?php if(!empty($platforms)) : ?>
							<select name="platform_id" class="selectpicker">
								<option value="all"><?php esc_html_e('All platforms', 'planetshine-goodgame'); ?></option>
								<?php foreach($platforms as $platform) : ?>
								<option value="<?php echo esc_attr($platform->term_id); ?>"><?php echo esc_html($platform->name); ?></option>
								<?php endforeach; ?>
							</select>
							<?php endif; ?>

							<select name="interval" class="selectpicker">
								<option value="all"><?php esc_html_e('All time', 'planetshine-goodgame'); ?></option>
								<option value="12-month"><?php esc_html_e('Last year', 'planetshine-goodgame'); ?></option>
								<option value="6-month"><?php esc_html_e('Last 6 months', 'planetshine-goodgame'); ?></option>
								<option value="3-month"><?php esc_html_e('Last 3 months', 'planetshine-goodgame'); ?></option>
								<option value="1-month"><?php esc_html_e('Last month', 'planetshine-goodgame'); ?></option>
								<option value="1-week"><?php esc_html_e('Last week', 'planetshine-goodgame'); ?></option>
							</select>
						</div>
					</form>

					<div class="items-wrapper">
						<?php if(!empty($items)) : ?>

							<?php foreach($items as $post) : ?>
								<div class="row">
									<div>
										<?php
										setup_postdata($post);
										$post->platform_id = 'all';
										get_template_part('theme/templates/post-list-platform-item');
										?>
									</div>
								</div>
							<?php endforeach; ?>

							<div class="row">
								<a href="<?php echo GoodGameInstance()->get_platform_view_more_link($post->platform_id); ?>" class="btn-default"><?php esc_html_e('View more games', 'planetshine-goodgame'); ?></a>
							</div>

						<?php else : ?>
							<p class="empty"><?php esc_html_e('No posts were found!', 'planetshine-goodgame'); ?></p>
						<?php endif; ?>
					</div>
				</div>

			<?php echo $after_widget; ?>

		<?php endif; ?>

        <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('goodgame_top_reviews_by_platforms', $cache, 'widget');
	}

	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);

		$this->clear_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['goodgame_top_reviews_by_platforms']) )
			delete_option('goodgame_top_reviews_by_platforms');

		return $instance;
	}

	function clear_widget_cache()
    {
		wp_cache_delete('goodgame_top_reviews_by_platforms', 'widget');
	}

	function form( $instance )
    {
		$title = isset( $instance['title'] ) ? esc_sql( $instance['title'] ) : 'Top 5 rated games';
        $count = isset( $instance['count'] ) ? esc_sql( $instance['count'] ) : 5;
		?>
			<p>
                <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'planetshine-goodgame'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'count' )); ?>"><?php esc_html_e( 'Post count:' , 'planetshine-goodgame'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'count' )); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
            </p>
		<?php
	}
}

