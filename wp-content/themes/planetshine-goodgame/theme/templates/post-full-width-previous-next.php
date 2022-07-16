<?php
	global $post;
	$items = array();
	$items['prev'] = get_adjacent_post( true, '', true);
	$items['next'] = get_adjacent_post( true, '', false);

	if(!empty($items['next']) || !empty($items['prev'])) :
	?>
	<div class="controls prev-next-post-controls<?php if(empty($items['prev']) || empty($items['next'])) { echo ' one-link-only'; } ?>">
		<?php
			if(!empty($items['prev']))
			{
				?><a href="<?php echo get_permalink( $items['prev'] ); ?>" class="previous"><span><?php esc_html_e('Previous post', 'planetshine-goodgame'); ?></span></a><?php
			}
			if(!empty($items['next']))
			{
				?><a href="<?php echo get_permalink( $items['next'] ); ?>" class="next"><span><?php esc_html_e('Next post', 'planetshine-goodgame'); ?></span></a><?php
			}
		?>
	</div>
	<?php
	endif;
?>
