<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Review_Summary extends GoodGame_VC_Block_Base {

		public $shortcode = 'review_summary';
		public $classname = 'GoodGame_Review_Summary';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			return array(
				"name" => esc_html__("Review Summary", 'planetshine-goodgame'),
				"base" => "review_summary",
				"class" => "",
				"category" => esc_html__('GoodGame Post Blocks', 'planetshine-goodgame'),
				"params" => array(
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Label for Post rating", 'planetshine-goodgame'),
						"param_name" => "rating_label",
						"value" => esc_html__("Author's rating", 'planetshine-goodgame'),
				   ),
					array(
						 "type" => "textarea",
						 "holder" => "div",
						 "class" => "",
						 "heading" => esc_html__("Rating", 'planetshine-goodgame'),
						 "param_name" => "content",
						 "value" => "",
						 "description" => esc_html__('List of Ratings. Enter them in format like this: [rating title="Graphics" value="8.5"] This will result in "Graphics" being rated with 8.5 out of 10', 'planetshine-goodgame')
					),
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Heading for positive feature list", 'planetshine-goodgame'),
						"param_name" => "positive_heading",
						"value" => esc_html__("What's good", 'planetshine-goodgame'),
				   ),
					array(
						 "type" => "textarea",
						 "holder" => "div",
						 "class" => "",
						 "heading" => esc_html__("Positives", 'planetshine-goodgame'),
						 "param_name" => "positives",
						 "value" => "",
						 "description" => esc_html__("List of positives. Enter each item in new row.", 'planetshine-goodgame')
					),
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Negative for positive feature list", 'planetshine-goodgame'),
						"param_name" => "negative_heading",
						"value" => esc_html__("What's bad", 'planetshine-goodgame'),
				   ),
					array(
						 "type" => "textarea",
						 "holder" => "div",
						 "class" => "",
						 "heading" => esc_html__("Negatives", 'planetshine-goodgame'),
						 "param_name" => "negatives",
						 "value" => "",
						 "description" => esc_html__("List of negatives. Enter each item in new row.", 'planetshine-goodgame')
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
				'rating_label' => esc_html__("Author's rating", 'planetshine-goodgame'),
				'positive_heading' => esc_html__("The good", 'planetshine-goodgame'),
				'negative_heading' => esc_html__("The bad", 'planetshine-goodgame'),
				'positives' => '',
				'negatives' => '',
				'tags' => '',
			), $atts ) );

			?><div class="review-summary-gg">
				<div class="table-wrapper">
					<?php GoodGameInstance()->get_rating_stars(true, $rating_label); ?>

					<div class="details">
						<h3><?php esc_html_e('Overall rating', 'planetshine-goodgame'); ?></h3>
						<div class="ratings">
						<?php
							$content = strip_tags($content);
							 echo do_shortcode($content);
						?>
						</div>
					</div>
				</div>
				<div class="goodbad">
					<div class="good">
						<div class="title-default">
							<div><span><?php echo esc_html($positive_heading); ?></span></div>
						</div>
						<?php
						$positives = explode("\n", $positives);
						if(!empty($positives) && strlen(trim($positives[0])) > 0) : ?>
							<?php
								echo '<ul>';
								foreach($positives as $item)
								{
									echo '<li><span>' . $item . '</span></li>';
								}
								echo '</ul>';
							?>
						<?php endif; ?>
					</div>

					<div class="bad">
						<div class="title-default">
							<div><span><?php echo esc_html($negative_heading); ?></span></div>
						</div>

						<?php
						$negatives = explode("\n", $negatives);
						if(!empty($negatives) && strlen(trim($negatives[0])) > 0) : ?>
							<?php
								echo '<ul>';
								foreach($negatives as $item)
								{
									echo '<li><span>' . $item . '</span></li>';
								}
								echo '</ul>';
							?>
						<?php endif; ?>

					</div>
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
	new GoodGame_Review_Summary();

}
