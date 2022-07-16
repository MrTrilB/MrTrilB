<?php
	$image = goodgame_get_thumbnail('goodgame_post_list_item_medium', true, false);
?>

<div class="row">
	<div class="col-md-12">
		<div class="post-featured">

			<div class="image" <?php if($image) { ?>style="background-image: url(<?php echo esc_url($image); ?>);" <?php } ?>></div>

			<div class="overlay-wrapper">
				<div class="title">

					<?php get_template_part('theme/templates/post-dropdown-platforms-categories'); ?>

					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

					<?php get_template_part('theme/templates/title-legend'); ?>

					<a href="<?php the_permalink(); ?>" class="btn btn-default btn-dark"><?php esc_html_e('Read more', 'planetshine-goodgame'); ?></a>

				</div>

			</div>
		</div>
	</div>
</div>