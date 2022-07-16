<?php get_header(); ?>

<div class="container-fluid page-title">
	<div class="container">
		<div class="title-wrapper">
			<h1><span><s><a href="<?php the_permalink(); ?>"><?php woocommerce_page_title(); ?></a></s></span></h1>
		</div>
	</div>
</div>


<?php
    $class = 'full-width';
    $has_sidebar = false;
    if(
        ((is_shop() || is_product_category()) && goodgame_gs('show_shop_sidebar') == 'on')
        ||
        (is_product() && goodgame_gs('show_product_sidebar') == 'on')
    )
    {
        $class = '';
        $has_sidebar = true;
    }
?>

<div class="container main-content-wrapper post-main-wrapper <?php if($has_sidebar) { echo 'sidebar-' . goodgame_get_sidebar_position(); } else { echo 'sidebar-disabled'; } ?>">

	<div <?php post_class('main-content hentry'); ?>>

		<?php woocommerce_content(); ?>

	</div>

	<?php
    if($has_sidebar)
    {
        get_sidebar();
    }
    ?>

</div>

<?php get_footer(); ?>
