<?php

	$thumb = goodgame_get_thumbnail('goodgame_post_list_item_small', true, false);
?>
<div class="post">
	<div class="overlay-wrapper text-overlay">
		<div class="content">
			<div>
				<a href="<?php the_permalink(); ?>" class="btn btn-default btn-dark"><?php esc_html_e('Read more', 'planetshine-goodgame'); ?></a>
			</div>
		</div>

		<?php if($thumb) : ?><div class="overlay" style="background-image: url(<?php echo esc_url($thumb); ?>);"></div><?php endif; ?>
	</div>

	<?php if($thumb) : ?>
		<div class="image">
			<a href="<?php the_permalink(); ?>">
				<?php
				$post_image_width = goodgame_get_post_image_width($post->ID);
				if($post_image_width == 'video' || $post_image_width == 'video_autoplay')
				{
					?><span class="btn-circle btn-play"></span><?php
				}
				?>
				<img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title(); ?>">
			</a>
		</div>
	<?php endif; ?>

	<div class="title">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?> <?php if( GoodGameInstance()->is_post_hot(get_the_ID())) { ?><span class="hot"><?php esc_html_e('Hot', 'planetshine-goodgame');?></span><?php } ?></a></h3>
	</div>
</div>
