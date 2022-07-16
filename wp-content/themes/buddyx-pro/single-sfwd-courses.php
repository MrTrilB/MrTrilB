<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

use LearnDash_Custom_Label;

get_header();

buddyxpro()->print_styles('buddyxpro-content');
buddyxpro()->print_styles('buddyxpro-sidebar', 'buddyxpro-widgets');
?>

<?php do_action( 'buddyx_sub_header' );  ?>

<?php do_action('buddyx_before_content'); ?>

<div class="single-course-site-wrapper">
    <main id="primary" class="site-main">

        <?php
        if (have_posts()) {

            while (have_posts()) {
                the_post();

                get_template_part('template-parts/content/entry', get_post_type());
            }

            if (!is_singular()) {
                get_template_part('template-parts/content/pagination');
            }
        } else {
            get_template_part('template-parts/content/error');
        }
        ?>

    </main><!-- #primary -->

    <aside id="secondary" class="primary-sidebar widget-area learndash-course-widget">
        <div class="sticky-sidebar">
            <div class="learndash-course-widget-wrap">
                <?php
                $course_info = array();
                $course_id = learndash_get_course_id(get_the_ID());
                $course = get_post($course_id);
                $buddyx_ld_ccf_enable = get_post_meta($course_id, 'buddyx_ld_ccf_enable', true);
                $buddyx_ld_features = get_post_meta($course_id, 'buddyx_ld_features', true);
                if (is_user_logged_in()) {
                    $user_id = get_current_user_id();
                } else {
                    $user_id = 0;
                }

                $lessons = learndash_get_course_lessons_list($course);
                $course_info['lessons'] = count($lessons);

                $topics_count = 0;
                $quizzes_count = 0;
                foreach ($lessons as $lesson_index => $lesson) {
                    $topics = learndash_get_topic_list($lesson['post']->ID, $course_id);
                    if ($topics) {
                        $topics_count += count($topics);
                    }
                    $lesson_quiz_list = learndash_get_lesson_quiz_list($lesson['post']->ID, $user_id, $course_id);
                    $quizzes_count += count($lesson_quiz_list);
                }
                $course_info['topics'] = $topics_count;

                $quizzes = learndash_get_course_quiz_list($course);
                $course_info['quizzes'] = $quizzes_count + count($quizzes);
                $lms_veriosn = version_compare(LEARNDASH_VERSION, '2.6.4');
                if ($lms_veriosn >= 0) {
                    $certificate = learndash_get_course_meta_setting($course_id, 'certificate');
                } else {
                    $certificate = get_course_meta_setting($course_id, 'certificate');
                }
                if ($certificate) {
                    $course_info['certificate'] = esc_html__('Yes', 'buddyxpro');
                } else {
                    $course_info['certificate'] = esc_html__('No', 'buddyxpro');
                }

                $students_enrolled = learndash_get_users_for_course($course_id, array(), true);
                $query_args = $students_enrolled->query_vars;
                $students = array();
                if (isset($query_args['include']) && !empty($query_args['include'])) {
                    $students = $students_enrolled->get_results();
                }
                if (empty($students_enrolled)) {
                    $students_enrolled = array();
                } else {
                    $query_args = $students_enrolled->query_vars;
                    $students_enrolled = $query_args['include'];
                }
                $course_info['students'] = count($students_enrolled);

                $course_info['assignment'] = esc_html__('No', 'buddyxpro');
                foreach ($lessons as $key => $lesson) {
                    $course_step_post = get_post($lesson['post']->ID);
                    $post_settings = learndash_get_setting($course_step_post);
                    if (isset($post_settings['lesson_assignment_upload']) && ( 'on' === $post_settings['lesson_assignment_upload'] )) {
                        $course_info['assignment'] = esc_html__('Yes', 'buddyxpro');
                        break;
                    }
                }

                $_learndash_course_grid_video_embed_code = get_post_meta($course_id, '_learndash_course_grid_video_embed_code', true);
                if ($_learndash_course_grid_video_embed_code != '') :
                    echo '<div class="lm-course-thumbnail">';
                    echo wp_oembed_get($_learndash_course_grid_video_embed_code);
                    echo '</div>';
                else :
                    if (has_post_thumbnail($course_id)) {
                        echo '<div class="lm-course-thumbnail">';
                        echo get_the_post_thumbnail($course_id);
                        echo '</div>';
                    }
                endif;
                if (!empty($students)) :
                    ?>
                    <div class="lm-course-students-wrap">
                        <?php
                        $i = 0;
                        foreach ($students as $student) :
                            if ($i == 5) {
                                break;
                            }

                            $student_avatar_url = get_avatar_url($student);
                            ?>
                            <img alt="student avatar" src="<?php echo $student_avatar_url; ?>" class="lm-student-avatar" width="40"
                                height="40">
                                <?php
                                $i++;
                            endforeach;
                            if (count($students) > 5) {
                                echo '<span>+' . count($students) . '&nbsp;' . esc_html__('enrolled', 'buddyxpro') . '</span>';
                            }
                            ?>
                    </div>
                    <?php
                endif;

                /**
                 * Course info bar
                 */

                learndash_get_template_part(
                    'modules/infobar/course.php',
                    array(
                        'has_access'    => sfwd_lms_has_access( $course_id, $user_id ),
                        'user_id'       => $user_id,
                        'course_id'     => $course_id,
                        'course_status' => learndash_course_status( $course_id, $user_id ),
                        'post'          => $post,
                    ),
                    true
                );

                echo do_shortcode('[ld_course_resume course_id ="' . $course_id . '" user_id ="' . $user_id . '" label="' . esc_html__('Continue', 'buddyxpro') . '"]');

                $course_features_label = sprintf(esc_html_x('%s Features', 'Course Features  Label', 'buddyxpro'), LearnDash_Custom_Label::get_label('course'));

                echo '<div class="lm-tab-course-info">';
                echo '<h3 class="title">' . $course_features_label . '</h3>';
                $course_features = array(
                    'lessons' => array(
                        'slug' => 'lessons',
                        'label' => LearnDash_Custom_Label::get_label('lessons'),
                        'value' => $course_info['lessons'],
                        'icon' => 'fas fa-copy',
                    ),
                    'topics' => array(
                        'slug' => 'lessons',
                        'label' => LearnDash_Custom_Label::get_label('topics'),
                        'value' => $course_info['topics'],
                        'icon' => 'fas fa-bookmark',
                    ),
                    'quizzes' => array(
                        'slug' => 'quizzes',
                        'label' => LearnDash_Custom_Label::get_label('quizzes'),
                        'value' => $course_info['quizzes'],
                        'icon' => 'fas fa-puzzle-piece',
                    ),
                    'students' => array(
                        'slug' => 'students',
                        'label' => esc_html__('Students', 'buddyxpro'),
                        'value' => $course_info['students'],
                        'icon' => 'fas fa-users',
                    ),
                    'certificate' => array(
                        'slug' => 'certificate',
                        'label' => esc_html__('Certificate', 'buddyxpro'),
                        'value' => $course_info['certificate'],
                        'icon' => 'fas fa-graduation-cap',
                    ),
                    'assignment' => array(
                        'slug' => 'assignment',
                        'label' => esc_html__('Assignment', 'buddyxpro'),
                        'value' => $course_info['assignment'],
                        'icon' => 'fas fa-pencil-alt',
                    ),
                );
                $course_features = apply_filters('learnmate_modify_course_features_in_tab', $course_features);
                echo '<ul>';

                if ($buddyx_ld_ccf_enable == 'yes') {
                    for ($i = 0; $i < sizeof($buddyx_ld_features['icon']); $i++) {
                        ?>
                        <li class="<?php echo $course_feature['slug']; ?>">
                            <i class="<?php echo $buddyx_ld_features['icon'][$i]; ?>"></i>
                            <span class="lm-course-feature-value"><?php echo $buddyx_ld_features['text'][$i]; ?></span>
                        </li>
                        <?php
                    }
                } else {
                    foreach ($course_features as $course_feature) {
                        ?>
                        <li class="<?php echo $course_feature['slug']; ?>">
                            <i class="<?php echo $course_feature['icon']; ?>"></i>
                            <span class="lm-course-feature-label"><?php echo $course_feature['label']; ?></span>
                            <span class="lm-course-feature-value"><?php echo $course_feature['value']; ?></span>
                        </li>
                        <?php
                    }
                }
                echo '</ul>';
                echo '</div>';
                ?>
            </div>
        </div>
    </aside>
</div>

<?php do_action('buddyx_after_content'); ?>
<?php
get_footer();
