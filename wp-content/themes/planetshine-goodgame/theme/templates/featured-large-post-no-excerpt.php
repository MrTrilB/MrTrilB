<?php
	$image = goodgame_get_thumbnail('goodgame_featured_list_item_large', true, true);
?>
<div class="post-featured no-animation" <?php if($image) { ?>style="background-image: url(<?php echo esc_url($image); ?>);" <?php } ?>>
	<div class="overlay-wrapper">
		<div class="title">

			<?php get_template_part('theme/templates/post-dropdown-platforms-categories'); ?>

			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

			<?php get_template_part('theme/templates/title-legend'); ?>

			<a href="<?php the_permalink(); ?>" class="btn btn-default btn-dark"><?php esc_html_e('Read more', 'planetshine-goodgame'); ?></a>
		</div>
		<div class="overlay" <?php if($image) { ?>style="background-image: url(<?php echo esc_url($image); ?>);"<?php } ?>></div>
	</div>
</div>
