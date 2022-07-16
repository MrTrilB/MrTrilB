<?php

/**
 * Learndash Feature Course Widget.
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


class LD_Course_Features_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'ld_course_features_widget',
			'description' => sprintf(
				__( 'Display an associated items of %s ', 'buddyxpro' ),
				LearnDash_Custom_Label::get_label( 'course' )
			),
		);
		parent::__construct(
			'ld_course_features_widget',
			sprintf(
				__( '%s Features', 'buddyxpro' ),
				LearnDash_Custom_Label::get_label( 'course' )
			),
			$widget_ops
		);
	}

	/**
	 * Outputs the content for the current Search widget instance.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		$course_info       = array();
		$lesson_value      = isset( $instance['lesson'] ) ? (bool) $instance['lesson'] : false;
		$topics_value      = isset( $instance['topics'] ) ? (bool) $instance['topics'] : false;
		$quizzes_value     = isset( $instance['quizzes'] ) ? (bool) $instance['quizzes'] : false;
		$student_value     = isset( $instance['student'] ) ? (bool) $instance['student'] : false;
		$certificate_value = isset( $instance['certificate'] ) ? (bool) $instance['certificate'] : false;
		$assignment_value  = isset( $instance['assignment'] ) ? (bool) $instance['assignment'] : false;
		$course_id         = learndash_get_course_id( get_the_ID() );
		$course            = get_post( $course_id );
		$title             = apply_filters( 'widget_title', $instance['title'] );

		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
		} else {
			$user_id = 0;
		}

		$lessons                = learndash_get_course_lessons_list( $course );
		$course_info['lessons'] = count( $lessons );

		$topics_count  = 0;
		$quizzes_count = 0;
		foreach ( $lessons as $lesson_index => $lesson ) {
			$topics = learndash_get_topic_list( $lesson['post']->ID, $course_id );
			if ( $topics ) {
				$topics_count += count( $topics );
			}
			$lesson_quiz_list = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id, $course_id );
			$quizzes_count   += count( $lesson_quiz_list );
		}
		$course_info['topics'] = $topics_count;

		$quizzes                = learndash_get_course_quiz_list( $course );
		$course_info['quizzes'] = $quizzes_count + count( $quizzes );
		$lms_veriosn            = version_compare( LEARNDASH_VERSION, '2.6.4' );
		if ( $lms_veriosn >= 0 ) {
			$certificate = learndash_get_course_meta_setting( $course_id, 'certificate' );
		} else {
			 $certificate = get_course_meta_setting( $course_id, 'certificate' );
		}
		if ( $certificate ) {
			$course_info['certificate'] = esc_html__( 'Yes', 'buddyxpro' );
		} else {
			$course_info['certificate'] = esc_html__( 'No', 'buddyxpro' );
		}

		$students_enrolled = learndash_get_users_for_course( $course_id, array(), true );
		if ( empty( $students_enrolled ) ) {
			$students_enrolled = array();
		} else {
			$query_args        = $students_enrolled->query_vars;
			$students_enrolled = $query_args['include'];
		}
		$course_info['students'] = count( $students_enrolled );

		$course_info['assignment'] = esc_html__( 'No', 'buddyxpro' );
		foreach ( $lessons as $key => $lesson ) {
			$course_step_post = get_post( $lesson['post']->ID );
			$post_settings    = learndash_get_setting( $course_step_post );
			if ( isset( $post_settings['lesson_assignment_upload'] ) && ( 'on' === $post_settings['lesson_assignment_upload'] ) ) {
				$course_info['assignment'] = esc_html__( 'Yes', 'buddyxpro' );
				break;
			}
		}
		$course_features = array();
		if ( true === $lesson_value ) {
			$course_features['lessons'] = array(
				'slug'  => 'lessons',
				'label' => LearnDash_Custom_Label::get_label( 'lessons' ),
				'value' => $course_info['lessons'],
				'icon'  => 'fas fa-copy',
			);
		}
		if ( true === $topics_value ) {
			$course_features['topics'] = array(
				'slug'  => 'lessons',
				'label' => LearnDash_Custom_Label::get_label( 'topics' ),
				'value' => $course_info['topics'],
				'icon'  => 'fas fa-bookmark',
			);
		}
		if ( true === $quizzes_value ) {
			$course_features['quizzes'] = array(
				'slug'  => 'quizzes',
				'label' => LearnDash_Custom_Label::get_label( 'quizzes' ),
				'value' => $course_info['quizzes'],
				'icon'  => 'fas fa-puzzle-piece',
			);
		}
		if ( true === $student_value ) {
			$course_features['students'] = array(
				'slug'  => 'students',
				'label' => esc_html__( 'Students', 'buddyxpro' ),
				'value' => $course_info['students'],
				'icon'  => 'fas fa-users',
			);
		}
		if ( true === $certificate_value ) {
			$course_features['certificate'] = array(
				'slug'  => 'certificate',
				'label' => esc_html__( 'Certificate', 'buddyxpro' ),
				'value' => $course_info['certificate'],
				'icon'  => 'fas fa-graduation-cap',
			);
		}
		if ( true === $assignment_value ) {
			$course_features['assignment'] = array(
				'slug'  => 'assignment',
				'label' => esc_html__( 'Assignment', 'buddyxpro' ),
				'value' => $course_info['assignment'],
				'icon'  => 'fas fa-pencil-alt',
			);
		}

		$course_features = apply_filters( 'buddyx_ld_modify_course_features', $course_features );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
	<div class="lm-tab-course-info">
	<ul>
		<?php
		if ( ! empty( $course_features ) && is_array( $course_features ) ) {
			foreach ( $course_features as $course_feature ) {
				?>
		<li class="<?php echo $course_feature['slug']; ?>">
		  <i class="<?php echo $course_feature['icon']; ?>"></i>
		  <span class="lm-course-feature-label"><?php echo $course_feature['label']; ?></span>
		  <span class="lm-course-feature-value"><?php echo $course_feature['value']; ?></span>
		</li>
				<?php
			}
		}
		?>
	</ul>
	</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = sprintf( __( '%s Features', 'buddyxpro' ), LearnDash_Custom_Label::get_label( 'course' ) );
		}

		$lesson      = isset( $instance['lesson'] ) ? (bool) $instance['lesson'] : false;
		$topics      = isset( $instance['topics'] ) ? (bool) $instance['topics'] : false;
		$quizzes     = isset( $instance['quizzes'] ) ? (bool) $instance['quizzes'] : false;
		$student     = isset( $instance['student'] ) ? (bool) $instance['student'] : false;
		$certificate = isset( $instance['certificate '] ) ? (bool) $instance['certificate '] : false;
		$assignment  = isset( $instance['assignment'] ) ? (bool) $instance['assignment'] : false;

		?>
		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		 </p>
		 <p>
	   <input class="checkbox" id="<?php echo $this->get_field_id( 'lesson' ); ?>" <?php checked( $lesson ); ?> name="<?php echo $this->get_field_name( 'lesson' ); ?>" type="checkbox" />
	   <label for="<?php echo $this->get_field_name( 'lesson' ); ?>"><?php printf( esc_html__( '%s', 'buddyxpro' ), LearnDash_Custom_Label::get_label( 'lesson' ) ); ?></label>
		 </p>
	   <p>
			<input class="checkbox" id="<?php echo $this->get_field_id( 'topics' ); ?>" <?php checked( $topics ); ?> name="<?php echo $this->get_field_name( 'topics' ); ?>" type="checkbox" />
			<label for="<?php echo $this->get_field_name( 'topics' ); ?>"><?php printf( esc_html__( '%s', 'buddyxpro' ), LearnDash_Custom_Label::get_label( 'topic' ) ); ?></label>
	   </p>
	 <p>
			<input class="checkbox" id="<?php echo $this->get_field_id( 'quizzes' ); ?>" <?php checked( $quizzes ); ?> name="<?php echo $this->get_field_name( 'quizzes' ); ?>" type="checkbox" />
		  <label for="<?php echo $this->get_field_name( 'quizzes' ); ?>"><?php printf( esc_html__( '%s', 'buddyxpro' ), LearnDash_Custom_Label::get_label( 'quiz' ) ); ?></label>
	 </p>
	 <p>
		 <input class="checkbox" id="<?php echo $this->get_field_id( 'student' ); ?>" <?php checked( $student ); ?> name="<?php echo $this->get_field_name( 'student' ); ?>" type="checkbox" />
		 <label for="<?php echo $this->get_field_name( 'student' ); ?>"><?php _e( 'Student' ); ?></label>
	 </p>
   <p>
	 <input class="checkbox" id="<?php echo $this->get_field_id( 'certificate' ); ?>" <?php checked( $certificate ); ?> name="<?php echo $this->get_field_name( 'certificate' ); ?>" type="checkbox" />
	 <label for="<?php echo $this->get_field_name( 'certificate' ); ?>"><?php _e( 'Certificate' ); ?></label>
   </p>
   <p>
	  <input class="checkbox" id="<?php echo $this->get_field_id( 'assignment' ); ?>" <?php checked( $assignment ); ?> name="<?php echo $this->get_field_name( 'assignment' ); ?>" type="checkbox" />
	  <label for="<?php echo $this->get_field_name( 'assignment' ); ?>"><?php _e( 'Assignment' ); ?></label>
   </p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = array();
		$instance['title']       = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['lesson']      = isset( $new_instance['lesson'] ) ? (bool) $new_instance['lesson'] : false;
		$instance['topics']      = isset( $new_instance['topics'] ) ? (bool) $new_instance['topics'] : false;
		$instance['quizzes']     = isset( $new_instance['quizzes'] ) ? (bool) $new_instance['quizzes'] : false;
		$instance['student']     = isset( $new_instance['student'] ) ? (bool) $new_instance['student'] : false;
		$instance['certificate'] = isset( $new_instance['certificate'] ) ? (bool) $new_instance['certificate'] : false;
		$instance['assignment']  = isset( $new_instance['assignment'] ) ? (bool) $new_instance['assignment'] : false;
		return $instance;
	}

}

add_action(
	'widgets_init',
	function () {
		if ( class_exists( 'SFWD_LMS' ) ) {
			register_widget( 'LD_Course_Features_Widget' );
		}
	}
);

?>
