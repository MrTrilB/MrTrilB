<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

get_header();

buddyxpro()->print_styles( 'buddyxpro-content' );
buddyxpro()->print_styles( 'buddyxpro-sidebar', 'buddyxpro-widgets' );

$ld_sidebar = get_theme_mod( 'ld_sidebar_option', buddyx_defaults( 'ld-sidebar-option' ) );

$post_layout = get_theme_mod( 'blog_layout_option', buddyx_defaults( 'blog-layout-option' ) );

?>

<div class="site-sub-header">
    <div class="container">

        <?php get_template_part( 'template-parts/content/page_header' ); ?>

        <?php 
        $breadcrumbs = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );

        if ( ! empty( $breadcrumbs ) ) {
            buddyx_the_breadcrumb();
        }
        ?>
        
    </div>
</div>
	
	<?php do_action( 'buddyx_before_content' ); ?>

	<?php if ( $ld_sidebar == 'left' || $ld_sidebar == 'both' ) : ?>
		<aside id="secondary" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyxpro()->display_ld_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>
	
	<main id="primary" class="site-main">

	<div class="courses-searching">
		<?php get_buddyx_ld_course_search_form(); ?>
	</div>
		
		<?php
		if ( have_posts() ) {

			$classes = get_body_class();
			if(in_array('blog',$classes) || in_array('archive',$classes) || in_array('search',$classes)){ 
			
				$args = [ 'post_layout'		=> $post_layout,
						  'body_class'		=> $classes
						];
				get_template_part( 'template-parts/layout/entry', $post_layout, $args );
				
			} else {
				while ( have_posts() ) {
					the_post();
	
					get_template_part( 'template-parts/content/entry', get_post_type() );
				}
			}

			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>

	</main><!-- #primary -->
	
		<?php if ( $ld_sidebar == 'right' || $ld_sidebar == 'both' ) : ?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyxpro()->display_ld_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>

	<?php do_action( 'buddyx_after_content' ); ?>
<?php
get_footer();
