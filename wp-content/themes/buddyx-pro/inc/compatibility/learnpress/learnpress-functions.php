<?php
/**
 * Custom functions for LearnPress 3.x
 *
 * @package buddyxpro
 * 
 */


if ( !function_exists( 'learn_press_learnpress_hooks' ) ) {
	function learn_press_learnpress_hooks() {

		add_action( 'thim_single_course_payment', 'learn_press_course_price', 5 );
		add_action( 'thim_single_course_payment', 'learn_press_course_external_button', 10 );
		add_action( 'thim_single_course_payment', 'learn_press_course_purchase_button', 15 );
		add_action( 'thim_single_course_payment', 'learn_press_course_enroll_button', 20 );

		add_action( 'thim_single_course_payment', 'learn_press_course_price', 5 );
		add_action( 'thim_single_course_payment', 'learn_press_course_external_button', 10 );
		add_action( 'thim_single_course_payment', 'learn_press_course_purchase_button', 15 );
		add_action( 'thim_single_course_payment', 'learn_press_course_enroll_button', 20 );
		add_action( 'thim_single_course_meta', 'learn_press_course_instructor', 5 );
		add_action( 'thim_single_course_meta', 'learn_press_course_categories', 15 );
		add_action( 'thim_single_course_meta', 'buddyx_course_ratings', 25 );
		add_action( 'thim_single_course_meta', 'learn_press_course_progress', 30 );
		}
	}

add_action( 'after_setup_theme', 'learn_press_learnpress_hooks', 15 );

/**
 * Display course info
 */
if ( !function_exists( 'thim_course_info' ) ) {
	function thim_course_info() {
		$course    = LP()->global['course'];
		$course_id = get_the_ID();

		$course_skill_level = get_post_meta( $course_id, 'thim_course_skill_level', true );
		$course_language    = get_post_meta( $course_id, 'thim_course_language', true );
		$course_duration    = get_post_meta( $course_id, 'thim_course_duration', true );

		?>
		<div class="thim-course-info">
			<h3 class="title"><?php esc_html_e( 'Course Features', 'buddyxpro' ); ?></h3>
			<ul>
				<li class="lectures-feature">
					<i class="fa fa-files-o"></i>
					<span class="label"><?php esc_html_e( 'Lectures', 'buddyxpro' ); ?></span>
					<span
						class="value"><?php echo $course->get_curriculum_items( 'lp_lesson' ) ? count( $course->get_curriculum_items( 'lp_lesson' ) ) : 0; ?></span>
				</li>
				<li class="quizzes-feature">
					<i class="fa fa-puzzle-piece"></i>
					<span class="label"><?php esc_html_e( 'Quizzes', 'buddyxpro' ); ?></span>
					<span
						class="value"><?php echo $course->get_curriculum_items( 'lp_quiz' ) ? count( $course->get_curriculum_items( 'lp_quiz' ) ) : 0; ?></span>
				</li>
				<li class="students-feature">
					<i class="fa fa-users"></i>
					<span class="label"><?php esc_html_e( 'Students', 'buddyxpro' ); ?></span>
					<?php $user_count = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0; ?>
					<span class="value"><?php echo esc_html( $user_count ); ?></span>
				</li>
				<?php //thim_course_certificate( $course_id ); ?>
				<li class="assessments-feature">
					<i class="fa fa-check-square-o"></i>
					<span class="label"><?php esc_html_e( 'Assessments', 'buddyxpro' ); ?></span>
					<span
						class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'evaluate_lesson' ) ? esc_html__( 'Yes', 'buddyxpro' ) : esc_html__( 'Self', 'buddyxpro' ); ?></span>
				</li>
			</ul>
			<?php do_action( 'thim_after_course_info' ); ?>
		</div>
		<?php
	}
}

/**
 * Display course ratings
 */
if ( !function_exists( 'buddyx_course_ratings' ) ) {
	function buddyx_course_ratings() {

		if ( !is_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}
		$_reviewCount = $_aggregateRating = $rating_meta = '';
		$course_id    = get_the_ID();
		$course_rate  = learn_press_get_course_rate( $course_id );
		$ratings      = learn_press_get_course_rate_total( $course_id );
		if ( is_single() && $ratings > 0 ) {
			$_aggregateRating = 'itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"';
			$_reviewCount     = 'itemprop="reviewCount"';
			$rating_meta      = '<meta itemprop="ratingValue" content="' . esc_attr( $course_rate ) . '"/>
						<meta itemprop="ratingCount" content="' . esc_attr( $ratings ) . '"/>
						<div itemprop="itemReviewed" itemscope="" itemtype="http://schema.org/Organization">
							<meta itemprop="name" content="' . get_the_title( $course_id ) . '"/>
						</div>';
		}
		?>
		<div class="course-review">
			<label><?php esc_html_e( 'Review', 'buddyxpro' ); ?></label>
			<div class="value"<?php echo $_aggregateRating; ?>>
				<?php buddyx_print_rating( $course_rate ); ?>
				<span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'buddyxpro' ), '<span ' . $_reviewCount . '>' . number_format_i18n( $ratings ) . '</span>' ) : printf( __( '(%1$s review)', 'buddyxpro' ), '<span ' . $_reviewCount . '>0</span>' ); ?></span>
				<?php
				echo $rating_meta;
				?>
			</div>
		</div>
		<?php
	}
}

if ( !function_exists( 'buddyx_print_rating' ) ) {
	function buddyx_print_rating( $rate ) {
		if ( !is_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		?>
		<div class="review-stars-rated">
			<ul class="review-stars">
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
			</ul>
			<ul class="review-stars filled"
				style="<?php echo esc_attr( 'width: calc(' . ( $rate * 20 ) . '% - 2px)' ) ?>">
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
			</ul>
		</div>
		<?php

	}
}