<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Post_List_With_Heading extends GoodGame_VC_Block_Base {

		public $shortcode = 'post_list_with_heading';
		public $classname = 'GoodGame_Post_List_With_Heading';	//for 5.2 compatibility.

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
				'name'				=> esc_html__('Post list with Heading', 'planetshine-goodgame'),
				'description'		=> esc_html__('Large featured post followed by list of smaller posts', 'planetshine-goodgame'),
				'base'				=> 'post_list_with_heading',
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
						"value" => 8,
						"description" => esc_html__("How many posts should be shown", 'planetshine-goodgame')
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
			global $visible_platform_count;

			extract( shortcode_atts( array(
				'title' => esc_html__('Latest news', 'planetshine-goodgame'),
				'count' => 6,
				'platform' => NULL,
				'category' => NULL,
			), $atts ) );

			$unique_id = uniqid();

			/* Featured Post Query */
			$params = array(
				'category_name' => $category,
				'meta_key' => 'is_featured',
				'meta_value' => 'on'
			);

			$skip_id = array();
			$featured = goodgame_get_post_collection($params, 1, 1, $platform);
			if(!empty($featured))   //if featured post found, reduce the overal count
			{
				$featured = $featured[0];
				$count--;
				$skip_id[] = $featured->ID;
			}

			/* Post List Query */
			$params = array(
				'category_name' => $category,
				'post__not_in' => $skip_id
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
				if(!empty($cat))
				{
					$view_all = get_category_link($cat->cat_ID);
				}
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

			//if featured not found, take the first from items
			if(empty($featured) && !empty($items))
			{
				$featured = array_shift($items);
			}

			if(!empty($featured) || !empty($items)) :
			?>
				<div class="post-block post-image-60 post-list-with-heading">

					<div class="title-default">
						<div><span><?php echo esc_html($title); ?></span></div>
					</div>

					<?php
					if(!empty($featured))
					{
						$post = $featured;
						setup_postdata($post);
						$visible_platform_count = 1;
						echo '<div class="post-block heading-block">';
						get_template_part('theme/templates/featured-medium-post');
						echo '</div>';
					}
					?>

					<?php if(!empty($items)) : ?>

							<?php foreach($items as $post) : ?>

								<div class="row">
									<div class="col-md-12">
										<?php
										setup_postdata($post);
										get_template_part('theme/templates/post-list-item-small');
										?>
									</div>
								</div>

							<?php endforeach; ?>

					<?php endif; ?>

					<div class="more">
						<a href="<?php echo esc_url($view_all); ?>" class="btn btn-default"><?php esc_html_e('View more games', 'planetshine-goodgame'); ?></a>
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
	new GoodGame_Post_List_With_Heading();

}
