<?php
/**
 * The template for displaying search forms.
 *
 * @package buddyxpro
 */
?>

<form id="searchform" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">	
	<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'buddyxpro' ); ?></span>
	<input type="search" class="search-field-top" placeholder="<?php echo esc_attr( apply_filters( 'search_placeholder', __( 'Enter Keyword', 'buddyxpro' ) ) ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<input name="submit" type="submit"  value="<?php esc_attr_e( 'Go', 'buddyxpro' ); ?>" />
</form>
