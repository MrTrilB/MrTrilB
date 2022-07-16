<?php


if ( isset( $data['params'] ) ) {
	extract( $data['params'] );
}

$bp_reations_classes   = array();
$bp_reations_classes[] = 'bp-reactions-plugin-container';

if ( isset( $shortcode_align ) && $shortcode_align != '' ) {
	$bp_reations_classes[] = 'bp-reactions-' . $shortcode_align;
} else {
	$bp_reations_classes[] = 'bp-reactions-center';
}

$bp_reations_classes[] = 'bp-reactions-animation-' . $animation;
$title_styles_args = array(
	'color'          => $cta_font_color,
	'font-size'      => $cta_font_size . 'px',
	'font-weight'    => $cta_font_weight,
	'display'        => ( isset( $show_title ) && $show_title ) == 'false' ? 'none' : 'block',
	'padding-bottom' => '35px',
);

if ( isset( $cta_separator ) && $cta_separator == 'true' ) {
	$title_styles_args ['border-bottom']       = '1px solid #eee';
	$title_styles_args ['border-bottom-width'] = ( isset( $cta_separator ) && $cta_separator == 'true' ) ? $cta_separator_height . 'px' : 0;
	$title_styles_args ['border-bottom-style'] = $cta_separator_style;
	$title_styles_args ['border-bottom-color'] = $cta_separator_color;
}

$title_styles = bp_reactions_build_linestyle( $title_styles_args );

?>

<div class="post-footer-content-actiions">
	<div id="bp-reactions-post-<?php echo esc_attr( get_the_ID() ); ?>" class="reacted-count content-actions">
		<?php bpr_bp_post_type_reactions_meta( get_the_ID(), $post_type, $bp_shortcode_id ); ?>
	</div>
</div>

<div class="bp-reactions-wrap bp-post-reactions-wrap bp-reactions-layout-1 <?php echo implode( ' ', $bp_reations_classes ); ?>" >
	<div class="bp-reactions-container">
		<?php if ( $cta == 'true' && $cta_text != '' ) : ?>
			<div class="bp-reaction-call-to-action" style="<?php echo $title_styles; ?>"><?php echo stripslashes_deep( $cta_text ); ?></div>
		<?php endif; ?>

		<div class="bp-reactions reaction-options emoji-picker">
			<?php if ( ! empty( $emojis ) ) : ?>
				<?php
				foreach ( $emojis as $emoji ) :
					$emoji_count      = bp_reactions_emoji_counts( $emoji, get_the_ID(), $post_type, $bp_shortcode_id );
					$emoji_count_fmt  = bp_reactions_emoji_count_format( bp_reactions_emoji_counts( $emoji, get_the_ID(), $post_type, $bp_shortcode_id ) );
					$reaction_classes = $user_emoji_id == $emoji ? 'active' : '';
					?>
					<div class="emoji-pick <?php echo esc_attr( $reaction_classes ); ?>" data-post-id="<?php echo esc_attr( get_the_ID() ); ?>" data-type="<?php echo esc_attr( $post_type ); ?>" data-emoji-id="<?php echo $emoji; ?>" title="<?php echo esc_attr( $emoji ); ?>" data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>" >
						<div class="bp-emoji-arrow-badge bp-emoji-arrow-bottom-left">
							<span class="bp-rmoji-count-number" data-count='<?php echo esc_attr( $emoji_count ); ?>'><?php echo $emoji_count_fmt; ?></span>
						</div>
						<div class="emoji-lottie-holder" style="display: none"></div>
						<figure itemprop="gif" class="emoji-svg-holder" style="background-image: url('<?php echo get_buddypress_reaction_emoji( $emoji, 'svg' ); ?>'"></figure>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
