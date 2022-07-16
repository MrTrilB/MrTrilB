<?php

	$cats = wp_get_post_categories(get_the_ID());

    if(!empty($cats))
    {
		$cats_count = count($cats);

        echo '<div class="tags"><div>';

		if($cats_count > 1)
		{
			$first_cat = array_slice($cats, 0, 1);
			$cats = array_slice($cats, 1);

			$category = get_category($first_cat[0]);
			$link = get_category_link($category);
			echo '<div><a href="' . esc_url( $link ) . '" title="' . esc_attr($category->name) . '" class="tag-default"><span>' . $category->name . '</span></a></div>';

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
		else
		{
			$category = get_category($cats[0]);
			$link = get_category_link($category);
			echo '<div><a href="' . esc_url( $link ) . '" title="' . esc_attr($category->name) . '" class="tag-default"><span>' . $category->name . '</span></a></div>';
		}

		echo '</div>';

		echo '</div>';
    }
?>
