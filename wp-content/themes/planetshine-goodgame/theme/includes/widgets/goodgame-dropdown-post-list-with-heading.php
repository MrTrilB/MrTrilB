<?php
class GoodGameDropdownPostListWithHeading extends WP_Widget {

    var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;

	function __construct() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'goodgame_dropdown_post_list_with_heading';
		$this->widget_description = esc_html__( 'Post list with one large featured post', 'planetshine-goodgame' );
		$this->widget_idbase = 'goodgame_dropdown_post_list_with_heading';
		$this->widget_name = esc_html__( 'GoodGame Dropdown Post List With Featured Item', 'planetshine-goodgame' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		parent::__construct('goodgame_dropdown_post_list_with_heading', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'clear_widget_cache' ) );
	}

	function widget($args, $instance)
    {
        global $post;

		$cache = wp_cache_get('goodgame_dropdown_post_list_with_heading', 'widget');

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

        $category = !empty( $instance['cat'] ) ? $instance['cat'] : '';
		$platform = isset( $instance['plat'] ) ? $instance['plat'] : '';
		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Recent Posts', 'planetshine-goodgame') : $instance['title'], $instance, $this->id_base);

		$count = 7;

		/* Featured Post Query */
		$params = array(
			'category_name' => $category,
			'meta_key' => 'is_featured',
			'meta_value' => 'on'
		);

		$skip_id = array();
		$featured = goodgame_get_post_collection($params, 1, 1, $platform);
		if(!empty($featured))   //if featured post found, reduce the overal count
		{
			$featured = $featured[0];
			$count--;
			$skip_id[] = $featured->ID;
		}


		/* Post List Query */
		$params = array(
			'category_name' => $category,
			'post__not_in' => $skip_id
		);
		$items = goodgame_get_post_collection($params, $count, 1, $platform);


		//if featured not found, take the first from items
		if(empty($featured) && !empty($items))
		{
			$featured = array_shift($items);
		}

		if(!empty($featured) || !empty($items)) :
        ?>

		<?php echo $before_widget; ?>

			<div class="title-default">
				<div><span><?php echo esc_html($title); ?></span></div>
			</div>

			<div class="container post-block post-image-90">

				<div class="row">
					<div class="col-md-4">
						<?php
						if(!empty($featured))
						{
							$post = $featured;
							setup_postdata($post);
							get_template_part('theme/templates/featured-medium-post');
						}
						?>
					</div>
					<div class="col-md-8">
						<?php
							$chunks = array_chunk($items, 2);
							if(!empty($chunks))
							{
								foreach($chunks as $chunk)
								{
									echo '<div class="row">';

									foreach($chunk as $key => $post)
									{
										echo '<div class="col-md-6">';

										setup_postdata($post);
										get_template_part('theme/templates/post-list-item-medium-without-tags');

										echo '</div>';
									}

									echo '</div>';
								}
							}
						?>
					</div>
				</div>

			</div>

		<?php echo $after_widget; ?>

		<?php endif; ?>

        <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('goodgame_dropdown_post_list_with_heading', $cache, 'widget');
	}

	function update( $new_instance, $old_instance )
    {
		$instance = $old_instance;
        $instance['cat'] = esc_sql($new_instance['cat']);
		$instance['plat'] = esc_sql($new_instance['plat']);
		$instance['title'] = strip_tags($new_instance['title']);

		$this->clear_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['goodgame_dropdown_post_list_with_heading']) )
			delete_option('goodgame_dropdown_post_list_with_heading');

		return $instance;
	}

	function clear_widget_cache()
    {
		wp_cache_delete('goodgame_dropdown_post_list_with_heading', 'widget');
	}

	function form( $instance )
    {
        //get post categories
        $post_categories = get_terms('category');
		$post_cats = array('' => '');	//blank entry
		
        foreach($post_categories as $pc)
        {
            $post_cats[$pc->slug] = $pc->slug;
        }
		
		//get post platforms
		$post_platforms = GoodGameInstance()->get_all_platforms();
		$post_plats = array('' => '');	//blank entry
		
		if(!empty($post_platforms))
		{
			foreach($post_platforms as $pp)
			{
				$post_plats[$pp->slug] = $pp->slug;
			}
		}

        $current_cat = isset( $instance['cat'] ) ? esc_sql( $instance['cat'] ) : '';
		$current_plat = isset( $instance['plat'] ) ? esc_sql( $instance['plat'] ) : '';
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'planetshine-goodgame'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
			<p>
                <label for="<?php echo esc_attr($this->get_field_id( 'cat' )); ?>"><?php esc_html_e( 'Category:', 'planetshine-goodgame' ); ?></label><br/>
                <select name="<?php echo esc_attr($this->get_field_name( 'cat' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'cat' )); ?>" class="widefat">
                    <?php foreach($post_cats as $cat_item): ?>
                    <option value="<?php echo esc_attr($cat_item); ?>"<?php if($cat_item == $current_cat) { echo ' selected="selected"'; } ?>><?php echo ucfirst($cat_item); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
			<p>
                <label for="<?php echo esc_attr($this->get_field_id( 'plat' )); ?>"><?php esc_html_e( 'Platform:', 'planetshine-goodgame' ); ?></label><br/>
                <select name="<?php echo esc_attr($this->get_field_name( 'plat' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'plat' )); ?>" class="widefat">
                    <?php foreach($post_plats as $plat): ?>
                        <option value="<?php echo esc_attr($plat); ?>"<?php if($plat == $current_plat) echo ' selected="selected"'; ?>><?php echo ucfirst($plat); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        <?php
	}
}
