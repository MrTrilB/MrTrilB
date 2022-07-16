<?php if(class_exists('GoodGame_Status')) : ?>

<div class="section-item clearfix checklist" id="setup_checklist">
	<h3>Theme setup checklist</h3>

	<?php
	$checklist = GOODGAME_STATUS()->getChecklist();
	$progress = 0;

	if(!empty($checklist))
	{
	?>
		<ul>
			<?php foreach($checklist as $key => $item) : ?>
			<li class="checklist-item <?php echo ($item['status'] ? 'completed' : 'todo'); ?>">
				<div class="check-status">
					<?php
					if($item['status'])
					{
						echo '<div class="checker"><span class="checked"></span></div>';
						echo '<span>Completed</span>';
						$progress++;
					}
					else
					{
						echo '<div class="checker"><span class=""></span></div>';
						echo '<span>To do</span>';
					}
					?>
				</div>
				<h4><?php echo esc_html($item['name']); ?></h4>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php
		$progress = round($progress/count($checklist), 2);
	}
	?>

	<div id="status-progress" data-progress="<?php echo esc_attr($progress); ?>"></div>
</div>

<div class="section-item clearfix checklist" id="plugin_status">
	<h3>Plugin status</h3>

	<ul>
		<?php

		$plugins = GoodGameInstance()->get_bunlded_plugins();

		foreach($plugins as $plugin)
		{
			$active = false;
            if (class_exists($plugin['class'])) {
                $active = true;
            }
			?>
			<li class="checklist-item plugin-item <?php echo ($active ? 'completed' : 'todo'); ?>">
				<div class="check-status">
					<?php
					if($active)
					{
						echo '<div class="checker"><span class="checked"></span></div>';
						echo '<span>Installed</span>';
					}
					else
					{
						echo '<div class="checker"><span class=""></span></div>';
						echo '<span>Not installed</span>';
					}
					?>
				</div>
				<h4><?php echo esc_html($plugin['name']) . ' ' . ($plugin['required'] ? '<span>(required)</span>' : '') ?></h4>
			</li>
			<?php
		}
		?>
	</ul>
</div>

<div class="section-item clearfix" id="version">
	<h3>Version</h3>
	<?php
	echo '<p>Theme version: <strong>' . goodgame_gs('theme_version') . '</strong></p>';

	echo '<p>Wordpress version: <strong>' . get_bloginfo('version') . '</strong></p>';
	?>
</div>

<?php else: ?>
<?php var_dump($checklist); ?>
<?php echo '<div class="section-item"><h4>' . esc_html__('Please active the "Planetshine GoodGame Theme Extension" plugin to enable the status page', 'planetshine-goodgame') . '</h4></div>'; ?>

<?php endif; ?>
