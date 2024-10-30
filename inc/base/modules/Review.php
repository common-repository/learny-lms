<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Review extends BaseController
{

    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_review', array($this, 'post'));
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
        switch ($task) {
            case 'edit_review':
                $this->edit_review();
                break;
        }
    }

    public static function edit_review()
    {
        $table = self::$tables['review'];
        if (self::verify_nonce('edit_review_nonce') == true) {
            $user_id = get_current_user_id();
            $course_id     = sanitize_text_field($_POST['review_course_id']);
            $data['review_details'] = sanitize_text_field($_POST['review_for_course_' . $course_id]);
            $data['review_rating'] = sanitize_text_field($_POST['rating_for_course_' . $course_id]);
            $data['review_course_id'] = $course_id;
            $data['review_user_id'] = get_current_user_id();
            $data['review_date'] = strtotime(date('D, d-M-Y'));

            if (Helper::has_purchased($course_id)) {
                global $wpdb;
                $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `review_course_id` = %d AND `review_user_id` = %d", $course_id, $user_id));
                if ($result && count($result) > 0) {
                    $result = $result[0];
                    $wpdb->update($table, $data, array('review_id' => $result->review_id));
                } else {
                    $wpdb->insert($table, $data);
                }

                // INSERT AVG RATING INTO COURSE TABLE ALSO
                $avg_rating = Review::get_course_rating($course_id);
                update_post_meta($course_id, 'ly_course_rating', $avg_rating);

                echo json_encode(['status' => true, 'message' => esc_html__("Review Updated Successfully", BaseController::$text_domain), 'course_id' => $course_id, 'rating' => $data['review_rating'], 'review' => $data['review_details']]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $course_id
     * @return void
     */
    public static function get_course_rating($course_id)
    {
        $table = self::$tables['review'];
        global $wpdb;
        $total_rating = 0;
        $ratings = $wpdb->get_results($wpdb->prepare("SELECT `review_rating` FROM $table WHERE `review_course_id` = %d", $course_id));
        $number_of_rows = count($ratings);
        foreach ($ratings as $key => $rating) {
            $total_rating = $total_rating + $rating->review_rating;
        }

        if ($total_rating && $number_of_rows) {
            $avg_rating = $total_rating / $number_of_rows;
        } else {
            $avg_rating = 0;
        }
        return $avg_rating;
    }

    /**
     * Undocumented function
     *
     * @param int $user_id
     * @param int $course_id
     * @return object
     */
    public static function get_user_wise_course_review($user_id, $course_id)
    {
        $table = self::$tables['review'];
        global $wpdb;

        $review = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE `review_course_id` = %d AND `review_user_id` = %d", $course_id, $user_id));
        return $review;
    }


    /**
     * THIS FUNCTION RETURNS THE NUMBER OF REVIEWERS
     *
     * @param int $course_id
     * @return int
     */
    public static function get_number_of_reviewers($course_id)
    {
        $table = self::$tables['review'];
        global $wpdb;

        $ratings = $wpdb->get_results($wpdb->prepare("SELECT `review_rating` FROM $table WHERE `review_course_id` = %d", $course_id));
        $number_of_rows = count($ratings);
        return $number_of_rows > 0 ? $number_of_rows : 0;
    }


    /**
     * GET A COURSE REVIEWS
     *
     * @param integer $course_id
     * @return array
     */
    public static function get_course_reviews(int $course_id)
    {
        $table = self::$tables['review'];
        global $wpdb;

        $reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `review_course_id` = %d", $course_id));
        return $reviews;
    }

    /**
     * GET A COURSE REVIEWS RATING WISE
     *
     * @param integer $course_id
     * @return array
     */
    public static function get_rating_wise_course_review(int $course_id)
    {
        $rating_wise_course_review = array(0, 0, 0, 0, 0, 0);
        $table = self::$tables['review'];
        global $wpdb;

        $reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `review_course_id` = %d", $course_id));

        foreach ($reviews as $review) {
            $rating_wise_course_review[$review->review_rating] = $rating_wise_course_review[$review->review_rating] + 1;
        }

        unset($rating_wise_course_review[0]);

        foreach ($rating_wise_course_review as $rating => $total_number_of_rating) {
            if (count($reviews) == 0) {
                $rating_wise_course_review[$rating] = 0;
            } else {
                $rating_wise_course_review[$rating] = ($total_number_of_rating * 100) / count($reviews);
            }
        }

        return $rating_wise_course_review;
    }


    /**
     * GET INSTRUCTOR RATING
     *
     * @param integer $instructor_id
     * @return float
     */
    public static function get_instructor_rating(int $instructor_id)
    {
        $table = self::$tables['review'];
        global $wpdb;
        $course_ids = Instructor::get_instructor_course_ids($instructor_id);
        $course_ids = implode(',', $course_ids);
        $reviews = $wpdb->get_results($wpdb->prepare("SELECT `review_rating` FROM $table WHERE `review_course_id` IN (%1s)", $course_ids));

        $total_rating = 0;

        foreach ($reviews as $key => $review) {
            $total_rating = $total_rating + $review->review_rating;
        }

        if (count($reviews) > 0) {
            return number_format((float)$total_rating / count($reviews), 2, '.', '');
        } else {
            return 0;
        }
    }

    /**
     * GET INSTRUCTOR NUMBER OF REVIEWS
     *
     * @param integer $instructor_id
     * @return float
     */
    public static function get_instructors_number_of_reviews(int $instructor_id)
    {
        $table = self::$tables['review'];
        global $wpdb;
        $course_ids = Instructor::get_instructor_course_ids($instructor_id);
        $course_ids = implode(',', $course_ids);
        $reviews = $wpdb->get_results($wpdb->prepare("SELECT `review_rating` FROM $table WHERE `review_course_id` IN (%1s)", $course_ids));

        return count($reviews);
    }
}
