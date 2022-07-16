<?php
/**
 * Template part for displaying a pagination
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

the_posts_pagination(
	[
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'buddyxpro' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'buddyxpro' ),
		'screen_reader_text' => __( 'Page navigation', 'buddyxpro' ),
	]
);
