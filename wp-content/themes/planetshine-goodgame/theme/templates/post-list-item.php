<div class="item">
	<?php
		$image = goodgame_get_thumbnail('goodgame_product_single', true, false);
        if($image)
        {
            ?>
				<div class="image">
					<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_attr($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" /></a>
				</div>
            <?php
        }
    ?>
	<div class="text">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php get_template_part('theme/templates/title-legend'); ?>
		<div class="intro">
			<p><?php echo goodgame_excerpt(30); ?></p>
			<p><a href="<?php the_permalink(); ?>" class="link-continue"><?php esc_html_e('Continue reading', 'planetshine-goodgame'); ?></a></p>
		</div>
	</div>
</div>
