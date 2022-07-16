<?php
if(goodgame_pagination_exists())
{
	$pages = goodgame_get_pagination();
	?>
		<div class="pagination-wrapper">
			<ul class="pagination">
				<li class="controls<?php if(goodgame_get_current_page_num() == 1) { echo ' disabled'; } ?>"><a href="<?php echo esc_url(get_pagenum_link(1)); ?>"><i class="fa fa-step-backward"></i></a></li>
				<li class="controls<?php if(goodgame_get_current_page_num() == 1) { echo ' disabled'; } ?>"><a href="<?php echo esc_url(goodgame_get_prev_page_link()) ?>"><i class="fa fa-caret-left"></i></a></li>
				<?php
				foreach($pages as $page)
				{
					echo '<li>' . $page . '</li>';
				}
				?>
				<li class="controls<?php if(goodgame_get_current_page_num() == goodgame_get_max_pages()) { echo ' disabled'; } ?>"><a href="<?php echo esc_url(goodgame_get_next_page_link()) ?>"><i class="fa fa-caret-right"></i></a></li>
				<li class="controls<?php if(goodgame_get_current_page_num() == goodgame_get_max_pages()) { echo ' disabled'; } ?>"><a href="<?php echo esc_url(get_pagenum_link(goodgame_get_max_pages())); ?>"><i class="fa fa-step-forward"></i></a></li>
			</ul>
		</div>
	<?php
}
?>
