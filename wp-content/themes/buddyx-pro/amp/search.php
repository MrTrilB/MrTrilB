<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

get_template_part( 'amp/header');

buddyxpro()->print_styles( 'buddyxpro-content' );

?>
	<div class="amp-container">
    <div class="amp-site-wrapper">

		<main id="primary" class="site-main">
			<?php
			if ( have_posts() ) {

				get_template_part( 'template-parts/content/page_header' );

				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content/entry', get_post_type() );
				}

				get_template_part( 'template-parts/content/pagination' );
			} else {
				get_template_part( 'template-parts/content/error' );
			}
			?>
		</main><!-- #primary -->
		<?php get_sidebar();?>

	</div>
	</div>
<?php
get_template_part( 'amp/footer'); ?>
