<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Home_Slider extends GoodGame_VC_Block_Base {

		public $shortcode = 'home_slider';
		public $classname = 'GoodGame_Home_Slider';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			return array(
				'name'				=> esc_html__('Home slider', 'planetshine-goodgame'),
				'description'		=> esc_html__('Display large home slider for posts', 'planetshine-goodgame'),
				'base'				=> 'home_slider',
				"as_parent"			=> array('only' => 'home_slider_item'),
				"content_element"	=> true,
				"show_settings_on_create" => false,
				"js_view"			=> 'VcColumnView',
				'class'				=> '',
				'category'			=> esc_html__('GoodGame', 'planetshine-goodgame'),
				'params'			=> array(
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Interval", 'planetshine-goodgame'),
						"param_name" => "interval",
						"value" => 0,
						"description" => esc_html__("The amount of miliseconds (1000ms = 1sec) to delay between advancing the slider. Entering 0 will disable auto advance.", 'planetshine-goodgame')
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
				'show_sidebar' => '',
				'interval' => 0
			), $atts ) );

			$slide_count = substr_count($content, 'home_slider_item');
			$unique_id = uniqid();

			$content = preg_replace('/home_slider_item/', 'home_slider_item first="1" ', $content, 1);

			if($slide_count > 0) : ?>


			<div id="goodgame-slider-<?php echo esc_attr($unique_id); ?>" class="owl-carousel goodgame-homepage-slider" data-interval="<?php echo esc_attr($interval); ?>">
				<?php  echo do_shortcode($content); ?>
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
	new GoodGame_Home_Slider();

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_Home_Slider extends WPBakeryShortCodesContainer { }
	}
}