<?php

	$thumb = goodgame_get_thumbnail('goodgame_post_list_item_medium', true, false);
?>

<div class="post-block post-image-90">
	<div class="post">

		<?php if($thumb) : ?>
			<div class="image">
				<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title(); ?>"></a>
			</div>
		<?php endif; ?>

		<div class="title">
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?> <?php if( GoodGameInstance()->is_post_hot(get_the_ID())) { ?><span class="hot"><?php esc_html_e('Hot', 'planetshine-goodgame'); ?></span><?php } ?></a></h3>
			<?php get_template_part('theme/templates/title-legend'); ?>
		</div>
	</div>
</div>
