<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Compact_Post_Columns extends GoodGame_VC_Block_Base {

		public $shortcode = 'compact_post_columns';
		public $classname = 'GoodGame_Compact_Post_Columns';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			//get platforms
			$post_platforms = GoodGameInstance() -> get_all_platforms();
			$post_plats = array('' => '');
			if(!empty($post_platforms))
			{
				foreach($post_platforms as $pp)
				{
					$post_plats[$pp->slug] = $pp->slug;
				}
			}
			
			//get categories
			$post_categories = get_terms('category');
			$post_cats = array('' => '');
			foreach($post_categories as $pc)
			{
				$post_cats[$pc->slug] = $pc->slug;
			}

			return array(
				'name'				=> esc_html__('Compact post columns', 'planetshine-goodgame'),
				'description'		=> esc_html__('Columns with post titles and small thumbnails ', 'planetshine-goodgame'),
				'base'				=> 'compact_post_columns',
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
						"description" => esc_html__("The title for post block", 'planetshine-goodgame')
					),
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Count", 'planetshine-goodgame'),
						"param_name" => "count",
						"value" => 9,
						"description" => esc_html__("How many posts should be shown", 'planetshine-goodgame')
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
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Post platform", 'planetshine-goodgame'),
						"param_name" => "platform",
						"value" => $post_plats,
						"description" => esc_html__("List posts from specific platform", 'planetshine-goodgame')
				   ),
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Post category", 'planetshine-goodgame'),
						"param_name" => "category",
						"value" => $post_cats,
						"description" => esc_html__("List posts from specific category", 'planetshine-goodgame')
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
				'title' => esc_html__('Latest news', 'planetshine-goodgame'),
				'count' => 6,
				'columns' => 3,
				'platform' => NULL,
				'category' => NULL,
			), $atts ) );

			$unique_id = uniqid();


			/* Post List Query */
			$params = array(
				'category_name' => $category,
			);
			$items = goodgame_get_post_collection($params, $count, 1, $platform);

			//get link
			if(!empty($platform))
			{
				$plat = get_term_by('slug', $platform, 'platform');
				if(!empty($plat))
				{
					$view_all = get_category_link($plat->term_id, 'platform');
				}
			}
			elseif(!empty($category))
			{
				$cat = get_category_by_slug($category);
				$view_all = get_category_link($cat->cat_ID);
			}
			else
			{
				if(get_option('show_on_front') == 'page')
				{
					$view_all = get_permalink( get_option( 'page_for_posts' ) );
				}
				else
				{
					$view_all = get_home_url();
				}
			}

			//set bootstrap column size n/12
			$bs_collumn = 4;
			if($columns == 4)
			{
				$bs_collumn = 3;
			}

			if(!empty($items)) :
			?>

				<div class="post-block post-columns post-columns-small">
					<div class="row">
						<div class="col-md-12">

							<div class="title-default">
								<div><span><?php echo esc_html($title); ?></span></div>
									<a href="<?php echo esc_url($view_all); ?>" class="more"><span><?php esc_html_e('View all', 'planetshine-goodgame'); ?></span></a>
							</div>

							<div class="post-block post-image-60">
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
													<?php get_template_part('theme/templates/post-list-item-small'); ?>
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


			<?php endif; ?>

			<?php
			$return = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $return;

		}

	}

	//Create instance of VC block
	new GoodGame_Compact_Post_Columns();

}
