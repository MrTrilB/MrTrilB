<?php
	global $post;
	$thumb = goodgame_get_thumbnail('goodgame_post_list_item_medium', true, true);
	$post_image_width = goodgame_get_post_image_width($post->ID);
?>
<div class="post-block">


	<div class="image">

		<?php
			if($post_image_width == 'video_autoplay' || $post_image_width == 'video')
			{
				?><a href="<?php the_permalink(); ?>" class="btn-circle btn-play"></a><?php
			}
		?>

		<?php GoodGameInstance()->get_rating_stars(); ?>

		<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($thumb); ?>" /></a>
	</div>


	<div class="title">

		<?php get_template_part('theme/templates/post-dropdown-platforms-categories'); ?>

		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<?php get_template_part('theme/templates/title-legend'); ?>

	</div>

</div>
