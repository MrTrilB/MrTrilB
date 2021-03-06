<?php
/**
 * Template part for displaying a post's featured image
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

$post_layout = get_theme_mod( 'blog_layout_option', buddyx_defaults( 'blog-layout-option' ) );

if ( $post_layout == 'grid-layout' ) {
	$blog_grid_columns = get_theme_mod( 'blog_grid_columns', buddyx_defaults( 'blog-grid-columns' ) );
	if ( $blog_grid_columns == 'two-column' ) {
		$thumbnail_size = 'buddyxpro-col-two';
	} else {
		$thumbnail_size = 'buddyxpro-featured';
	}
} elseif ( $post_layout == 'masonry-layout' ) {
	$post_per_row = get_theme_mod( 'post_per_row', buddyx_defaults( 'post-per-row' ) );

	if ( $post_per_row == 'buddyx-masonry-2' ) {
		$thumbnail_size = 'buddyxpro-col-two';
	} else {
		$thumbnail_size = 'buddyxpro-col-three';
	}
} elseif ( $post_layout == 'list-layout' ) {
	$thumbnail_size = 'buddyxpro-list';
} else {
	$thumbnail_size = 'buddyxpro-featured';
}

// Audio or video attachments can have featured images, so they need to be specifically checked.
$support_slug = get_post_type();
if ( 'attachment' === $support_slug ) {
	if ( wp_attachment_is( 'audio' ) ) {
		$support_slug .= ':audio';
	} elseif ( wp_attachment_is( 'video' ) ) {
		$support_slug .= ':video';
	}
}

if ( post_password_required() || ! post_type_supports( $support_slug, 'thumbnail' ) || ! has_post_thumbnail() ) {
	return;
}
?>
<div class="buddyx-post-thumbnail entry-post-thumbnail">
	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
		global $wp_query;
		if ( 0 === $wp_query->current_post ) {
			the_post_thumbnail(
				$thumbnail_size,
				[
					'class' => 'skip-lazy',
					'alt'   => the_title_attribute(
						[
							'echo' => false,
						]
					),
				]
			);
		} else {
			the_post_thumbnail(
				$thumbnail_size,
				[
					'alt' => the_title_attribute(
						[
							'echo' => false,
						]
					),
				]
			);
		}
		?>
	</a><!-- .post-thumbnail -->
</div><!-- .post-thumbnail -->


