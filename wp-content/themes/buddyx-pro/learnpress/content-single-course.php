<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-single-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

/**
 * @deprecated
 */
do_action( 'learn_press_before_main_content' );
do_action( 'learn_press_before_single_course' );
do_action( 'learn_press_before_single_course_summary' );

/**
 * @since 3.0.0
 */
do_action( 'learn-press/before-main-content' );

do_action( 'learn-press/before-single-course' );

?>

<div class="learnpress-content learn-press">
<div class="header_single_content">
    <div class="course-meta">
        <?php do_action( 'thim_single_course_meta' );?>
    </div>
    <div class="course-payment">
        <?php do_action( 'thim_single_course_payment' );?>
     </div>
</div>
</div>

<div class="course-thumbnail">
	<?php
	if ( has_post_thumbnail() && ! post_password_required() && is_singular() ) {
		?>
			<?php the_post_thumbnail(); ?>
		<?php
	}
	?>
</div>

<div id="learn-press-course" class="course-summary">
	<?php

	/**
	 * @since 3.0.0
	 *
	 * @see learn_press_single_course_summary()
	 */
	do_action( 'learn-press/single-course-summary' );
	?>
</div>
<?php

/**
 * @since 3.0.0
 */
do_action( 'learn-press/after-main-content' );

do_action( 'learn-press/after-single-course' );

/**
 * @deprecated
 */
do_action( 'learn_press_after_single_course_summary' );
do_action( 'learn_press_after_single_course' );
do_action( 'learn_press_after_main_content' );