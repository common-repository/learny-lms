<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Wishlist extends BaseController
{

    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_wishlist', array($this, 'post'));
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
            case 'edit_wishlist':
                $this->edit_wishlist();
                break;
        }
    }

    /**
     * EDITING OR ADDING WISHLISTED COURSE
     *
     * @return json
     */
    public static function edit_wishlist()
    {
        $table = self::$tables['wishlist'];
        if (self::verify_nonce('edit_wishlist_nonce') == true) {
            $user_id = get_current_user_id();
            $course_id     = sanitize_text_field($_POST['wishlist_course_id']);
            $data['wishlist_course_id'] = $course_id;
            $data['wishlist_user_id'] = get_current_user_id();
            $data['wishlist_date'] = strtotime(date('D, d-M-Y'));

            if (is_user_logged_in()) {
                global $wpdb;
                $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE `wishlist_course_id` = %d AND `wishlist_user_id` = %d", $course_id, $user_id));
                if ($result && count((array)$result) > 0) {
                    $wpdb->delete($table, ['wishlist_id' => $result->wishlist_id]);
                    echo json_encode(['status' => true, 'course_id' => $course_id, 'message' => esc_html__("Course Has Been Removed Successfully", BaseController::$text_domain), 'isAdded' => false]);
                } else {
                    $wpdb->insert($table, $data);
                    echo json_encode(['status' => true, 'course_id' => $course_id, 'message' => esc_html__("Course Has Been Wishlisted Successfully", BaseController::$text_domain), 'isAdded' => true]);
                }
            } else {
                echo json_encode(['status' => false, 'course_id' => 0, 'message' => esc_html__("Make sure to login first", BaseController::$text_domain), 'isAdded' => false]);
            }
        }
    }

    /**
     * GETTING ALL THE WISHLISTED COURSES
     *
     * @return object
     */
    public static function get_wishlisted_courses()
    {
        $table = self::$tables['wishlist'];
        global $wpdb;
        $user_id = get_current_user_id();
        $wishlist = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `wishlist_user_id` = %d", $user_id));
        return $wishlist;
    }

    /**
     * GETTING ALL THE WISHLISTED COURSES
     *
     * @return object
     */
    public static function is_wishlisted($course_id)
    {
        $table = self::$tables['wishlist'];
        global $wpdb;
        $user_id = get_current_user_id();
        $wishlist = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE `wishlist_user_id` = %d AND `wishlist_course_id` = %d", $user_id, $course_id));
        return $wishlist;
    }
}
