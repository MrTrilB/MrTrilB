<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Post_Platform_Slider extends GoodGame_VC_Block_Base {

		public $shortcode = 'post_platform_slider';
		public $classname = 'GoodGame_Post_Platform_Slider';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			//get platforms
			$post_platforms = GoodGameInstance() -> get_all_platforms();
			$post_plats = array();
			if(!empty($post_platforms))
			{
				foreach($post_platforms as $pp)
				{
					$post_plats[$pp->slug] = $pp->slug;
				}
			}

			return array(
				'name'				=> esc_html__('Post platform slider', 'planetshine-goodgame'),
				'description'		=> esc_html__('Display post slider with platform tabs', 'planetshine-goodgame'),
				'base'				=> 'post_platform_slider',
				"content_element"	=> true,
				'class'				=> '',
				'category'			=> esc_html__('GoodGame', 'planetshine-goodgame'),
				'params'			=> array(
					array(
						"type" => "checkbox",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Post platforms", 'planetshine-goodgame'),
						"param_name" => "platforms",
						"value" => $post_plats,
						"description" => esc_html__("Check which platforms to show in tabs", 'planetshine-goodgame')
					),
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Show first", 'planetshine-goodgame'),
						"param_name" => "show_first",
						"value" => $post_plats,
						"description" => esc_html__("Select which platforms to show first. Must be checked in above.", 'planetshine-goodgame')
					),
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Count", 'planetshine-goodgame'),
						"param_name" => "count",
						"value" => 12,
						"description" => esc_html__("How many posts should be shown per platform", 'planetshine-goodgame')
					),
					array(
						 "type" => "textfield",
						 "holder" => "div",
						 "class" => "",
						 "heading" => esc_html__("Interval", 'planetshine-goodgame'),
						 "param_name" => "interval",
						 "value" => 0,
						 "description" => esc_html__("The amount of miliseconds to delay between advancing the slider. Entering 0 will disable auto advance.", 'planetshine-goodgame')
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
				'count' => 12,
				'platforms' => '',
				'show_first' => '',
				'interval' => 0
			), $atts ) );

			if($interval == 0 || !is_numeric($interval)) { $interval = 'false'; }

			$unique_id = uniqid();

			if(strlen($platforms) > 0)
			{
				$platforms = explode(',', $platforms);
				if(!empty($platforms))
				{
					//get first element, if show first is not in list
					if(!in_array($show_first, $platforms))
					{
						reset($platforms);
						$show_first = array_shift($platforms);
					}
					else	//remove the first item from array
					{
						$key = array_search($show_first, $platforms);
						unset($platforms[$key]);
					}

					$first_plat = get_term_by('slug', $show_first, 'platform');

				?>

					<div class="container video-slider dynamic-category-slider">
						<div class="sorting">
							<div class="buttons">
								<?php
								if($first_plat) {
									echo '<a href="#" class="btn btn-sort active" id="obj-' . $unique_id . '-' .  $first_plat->slug . '"><span>' . $first_plat->name . '</span></a>';
								}

								if(!empty($platforms))
								{
									foreach($platforms as $plat)
									{
										$plat_obj = get_term_by('slug', $plat, 'platform');
										if($plat_obj) {
											echo '<a href="#" class="btn btn-sort" id="obj-' . $unique_id . '-' .  $plat_obj->slug . '"><span>' . $plat_obj->name . '</span></a>';
										}
									}
								}
								?>
							</div>
						</div>

						<?php

						if($first_plat) {
							self::single_slider($unique_id, $first_plat, $count, $interval);
						}

						if(!empty($platforms))
						{
							foreach($platforms as $plat)
							{
								$plat_obj = get_term_by('slug', $plat, 'platform');
								if($plat_obj) {
									self::single_slider_placeholder($unique_id, $plat_obj, $count);
								}
							}
						}
						?>

					</div>

					<?php
				}

			}


			$return = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $return;
		}


		public static function single_slider($unique_id, $platform, $count, $interval, $is_ajax = false) {

			global $post, $visible_platform_count;
			
			$items = goodgame_get_post_collection(array(), $count, 1, $platform);
			if(!empty($items)) {

				$chunks = array_chunk($items, 4);
				?>

				<?php if(!$is_ajax) { ?>
				<div id="slider-<?php echo esc_attr($unique_id) . '-' . $platform->slug; ?>" class="carousel slide dynamic-slide" data-count="<?php echo esc_attr($count); ?>" data-platform="true" data-slug="<?php echo esc_attr($platform->slug); ?>" data-unique_id="<?php echo esc_attr($unique_id); ?>" data-ride="carousel" data-interval="<?php echo esc_attr($interval); ?>">
				<?php } ?>

					<?php if(!empty($chunks) && count($chunks) > 1) : ?>
						<div class="controls right">
							<ol class="carousel-indicators">
								<?php for($i = 0; $i < count($chunks); $i++) { ?>

									<li data-target="#slider-<?php echo esc_attr($unique_id) . '-' . $platform->slug; ?>" data-slide-to="<?php echo esc_attr($i); ?>" <?php if($i == 0) { echo 'class="active"'; } ?>></li>

								<?php } ?>
							</ol>

							<a href="#slider-<?php echo esc_attr($unique_id) . '-' . $platform->slug; ?>" data-slide="prev" class="btn btn-default"><i class="fa fa-caret-left"></i></a>
							<a href="#slider-<?php echo esc_attr($unique_id) . '-' . $platform->slug; ?>" data-slide="next" class="btn btn-default"><i class="fa fa-caret-right"></i></a>
						</div>
					<?php endif; ?>

					<div class="carousel-inner">
						<?php
						if(!empty($items))
						{

							foreach($chunks as $key => $chunk)
							{
								if(!empty($chunk))
								{
									?>
									<div class="slide item<?php if($key == 0) { echo ' active'; } ?>">
										<div class="container post-block post-slider">
											<div class="row">

												<?php
												foreach($chunk as $post)
												{
													?><div class="col-sm-3 col-xs-12"><?php

													setup_postdata($post);
													$visible_platform_count = 1;
													get_template_part('theme/templates/post-slider-item-large');

													?></div><?php
												}
												?>

											</div>
										</div>
									</div>
									<?php
								}
							}
						}
						?>
					</div>

				<?php if(!$is_ajax) { ?>
				</div>
				<?php
				}
			}
		}


		private static function single_slider_placeholder($unique_id, $platform, $count)
		{
			?>
			<div id="slider-<?php echo esc_attr($unique_id) . '-' . $platform->slug; ?>" class="carousel slide dynamic-slide dynamic-slide-hidden" data-count="<?php echo esc_attr($count); ?>" data-platform="true" data-slug="<?php echo esc_attr($platform->slug); ?>" data-unique_id="<?php echo esc_attr($unique_id); ?>" data-ride="carousel" data-interval="false">
				<div class="goodgame-loader"><div class="box"></div><div class="box"></div><div class="box"></div><div class="box"></div></div>
			</div>
			<?php
		}


	}

	//Create instance of VC block
	new GoodGame_Post_Platform_Slider();

}
