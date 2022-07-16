<?php
class GoodGameDropdownFeaturedPostList extends WP_Widget {

    var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;

	function __construct() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'goodgame_featured_post_list';
		$this->widget_description = esc_html__( 'Row of large featured posts', 'planetshine-goodgame' );
		$this->widget_idbase = 'goodgame_featured_post_list';
		$this->widget_name = esc_html__( 'GoodGame Dropdown Featured Post List', 'planetshine-goodgame' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		parent::__construct('goodgame_featured_post_list', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'clear_widget_cache' ) );
	}

	function widget($args, $instance)
    {
		$cache = wp_cache_get('goodgame_featured_post_list', 'widget');

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

		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Featured Posts', 'planetshine-goodgame') : $instance['title'], $instance, $this->id_base);
        $category = isset( $instance['cat'] ) ? $instance['cat'] : false;
        $platform = isset( $instance['plat'] ) ? $instance['plat'] : '';
		$featured = isset( $instance['featured'] ) ? ( $instance['featured'] ) : false;

		/* Post List Query */
		$params = array(
			'category_name' => $category
		);
		
		if($featured)
		{
			$params['meta_key'] = 'is_featured';
			$params['meta_value'] = 'on';
		}
		
		$items = goodgame_get_post_collection($params, 4, 1, $platform);

		//get link
		if(!empty($category))
		{
			$cat = get_category_by_slug($category);
			$view_all = get_category_link($cat->cat_ID);
		}
		elseif(!empty($platform) )
		{
			$view_all = get_term_link($platform, 'platform');
		}
		else
		{
			if(get_option('show_on_front') == 'page')
			{
				$view_all = get_permalink( get_option( 'page_for_posts' ) );
			}
			else
			{
				$view_all = get_home_url();
			}
		}
	
		if(!empty($items)) : ?>

			<?php echo $before_widget;  ?>

			<div class="title-default">
				<div><span><?php echo esc_html($title); ?></span></div>
			</div>

			<div class="container post-block post-video">
				<div class="row">

				<?php foreach($items as $post) : ?>

					<div class="col-md-3">
						<?php
						setup_postdata($post);
						get_template_part('theme/templates/post-slider-item');
						?>
					</div>

				<?php endforeach; ?>

				</div>
			</div>

			<div class="container post-block btn-more">
				<a href="<?php echo esc_url($view_all); ?>" class="btn btn-default btn-dark"><?php esc_html_e('View all', 'planetshine-goodgame') ?></a>
			</div>

			<?php echo $after_widget; ?>

		<?php endif; ?>

        <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('goodgame_featured_post_list', $cache, 'widget');
	}

	function update( $new_instance, $old_instance )
    {
		$instance = $old_instance;
		
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['cat'] = esc_sql($new_instance['cat']);
		$instance['plat'] = esc_sql($new_instance['plat']);
		$instance['featured'] = isset( $new_instance['featured'] ) ? (bool) $new_instance['featured'] : false;

		$this->clear_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['goodgame_featured_post_list']) )
			delete_option('goodgame_featured_post_list');

		return $instance;
	}

	function clear_widget_cache()
    {
		wp_cache_delete('goodgame_featured_post_list', 'widget');
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

        $title = isset( $instance['title'] ) ? $instance['title'] : 'Featured news';
        $current_cat = isset( $instance['cat'] ) ? esc_sql( $instance['cat'] ) : '';
        $current_plat = isset( $instance['plat'] ) ? esc_sql( $instance['plat'] ) : '';
		$featured = isset( $instance['featured'] ) ? (bool) ( $instance['featured'] ) : false;
        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'planetshine-goodgame'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'cat' )); ?>"><?php esc_html_e( 'Category:', 'planetshine-goodgame' ); ?></label><br/>
                <select name="<?php echo esc_attr($this->get_field_name( 'cat' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'cat' )); ?>" class="widefat">
                    <?php foreach($post_cats as $cat): ?>
                        <option value="<?php echo esc_attr($cat); ?>"<?php if($cat == $current_cat) echo ' selected="selected"'; ?>><?php echo ucfirst($cat); ?></option>
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
			
			<p>
				<input type="checkbox"<?php checked( $featured ); ?> id="<?php echo esc_attr($this->get_field_id( 'featured' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'featured' )); ?>" <?php echo esc_attr($featured); ?> />
				<label for="<?php echo esc_attr($this->get_field_id( 'featured' )); ?>"><?php esc_html_e( 'Display only featured posts', 'planetshine-goodgame' ); ?></label>
			</p>
			
		<?php
	}
}
