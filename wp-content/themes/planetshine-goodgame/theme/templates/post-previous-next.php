<?php
	global $post;
	$items = array();
	$items['prev'] = get_adjacent_post( true, '', true);
	$items['next'] = get_adjacent_post( true, '', false);

	if(!empty($items['next']) || !empty($items['prev'])) :
	?>
	<div class="row">
		<div class="col-md-12 post-block post-image-60 next-previous-wrapper">
			<div class="next-previous<?php if(empty($items['prev']) || empty($items['next'])) { echo ' one-link-only'; } ?>">

				<?php
					if(!empty($items['prev']))
					{
						?> <div class="previous"> <?php
						$post = $items['prev'];
						setup_postdata($post);
						?><div class="tag"><span><?php esc_html_e('Previous', 'planetshine-goodgame'); ?></span></div><?php
						get_template_part('theme/templates/post-previous-next-item');
						?> </div>  <?php
					}
				?>

				<?php
					if(!empty($items['next']))
					{
						?> <div class="next"> <?php
						$post = $items['next'];
						setup_postdata($post);
						?><div class="tag"><span><?php esc_html_e('Next', 'planetshine-goodgame'); ?></span></div><?php
						get_template_part('theme/templates/post-previous-next-item');
						?> </div>  <?php
					}
				?>

			</div>
		</div>
	</div>
	<?php
	wp_reset_postdata();
	endif;
?>
