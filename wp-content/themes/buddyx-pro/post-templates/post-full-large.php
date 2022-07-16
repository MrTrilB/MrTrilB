<?php
/**
 * Template Name: Full Width - Large container
 *
 * Template Post Type: post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

get_header();

buddyxpro()->print_styles( 'buddyxpro-content' );
buddyxpro()->print_styles( 'buddyxpro-sidebar', 'buddyxpro-widgets' );

?>
	
<div class="single-post-main-wrapper buddyx-content--large">

	<?php do_action( 'buddyx_sub_header' ); ?>

	<?php 	
	if ( get_post_type() == 'post') {
		get_template_part( 'template-parts/content/entry-header', get_post_type() ); 
	}
	?>
	
	<?php do_action( 'buddyx_before_content' ); ?>
	
	<main id="primary" class="site-main">
		
		<?php
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', get_post_type() );
			
		}
		?>

	</main><!-- #primary -->

	<?php do_action( 'buddyx_after_content' ); ?>

</div>
<?php
get_footer();