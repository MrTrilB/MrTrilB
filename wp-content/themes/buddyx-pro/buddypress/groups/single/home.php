<?php
/**
 * BuddyPress - Groups Home
 *
 * @since 3.0.0
 * @version 3.0.0
 */

$bp_nav_style = get_theme_mod( 'buddypress_single_group_nav_style', 'iconic' );
if ( $bp_nav_style == 'iconic' ) {
    $class = 'buddyx-nav-iconic';
}else {
    $class = 'buddyx-default';
}

$bp_nav_view = get_theme_mod( 'buddypress_single_group_nav_view', 'swipe' );
if ( $bp_nav_view == 'swipe' ) {
    $nav_view = 'buddyx-nav-swipe';
}else {
    $nav_view = 'buddyx-nav-more';
}

if ( bp_has_groups() ) :
	while ( bp_groups() ) :
		bp_the_group();
	?>

		<?php bp_nouveau_group_hook( 'before', 'home_content' ); ?>

		<div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups" class="groups-header single-headers">

			<?php bp_nouveau_group_header_template_part(); ?>

        </div><!-- #item-header -->
        
        <div class="container">
            <div class="site-wrapper group-home <?php echo esc_attr( $class); ?> <?php echo esc_attr( $nav_view); ?>">
                <div class="bp-wrap">

                    <?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

                        <?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>

                    <?php endif; ?>

                    <div id="item-body" class="item-body">

                        <?php bp_nouveau_group_template_part(); ?>

                    </div><!-- #item-body -->

                </div><!-- // .bp-wrap -->
                <?php if ( is_active_sidebar( 'single_group' ) && bp_is_group() ) : ?>
                    <aside id="secondary" class="primary-sidebar widget-area">
                        <div class="sticky-sidebar">
                            <?php dynamic_sidebar( 'single_group' ); ?>
                        </div>
                    </aside>
                <?php endif; ?>
        </div><!-- .site-wrapper -->
    </div><!-- .container -->

		<?php bp_nouveau_group_hook( 'after', 'home_content' ); ?>

	<?php endwhile; ?>

<?php
endif;
