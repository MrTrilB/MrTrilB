<?php
    $cats = wp_get_post_categories(get_the_ID());
	$plats = GoodGameInstance()->get_post_platforms();

    if(!empty($cats) || !empty($plats))
    {
        echo '<div class="tags"><div>';

		if(!empty($plats))
		{
			foreach($plats as $plat )
			{
				get_term_link($plat, 'platform');
				echo '<div><a href="' . get_term_link($plat, 'platform') . '" title="' . esc_attr($plat->name) . '" class="tag-default ' . esc_attr('tag-'.$plat->slug) . '"><span>' . $plat->name . '</span></a></div>';
			}
		}

		if(!empty($cats))
		{
			foreach($cats as $cat )
			{
				$category = get_category($cat);
				$link = get_category_link($category);
				echo '<div><a href="' . esc_url( $link ) . '" title="' . esc_attr($category->name) . '" class="tag-default"><span>' . $category->name . '</span></a></div>';
			}
		}

		echo '</div>';

		echo '</div>';
    }
?>
