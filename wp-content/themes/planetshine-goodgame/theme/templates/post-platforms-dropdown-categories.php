<?php
$cats = wp_get_post_categories(get_the_ID());
$plats = GoodGameInstance()->get_post_platforms();

if(!empty($plats) || !empty($cats))
{
	echo '<div class="tags"><div>';

	if(!empty($plats))
	{
		foreach($plats as $plat)
		{
			get_term_link($plat, 'platform');
			echo '<div><a href="' . get_term_link($plat, 'platform') . '" title="' . esc_attr($plat->name) . '" class="tag-default ' . esc_attr('tag-'.$plat->slug) . '"><span>' . $plat->name . '</span></a></div>';
		}
	}

	if(!empty($cats))
	{
		?>
		<div>
			<a href="#" class="show-more"><span><i class="fa fa-plus"></i></span></a>
			<div class="more-dropdown" data-post_id="<?php echo esc_attr(get_the_ID()); ?>">
		<?php

		foreach($cats as $cat)
		{
			$category = get_category($cat);
			$link = get_category_link($category);
			echo '<div><a href="' . esc_url( $link ) . '" title="' . esc_attr($category->name) . '" class="tag-default"><span>' . $category->name . '</span></a></div>';
		}

		echo '</div></div>';
	}

	echo '</div>';

	echo '</div>';
}
