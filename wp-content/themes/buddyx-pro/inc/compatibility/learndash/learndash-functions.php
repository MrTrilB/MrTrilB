<?php
/**
 * Custom functions for Learndash
 *
 * @link    https://wbcomdesigns.com/
 * @package buddyxpro
 */

/**
 * Remove sub header from single course page.
 *
 * @return void
 */
function buddyx_ld_remove_sub_header() {
	if ( is_single() && 'sfwd-courses' === get_post_type() ) {
		remove_action( 'buddyx_sub_header', 'buddyx_sub_header' );
		add_action( 'buddyx_sub_header', 'buddyx_learndash_single_course_header' );
	}
}
add_action( 'wp', 'buddyx_ld_remove_sub_header' );

/**
 * Remove course infobar from single course page.
 *
 * @return void
 */
add_filter( 'learndash_template', 'buddyx_hide_course_infobar', 999, 5 );
function buddyx_hide_course_infobar( $filepath, $name, $args, $echo, $return_file_path ) {
	if ( 'modules/infobar.php' === $name && is_single() && 'sfwd-courses' === get_post_type() ) {
		return $filepath = '';
	} else {
		return $filepath;
	}
}

/**
 * Add Udmey header on single course
 *
 * @return void
 */
function buddyx_learndash_single_course_header() {  ?>
	<div class="site-sub-header">
		<?php
		$breadcrumb = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );
		$args       = array(
			'post_id' => get_the_ID(), // use post_id, not post_ID
		);
		$comments   = get_comments( $args );

		$students_enrolled = learndash_get_users_for_course( get_the_ID(), array(), true );
		if ( empty( $students_enrolled ) ) {
			$students_enrolled = array();
		} else {
			$query_args        = $students_enrolled->query_vars;
			$students_enrolled = $query_args['include'];
		}

		$description = get_post_meta( get_the_ID(), '_learndash_course_grid_short_description', true );

		$author_id          = array( get_post_field( 'post_author', get_the_ID() ) );
		$_ld_instructor_ids = get_post_meta( get_the_ID(), '_ld_instructor_ids', true );
		if ( empty( $_ld_instructor_ids ) ) {
			$_ld_instructor_ids = array();
		}
		$ir_shared_instructor_ids = get_post_meta( get_the_ID(), 'ir_shared_instructor_ids', true );
		if ( $ir_shared_instructor_ids != '' ) {
			$ir_shared_instructor_ids = explode( ',', $ir_shared_instructor_ids );
		} else {
			$ir_shared_instructor_ids = array();
		}

		$author_ids = array_merge( $author_id, $_ld_instructor_ids, $ir_shared_instructor_ids );
		$author_ids = array_unique( $author_ids );

		$show_cover_image  = '';
		$cover_image_class = '';
		$image_id          = get_post_meta( get_the_ID(), '_course_image_id', true );
		if ( $image_id && get_post( $image_id ) ) {
			$_course_image     = wp_get_attachment_image_src( $image_id, 'large' );
			$show_cover_image  = 1;
			$cover_image_class = 'course-cover-image';
		}
		?>
		<div class="learndash-single-course-header <?php echo esc_attr( $cover_image_class ); ?>" <?php if ( $show_cover_image == 1 ) : ?> style="background-image:url('<?php echo esc_url( $_course_image[0] ); ?>')"<?php endif; ?>>
			<div class="container">
				<div class="learndash-single-course-header-inner-wrap">
				<?php if ( $breadcrumb && function_exists( 'buddyx_the_breadcrumb' ) ) : ?>
						<div class="lm-breadcrumbs-wrapper">
							<?php buddyx_the_breadcrumb(); ?>
						</div>
					<?php endif; ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<p class="course-header-short-description">
					<?php
					if ( $description != '' ) {
						echo $description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
					} else {
						$post_content = get_the_content();
						if ( strlen( $post_content ) < 500 ) {
							print $post_content . '...'; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
						} else {
							$post_trimmed = substr( $post_content, 0, strpos( $post_content, ' ', 200 ) );
							print $post_trimmed . '...'; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
						}
					}
					?>
					</p>
					<?php if ( ! isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) : ?>
						<div class="learndash-course-info">
							<div class="learndash-course-student-enrollment">
								<?php echo count( $students_enrolled ) . ' ' . ( ( count( $students_enrolled ) > 1 ) ? __( 'students', 'buddyxpro' ) : __( 'student', 'buddyxpro' ) ); ?>
							</div>
						</div>
					<?php endif; ?>
					<div class="learndash-course-instructor">
						<?php
						if ( ! empty( $author_ids ) ) {
							$instructor_image = '';
							$instructor_name  = '';
							$i                = 0;

							remove_filter( 'author_link', 'wpforo_change_author_default_page' );
							foreach ( $author_ids as $insttuctor_id ) {
								$author_avatar_url = get_avatar_url( $insttuctor_id );
								$author_url        = apply_filters( 'buddyx_ld_filter_course_author_url', get_author_posts_url( $insttuctor_id ), $insttuctor_id );
								$first_name        = get_the_author_meta( 'user_firstname', $insttuctor_id );
								$last_name         = get_the_author_meta( 'user_lastname', $insttuctor_id );
								$author_name       = get_the_author_meta( 'display_name', $insttuctor_id );
								if ( ! empty( $first_name ) && ! empty( $last_name ) && $author_name == '' ) {
									$author_name = $first_name . ' ' . $last_name;
								}
								if ( $i < 3 ) {
									$instructor_image .= '<img alt="instructor avatar" src="' . $author_avatar_url . '" class="lm-author-avatar" width="40" height="40">';
								}
								$instructor_name .= '<a href="' . $author_url . '" target="_blank">' . $author_name . '</a>, ';
								$i++;
							}
							?>
							<div class="instructor-avatar">
								<?php echo $instructor_image; ?>
							</div>
							<div class="instructor-name">
								<?php echo substr( $instructor_name, 0, -2 ); ?>
							</div>
							<?php
						}
						?>
					</div>
					<div class="last-update-date">
						<span class="last-update-date_icon">
							<i class="fas fa-certificate"></i>
						</span>
						<span><?php echo sprintf( __( 'Last updated %s', 'buddyxpro' ), the_modified_date( '', '', '', false ) ); ?> </span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

add_filter( 'buddyx_ld_filter_course_author_url', 'buddyx_ld_filter_course_author_url', 10, 1 );
function buddyx_ld_filter_course_author_url( $author_url ) {
	$author_url .= '?post_type=sfwd-courses';
	return $author_url;
}

add_filter( 'learndash_header_tab_menu', 'buddyx_ld_manage_metaboxes', 10, 4 );
function buddyx_ld_manage_metaboxes( $header_data_tabs, $menu_tab_key, $screen_post_type ) {
	foreach ( $header_data_tabs as $key => $data_tabs ) {
		if ( 'sfwd-courses-settings' === $data_tabs['id'] ) {
			array_push( $header_data_tabs[ $key ]['metaboxes'], 'learndash_course_custom_features' );
		}
	}
	return $header_data_tabs;
}


