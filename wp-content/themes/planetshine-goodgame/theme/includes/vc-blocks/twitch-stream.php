<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Twitch_Stream extends GoodGame_VC_Block_Base {

		public $shortcode = 'twitch_stream';
		public $classname = 'GoodGame_Twitch_Stream';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			return array(
				'name'				=> esc_html__('Twitch live stream', 'planetshine-goodgame'),
				'description'		=> esc_html__('Display Twitch live stream', 'planetshine-goodgame'),
				'base'				=> 'twitch_stream',
				"content_element"	=> true,
				'class'				=> '',
				'category'			=> esc_html__('GoodGame Post Blocks', 'planetshine-goodgame'),
				'params'			=> array(
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Username", 'planetshine-goodgame'),
						"param_name" => "username",
						"value" => '',
						"description" => esc_html__("Streamer's username", 'planetshine-goodgame')
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
				'username' => '',
			), $atts ) );
			?>

			<div class="twitch-stream-wrapper" data-username="<?php echo esc_attr($username); ?>"></div>
			
			<?php
			$return = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $return;
		}

	}

	//Create instance of VC block
	new GoodGame_Twitch_Stream();

}
