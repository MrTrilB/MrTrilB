<?php
	global $visible_platform_count;

	$visible_count = (!empty($visible_platform_count) && intval($visible_platform_count) > 0) ? intval($visible_platform_count) : 4;
	$visible_platform_count = NULL;
	$cats = wp_get_post_categories(get_the_ID());
	$cats_count = (!empty($cats)) ? count($cats) : -1;
	$plats = GoodGameInstance()->get_post_platforms();
	$plats_count = (!empty($plats)) ? count($plats) : -1;
	$all_count = 0;

	if($cats_count > 0) $all_count += $cats_count;
	if($plats_count > 0) $all_count += $plats_count;

    if( $all_count > 0 )
    {
		echo '<div class="tags"><div>';

		if ($all_count > $visible_count)
		{
			$i = 0;
			if($plats_count > 0)
			{
				foreach($plats as $plat)
				{
					if($i == $visible_count)
					{ ?>
						<div>
							<a href="#" class="show-more"><span><i class="fa fa-plus"></i></span></a>
							<div class="more-dropdown" data-post_id="<?php echo esc_attr(get_the_ID()); ?>">
					<?php }



					get_term_link($plat, 'platform');
					echo '<div><a href="' . get_term_link($plat, 'platform') . '" title="' . esc_attr($plat->name) . '" class="tag-default ' . esc_attr('tag-'.$plat->slug) . '"><span>' . $plat->name . '</span></a></div>';

					$i++;
				}
			}
			if($cats_count > 0)
			{
				foreach($cats as $cat )
				{
					if($i == $visible_count)
					{ ?>
						<div>
							<a href="#" class="show-more"><span><i class="fa fa-plus"></i></span></a>
							<div class="more-dropdown" data-post_id="<?php echo esc_attr(get_the_ID()); ?>">
					<?php }
					$category = get_category($cat);
					$link = get_category_link($category);
					echo '<div><a href="' . esc_url( $link ) . '" title="' . esc_attr($category->name) . '" class="tag-default"><span>' . $category->name . '</span></a></div>';
					$i++;
				}
			}

			echo '</div></div>';
		}
		else
		{
			if($plats_count > 0)
			{
				foreach($plats as $plat)
				{
					get_term_link($plat, 'platform');
					echo '<div><a href="' . get_term_link($plat, 'platform') . '" title="' . esc_attr($plat->name) . '" class="tag-default ' . esc_attr('tag-'.$plat->slug) . '"><span>' . $plat->name . '</span></a></div>';
				}
			}

			if($cats_count > 0)
			{
				foreach($cats as $cat)
				{
					$category = get_category($cat);
					$link = get_category_link($category);
					echo '<div><a href="' . esc_url( $link ) . '" title="' . esc_attr($category->name) . '" class="tag-default"><span>' . $category->name . '</span></a></div>';
				}
			}
		}

		echo '</div>';


		echo '</div>';

    }

?>
