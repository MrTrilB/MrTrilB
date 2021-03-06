<?php


if(goodgame_gs('show_header_login') == 'on')
{
	get_template_part( 'theme/templates/login-lightbox');
}
if(is_singular('gallery'))
{
	get_template_part( 'theme/templates/gallery-lightbox');
}
if(goodgame_gs('show_menu_search') == 'on')
{
	get_template_part( 'theme/templates/search-lightbox');
}
?>


<div class="focus">

<?php if(goodgame_gs('show_header_dock') == 'on') : ?>
	<div class="container-fluid dock">
		<div class="container">

			<?php
				if(goodgame_gs('show_trending') == 'on')
				{
					get_template_part( 'theme/templates/trending-news');
				}
			?>

			<div class="social-login">

				<div class="social">
					<?php
					if(goodgame_gs('show_header_social') == 'on')
					{
						get_template_part( 'theme/templates/social-icons');
					}
					?>
				</div>

				<?php if(goodgame_gs('show_header_login') == 'on') : ?>
					<div class="login">
						<?php if(is_user_logged_in()) :
							$user = wp_get_current_user();
							?>
							<span><?php echo esc_attr($user->user_login); ?></span>
							<a href="<?php echo wp_logout_url(get_home_url()); ?>" class="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php echo esc_html_e('Sign out', 'planetshine-goodgame'); ?></a>
						<?php else: ?>
							<a href="#" class="show-lightbox"><?php esc_html_e('Account', 'planetshine-goodgame'); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
<?php endif; ?>

<?php
	$header_order_list = array(
		'logo_left_banner_middle_right_custom'	=> array('logo', 'banner', 'shortcode'),
		'logo_left_shortcode_middle_banner_right' => array('logo', 'shortcode', 'banner'),
		'logo_left_banner_right'				=> array('logo', 'banner'),
		'logo_left_shortcode_right'				=> array('logo', 'shortcode'),
		'logo_middle'							=> array('logo'),
		'banner_left_logo_right'				=> array('banner', 'logo'),
		'shortcode_left_logo_right'				=> array('shortcode', 'logo'),
	);

	$header_layout = $header_order_list[goodgame_gs('header_layout')];
?>

<div class="container header header-items-<?php echo esc_attr(count($header_layout)); ?>">
	<?php
		foreach($header_layout as $key => $hl)
		{
			$item_class = 'header_item ';

			if(count($header_layout) == 1)
			{
				$item_class .= 'single_centered';
			}
			else if($key == 0)
			{
				$item_class .= 'left';
			}
			else if(count($header_layout) > 2 && $key == 1)
			{
				$item_class .= 'middle';
			}
			else
			{
				$item_class .= 'right';
			}

			if($hl == 'logo')
			{
				if(goodgame_gs('use_image_logo') == 'image_logo') {
					?>
					<div class="logo-1 <?php echo esc_attr($item_class); ?>">
						<a href="<?php echo home_url('/'); ?>"><img src="<?php echo esc_url(goodgame_get_attachment_src(goodgame_gs('logo_image'))); ?>" alt="<?php esc_attr(goodgame_gs('logo_image_alt')); ?>" <?php if(goodgame_gs('logo_max_width') != 'none') { echo 'style="max-width: ' . esc_attr(goodgame_gs('logo_max_width')) . ';"'; } ?>></a>
					</div>
					<?php
				} else {
					?>
					<div class="logo-1 <?php echo esc_attr($item_class); ?>" <?php if(goodgame_gs('logo_max_width') != 'none') { echo 'style="max-width: ' . esc_attr(goodgame_gs('logo_max_width')) . ';"'; } ?>>
						<a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
						<p><?php bloginfo('description'); ?></p>
					</div>
					<?php
				}
			}
			else if($hl == 'banner')
			{
				echo $banner = goodgame_get_banner_by_location('header_ad', $item_class);
			}
			else if($hl == 'shortcode')
			{
				?>
				<div class="<?php echo esc_attr($item_class); ?>">
					<?php
						echo do_shortcode(goodgame_gs('header_shortcode'));
					?>
				</div>
				<?php
			}
		}
	?>
</div>


<?php get_template_part('theme/templates/menu'); ?>
