<?php get_header(); ?>
<?php wp_reset_postdata(); ?>

<div class="container-fluid page-title">
	<div class="container">
		<div class="title-wrapper">
			<h1><span><s><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></s></span></h1>
		</div>
	</div>
</div>

<div class="container main-content-wrapper post-main-wrapper sidebar-<?php echo goodgame_get_sidebar_position(); ?>">

    <div <?php post_class('main-content hentry'); ?>>

		<?php the_content(); ?>

	</div>

	<?php get_sidebar('forum'); ?>

</div>

<?php get_footer(); ?>