add_action( 'add_meta_boxes', 'buddyx_learndash_course_meta_boxes', 10 );
function buddyx_learndash_course_meta_boxes() {

	add_meta_box(
		'learndash_course_custom_features',
		__( 'Custom Course Features', 'buddyxpro' ),
		'buddyx_render_course_custom_features_meta_box',
		array( 'sfwd-courses' )
	);

	add_meta_box(
		'learndash_course_cover_image',
		__( 'Course Cover Image', 'buddyxpro' ),
		( 'buddyx_render_custom_course_cover_image' ),
		array( 'sfwd-courses' ),
		'side'
	);
}

function buddyx_render_custom_course_cover_image( $post ) {
	global $content_width, $_wp_additional_image_sizes;

	$image_id = get_post_meta( $post->ID, '_course_image_id', true );

	$old_content_width = $content_width;
	$content_width     = 254;

	if ( $image_id && get_post( $image_id ) ) {

		if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
			$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
		} else {
			$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
		}

		if ( ! empty( $thumbnail_html ) ) {
			$content  = $thumbnail_html;
			$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_listing_image_button" >' . esc_html__( 'Remove listing image', 'reign-learndash-addon' ) . '</a></p>';
			$content .= '<input type="hidden" id="upload_listing_image" name="_course_cover_image" value="' . esc_attr( $image_id ) . '" />';
		}

		$content_width = $old_content_width;
	} else {

		$content  = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
		$content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set cover image', 'reign-learndash-addon' ) . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'reign-learndash-addon' ) . '" data-uploader_button_text="' . esc_attr__( 'Set cover image', 'reign-learndash-addon' ) . '">' . esc_html__( 'Set cover image', 'reign-learndash-addon' ) . '</a></p>';
		$content .= '<input type="hidden" id="upload_listing_image" name="_course_cover_image" value="" />';

	}

	$content .= esc_html__( 'Set cover image in single course layout header.', 'reign-learndash-addon' );

	echo $content;
}

function buddyx_render_course_custom_features_meta_box( $post ) {
	$post_id              = $post->ID;
	$fontawesome_icons    = buddyx_learndash_fontawesome_icons();
	$buddyx_ld_ccf_enable = get_post_meta( $post_id, 'buddyx_ld_ccf_enable', true );
	$buddyx_ld_features   = get_post_meta( $post_id, 'buddyx_ld_features', true );
	?>
	<div class="sfwd sfwd_options">
		<div class="buddyx-sfwd_input">
			<span class="sfwd_option_label" style="text-align:right;vertical-align:top;">
				<label class="buddyx-sfwd_label textinput"><?php _e( 'Enable', 'learndash-course-grid' ); ?></label></a>
			</span>
			<span class="buddyx-sfwd_option_input">
				<div class="buddyx-sfwd_option_div">
					<input type="checkbox" name="buddyx_ld_ccf_enable" value="yes" <?php checked( $buddyx_ld_ccf_enable, 'yes', true ); ?> />
				</div>
				<div class="sfwd_help_text_div" style="display:none" id="buddyx_learndash_ccf_enable">
				</div>
			</span>
		</div>
		<div class="sfwd_input">
			<div class="rla-custom-features">
				<table id="custom-course-feature-lists">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Icon', 'buddyxpro' ); ?></th>
							<th><?php esc_html_e( 'Feature', 'buddyxpro' ); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php if ( ! empty( $buddyx_ld_features ) ) : ?>
							<?php for ( $i = 0; $i < count( $buddyx_ld_features['icon'] ); $i++ ) : ?>
								<tr>
									<td>
										<span class="ld-select ld-select2">
											<select class="ld-select buddyx-select2-element"  name="buddyx_ld_features[icon][]">
											<?php
											foreach ( $fontawesome_icons as $icons ) :
												$icon_html = '<i class="' . $icons . '"></i>';
												?>
												<option value="<?php echo $icons; ?>" <?php selected( $buddyx_ld_features['icon'][ $i ], $icons ); ?>><?php echo $icons; ?></option>
											<?php endforeach; ?>
											</select>
										</span>
									</td>
									<td class="buddyx_ld_features">
										<input type="text" name="buddyx_ld_features[text][]" value="<?php echo $buddyx_ld_features['text'][ $i ]; ?>" />
									</td>
									<td>
										<span class="rla-delete-course-feature dashicons dashicons-no-alt"></span>
									</td>
								</tr>
							<?php endfor; ?>
							<?php else : ?>
							<tr>
								<td>
									<select name="buddyx_ld_features[icon][] buddyx-select2-element">
									<?php
									foreach ( $fontawesome_icons as $icons ) :
										$icon_html = '<i class="' . $icons . '"></i>';
										?>
										<option value="<?php echo $icons; ?>" <?php selected( $social_channel_value['fontawesome_icon'], $icons ); ?>><?php echo $icons; ?></option>
									<?php endforeach; ?>
									</select>
								</td>
								<td>
									<input type="text" name="buddyx_ld_features[text][]" value="" />
								</td>
								<td>
									<a href="" class="rla-delete-course-feature">X</a>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<button class="rla-add-course-feature"><?php esc_html_e( 'Add Feature', 'buddyxpro' ); ?></button>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'save_post', 'buddyx_ld_save_post_meta' );
function buddyx_ld_save_post_meta( $post_id ) {
	// Bail if we're doing an auto save.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// if our current user can't edit this post, bail.
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	if ( isset( $_POST['post_type'] ) && ( $_POST['post_type'] == 'sfwd-courses' ) ) {

		if ( isset( $_POST['buddyx_ld_ccf_enable'] ) ) {
			update_post_meta( $post_id, 'buddyx_ld_ccf_enable', 'yes' );
		} else {
			update_post_meta( $post_id, 'buddyx_ld_ccf_enable', '' );
		}

		update_post_meta( $post_id, 'buddyx_ld_features', $_POST['buddyx_ld_features'] );

		update_post_meta( $post_id, 'rla_course_layout', $_POST['rla_course_layout'] );

		if ( isset( $_POST['_course_cover_image'] ) ) {
			$image_id = (int) $_POST['_course_cover_image'];
			update_post_meta( $post_id, '_course_image_id', $image_id );
		}

		do_action( 'rla_cs_save_coming_soon_options' );
	}
}

