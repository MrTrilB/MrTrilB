<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Banner300 extends GoodGame_VC_Block_Base {

		public $shortcode = 'goodgame_banner_300';
		public $classname = 'GoodGame_Banner300';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			//banners 300x300
			$banners_300x300_data = goodgame_get_active_banners('300x300');
			$banners_300x300 = array();
			foreach($banners_300x300_data as $banner)
			{
				$banners_300x300[$banner['ad_title']] = $banner['ad_slug'];
			}

			return array(
				"name" => esc_html__("Banner 300x300", 'planetshine-goodgame'),
				"base" => "goodgame_banner_300",
				"class" => "",
				"category" => esc_html__('GoodGame Banners', 'planetshine-goodgame'),
				"params" => array(
					array(
						 "type" => "checkbox",
						 "holder" => "div",
						 "class" => "",
						 "heading" => esc_html__("Banner", 'planetshine-goodgame'),
						 "param_name" => "banner",
						 "value" => $banners_300x300,
						 "description" => esc_html__("Display 300x300px banners.  Check more than one to have multiple banners in rotation", 'planetshine-goodgame')
					),
				)
			);
		}

		/*
		 * Shortcode content
		 */
		public static function shortcode($atts = array(), $content = '') {

			ob_start();
			global $post;

			extract( shortcode_atts( array(
				'banner' => ''
			), $atts ) );

			if(!empty($banner))
			{
				$banner_parts = explode(',', $banner);
				$rand = rand(0, sizeof($banner_parts)-1);    //banner rotation
				$banner_id = $banner_parts[$rand];
				$banner_data = goodgame_get_banner_by_size_and_slug($banner_id, '300x300');

				if($banner_data)
				{
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
								echo stripslashes($banner_data['googlead_content']);
						} ?>
					</div>
				<?php
				}
			}

			$return = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $return;
		}


	}

	//Create instance of VC block
	new GoodGame_Banner300();

}
