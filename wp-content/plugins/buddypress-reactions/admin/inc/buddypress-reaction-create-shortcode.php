<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/admin
 */

global $wpdb;
$options    = array(
	'emojis'               => array( 64, 190, 36, 5, 42, 29 ),
	'shortcode_name'       => '',
	'shortcode_align'      => 'left',
	'post_type'            => 'post',
	'auto_append'          => '1',
	'animation'            => 'true',
	'cta'                  => 'true',
	'cta_text'             => esc_html__( 'What’s your Reaction?', 'buddypress-reactions' ),
	'cta_font_size'        => '16',
	'cta_font_weight'      => '400',
	'cta_font_color'       => '#000000',
	'cta_separator'        => 'true',
	'cta_separator_color'  => '#eeeeee',
	'cta_separator_height' => '1',
	'cta_separator_style'  => 'solid',
);
$bpr_id     = '';
$table_name = $wpdb->prefix . 'bp_reactions_shortcodes ';
if ( isset( $_GET['bpr_id'] ) && $_GET['bpr_id'] != '' ) {
	$bpr_id = $_GET['bpr_id'];
}
if ( isset( $_GET['bpr_clone_id'] ) && $_GET['bpr_clone_id'] != '' ) {
	$bpr_id = $_GET['bpr_clone_id'];
}

if ( $bpr_id != '' ) {
	$where_search   = 'WHERE id = ' . $bpr_id;
	$query          = 'SELECT * FROM ' . $table_name . " {$where_search} ";
	$reactions_data = (array) $wpdb->get_row( $query );

	$name                   = $reactions_data['name'];
	$post_type              = $reactions_data['post_type'];
	$options                = json_decode( $reactions_data['options'], true );
	$options['auto_append'] = $reactions_data['front_render'];
}


$emojis = get_buddypress_reactions_emojis( 'inbuilt' );
$layout = '';


$post_types = apply_filters(
	'bp_reactions_get_post_types',
	get_post_types(
		array(
			'show_ui' => true,
			'public'  => true,
		)
	)
);
$post_types = array();
$post_types['post'] = 'Post';

?>