if ( ! function_exists( 'buddyx_learndash_fontawesome_icons' ) ) {
	function buddyx_learndash_fontawesome_icons() {
		return array(
			'fab fa-500px',
			'fab fa-accessible-icon',
			'fab fa-accusoft',
			'fas fa-address-book',
			'far fa-address-book',
			'fas fa-address-card',
			'far fa-address-card',
			'fas fa-adjust',
			'fab fa-adn',
			'fab fa-adversal',
			'fab fa-affiliatetheme',
			'fab fa-algolia',
			'fas fa-align-center',
			'fas fa-align-justify',
			'fas fa-align-left',
			'fas fa-align-right',
			'fab fa-amazon',
			'fas fa-ambulance',
			'fas fa-american-sign-language-interpreting',
			'fab fa-amilia',
			'fas fa-anchor',
			'fab fa-android',
			'fab fa-angellist',
			'fas fa-angle-double-down',
			'fas fa-angle-double-left',
			'fas fa-angle-double-right',
			'fas fa-angle-double-up',
			'fas fa-angle-down',
			'fas fa-angle-left',
			'fas fa-angle-right',
			'fas fa-angle-up',
			'fab fa-angrycreative',
			'fab fa-angular',
			'fab fa-app-store',
			'fab fa-app-store-ios',
			'fab fa-apper',
			'fab fa-apple',
			'fab fa-apple-pay',
			'fas fa-archive',
			'fas fa-arrow-alt-circle-down',
			'far fa-arrow-alt-circle-down',
			'fas fa-arrow-alt-circle-left',
			'far fa-arrow-alt-circle-left',
			'fas fa-arrow-alt-circle-right',
			'far fa-arrow-alt-circle-right',
			'fas fa-arrow-alt-circle-up',
			'far fa-arrow-alt-circle-up',
			'fas fa-arrow-circle-down',
			'fas fa-arrow-circle-left',
			'fas fa-arrow-circle-right',
			'fas fa-arrow-circle-up',
			'fas fa-arrow-down',
			'fas fa-arrow-left',
			'fas fa-arrow-right',
			'fas fa-arrow-up',
			'fas fa-arrows-alt',
			'fas fa-arrows-alt-h',
			'fas fa-arrows-alt-v',
			'fas fa-assistive-listening-systems',
			'fas fa-asterisk',
			'fab fa-asymmetrik',
			'fas fa-at',
			'fab fa-audible',
			'fas fa-audio-description',
			'fab fa-autoprefixer',
			'fab fa-avianex',
			'fab fa-aviato',
			'fab fa-aws',
			'fas fa-backward',
			'fas fa-balance-scale',
			'fas fa-ban',
			'fab fa-bandcamp',
			'fas fa-barcode',
			'fas fa-bars',
			'fas fa-bath',
			'fas fa-battery-empty',
			'fas fa-battery-full',
			'fas fa-battery-half',
			'fas fa-battery-quarter',
			'fas fa-battery-three-quarters',
			'fas fa-bed',
			'fas fa-beer',
			'fab fa-behance',
			'fab fa-behance-square',
			'fas fa-bell',
			'far fa-bell',
			'fas fa-bell-slash',
			'far fa-bell-slash',
			'fas fa-bicycle',
			'fab fa-bimobject',
			'fas fa-binoculars',
			'fas fa-birthday-cake',
			'fab fa-bitbucket',
			'fab fa-bitcoin',
			'fab fa-bity',
			'fab fa-black-tie',
			'fab fa-blackberry',
			'fas fa-blind',
			'fab fa-blogger',
			'fab fa-blogger-b',
			'fab fa-bluetooth',
			'fab fa-bluetooth-b',
			'fas fa-bold',
			'fas fa-bolt',
			'fas fa-bomb',
			'fas fa-book',
			'fas fa-bookmark',
			'far fa-bookmark',
			'fas fa-braille',
			'fas fa-briefcase',
			'fab fa-btc',
			'fas fa-bug',
			'fas fa-building',
			'far fa-building',
			'fas fa-bullhorn',
			'fas fa-bullseye',
			'fab fa-buromobelexperte',
			'fas fa-bus',
			'fab fa-buysellads',
			'fas fa-calculator',
			'fas fa-calendar',
			'far fa-calendar',
			'fas fa-calendar-alt',
			'far fa-calendar-alt',
			'fas fa-calendar-check',
			'far fa-calendar-check',
			'fas fa-calendar-minus',
			'far fa-calendar-minus',
			'fas fa-calendar-plus',
			'far fa-calendar-plus',
			'fas fa-calendar-times',
			'far fa-calendar-times',
			'fas fa-camera',
			'fas fa-camera-retro',
			'fas fa-car',
			'fas fa-caret-down',
			'fas fa-caret-left',
			'fas fa-caret-right',
			'fas fa-caret-square-down',
			'far fa-caret-square-down',
			'fas fa-caret-square-left',
			'far fa-caret-square-left',
			'fas fa-caret-square-right',
			'far fa-caret-square-right',
			'fas fa-caret-square-up',
			'far fa-caret-square-up',
			'fas fa-caret-up',
			'fas fa-cart-arrow-down',
			'fas fa-cart-plus',
			'fab fa-cc-amex',
			'fab fa-cc-apple-pay',
			'fab fa-cc-diners-club',
			'fab fa-cc-discover',
			'fab fa-cc-jcb',
			'fab fa-cc-mastercard',
			'fab fa-cc-paypal',
			'fab fa-cc-stripe',
			'fab fa-cc-visa',
			'fab fa-centercode',
			'fas fa-certificate',
			'fas fa-chart-area',
			'fas fa-chart-bar',
			'far fa-chart-bar',
			'fas fa-chart-line',
			'fas fa-chart-pie',
			'fas fa-check',
			'fas fa-check-circle',
			'far fa-check-circle',
			'fas fa-check-square',
			'far fa-check-square',
			'fas fa-chevron-circle-down',
			'fas fa-chevron-circle-left',
			'fas fa-chevron-circle-right',
			'fas fa-chevron-circle-up',
			'fas fa-chevron-down',
			'fas fa-chevron-left',
			'fas fa-chevron-right',
			'fas fa-chevron-up',
			'fas fa-child',
			'fab fa-chrome',
			'fas fa-circle',
			'far fa-circle',
			'fas fa-circle-notch',
			'fas fa-clipboard',
			'far fa-clipboard',
			'fas fa-clock',
			'far fa-clock',
			'fas fa-clone',
			'far fa-clone',
			'fas fa-closed-captioning',
			'far fa-closed-captioning',
			'fas fa-cloud',
			'fas fa-cloud-download-alt',
			'fas fa-cloud-upload-alt',
			'fab fa-cloudscale',
			'fab fa-cloudsmith',
			'fab fa-cloudversify',
			'fas fa-code',
			'fas fa-code-branch',
			'fab fa-codepen',
			'fab fa-codiepie',
			'fas fa-coffee',
			'fas fa-cog',
			'fas fa-cogs',
			'fas fa-columns',
			'fas fa-comment',
			'far fa-comment',
			'fas fa-comment-alt',
			'far fa-comment-alt',
			'fas fa-comments',
			'far fa-comments',
			'fas fa-compass',
			'far fa-compass',
			'fas fa-compress',
			'fab fa-connectdevelop',
			'fab fa-contao',
			'fas fa-copy',
			'far fa-copy',
			'fas fa-copyright',
			'far fa-copyright',
			'fab fa-cpanel',
			'fab fa-creative-commons',
			'fas fa-credit-card',
			'far fa-credit-card',
			'fas fa-crop',
			'fas fa-crosshairs',
			'fab fa-css3',
			'fab fa-css3-alt',
			'fas fa-cube',
			'fas fa-cubes',
			'fas fa-cut',
			'fab fa-cuttlefish',
			'fab fa-d-and-d',
			'fab fa-dashcube',
			'fas fa-database',
			'fas fa-deaf',
			'fab fa-delicious',
			'fab fa-deploydog',
			'fab fa-deskpro',
			'fas fa-desktop',
			'fab fa-deviantart',
			'fab fa-digg',
			'fab fa-digital-ocean',
			'fab fa-discord',
			'fab fa-discourse',
			'fab fa-dochub',
			'fab fa-docker',
			'fas fa-dollar-sign',
			'fas fa-dot-circle',
			'far fa-dot-circle',
			'fas fa-download',
			'fab fa-draft2digital',
			'fab fa-dribbble',
			'fab fa-dribbble-square',
			'fab fa-dropbox',
			'fab fa-drupal',
			'fab fa-dyalog',
			'fab fa-earlybirds',
			'fab fa-edge',
			'fas fa-edit',
			'far fa-edit',
			'fas fa-eject',
			'fas fa-ellipsis-h',
			'fas fa-ellipsis-v',
			'fab fa-ember',
			'fab fa-empire',
			'fas fa-envelope',
			'far fa-envelope',
			'fas fa-envelope-open',
			'far fa-envelope-open',
			'fas fa-envelope-square',
			'fab fa-envira',
			'fas fa-eraser',
			'fab fa-erlang',
			'fab fa-etsy',
			'fas fa-euro-sign',
			'fas fa-exchange-alt',
			'fas fa-exclamation',
			'fas fa-exclamation-circle',
			'fas fa-exclamation-triangle',
			'fas fa-expand',
			'fas fa-expand-arrows-alt',
			'fab fa-expeditedssl',
			'fas fa-external-link-alt',
			'fas fa-external-link-square-alt',
			'fas fa-eye',
			'fas fa-eye-dropper',
			'fas fa-eye-slash',
			'far fa-eye-slash',
			'fab fa-facebook',
			'fab fa-facebook-f',
			'fab fa-facebook-messenger',
			'fab fa-facebook-square',
			'fas fa-fast-backward',
			'fas fa-fast-forward',
			'fas fa-fax',
			'fas fa-female',
			'fas fa-fighter-jet',
			'fas fa-file',
			'far fa-file',
			'fas fa-file-alt',
			'far fa-file-alt',
			'fas fa-file-archive',
			'far fa-file-archive',
			'fas fa-file-audio',
			'far fa-file-audio',
			'fas fa-file-code',
			'far fa-file-code',
			'fas fa-file-excel',
			'far fa-file-excel',
			'fas fa-file-image',
			'far fa-file-image',
			'fas fa-file-pdf',
			'far fa-file-pdf',
			'fas fa-file-powerpoint',
			'far fa-file-powerpoint',
			'fas fa-file-video',
			'far fa-file-video',
			'fas fa-file-word',
			'far fa-file-word',
			'fas fa-film',
			'fas fa-filter',
			'fas fa-fire',
			'fas fa-fire-extinguisher',
			'fab fa-firefox',
			'fab fa-first-order',
			'fab fa-firstdraft',
			'fas fa-flag',
			'far fa-flag',
			'fas fa-flag-checkered',
			'fas fa-flask',
			'fab fa-flickr',
			'fab fa-fly',
			'fas fa-folder',
			'far fa-folder',
			'fas fa-folder-open',
			'far fa-folder-open',
			'fas fa-font',
			'fab fa-font-awesome',
			'fab fa-font-awesome-alt',
			'fab fa-font-awesome-flag',
			'fab fa-fonticons',
			'fab fa-fonticons-fi',
			'fab fa-fort-awesome',
			'fab fa-fort-awesome-alt',
			'fab fa-forumbee',
			'fas fa-forward',
			'fab fa-foursquare',
			'fab fa-free-code-camp',
			'fab fa-freebsd',
			'fas fa-frown',
			'far fa-frown',
			'fas fa-futbol',
			'far fa-futbol',
			'fas fa-gamepad',
			'fas fa-gavel',
			'fas fa-gem',
			'far fa-gem',
			'fas fa-genderless',
			'fab fa-get-pocket',
			'fab fa-gg',
			'fab fa-gg-circle',
			'fas fa-gift',
			'fab fa-git',
			'fab fa-git-square',
			'fab fa-github',
			'fab fa-github-alt',
			'fab fa-github-square',
			'fab fa-gitkraken',
			'fab fa-gitlab',
			'fab fa-gitter',
			'fas fa-glass-martini',
			'fab fa-glide',
			'fab fa-glide-g',
			'fas fa-globe',
			'fab fa-gofore',
			'fab fa-goodreads',
			'fab fa-goodreads-g',
			'fab fa-google',
			'fab fa-google-drive',
			'fab fa-google-play',
			'fab fa-google-plus',
			'fab fa-google-plus-g',
			'fab fa-google-plus-square',
			'fab fa-google-wallet',
			'fas fa-graduation-cap',
			'fab fa-gratipay',
			'fab fa-grav',
			'fab fa-gripfire',
			'fab fa-grunt',
			'fab fa-gulp',
			'fas fa-h-square',
			'fab fa-hacker-news',
			'fab fa-hacker-news-square',
			'fas fa-hand-lizard',
			'far fa-hand-lizard',
			'fas fa-hand-paper',
			'far fa-hand-paper',
			'fas fa-hand-peace',
			'far fa-hand-peace',
			'fas fa-hand-point-down',
			'far fa-hand-point-down',
			'fas fa-hand-point-left',
			'far fa-hand-point-left',
			'fas fa-hand-point-right',
			'far fa-hand-point-right',
			'fas fa-hand-point-up',
			'far fa-hand-point-up',
			'fas fa-hand-pointer',
			'far fa-hand-pointer',
			'fas fa-hand-rock',
			'far fa-hand-rock',
			'fas fa-hand-scissors',
			'far fa-hand-scissors',
			'fas fa-hand-spock',
			'far fa-hand-spock',
			'fas fa-handshake',
			'far fa-handshake',
			'fas fa-hashtag',
			'fas fa-hdd',
			'far fa-hdd',
			'fas fa-heading',
			'fas fa-headphones',
			'fas fa-heart',
			'far fa-heart',
			'fas fa-heartbeat',
			'fab fa-hire-a-helper',
			'fas fa-history',
			'fas fa-home',
			'fab fa-hooli',
			'fas fa-hospital',
			'far fa-hospital',
			'fab fa-hotjar',
			'fas fa-hourglass',
			'far fa-hourglass',
			'fas fa-hourglass-end',
			'fas fa-hourglass-half',
			'fas fa-hourglass-start',
			'fab fa-houzz',
			'fab fa-html5',
			'fab fa-hubspot',
			'fas fa-i-cursor',
			'fas fa-id-badge',
			'far fa-id-badge',
			'fas fa-id-card',
			'far fa-id-card',
			'fas fa-image',
			'far fa-image',
			'fas fa-images',
			'far fa-images',
			'fab fa-imdb',
			'fas fa-inbox',
			'fas fa-indent',
			'fas fa-industry',
			'fas fa-info',
			'fas fa-info-circle',
			'fab fa-instagram',
			'fab fa-internet-explorer',
			'fab fa-ioxhost',
			'fas fa-italic',
			'fab fa-itunes',
			'fab fa-itunes-note',
			'fab fa-jenkins',
			'fab fa-joget',
			'fab fa-joomla',
			'fab fa-js',
			'fab fa-js-square',
			'fab fa-jsfiddle',
			'fas fa-key',
			'fas fa-keyboard',
			'far fa-keyboard',
			'fab fa-keycdn',
			'fab fa-kickstarter',
			'fab fa-kickstarter-k',
			'fas fa-language',
			'fas fa-laptop',
			'fab fa-laravel',
			'fab fa-lastfm',
			'fab fa-lastfm-square',
			'fas fa-leaf',
			'fab fa-leanpub',
			'fas fa-lemon',
			'far fa-lemon',
			'fab fa-less',
			'fas fa-level-down-alt',
			'fas fa-level-up-alt',
			'fas fa-life-ring',
			'far fa-life-ring',
			'fas fa-lightbulb',
			'far fa-lightbulb',
			'fab fa-line',
			'fas fa-link',
			'fab fa-linkedin',
			'fab fa-linkedin-in',
			'fab fa-linode',
			'fab fa-linux',
			'fas fa-lira-sign',
			'fas fa-list',
			'fas fa-list-alt',
			'far fa-list-alt',
			'fas fa-list-ol',
			'fas fa-list-ul',
			'fas fa-location-arrow',
			'fas fa-lock',
			'fas fa-lock-open',
			'fas fa-long-arrow-alt-down',
			'fas fa-long-arrow-alt-left',
			'fas fa-long-arrow-alt-right',
			'fas fa-long-arrow-alt-up',
			'fas fa-low-vision',
			'fab fa-lyft',
			'fab fa-magento',
			'fas fa-magic',
			'fas fa-magnet',
			'fas fa-male',
			'fas fa-map',
			'far fa-map',
			'fas fa-map-marker',
			'fas fa-map-marker-alt',
			'fas fa-map-pin',
			'fas fa-map-signs',
			'fas fa-mars',
			'fas fa-mars-double',
			'fas fa-mars-stroke',
			'fas fa-mars-stroke-h',
			'fas fa-mars-stroke-v',
			'fab fa-maxcdn',
			'fab fa-medapps',
			'fab fa-medium',
			'fab fa-medium-m',
			'fas fa-medkit',
			'fab fa-medrt',
			'fab fa-meetup',
			'fas fa-meh',
			'far fa-meh',
			'fas fa-mercury',
			'fas fa-microchip',
			'fas fa-microphone',
			'fas fa-microphone-slash',
			'fab fa-microsoft',
			'fas fa-minus',
			'fas fa-minus-circle',
			'fas fa-minus-square',
			'far fa-minus-square',
			'fab fa-mix',
			'fab fa-mixcloud',
			'fab fa-mizuni',
			'fas fa-mobile',
			'fas fa-mobile-alt',
			'fab fa-modx',
			'fab fa-monero',
			'fas fa-money-bill-alt',
			'far fa-money-bill-alt',
			'fas fa-moon',
			'far fa-moon',
			'fas fa-motorcycle',
			'fas fa-mouse-pointer',
			'fas fa-music',
			'fab fa-napster',
			'fas fa-neuter',
			'fas fa-newspaper',
			'far fa-newspaper',
			'fab fa-nintendo-switch',
			'fab fa-node',
			'fab fa-node-js',
			'fab fa-npm',
			'fab fa-ns8',
			'fab fa-nutritionix',
			'fas fa-object-group',
			'far fa-object-group',
			'fas fa-object-ungroup',
			'far fa-object-ungroup',
			'fab fa-odnoklassniki',
			'fab fa-odnoklassniki-square',
			'fab fa-opencart',
			'fab fa-openid',
			'fab fa-opera',
			'fab fa-optin-monster',
			'fab fa-osi',
			'fas fa-outdent',
			'fab fa-page4',
			'fab fa-pagelines',
			'fas fa-paint-brush',
			'fab fa-palfed',
			'fas fa-paper-plane',
			'far fa-paper-plane',
			'fas fa-paperclip',
			'fas fa-paragraph',
			'fas fa-paste',
			'fab fa-patreon',
			'fas fa-pause',
			'fas fa-pause-circle',
			'far fa-pause-circle',
			'fas fa-paw',
			'fab fa-paypal',
			'fas fa-pen-square',
			'fas fa-pencil-alt',
			'fas fa-percent',
			'fab fa-periscope',
			'fab fa-phabricator',
			'fab fa-phoenix-framework',
			'fas fa-phone',
			'fas fa-phone-square',
			'fas fa-phone-volume',
			'fab fa-pied-piper',
			'fab fa-pied-piper-alt',
			'fab fa-pied-piper-pp',
			'fab fa-pinterest',
			'fab fa-pinterest-p',
			'fab fa-pinterest-square',
			'fas fa-plane',
			'fas fa-play',
			'fas fa-play-circle',
			'far fa-play-circle',
			'fab fa-playstation',
			'fas fa-plug',
			'fas fa-plus',
			'fas fa-plus-circle',
			'fas fa-plus-square',
			'far fa-plus-square',
			'fas fa-podcast',
			'fas fa-pound-sign',
			'fas fa-power-off',
			'fas fa-print',
			'fab fa-product-hunt',
			'fab fa-pushed',
			'fas fa-puzzle-piece',
			'fab fa-python',
			'fab fa-qq',
			'fas fa-qrcode',
			'fas fa-question',
			'fas fa-question-circle',
			'far fa-question-circle',
			'fab fa-quora',
			'fas fa-quote-left',
			'fas fa-quote-right',
			'fas fa-random',
			'fab fa-ravelry',
			'fab fa-react',
			'fab fa-rebel',
			'fas fa-recycle',
			'fab fa-red-river',
			'fab fa-reddit',
			'fab fa-reddit-alien',
			'fab fa-reddit-square',
			'fas fa-redo',
			'fas fa-redo-alt',
			'fas fa-registered',
			'far fa-registered',
			'fab fa-rendact',
			'fab fa-renren',
			'fas fa-reply',
			'fas fa-reply-all',
			'fab fa-replyd',
			'fab fa-resolving',
			'fas fa-retweet',
			'fas fa-road',
			'fas fa-rocket',
			'fab fa-rocketchat',
			'fab fa-rockrms',
			'fas fa-rss',
			'fas fa-rss-square',
			'fas fa-ruble-sign',
			'fas fa-rupee-sign',
			'fab fa-safari',
			'fab fa-sass',
			'fas fa-save',
			'far fa-save',
			'fab fa-schlix',
			'fab fa-scribd',
			'fas fa-search',
			'fas fa-search-minus',
			'fas fa-search-plus',
			'fab fa-searchengin',
			'fab fa-sellcast',
			'fab fa-sellsy',
			'fas fa-server',
			'fab fa-servicestack',
			'fas fa-share',
			'fas fa-share-alt',
			'fas fa-share-alt-square',
			'fas fa-share-square',
			'far fa-share-square',
			'fas fa-shekel-sign',
			'fas fa-shield-alt',
			'fas fa-ship',
			'fab fa-shirtsinbulk',
			'fas fa-shopping-bag',
			'fas fa-shopping-basket',
			'fas fa-shopping-cart',
			'fas fa-shower',
			'fas fa-sign-in-alt',
			'fas fa-sign-language',
			'fas fa-sign-out-alt',
			'fas fa-signal',
			'fab fa-simplybuilt',
			'fab fa-sistrix',
			'fas fa-sitemap',
			'fab fa-skyatlas',
			'fab fa-skype',
			'fab fa-slack',
			'fab fa-slack-hash',
			'fas fa-sliders-h',
			'fab fa-slideshare',
			'fas fa-smile',
			'far fa-smile',
			'fab fa-snapchat',
			'fab fa-snapchat-ghost',
			'fab fa-snapchat-square',
			'fas fa-snowflake',
			'far fa-snowflake',
			'fas fa-sort',
			'fas fa-sort-alpha-down',
			'fas fa-sort-alpha-up',
			'fas fa-sort-amount-down',
			'fas fa-sort-amount-up',
			'fas fa-sort-down',
			'fas fa-sort-numeric-down',
			'fas fa-sort-numeric-up',
			'fas fa-sort-up',
			'fab fa-soundcloud',
			'fas fa-space-shuttle',
			'fab fa-speakap',
			'fas fa-spinner',
			'fab fa-spotify',
			'fas fa-square',
			'far fa-square',
			'fab fa-stack-exchange',
			'fab fa-stack-overflow',
			'fas fa-star',
			'far fa-star',
			'fas fa-star-half',
			'far fa-star-half',
			'fab fa-staylinked',
			'fab fa-steam',
			'fab fa-steam-square',
			'fab fa-steam-symbol',
			'fas fa-step-backward',
			'fas fa-step-forward',
			'fas fa-stethoscope',
			'fab fa-sticker-mule',
			'fas fa-sticky-note',
			'far fa-sticky-note',
			'fas fa-stop',
			'fas fa-stop-circle',
			'far fa-stop-circle',
			'fab fa-strava',
			'fas fa-street-view',
			'fas fa-strikethrough',
			'fab fa-stripe',
			'fab fa-stripe-s',
			'fab fa-studiovinari',
			'fab fa-stumbleupon',
			'fab fa-stumbleupon-circle',
			'fas fa-subscript',
			'fas fa-subway',
			'fas fa-suitcase',
			'fas fa-sun',
			'far fa-sun',
			'fab fa-superpowers',
			'fas fa-superscript',
			'fab fa-supple',
			'fas fa-sync',
			'fas fa-sync-alt',
			'fas fa-table',
			'fas fa-tablet',
			'fas fa-tablet-alt',
			'fas fa-tachometer-alt',
			'fas fa-tag',
			'fas fa-tags',
			'fas fa-tasks',
			'fas fa-taxi',
			'fab fa-telegram',
			'fab fa-telegram-plane',
			'fab fa-tencent-weibo',
			'fas fa-terminal',
			'fas fa-text-height',
			'fas fa-text-width',
			'fas fa-th',
			'fas fa-th-large',
			'fas fa-th-list',
			'fab fa-themeisle',
			'fas fa-thermometer-empty',
			'fas fa-thermometer-full',
			'fas fa-thermometer-half',
			'fas fa-thermometer-quarter',
			'fas fa-thermometer-three-quarters',
			'fas fa-thumbs-down',
			'far fa-thumbs-down',
			'fas fa-thumbs-up',
			'far fa-thumbs-up',
			'fas fa-thumbtack',
			'fas fa-ticket-alt',
			'fas fa-times',
			'fas fa-times-circle',
			'far fa-times-circle',
			'fas fa-tint',
			'fas fa-toggle-off',
			'fas fa-toggle-on',
			'fas fa-trademark',
			'fas fa-train',
			'fas fa-transgender',
			'fas fa-transgender-alt',
			'fas fa-trash',
			'fas fa-trash-alt',
			'far fa-trash-alt',
			'fas fa-tree',
			'fab fa-trello',
			'fab fa-tripadvisor',
			'fas fa-trophy',
			'fas fa-truck',
			'fas fa-tty',
			'fab fa-tumblr',
			'fab fa-tumblr-square',
			'fas fa-tv',
			'fab fa-twitch',
			'fab fa-twitter',
			'fab fa-twitter-square',
			'fab fa-typo3',
			'fab fa-uber',
			'fab fa-uikit',
			'fas fa-umbrella',
			'fas fa-underline',
			'fas fa-undo',
			'fas fa-undo-alt',
			'fab fa-uniregistry',
			'fas fa-universal-access',
			'fas fa-university',
			'fas fa-unlink',
			'fas fa-unlock',
			'fas fa-unlock-alt',
			'fab fa-untappd',
			'fas fa-upload',
			'fab fa-usb',
			'fas fa-user',
			'far fa-user',
			'fas fa-user-circle',
			'far fa-user-circle',
			'fas fa-user-md',
			'fas fa-user-plus',
			'fas fa-user-secret',
			'fas fa-user-times',
			'fas fa-users',
			'fab fa-ussunnah',
			'fas fa-utensil-spoon',
			'fas fa-utensils',
			'fab fa-vaadin',
			'fas fa-venus',
			'fas fa-venus-double',
			'fas fa-venus-mars',
			'fab fa-viacoin',
			'fab fa-viadeo',
			'fab fa-viadeo-square',
			'fab fa-viber',
			'fas fa-video',
			'fab fa-vimeo',
			'fab fa-vimeo-square',
			'fab fa-vimeo-v',
			'fab fa-vine',
			'fab fa-vk',
			'fab fa-vnv',
			'fas fa-volume-down',
			'fas fa-volume-off',
			'fas fa-volume-up',
			'fab fa-vuejs',
			'fab fa-weibo',
			'fab fa-weixin',
			'fab fa-whatsapp',
			'fab fa-whatsapp-square',
			'fas fa-wheelchair',
			'fab fa-whmcs',
			'fas fa-wifi',
			'fab fa-wikipedia-w',
			'fas fa-window-close',
			'far fa-window-close',
			'fas fa-window-maximize',
			'far fa-window-maximize',
			'fas fa-window-minimize',
			'fas fa-window-restore',
			'far fa-window-restore',
			'fab fa-windows',
			'fas fa-won-sign',
			'fab fa-wordpress',
			'fab fa-wordpress-simple',
			'fab fa-wpbeginner',
			'fab fa-wpexplorer',
			'fab fa-wpforms',
			'fas fa-wrench',
			'fab fa-xbox',
			'fab fa-xing',
			'fab fa-xing-square',
			'fab fa-y-combinator',
			'fab fa-yahoo',
			'fab fa-yandex',
			'fab fa-yandex-international',
			'fab fa-yelp',
			'fas fa-yen-sign',
			'fab fa-yoast',
			'fab fa-youtube',
		);

	}
}

