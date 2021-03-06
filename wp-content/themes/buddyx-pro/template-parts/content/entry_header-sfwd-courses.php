<?php
/**
 * Template part for displaying a post's header
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

?>

<header class="entry-header">
	<?php get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() ); ?>

	<div class="post-categories"><?php the_category( ' ' ); ?></div>
	
	<?php if ( ! is_singular() ) { 
		get_template_part( 'template-parts/content/entry_title', get_post_type() );
	}

	get_template_part( 'template-parts/content/entry_meta', get_post_type() );
	?>
</header><!-- .entry-header -->
