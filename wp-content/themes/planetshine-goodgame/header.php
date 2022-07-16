<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

	<!-- BEGIN head -->
	<head>
        <!-- Meta tags -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="<?php bloginfo( 'charset' ); ?>">

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
            {
                wp_enqueue_script( 'comment-reply' );
            }
		?>

        <?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
        <?php
        if(function_exists('wp_body_open')) {
            wp_body_open();
        }
        ?>
        <?php get_template_part('theme/templates/header'); ?>