/**
 * Author Page
 *
 * @return void
 */
add_filter( 'template_include', 'buddyx_learndash_get_author_template', 13 );
function buddyx_learndash_get_author_template( $template ) {
	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		remove_action( 'buddyx_sub_header', 'buddyx_sub_header' );
		add_action( 'buddyx_sub_header', 'buddyx_learndash_instructor_header', 9 );
	}
	return $template;
}

function buddyx_learndash_instructor_header() {
	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		global $wpdb;
		$author_id          = get_query_var( 'author' );
		$first_name         = get_the_author_meta( 'user_firstname', $author_id );
		$last_name          = get_the_author_meta( 'user_lastname', $author_id );
		$author_name        = get_the_author_meta( 'display_name', $author_id );
		$_ld_instructor_ids = get_post_meta( $course_id, '_ld_instructor_ids', true );
		if ( ! empty( $first_name ) ) {
			$author_name = $first_name . ' ' . $last_name;
		}
		$author_avatar_url  = get_avatar_url( $author_id );
		$author_description = get_the_author_meta( 'description', $author_id );
		$social_links_list  = array();
		$email              = get_the_author_meta( 'email', $author_id );
		if ( ! empty( $email ) ) {
			$social_links_list[] = array(
				'title'      => esc_html__( 'Email', 'buddyxpro' ),
				'link'       => 'mailto:' . $email,
				'icon_class' => 'fa fa-envelope',
			);
		}
		$url = get_the_author_meta( 'url', $author_id );
		if ( ! empty( $url ) ) {
			$social_links_list[] = array(
				'title'      => esc_html__( 'Website', 'buddyxpro' ),
				'link'       => $url,
				'icon_class' => 'fa fa-link',
			);
		}
		if ( defined( 'WPSEO_VERSION' ) ) {
			$twitter = get_the_author_meta( 'twitter', $author_id );
			if ( ! empty( $twitter ) ) {
				$social_links_list[] = array(
					'title'      => esc_html__( 'Twitter', 'buddyxpro' ),
					'link'       => $twitter,
					'icon_class' => 'fa fa-twitter',
				);
			}
			$facebook = get_the_author_meta( 'facebook', $author_id );
			if ( ! empty( $facebook ) ) {
				$social_links_list[] = array(
					'title'      => esc_html__( 'Facebook', 'buddyxpro' ),
					'link'       => $facebook,
					'icon_class' => 'fa fa-facebook',
				);
			}
		}
		?>
		<div class="site-sub-header">
			<div class="container">
				<div class="buddyx-learndash-author-info">
					<div class="container">
						<div class="lm-course-author-info-tab">
							<div class="lm-course-author lm-course-author-avatar" itemscope="" itemtype="http://schema.org/Person">
								<img alt="instructor avatar" src="<?php echo esc_url( $author_avatar_url ); ?>" class="lm-author-avatar" width="150" height="150">
							</div>
							<div class="lm-author-bio">
								<div class="lm-author-top">
									<h4 class="lm-author-title"><?php echo $author_name; ?></h4>
								</div>
								<ul class="lm-author-social">
									<?php
									foreach ( $social_links_list as $key => $social_link ) {
										?>
										<li>
											<a href="<?php echo $social_link['link']; ?>"><i class="<?php echo $social_link['icon_class']; ?>"></i></a>
										</li>
										<?php
									}
									?>
								</ul>
								<div class="lm-author-description">
									<?php echo $author_description; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

add_action( 'pre_get_posts', 'buddyxld_author_course_list_query_args', 99 );
/**
 * Display instructo's course
 *
 * @param [type] $query
 * @return void
 */
function buddyxld_author_course_list_query_args( $query ) {
	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		$author_id             = get_query_var( 'author' );
		$_GET['course_instid'] = $author_id;
		add_filter( 'posts_clauses', 'buddyx_learndash_course_posts_clauses', 99 );
	}
	if ( isset( $_GET['course_catid'] ) && $_GET['course_catid'] != '' ) {
		$taxonomy_query = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'ld_course_category',
				'field'    => 'term_id',
				'terms'    => intval( $_GET['course_catid'] ),
			),
		);
		$query->set( 'tax_query', $taxonomy_query );
	}
	if ( isset( $_GET['course_instid'] ) && $_GET['course_instid'] != '' ) {
		add_filter( 'posts_clauses', 'buddyx_learndash_course_posts_clauses', 99 );
	}
	if ( isset( $_GET['course_instid'] ) && isset( $_GET['course_catid'] ) ) {
		add_filter( 'reign_set_sidebar_id', 'reign_learndash_reign_set_sidebar_id' );
	}
}

