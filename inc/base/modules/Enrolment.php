<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Enrolment extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_enrolment', array($this, 'post'));
    }

    // Main method for handling all the post data submitted during a form submission
    public function post()
    {
        $task = sanitize_text_field($_POST['task']);
        $this->handle_posts($task);
    }

    // Method for handling form submission according to task of the form
    public function handle_posts($task)
    {
    }

    /**
     * Undocumented function
     *
     * @param $student_id
     * @return array
     */
    public static function get_enrolled_courses($student_id = null)
    {
        $student_id = isset($student_id) && !empty($student_id) ? $student_id : get_current_user_id();
        global $wpdb;
        $table = self::$tables['enrolment'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `enrolment_user_id` = %d ORDER BY $table.`enrolment_date` ASC", $student_id));
        return $result;
    }

    /**
     * GETTING NUMBER OF ENROLMENTS
     *
     * @param $course_id
     * @return array
     */
    public static function get_number_of_enrolled_student($course_id)
    {
        global $wpdb;
        $table = self::$tables['enrolment'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `enrolment_course_id` = %d ORDER BY $table.`enrolment_date` ASC", $course_id));
        return $result;
    }


    /**
     * THIS FUNCTION IS USED FOR ENROLLING A STUDENT AFTER A SUCCESSFUL PAYMENT
     *
     * @param int $user_id
     * @param int $course_id
     * @return bool
     */
    public static function enrol_after_payment($user_id, $course_id)
    {
        if (!empty($user_id) && !empty($course_id)) {
            $table = self::$tables['enrolment'];
            $data['enrolment_date'] = strtotime(date('D, d-M-Y'));
            global $wpdb;
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `enrolment_user_id` = %d AND `enrolment_course_id` = %d", $user_id, $course_id));
            if (count($result) > 0) {
                return false;
            } else {
                $data['enrolment_user_id'] = $user_id;
                $data['enrolment_course_id'] = $course_id;
                $wpdb->insert($table, $data);
                return true;
            }
        }
    }

    /**
     * THIS FUNCTION IS REQUIRED TO SAVE THE COURSE PROGRESS
     *
     * @param int $course_id
     * @param int $lesson_id
     * @param int $progress
     * @return json
     */
    public static function save_course_progress($course_id, $lesson_id, $progress)
    {
        $current_logged_in_user_id = get_current_user_id();
        global $wpdb;
        $table = self::$tables['watch_history'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `watch_history_user_id` = %d AND `watch_history_course_id` = %d", $current_logged_in_user_id, $course_id));

        if (count($result) > 0) {
            $result = $result[0];
            $completed_lessons = !empty($result->watch_history_completed_lessons) ? json_decode($result->watch_history_completed_lessons) : array();
            if ($progress) {
                if (!in_array($lesson_id, $completed_lessons)) {
                    array_push($completed_lessons, $lesson_id);
                }
            } else {
                if (in_array($lesson_id, $completed_lessons)) {
                    $key = array_search($lesson_id, $completed_lessons);
                    if (false !== $key) {
                        unset($completed_lessons[$key]);
                    }
                }
            }

            $data['watch_history_completed_lessons'] = json_encode($completed_lessons);

            $wpdb->update($table, $data, array('watch_history_id' => $result->watch_history_id));
        } else {
            $data['watch_history_user_id'] = $current_logged_in_user_id;
            $data['watch_history_course_id'] = sanitize_text_field($course_id);
            if ($progress) {
                $data['watch_history_completed_lessons'] = json_encode(array($lesson_id));
            }
            $wpdb->insert($table, $data);
        }

        return json_encode(['status' => true, 'message' => esc_html__("Course Progress Saved Successfully", BaseController::$text_domain)]);
    }


    /**
     * SAVING THE LAST PLAYED LESSON
     *
     * @param int $course_id
     * @param int $lesson_id
     * @return json
     */
    public static function save_last_played_lesson($course_id, $lesson_id)
    {
        $current_logged_in_user_id = get_current_user_id();
        global $wpdb;
        $table = self::$tables['watch_history'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `watch_history_user_id` = %d AND `watch_history_course_id` = %d", $current_logged_in_user_id, $course_id));

        if (count($result) > 0) {
            $result = $result[0];

            $data['watch_history_last_played_lesson_id'] = sanitize_text_field($lesson_id);

            $wpdb->update($table, $data, array('watch_history_id' => $result->watch_history_id));
        } else {
            $data['watch_history_user_id'] = $current_logged_in_user_id;
            $data['watch_history_course_id'] = sanitize_text_field($course_id);
            $data['watch_history_last_played_lesson_id'] = sanitize_text_field($lesson_id);
            $wpdb->insert($table, $data);
        }

        return json_encode(['status' => true, 'message' => esc_html__("Course Progress Saved Successfully", BaseController::$text_domain)]);
    }


    /**
     * GET WATCH HISTORY FOR THE COURSE
     *
     * @param int $course_id
     * @return array
     */
    public static function get_watch_history($course_id)
    {
        $current_logged_in_user_id = get_current_user_id();
        global $wpdb;
        $table = self::$tables['watch_history'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `watch_history_user_id` = %d AND `watch_history_course_id` = %d", $current_logged_in_user_id, $course_id));

        $watch_history = array();

        if (count($result) > 0) {
            $result = $result[0];

            $watch_history = json_decode($result->watch_history_completed_lessons);
        }

        return $watch_history;
    }
}
