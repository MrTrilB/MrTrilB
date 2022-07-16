<?php get_header(); ?>

<div class="container page-not-found">
	<h6>404</h6>
	<h3><?php esc_html_e('Page not found', 'planetshine-goodgame'); ?></h3>
	<p><?php esc_html_e('The page you are looking for could have been deleted, or has never existed.', 'planetshine-goodgame'); ?></p>
	<p><?php esc_html_e('You can go back', 'planetshine-goodgame');?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Home', 'planetshine-goodgame'); ?></a> <?php esc_html_e('or try to search something else', 'planetshine-goodgame'); ?></p>
</div>

<?php get_footer(); ?>
