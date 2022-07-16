<?php get_header(); ?>
<?php wp_reset_postdata(); ?>

<div class="container-fluid page-title">
	<div class="container">
		<div class="title-wrapper">
			<h1><span><s><?php the_title(); ?></s></span></h1>
		</div>
	</div>
</div>


<div class="container main-content-wrapper post-main-wrapper sidebar-<?php echo goodgame_get_sidebar_position(); ?>">

    <div <?php post_class('main-content hentry'); ?>>

		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					?><div class="the-content-container"><?php the_content(); ?></div><?php
				endwhile;
			else :
					echo esc_html_e('no posts found!', 'planetshine-goodgame');
			endif;
		?>

	</div>

	<?php get_sidebar('buddypress'); ?>

</div>

<?php get_footer(); ?>