/**
 * This is helping function to remove action for search course author wise and category wise
 */
add_action( 'wp_head', 'remove_pre_get_posts_clauses', 99 );
function remove_pre_get_posts_clauses() {
	remove_action( 'pre_get_posts', 'buddyxld_author_course_list_query_args', 99 );
}

/**
 * This is helping function to display instructor courses
 *
 * @param [type] $clauses
 * @return void
 */
function buddyx_learndash_course_posts_clauses( $clauses ) {
	global $wpdb;
	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		$author_id             = get_query_var( 'author' );
		$_GET['course_instid'] = $author_id;
	}
	if ( isset( $_GET['course_instid'] ) && $_GET['course_instid'] != '' ) {
		$clauses['join']   .= " INNER JOIN {$wpdb->prefix}postmeta pm6 ON ( {$wpdb->prefix}posts.ID = pm6.post_id ) INNER JOIN {$wpdb->prefix}postmeta pm7 ON ( {$wpdb->prefix}posts.ID = pm7.post_id ) ";
		$where              = ' ' . $clauses['where'] . " AND {$wpdb->prefix}posts.post_author = {$_GET['course_instid']} ";
		$clauses['where']   = str_replace( "AND ({$wpdb->prefix}posts.post_author = " . $_GET['course_instid'] . ')', '', $clauses['where'] );
		$clauses['where']   = $where . " OR ( (  (pm6.meta_key = '_ld_instructor_ids' AND pm6.meta_value REGEXP '.*;s:[0-9]+:\"{$_GET['course_instid']}\"*' ) OR (pm7.meta_key = 'ir_shared_instructor_ids' AND  FIND_IN_SET ({$_GET['course_instid']}, pm7.meta_value)) ) {$clauses['where']}  )";
		$clauses['groupby'] = " {$wpdb->prefix}posts.ID";
		remove_filter( 'posts_clauses', 'buddyx_learndash_course_posts_clauses', 99 );
	}
	return $clauses;
}

