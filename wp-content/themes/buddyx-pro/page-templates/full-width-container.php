<?php
/**
 * Template Name: Page No Sidebar
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

get_header();

buddyxpro()->print_styles( 'buddyxpro-content' );
buddyxpro()->print_styles( 'buddyxpro-sidebar', 'buddyxpro-widgets' );

?>	
	<?php do_action( 'buddyx_sub_header' ); ?>
	
	<?php do_action( 'buddyx_before_content' ); ?>

		<main id="primary" class="site-main">
			<?php
			if ( have_posts() ) {

				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content/entry', 'full-width' );
				}
				
			} else {
				get_template_part( 'template-parts/content/error' );
			}
			?>
		</main><!-- #primary -->
	</div><!-- .container -->

	<?php do_action( 'buddyx_after_content' ); ?>
<?php
get_footer();
