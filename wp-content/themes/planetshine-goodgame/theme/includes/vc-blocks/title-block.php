<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Title_block extends GoodGame_VC_Block_Base {

		public $shortcode = 'title_block';
		public $classname = 'GoodGame_Title_block';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			return array(
				"name" => esc_html__("Title block", 'planetshine-goodgame'),
				"base" => "title_block",
				"class" => "",
				"category" => esc_html__('GoodGame', 'planetshine-goodgame'),
				"params" => array(
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Title", 'planetshine-goodgame'),
						"param_name" => "title",
						"value" => esc_html__("Latest news", 'planetshine-goodgame'),
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
				'title' => ''
			), $atts ) );

			?>
				<div class="title-default">
					<div>
						<span><?php echo esc_html($title); ?></span>
					</div>
				</div>
			<?php

			$return = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $return;
		}


	}

	//Create instance of VC block
	new GoodGame_Title_block();

}
