<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Post_Category_Slider extends GoodGame_VC_Block_Base {

		public $shortcode = 'post_category_slider';
		public $classname = 'GoodGame_Post_Category_Slider';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			//get categories
			$post_categories = get_terms('category');
			$post_cats = array();

			if(!empty($post_categories))
			{
				foreach($post_categories as $pc)
				{
					$post_cats[$pc->slug] = $pc->slug;
				}
			}

			return array(
				'name'				=> esc_html__('Post category slider', 'planetshine-goodgame'),
				'description'		=> esc_html__('Display post slider with category tabs', 'planetshine-goodgame'),
				'base'				=> 'post_category_slider',
				"content_element"	=> true,
				'class'				=> '',
				'category'			=> esc_html__('GoodGame', 'planetshine-goodgame'),
				'params'			=> array(
					array(
						"type" => "checkbox",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Post categories", 'planetshine-goodgame'),
						"param_name" => "categories",
						"value" => $post_cats,
						"description" => esc_html__("Check which categories to show in tabs", 'planetshine-goodgame')
					),
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Show first", 'planetshine-goodgame'),
						"param_name" => "show_first",
						"value" => $post_cats,
						"description" => esc_html__("Select which categories to show first. Must be checked in above.", 'planetshine-goodgame')
					),
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Count", 'planetshine-goodgame'),
						"param_name" => "count",
						"value" => 12,
						"description" => esc_html__("How many posts should be shown per category", 'planetshine-goodgame')
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
				'categories' => '',
				'show_first' => '',
				'interval' => 0
			), $atts ) );

			if($interval == 0 || !is_numeric($interval)) { $interval = 'false'; }

			$unique_id = uniqid();

			if(strlen($categories) > 0)
			{
				$categories = explode(',', $categories);
				if(!empty($categories))
				{
					//get first element, if show first is not in list
					if(!in_array($show_first, $categories))
					{
						reset($categories);
						$show_first = array_shift($categories);
					}
					else	//remove the first item from array
					{
						$key = array_search($show_first, $categories);
						unset($categories[$key]);
					}

					$first_cat = get_term_by('slug', $show_first, 'category');

				?>

					<div class="container video-slider dynamic-category-slider">
						<div class="sorting">
							<div class="buttons">
								<?php echo '<a href="#" class="btn btn-sort active" id="obj-' . $unique_id . '-' .  $first_cat->slug . '"><span>' . $first_cat->name . '</span></a>'; ?>
								<?php
								if(!empty($categories))
								{
									foreach($categories as $cat)
									{
										$cat_obj = get_term_by('slug', $cat, 'category');
										echo '<a href="#" class="btn btn-sort" id="obj-' . $unique_id . '-' .  $cat_obj->slug . '"><span>' . $cat_obj->name . '</span></a>';
									}
								}
								?>
							</div>
						</div>

						<?php

						self::single_slider($unique_id, $first_cat, $count, $interval);

						if(!empty($categories))
						{
							foreach($categories as $cat)
							{
								$cat_obj = get_term_by('slug', $cat, 'category');
								self::single_slider_placeholder($unique_id, $cat_obj, $count);
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


		public static function single_slider($unique_id, $category, $count, $interval, $is_ajax = false) {

			global $post, $visible_platform_count;

			/* Post list Query */
			$params = array(
				'category_name' => $category->slug,
			);

			$items = goodgame_get_post_collection($params, $count, 1);
			$chunks = array_chunk($items, 4);

			?>

			<?php if(!$is_ajax) { ?>
			<div id="slider-<?php echo esc_attr($unique_id) . '-' . $category->slug; ?>" class="carousel slide dynamic-slide" data-count="<?php echo esc_attr($count); ?>" data-platform="false" data-slug="<?php echo esc_attr($category->slug); ?>" data-unique_id="<?php echo esc_attr($unique_id); ?>" data-ride="carousel" data-interval="<?php echo esc_attr($interval); ?>">
			<?php } ?>

				<?php if(!empty($chunks) && count($chunks) > 1) : ?>
					<div class="controls right">
						<ol class="carousel-indicators">
							<?php for($i = 0; $i < count($chunks); $i++) { ?>

								<li data-target="#slider-<?php echo esc_attr($unique_id) . '-' . $category->slug; ?>" data-slide-to="<?php echo esc_attr($i); ?>" <?php if($i == 0) { echo 'class="active"'; } ?>></li>

							<?php } ?>
						</ol>

						<a href="#slider-<?php echo esc_attr($unique_id) . '-' . $category->slug; ?>" data-slide="prev" class="btn btn-default"><i class="fa fa-caret-left"></i></a>
						<a href="#slider-<?php echo esc_attr($unique_id) . '-' . $category->slug; ?>" data-slide="next" class="btn btn-default"><i class="fa fa-caret-right"></i></a>
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


		private static function single_slider_placeholder($unique_id, $category, $count)
		{
			?>
			<div id="slider-<?php echo esc_attr($unique_id) . '-' . $category->slug; ?>" class="carousel slide dynamic-slide dynamic-slide-hidden" data-count="<?php echo esc_attr($count); ?>" data-platform="false" data-slug="<?php echo esc_attr($category->slug); ?>" data-unique_id="<?php echo esc_attr($unique_id); ?>" data-ride="carousel" data-interval="false">
				<div class="goodgame-loader"><div class="box"></div><div class="box"></div><div class="box"></div><div class="box"></div></div>
			</div>
			<?php
		}


	}

	//Create instance of VC block
	new GoodGame_Post_Category_Slider();

}
