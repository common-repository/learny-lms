<?php

use Learny\base\BaseController;
use Learny\base\modules\Enrolment;
use Learny\base\modules\Lesson;

$basecontroller = new BaseController();


defined('ABSPATH') or die('Direct access is prohibitted');

// STUDENT DASHBOARD PAGE
$learny_student_dashboard_page = esc_html(get_option('ly_dashboard_page'));
$learny_student_dashboard_page_url = esc_url_raw(get_permalink($learny_student_dashboard_page));

$course_slug = !empty($_GET['course']) ? sanitize_text_field($_GET['course']) : "";
$lesson_slug = !empty($_GET['lesson']) ? sanitize_text_field($_GET['lesson']) : "";

$args = array(
    'name'        => $course_slug,
    'post_type'   => 'learny-courses',
    'post_status' => 'publish',
    'numberposts' => 1
);
$course_details = get_posts($args);

if ($course_slug && $course_details) :

    $course_details = $course_details[0];

    // GETTING THE WATCH HISTORY ARRAY
    $watch_history = Enrolment::get_watch_history($course_details->ID);


    $lesson_slug = !empty($lesson_slug) ? $lesson_slug : Lesson::get_last_played_lesson($course_details->ID);

    $args = array(
        'compare'     => '=',
        'name'        => $lesson_slug ?? "  ",
        'post_type'   => 'learny-lessons',
        'post_status' => 'publish',
        'numberposts' => 1,
        'posts_per_page' => 1
    );

    $selected_lesson = new WP_Query($args);
    if ($selected_lesson->found_posts) :
?>
        <div class="learny-preloader"></div>
        <div class="container-fluid overflow-hidden learny-shortcode-full-width learny-page-content learny-d-none">

            <!-- COURSE HEADER WITH NECESSARY BUTTONS AND TITLES -->
            <div class="row learny-student-page-header bg-light">
                <h3>
                    <?php echo esc_html($course_details->post_title); ?>
                    <a href="<?php echo esc_url_raw(get_the_permalink($course_details->ID)); ?>" class="btn btn-primary float-end me-1"><?php esc_html_e('Course Details', BaseController::$text_domain); ?></a>
                    <a href="<?php echo esc_url_raw($learny_student_dashboard_page_url); ?>" class="btn btn-primary float-end me-1"><?php esc_html_e('Back To My Courses', BaseController::$text_domain); ?></a>
                </h3>
            </div>

            <!-- LESSON SIDEBAR AND LESSOB BODY -->
            <div class="row vh-100 overflow-auto">
                <div class="col-sm-12 col-xl-8 d-flex flex-column h-sm-100">
                    <?php include 'player.php'; ?>
                </div>
                <div class="col-sm-12 col-xl-4 px-0 bg-light d-flex sticky-top learny-course-player-accordion">
                    <?php include 'sidebar.php'; ?>
                </div>
            </div>

            <!-- BOTTOM TABS -->
            <div class="row vh-100 overflow-auto mt-3">
                <div class="col-sm-12 col-xl-12 d-flex flex-column h-sm-100">
                    <?php include 'tabs.php'; ?>
                </div>
            </div>

        </div>
    <?php else : ?>
        <h2><?php esc_html_e('Invalid Lesson', BaseController::$text_domain); ?></h2>
    <?php endif; ?>
<?php else : ?>
    <h2><?php esc_html_e('Invalid Course', BaseController::$text_domain); ?></h2>
<?php endif; ?>

<script>
    "use strict";
    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            jQuery(".learny-preloader").addClass('learny-d-none');
            jQuery('.learny-page-content').removeClass('learny-d-none');

            jQuery('.learny-lesson-completion-checkbox').on('click', trackCourseProgress);

            saveLastViewedLesson();
        }, 500);
    }, false);


    // TRACKING COURSE PROGRESS
    function trackCourseProgress() {
        let value = jQuery(this).val();
        value = value.split("-");
        let courseId = value[0];
        let lessonId = value[1];
        var progress;

        if (jQuery(this).is(':checked')) {
            progress = 1;
        } else {
            progress = 0;
        }

        learnyMakeGenericAjaxCall('save_course_progress', null, courseId, lessonId, progress).then(function(response) {

            // DO STUFF HERE
        }).catch(function(err) {
            // Run this when promise was rejected via reject()
            console.log(err);
        });
    }

    // SAVING THE LAST VIEWED LESSON
    function saveLastViewedLesson() {
        let currentLessonId = '<?php echo esc_js($selected_lesson_details->ID); ?>';
        let currentCourseId = '<?php echo esc_js($course_details->ID); ?>';

        if (currentLessonId && currentCourseId) {
            learnyMakeGenericAjaxCall('save_last_played_lesson', null, currentCourseId, currentLessonId).then(function(response) {

                // DO STUFF HERE
            }).catch(function(err) {});
        }
    }
</script>