<div class="wbcom-tab-content bp-reactions bp-reactions-shortcode-generator">
	<div class="bp-reactions-messages-container">
		<div class="bp-reactions-message bp-reactions-message-error">
			<p><?php esc_html_e( 'You have reached maximum allowed emojis for the layout', 'buddypress-reactions' ); ?></p>
		</div>
	</div>
	<form method="post" action="options.php" class="bp-member-blog-gen-form">
		<?php
		settings_fields( 'bp_reaction_settings_section' );
		do_settings_sections( 'bp_reaction_settings_section' );
		?>
		<div class="emoji-picker-wrapper">
			<div class="emoji-picker-scrollbar">
				<div class="option-wrap emoji-picker">
					<?php
					foreach ( $emojis as $emoji ) :
						$active_class = ( isset( $options['emojis'] ) && in_array( $emoji->id, $options['emojis'] ) ) ? 'active' : '';
						?>
						<div class="emoji-pick <?php echo $active_class; ?>" data-emoji_id="<?php echo $emoji->id; ?>" title="<?php echo $emoji->name; ?>">
							<div class="emoji-lottie-holder" style="display: none"></div>
							<figure itemprop="gif" class="emoji-svg-holder" style="background-image: url('<?php echo get_buddypress_reaction_emoji( $emoji->id, 'svg' ); ?>'"></figure>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="wbcom-option-wrap">
			<div class="drag-and-drop">
				<span><i class="dashicons dashicons-move"></i><?php esc_html_e( 'Drag & Drop to Arrange', 'buddypress-reactions' ); ?></span>
				<!--button class="btn reset-emoji-picker" type="button"><i class="qas qa-redo-alt mr-2"></i> <?php // esc_html_e( 'Reset picker', 'buddypress-reactions' ); ?></button-->
			</div>
			<div class="wbcom-picker-empty" 
			<?php
			if ( isset( $options['emojis'] ) && ! empty( $options['emojis'] ) ) :
				?>
				 style="display:none;"<?php endif; ?> >
				<i class="dashicons dashicons-info"></i>
				<?php esc_html_e( 'Select emojis above to make your own set of reactions', 'buddypress-reactions' ); ?>
			</div>
			<div class="wbcom-picked-emojis">
				<?php
				if ( isset( $options['emojis'] ) && ! empty( $options['emojis'] ) ) :
					foreach ( $options['emojis'] as $name => $id ) :
						if ( $id != - 1 ) {
							?>
							<input type="hidden" name="bp_reactions[emojis][]" id="bp_reactions_emoji_id_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $id ); ?>" />
							<div class="picked-emoji emoji-lottie-holder lottie-element" data-emoji_id="<?php echo esc_attr( $id ); ?>">
							</div>
							<?php
						} endforeach;
				endif;
				?>
			</div>
		</div>
		<div class="wbcom-reactions-options">
			<div class="option-section">
				<label class="option-label" for="bp_reactions_shortcode_name"><?php esc_html_e( 'Name your shortcode', 'buddypress-reactions' ); ?></label>
				<input id="bp_reactions_shortcode_name" type="text" name="bp_reactions[shortcode_name]" value="<?php echo ( isset( $options['shortcode_name'] ) ) ? $options['shortcode_name'] : ''; ?>" class="regular-text" />
			</div>
			<!--div class="option-section">
				<label class="option-label" for="bp_reactions_shortcode_name"><?php //esc_html_e( 'Shortcode Alignment', 'buddypress-reactions' ); ?></label>
				<p class="description"><?php //esc_html_e( 'Set your emoji reactions to align with your content.', 'buddypress-reactions' ); ?></p>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[shortcode_align]" id="shortcode_align_left" value="left" <?php //checked( $options['shortcode_align'], 'left' ); ?>>
						<?php //esc_html_e( 'Left', 'buddypress-reactions' ); ?>
					</label>
				</div>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[shortcode_align]" id="animation_true" value="center" <?php //checked( $options['shortcode_align'], 'center' ); ?>>
						<?php //esc_html_e( 'Center', 'buddypress-reactions' ); ?>
					</label>
				</div>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[shortcode_align]" id="animation_true" value="right" <?php //checked( $options['shortcode_align'], 'right' ); ?>>
						<?php //esc_html_e( 'Right', 'buddypress-reactions' ); ?>
					</label>
				</div>
			</div-->
			<div class="option-section">
				<label class="option-label" for="bp_reactions_post_type"><?php esc_html_e( 'Bind to post type', 'buddypress-reactions' ); ?></label>
				<select name="bp_reactions[post_type]" id="bp_reactions_post_type">
					<option value=""><?php esc_html_e( 'Select post type', 'buddypress-reactions' ); ?></option>
					<?php foreach ( $post_types as $key => $type ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $options['post_type'], esc_attr( $key ) ); ?>><?php echo esc_html( $type ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="option-section">
				<label class="option-label"><input type="checkbox" name="bp_reactions[auto_append]" value="1" <?php checked( 1, $options['auto_append'] ); ?>/><?php esc_html_e( 'Automatically render shortcode for the bound post type', 'buddypress-reactions' ); ?></label>
				<p class="description"><?php esc_html_e( 'If you have bound shortcode to any post type above and decided to paste it manually into your post, then you must uncheck this option. Otherwise let plugin to handle it automatically.', 'buddypress-reactions' ); ?></p>
			</div>
		</div>
		<div class="wbcom-reactions-options">
			<div class="option-section">
				<label class="option-label" for="bp_reactions_shortcode_name"><?php esc_html_e( 'Emoji Animation', 'buddypress-reactions' ); ?></label>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[animation]" id="animation_true" value="true" <?php checked( $options['animation'], 'true' ); ?> >
						<?php esc_html_e( 'Animated', 'buddypress-reactions' ); ?>
					</label>
				</div>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[animation]" id="animation_true" value="on_hover" <?php checked( $options['animation'], 'on_hover' ); ?> >
						<?php esc_html_e( 'Animate single emoji on hover', 'buddypress-reactions' ); ?>
					</label>
				</div>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[animation]" id="animation_true" value="on_hover_all" <?php checked( $options['animation'], 'on_hover_all' ); ?> >
						<?php esc_html_e( 'Animate all on hover', 'buddypress-reactions' ); ?>
					</label>
				</div>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[animation]" id="animation_true" value="false" <?php checked( $options['animation'], 'false' ); ?> >
						<?php esc_html_e( 'Static', 'buddypress-reactions' ); ?>
					</label>
				</div>
			</div>
		</div>
		<!--div class="wbcom-reactions-options">
			<div class="option-section">
				<label class="option-label" for="bp_reactions_shortcode_name"><?php //esc_html_e( 'Call to Action', 'buddypress-reactions' ); ?></label>
				<p class="description"><?php //esc_html_e( 'Write a message located above your emojis.', 'buddypress-reactions' ); ?></p>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[cta]" id="animation_true" value="true"  <?php //checked( $options['cta'], 'true' ); ?> >
						<?php //esc_html_e( 'Show CTA', 'buddypress-reactions' ); ?>
					</label>
				</div>
				<div class="circle-radio form-group-inline ">
					<label>
						<input type="radio" name="bp_reactions[cta]" id="animation_true" value="false" <?php //checked( $options['cta'], 'false' ); ?> >
						<?php //esc_html_e( 'Hide CTA', 'buddypress-reactions' ); ?>
					</label>
				</div>
			</div>
			<div class="option-section">			
				<input type="text" name="bp_reactions[cta_text]" class="regular-text" placeholder="<?php esc_html_e( "What's your Reaction?", 'buddypress-reactions' ); ?>" value="<?php echo ( isset( $options['cta_text'] ) && $options['cta_text'] != '' ) ? stripslashes_deep( $options['cta_text'] ) : ''; ?>"/>
			</div>
			<div class="option-section">
				<div class="row">
					<div class="col">
						<label>					
							<?php //esc_html_e( 'Font Size', 'buddypress-reactions' ); ?>
						</label>
						<input type="number" name="bp_reactions[cta_font_size]" value="<?php //echo ( isset( $options['cta_font_size'] ) ) ? $options['cta_font_size'] : ''; ?>" class="regular-text" min="8" max="50" step="1"/>
					</div>
					<div class="col">
						<label>
							<?php //esc_html_e( 'Font Weight', 'buddypress-reactions' ); ?>
						</label>
						<input type="number" name="bp_reactions[cta_font_weight]" value="<?php //echo ( isset( $options['cta_font_weight'] ) ) ? $options['cta_font_weight'] : ''; ?>" class="regular-text" min="100" max="600" step="100"/>
					</div>
					<div class="col">
						<label>		
							<?php //esc_html_e( 'Color', 'buddypress-reactions' ); ?>
						</label>
						<input type="text" name="bp_reactions[cta_font_color]" value="<?php //echo ( isset( $options['cta_font_color'] ) ) ? $options['cta_font_color'] : ''; ?>" class="regular-text bp-reactions-color-picker" />
					</div>
				</div>
			</div>
			<div class="option-section">
				<label>
					<input type="checkbox" name="bp_reactions[cta_separator]" value="true" <?php //checked( ( isset( $options['cta_separator'] ) ) ? $options['cta_separator'] : '', 'true' ); ?> >
					<?php //esc_html_e( 'Enable separator under call to action', 'buddypress-reactions' ); ?>
				</label>
			</div>
			<div class="option-section">
				<div class="row">
					<div class="col">
						<label>
							<?php //esc_html_e( 'Color', 'buddypress-reactions' ); ?>
						</label>
						<input type="text" name="bp_reactions[cta_separator_color]" value="<?php //echo ( isset( $options['cta_separator_color'] ) ) ? $options['cta_separator_color'] : ''; ?>" class="regular-text bp-reactions-color-picker" />
					</div>
					<div class="col">
						<label>
							<?php //esc_html_e( 'Height', 'buddypress-reactions' ); ?>
						</label>
						<input type="number" name="bp_reactions[cta_separator_height]" value="<?php //echo ( isset( $options['cta_separator_height'] ) ) ? $options['cta_separator_height'] : ''; ?>" class="regular-text" min="0" max="10" step="1"/>
					</div>
					<div class="col">
						<label>					
							<?php //esc_html_e( 'Style', 'buddypress-reactions' ); ?>
						</label>
						<select name="bp_reactions[cta_separator_style]" id="cta_border_style" class="wpra-custom-select form-control ">
							<option value="none" <?php //selected( $options['cta_separator_style'], 'none' ); ?>><?php esc_html_e( 'None', 'buddypress-reactions' ); ?></option>
							<option value="dotted" <?php //selected( $options['cta_separator_style'], 'dotted' ); ?>><?php esc_html_e( 'Dotted', 'buddypress-reactions' ); ?></option>
							<option value="dashed" <?php //selected( $options['cta_separator_style'], 'dashed' ); ?>><?php esc_html_e( 'Dashed', 'buddypress-reactions' ); ?></option>
							<option value="solid" <?php //selected( $options['cta_separator_style'], 'solid' ); ?>><?php esc_html_e( 'Solid', 'buddypress-reactions' ); ?></option>
							<option value="double" <?php //selected( $options['cta_separator_style'], 'double' ); ?>><?php esc_html_e( 'Double', 'buddypress-reactions' ); ?></option>
							<option value="groove" <?php //selected( $options['cta_separator_style'], 'groove' ); ?>><?php esc_html_e( 'Groove', 'buddypress-reactions' ); ?></option>
							<option value="ridge" <?php //selected( $options['cta_separator_style'], 'ridge' ); ?>><?php esc_html_e( 'Ridge', 'buddypress-reactions' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div-->
		<?php if ( isset( $_GET['bpr_id'] ) && $_GET['bpr_id'] != '' ) { ?>
			<input type="hidden" name="bp_reactions[id]" value="<?php echo esc_attr( $_GET['bpr_id'] ); ?>" />
		<?php } ?>
		
		<?php if ( isset( $_GET['bpr_clone_id'] ) && $_GET['bpr_clone_id'] != '' ) { ?>
			<input type="hidden" name="bp_reactions[bpr_clone_id]" value="<?php echo esc_attr( $_GET['bpr_clone_id'] ); ?>" />
		<?php } ?>
		<?php submit_button(); ?>
	</form>

</div><!-- .wbcom-tab-content -->
