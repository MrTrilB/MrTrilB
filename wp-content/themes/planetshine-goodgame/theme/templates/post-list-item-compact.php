<?php
	$thumb = goodgame_get_thumbnail('goodgame_post_list_item_medium', true, false);
?>

<div class="post">

	<?php if($thumb) : ?>
		<div class="image">

			<?php $post_image_width = goodgame_get_post_image_width(get_the_ID()); ?>

			<?php
				if($post_image_width == 'video_autoplay' || $post_image_width == 'video')
				{
					?><a href="<?php the_permalink(); ?>" class="btn-circle btn-play"></a><?php
				}
			?>

			<?php GoodGameInstance()->get_rating_stars(); ?>

			<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title(); ?>"></a>
		</div>
	<?php endif; ?>

	<div class="title">

		<?php get_template_part('theme/templates/post-dropdown-platforms-categories'); ?>

		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?> <?php if( GoodGameInstance()->is_post_hot(get_the_ID())) { ?><span class="hot"><?php esc_html_e('Hot', 'planetshine-goodgame');?></span><?php } ?></a></h3>

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

		<a href="<?php the_permalink(); ?>" class="btn btn-default"><?php esc_html_e('Read more', 'planetshine-goodgame'); ?></a>
	</div>
</div>
