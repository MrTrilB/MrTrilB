<?php
	global $post;
	$thumb = goodgame_get_thumbnail('goodgame_post_single_full_screen', true, false);

	if($thumb) : ?>

	<div class="container-fluid page-title page-title-post-featured post-page-title post-block">
		<div class="container-fluid">
			<div class="featured-post-content post-featured" style="background-image: url(<?php echo esc_url($thumb); ?>)">
				<div class="overlay-wrapper">

					<div class="title">

						<?php get_template_part('theme/templates/post-categories'); ?>

						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<?php get_template_part('theme/templates/title-legend'); ?>

						<div class="intro">
							<p><?php echo goodgame_excerpt(); ?></p>
						</div>
					</div>
					<div class="overlay" style="background-image: url(<?php echo esc_url($thumb); ?>)"></div>
				</div>
			</div>
		</div>


		<?php get_template_part('theme/templates/post-full-width-previous-next'); ?>

	</div>


	<?php endif; ?>
