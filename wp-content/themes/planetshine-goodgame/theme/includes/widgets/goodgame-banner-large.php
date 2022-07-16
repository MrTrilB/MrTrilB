<?php
class GoodGameBannerLarge extends WP_Widget {

    var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;

	function __construct() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'goodgame_sidebar_banner';
		$this->widget_description = esc_html__( '300x300px banner', 'planetshine-goodgame' );
		$this->widget_idbase = 'goodgame_sidebar_banner';
		$this->widget_name = esc_html__( 'GoodGame Sidebar Banner', 'planetshine-goodgame' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		parent::__construct('goodgame_sidebar_banner', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'clear_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'clear_widget_cache' ) );
	}

	function widget($args, $instance)
    {
		$cache = wp_cache_get('goodgame_sidebar_banner', 'widget');

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

        $banner_string = isset( $instance['banner'] ) ? esc_attr( $instance['banner'] ) : '';
        $current_banners = explode('#', $banner_string);

        if(!empty($current_banners))
        {
            $rand = rand(0, sizeof($current_banners)-1);    //banner rotation
            $banner = $current_banners[$rand];
            $banner_data = goodgame_get_banner_by_size_and_slug($banner, '300x300');

            if($banner_data)
            {
                ?>
                <?php echo $before_widget; ?>

				<?php
					$mobile_disabled = '';
					if(empty($banner_data['mobile_enabled']))
					{
						$mobile_disabled = 'mobile_disabled';
					}
				?>

                <div class="banner banner-300x300 <?php echo(esc_attr($mobile_disabled)); ?>">
					<?php if($banner_data['ad_type'] == 'banner') { ?>
							<a href="<?php echo esc_url($banner_data['ad_link']); ?>" target="_blank"><img src="<?php echo esc_url(goodgame_banner_image_src($banner_data['ad_file'])); ?>" alt="<?php echo esc_attr($banner_data['ad_title']); ?>"></a>
					<?php } elseif($banner_data['ad_type'] == 'iframe') { ?>
						<iframe class="iframe-300x300" scrolling="no" src="<?php echo esc_url($banner_data['ad_iframe_src']); ?>"></iframe>
					<?php } elseif($banner_data['ad_type'] == 'shortcode') { ?>
						<?php echo do_shortcode($banner_data['shortcode']);  ?>
					<?php } else {
					        //hack for double slashes
							echo stripslashes(stripslashes($banner_data['googlead_content']));
					} ?>
				</div>

                <?php echo $after_widget; ?>
                <?php
            }
        }

        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('goodgame_sidebar_banner', $cache, 'widget');

	}

	function update( $new_instance, $old_instance )
    {
		$instance = $old_instance;
        $instance['banner'] = implode('#', array_keys($new_instance));

		$this->clear_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['goodgame_sidebar_banner']) )
			delete_option('goodgame_sidebar_banner');

		return $instance;
	}

	function clear_widget_cache()
    {
		wp_cache_delete('goodgame_sidebar_banner', 'widget');
	}

	function form( $instance )
    {
		$banner_string = isset( $instance['banner'] ) ? esc_attr( $instance['banner'] ) : '';
        $current_banners = explode('#', $banner_string);

        $banners = goodgame_get_active_banners('300x300');
        $ad_url = admin_url( 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin&view=ads_manager' );

        if(!empty($banners))
        {
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'banner' )); ?>"><?php esc_html_e( 'Banners:', 'planetshine-goodgame' ); ?></label><br/>

                <?php foreach($banners as $banner): ?>
                    <?php $checked = (in_array($banner['ad_slug'], $current_banners) ? 'checked' : ''); ?>
                    <input type="checkbox" id="<?php echo esc_attr($this->get_field_id( $banner['ad_slug'] )); ?>" name="<?php echo esc_attr($this->get_field_name( $banner['ad_slug'] )); ?>" <?php echo esc_attr($checked); ?> /><label for="<?php echo esc_attr($this->get_field_id( $banner['ad_slug'] )); ?>"><?php echo ucfirst($banner['ad_title']); ?></label><br/>
                <?php endforeach; ?>

            </p>
            <?php
        }
        else
        {
            echo '<p>'
                . esc_html__('There are no active ads for this location. ', 'planetshine-goodgame')
                . esc_html__('Supports: ', 'planetshine-goodgame') .'300x300px ads. '
                . '<strong><a href="' . esc_url($ad_url) . '">' . esc_html__('Create a new Ad!', 'planetshine-goodgame')  . '</a></strong>'
            .'</p>';
        }
	}
}
