<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Photo_Gallery extends GoodGame_VC_Block_Base {

		public $shortcode = 'photo_galleries';
		public $classname = 'GoodGame_Photo_Gallery';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			return array(
				'name'				=> esc_html__('Latest photo galleries', 'planetshine-goodgame'),
				'description'		=> esc_html__('List of photo galleries', 'planetshine-goodgame'),
				'base'				=> 'photo_galleries',
				"content_element"	=> true,
				'class'				=> '',
				'category'			=> esc_html__('GoodGame', 'planetshine-goodgame'),
				'params'			=> array(
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Title", 'planetshine-goodgame'),
						"param_name" => "title",
						"value" => esc_html__("Latest news", 'planetshine-goodgame'),
						"description" => esc_html__("The title for gallery block", 'planetshine-goodgame')
				   ),
				   array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Count", 'planetshine-goodgame'),
						"param_name" => "count",
						"value" => 3,
						"description" => esc_html__("How many galleries should be shown", 'planetshine-goodgame')
				   ),
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Columns", 'planetshine-goodgame'),
						"param_name" => "columns",
						"value" => array(3 => 3, 4 => 4),
						"description" => esc_html__("How many columns per row", 'planetshine-goodgame')
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
				'title' => esc_html__('Latest Photo Galleries', 'planetshine-goodgame'),
				'count' => 3,
				'columns' => 3,
			), $atts ) );

			$unique_id = uniqid();

			//set bootstrap column size n/12
			$bs_collumn = 4;
			if($columns == 4)
			{
				$bs_collumn = 3;
			}

			$items = goodgame_get_post_collection(array(), $count, 1, null, 'date', 'DESC', 'gallery');



			if(!empty($items))
			{
				?>
				<div class="post-block post-gallery">

						<div class="row">
							<div class="col-md-12">
								<div class="title-default">
									<div><span><?php echo esc_html($title); ?></span></div>
									<a href="<?php echo esc_url(get_post_type_archive_link('gallery')); ?>" class="more"><?php esc_html_e('View all', 'planetshine-goodgame'); ?></a>
								</div>

								<div class="galleries">

									<?php
									$chunks = array_chunk($items, $columns);
									if(!empty($chunks))
									{

										foreach($chunks as $chunk)
										{
										?>

										<div class="row">
											<?php
											foreach($chunk as $post)
											{
												setup_postdata($post);
												?>
												<div class="col-md-<?php echo esc_attr($bs_collumn); ?> col-sm-<?php echo esc_attr($bs_collumn); ?> col-xs-12">
													<?php get_template_part('theme/templates/loop-gallery-list-item'); ?>
												</div>
												<?php
											} ?>

										</div>

										<?php }

									}
									?>

								</div>

							</div>
						</div>

				</div>
				<?php

			}

			$return = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $return;

		}

	}

	if(class_exists('GoodGame_Photo_Gallery'))
	{
		//Create instance of VC block
		new GoodGame_Photo_Gallery();
	}
}
