<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
	class GoodGame_Exclusive_Post extends GoodGame_VC_Block_Base {

		public $shortcode = 'exclusive_post';
		public $classname = 'GoodGame_Exclusive_Post';	//for 5.2 compatibility.

		/*
		 * Return parameters
		 */
		public function getParams() {

			return array(
				'name'				=> esc_html__('Exclusive post', 'planetshine-goodgame'),
				'description'		=> esc_html__('Embed a specially styled singe post block', 'planetshine-goodgame'),
				'base'				=> 'exclusive_post',
				"content_element"	=> true,
				'class'				=> '',
				'category'			=> esc_html__('GoodGame', 'planetshine-goodgame'),
				'params'			=> array(

					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Post ID", 'planetshine-goodgame'),
						"param_name" => "post_id",
						"value" => '',
						"description" => esc_html__("The ID of the post you wish to display", 'planetshine-goodgame')
				    ),
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						"heading" => esc_html__("Image position", 'planetshine-goodgame'),
						"param_name" => "image_position",
						"value" => array('Left' => 'left', 'Right' => 'right'),
						"description" => esc_html__("Which side should the image be on", 'planetshine-goodgame')
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
				'post_id' => 1,
				'image'	=> '',
				'first' => 0,	//set automatically
				'image_position' => 'left'
			), $atts ) );

			$post = get_post($post_id);
			setup_postdata($post);

			if($post) :

				$thumb = goodgame_get_thumbnail('goodgame_post_single_medium', true, false);
				?>

				<div class="title-default">
					<div><span><?php esc_html_e('Exclusives', 'planetshine-goodgame'); ?></span></div>
				</div>

				<div class="post-block post-exclusive">
					<div class="col-md-12">
						<div class="post">

							<?php if($thumb) : ?>
								<div class="image" style="background-image: url(<?php echo esc_url($thumb); ?>);"></div>
							<?php endif; ?>

							<div class="text">
								<div class="title">

									<?php GoodGameInstance()->get_rating_stars(); ?>

									<?php get_template_part('theme/templates/post-dropdown-platforms-categories'); ?>

									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?> <?php if( GoodGameInstance()->is_post_hot(get_the_ID())) { ?><span class="hot"><?php esc_html_e('Hot', 'planetshine-goodgame'); ?></span><?php } ?></a></h3>
									<?php get_template_part('theme/templates/title-legend'); ?>

									<div class="intro">
										<?php
											if(has_excerpt())
											{
												 the_excerpt();
											}
											elseif(goodgame_gs('force_post_excerpt') == 'on')
											{
												 echo wpautop(goodgame_excerpt(100));
											}
											else
											{
												 the_content('');
											}
											?>
									</div>

									<a href="<?php the_permalink(); ?>" class="btn btn-default btn-dark"><?php esc_html_e('Read more', 'planetshine-goodgame'); ?></a>

								</div>
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
	new GoodGame_Exclusive_Post();

}
