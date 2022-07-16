<?php
/***** Background *****/

$default = str_replace(array('https:', 'http:'), '', goodgame_gs('gg_background_image'));
$mod = get_theme_mod('gg_background_image', $default);
if ( ! empty( $mod ) )
{
	goodgame_generate_css('body', 'background-image', 'gg_background_image', 'url(', ') !important' );
}
else
{
	echo 'body { background-image: none; }' . "\n";
}

//repeat
goodgame_generate_css('body', 'background-repeat', 'gg_background_repeat', '', '!important' );

//if repeat is off, then stretch the image
$mod = get_theme_mod('gg_background_repeat', goodgame_gs('gg_background_repeat'));
if($mod == 'no-repeat')
{
    echo 'body { background-size: cover !important; background-position: top center; } '. "\n";
}

goodgame_generate_css('body', 'background-attachment', 'gg_background_attachment', '', '!important' );
?>
