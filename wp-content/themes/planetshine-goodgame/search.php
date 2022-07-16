<?php get_header(); ?>

<!-- Catalog -->
<div class="container-fluid page-title">
    <div class="container">
		<div class="title-wrapper">
			<h1><span><s><?php esc_html_e('Search results', 'planetshine-goodgame'); ?></s></span></h1>
		</div>
    </div>
</div>

<div class="container main-content-wrapper sidebar-<?php echo goodgame_gs('sidebar_position'); ?>">

	<div class="main-content">
	<!-- Blog list -->

		<div class="row">
			<div class="col-md-12 search-query">
				<span><?php esc_html_e('Searched for:', 'planetshine-goodgame');?> "<s><?php echo get_search_query(); ?></s>"</span>
				<div class="legend">
					<a href="#" class="linked-post"><?php global $wp_query; echo esc_attr($wp_query->found_posts);?> <?php esc_html_e('results found', 'planetshine-goodgame'); ?></a>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-xs-12 search-results">

				<?php
				//open wrapper tags
				if(goodgame_gs('blog_item_style') == 'compact_double' || goodgame_gs('blog_item_style') == 'large')
				{
					?><div class="post-block post-image-top"><?php
				}
				else
				{
					?><div class="post-block post-image-300"><?php
				}
				?>

				<?php get_template_part( 'theme/templates/blog-loop'); ?>

				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-xs-12">

				<?php get_template_part( 'theme/templates/pagination' ); ?>

			</div>
		</div>

	</div>

	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
