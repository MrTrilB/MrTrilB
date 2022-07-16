<?php
	global $post_list_order, $post;

	if (empty($post_list_order)) $post_list_order = 1;
	else $post_list_order++;

	$thumb = goodgame_get_thumbnail('goodgame_post_list_item_medium', true, false);
	$platform = GoodGameInstance()->get_platform($post->platform_id);
?>


<span class="count"><?php echo esc_html($post_list_order); ?></span>

<?php GoodGameInstance()->get_rating_stars(false, '', true); ?>

<div class="title">
	<div class="tags">
		<div>
			<div><a href="<?php echo esc_url(get_term_link( $platform )); ?>" title="<?php echo esc_attr($platform->name); ?>" class="tag-default"><span><?php echo esc_html($platform->name); ?></span></a></div>
		</div>
	</div>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
</div>

<div class="image"<?php if($thumb) : ?> style="background-image: url(<?php echo esc_url($thumb); ?>)"<?php endif ?>></div>

<div class="overlay"></div>
<div class="overlay-hover"></div>

