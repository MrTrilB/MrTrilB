<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Home_Slider_Item extends GoodGame_VC_Block_Base {

		public $shortcode = 'home_slider_item';
		public $classname = 'GoodGame_Home_Slider_Item';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			return array(
				'name'				=> esc_html__('Home slider item', 'planetshine-goodgame'),
				'description'		=> esc_html__('Item for home slider', 'planetshine-goodgame'),
				'base'				=> 'home_slider_item',
				"as_child"			=> array('only' => 'home_slider'),
				"content_element"	=> true,
				'class'				=> '',
				'category'			=> esc_html__('GoodGame', 'planetshine-goodgame'),
				'params'			=> array(

					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Post ID", 'planetshine-goodgame'),
						"param_name" => "slider_post_id",
						"value" => '',
						"description" => esc_html__("The ID of the post you wish to display in this slide", 'planetshine-goodgame')
				    ),
					array(
						"type" => "attach_image",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Slider image", 'planetshine-goodgame'),
						"param_name" => "image",
						"value" => '',
						"description" => esc_html__("Recommended size - 500x650px", 'planetshine-goodgame')
					),
				),
			);
		}

		/*
		 * Shortcode content
		 */
		public static function shortcode($atts = array(), $content = '') {

			ob_start();
			global $post;

			extract( shortcode_atts( array(
				'slider_post_id' => 1,
				'image'	=> '',
				'first' => 0,	//set automatically
			), $atts ) );

			$post = get_post($slider_post_id);
			setup_postdata($post);

			if(is_numeric($image))
			{
				$src_parts = wp_get_attachment_image_src($image, 'goodgame_slider_image');
				if(!empty($src_parts))
				{
					$image = $src_parts[0];
				}
				else
				{
					$image = '';
				}
			}
			elseif($post) {
				$image = goodgame_get_thumbnail('goodgame_slider_image', true, false);
			}

			if($post) : ?>
				<div>
					<div class="post-block">
						<div class="image" style="background-image: url(<?php echo esc_url($image); ?>);"></div>

						<div class="title">
							<?php get_template_part('theme/templates/post-platforms-dropdown-categories'); ?>

							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php GoodGameInstance()->get_rating_stars(false, '', true); ?></h2>

							<?php get_template_part('theme/templates/title-legend'); ?>

							<div class="intro">
								<p><?php echo wpautop(goodgame_excerpt(100)); ?></p>
							</div>

							<a href="<?php the_permalink(); ?>" class="btn btn-default"><?php esc_html_e('Read more', 'planetshine-goodgame'); ?></a>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php
			$return = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $return;
		}

	}

	//Create instance of VC block
	new GoodGame_Home_Slider_Item();

	if ( class_exists( 'WPBakeryShortCode' ) ) {
		class WPBakeryShortCode_Home_Slider_Item extends WPBakeryShortCode { }
	}
}