/**
 * Display search form.
 *
 * @since 1.0.0
 *
 * @param bool $echo Default to echo and not return the form.
 * @return string|void String when $echo is false.
 */
function get_buddyx_ld_course_search_form( $echo = true ) {
	global $wpdb;

	do_action( 'pre_get_buddyx_ld_course_search_form' );

	$post_type_slug         = 'course';
	$ld_categorydropdown    = '';
	$ld_instructor_dropdown = '';
	$cat_filter             = get_theme_mod( 'ld_category_filter', buddyx_defaults( 'ld-category-filter' ) );
	$instructor_filter      = get_theme_mod( 'ld_instructors_filter', buddyx_defaults( 'ld-instructors-filter' ) );
	if ( ! empty( $cat_filter ) ) {
		// And also let this query be filtered.
		$get_ld_categories_args = array(
			'taxonomy' => 'ld_course_category',
			'type'     => 'course',
			'orderby'  => 'name',
			'order'    => 'ASC',
		);
		$ld_categories          = get_terms( $get_ld_categories_args );
		$ld_categorydropdown    = '<div id="buddyx-learndash-course-list-category-filters" class="select-wrap"> <select id="ld_' . $post_type_slug . '_categorydropdown_select" name="' . $post_type_slug . '_catid" onChange="jQuery(\'.courses-searching form\').submit()">';
		$ld_categorydropdown   .= '<option value="">' . sprintf(
			// translators: placeholder Category label
			esc_html__( 'All Categories', 'buddyxpro' )
		) . '</option>';

		foreach ( $ld_categories as $ld_category ) {
			$selected             = ( empty( $_GET[ $post_type_slug . '_catid' ] ) || $_GET[ $post_type_slug . '_catid' ] != $ld_category->term_id ) ? '' : 'selected="selected"';
			$ld_categorydropdown .= "<option value='" . $ld_category->term_id . "' " . $selected . '>' . $ld_category->name . '</option>';
		}

		$ld_categorydropdown .= '</select></div>';
	}

	if ( ! empty( $instructor_filter ) ) {
		$args = array(
			'orderby'  => 'user_nicename',
			'role__in' => array( 'administrator', 'ld_instructor', 'wdm_instructor' ),
			'order'    => 'ASC',
			'fields'   => array( 'ID', 'display_name' ),
		);

		$instructors = get_users( $args );

		$ld_instructor_dropdown  = '<div id="buddyx-learndash-course-list-category-filters" class="select-wrap"> <select id="ld_' . $post_type_slug . '_instructordropdown_select" name="' . $post_type_slug . '_instid" onChange="jQuery(\'.courses-searching form\').submit()">';
		$ld_instructor_dropdown .= '<option value="">' . sprintf(
			// translators: placeholder Category label
			esc_html__( 'All Instructors', 'buddyxpro' )
		) . '</option>';

		foreach ( $instructors as $ld_instructor ) {
			$course_get_sql = "SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta pm6 ON ( {$wpdb->prefix}posts.ID = pm6.post_id ) INNER JOIN {$wpdb->prefix}postmeta pm7 ON ( {$wpdb->prefix}posts.ID = pm7.post_id ) WHERE 1=1 AND ({$wpdb->prefix}posts.post_author = {$ld_instructor->ID} ) AND {$wpdb->prefix}posts.post_type = 'sfwd-courses' AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'graded' OR {$wpdb->prefix}posts.post_status = 'not_graded' OR {$wpdb->prefix}posts.post_status = 'private') AND {$wpdb->prefix}posts.post_author = {$ld_instructor->ID} OR ( ( (pm6.meta_key = '_ld_instructor_ids' AND 			pm6.meta_value REGEXP '.*;s:[0-9]+:\"{$ld_instructor->ID}\"*' ) OR (pm7.meta_key = 'ir_shared_instructor_ids' AND FIND_IN_SET ({$ld_instructor->ID}, pm7.meta_value)) ) AND {$wpdb->prefix}posts.post_type = 'sfwd-courses' AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'graded' OR {$wpdb->prefix}posts.post_status = 'not_graded' OR {$wpdb->prefix}posts.post_status = 'private') ) GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC LIMIT 1";
			$courses        = $wpdb->get_results( $course_get_sql );
			if ( ! empty( $courses ) ) {
				$selected                = ( empty( $_GET[ $post_type_slug . '_instid' ] ) || $_GET[ $post_type_slug . '_instid' ] != $ld_instructor->ID ) ? '' : 'selected="selected"';
				$ld_instructor_dropdown .= "<option value='" . $ld_instructor->ID . "' " . $selected . '>' . $ld_instructor->display_name . '</option>';

			}
		}

		$ld_instructor_dropdown .= '</select></div>';
	}

	$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';

	$format               = apply_filters( 'buddyx_ld_course_search_form_format', $format );
	$course_page_url      = get_post_type_archive_link( 'sfwd-courses' );
	$search_form_template = locate_template( 'buddyx_ld_course_searchform.php' );
	if ( '' != $search_form_template ) {
		ob_start();
		require $search_form_template;
		$form = ob_get_clean();
	} else {
		if ( 'html5' == $format ) {
			$form = '<form role="search" method="get" class="buddyx_ld_course_search-form search-form" action="' . esc_url( $course_page_url ) . '">
					' . $ld_categorydropdown . '
					' . $ld_instructor_dropdown . '
				<div class="ld-filter-input-wrap">
					<label>
						<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'buddyxpro' ) . '</span>
						<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'buddyxpro' ) . '" value="' . get_search_query() . '" name="s" />
					</label>
					<input name="post_type" value="sfwd-courses" type="hidden">
					<input type="submit" class="search-submit" value="' . esc_attr_x( 'Search', 'submit button', 'buddyxpro' ) . '" />
				</div>
				<input type="hidden" name="bp_search" class="rld_course_bp_search" value="0">
            </form>';
		} else {
			$form = '<form role="search" method="get" id="buddyx_ld_course_search-form searchform" class="searchform" action="' . esc_url( $course_page_url ) . '">
				<div id="buddyx-learndash-course-list-category-filters" class="select-wrap">
					' . $ld_categorydropdown . '
				</div>
				<div id="buddyx-learndash-course-list-category-filters" class="select-wrap">
					' . $ld_instructor_dropdown . '
				</div>
                <div class="ld-filter-input-wrap">
                    <label class="screen-reader-text" for="s">' . _x( 'Search for:', 'label', 'buddyxpro' ) . '</label>
                    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
                    <input name="post_type" value="sfwd-courses" type="hidden">
                    <input type="submit" id="searchsubmit" value="' . esc_attr_x( 'Search', 'submit button', 'buddyxpro' ) . '" />
                </div>
				<input type="hidden" name="bp_search" class="rld_course_bp_search" value="0">
            </form>';
		}
	}

	$result = apply_filters( 'get_buddyx_ld_course_search_form', $form );

	if ( null === $result ) {
		$result = $form;
	}

	if ( $echo ) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * Example usage for learndash-focus-header-usermenu-after action.
 */
add_action(
	'learndash-focus-header-usermenu-after',
	function( $course_id, $user_id ) {
		?>
		<a href="#" id="buddyx-toggle-track">
			<span class="learndash-dark-mode"><i class="fas fa-moon"></i></span>
			<span class="learndash-light-mode"><i class="fas fa-sun"></i></span>
		</a>
		<?php
	},
	10,
	2
);
