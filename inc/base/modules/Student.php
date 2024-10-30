<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use Learny\base\Helper;
use WP_Query;

defined('ABSPATH') or die('You can not access the file directly');

class Student extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_student', array($this, 'post'));
        add_action('delete_user', array($this, 'redirect_to_current_page'));
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
            case 'add_student':
                $this->add_student();
                break;
            case 'edit_student':
                $this->edit_student();
                break;
            case 'delete':
                $this->delete_student();
                break;
            case 'toggle_status':
                $this->toggle_status();
                break;
        }
    }

    /**
     * STORING DATA
     *
     * @return json
     */
    public static function add_student()
    {
        if (self::verify_nonce('add_student_nonce') == true) {

            // VALIDATING EMAIL
            if (!Helper::validate_email(sanitize_text_field($_POST['ly_email']))) {
                echo json_encode(['status' => false, 'message' => esc_html__("Invalid Email Address", BaseController::$text_domain)]);
                return;
            }

            $username = sanitize_text_field($_POST['ly_username']);
            $first_name = sanitize_text_field($_POST['ly_first_name']);
            $last_name = sanitize_text_field($_POST['ly_last_name']);
            $email = sanitize_text_field($_POST['ly_email']);
            $password = sanitize_text_field($_POST['ly_password']);
            $bio = sanitize_text_field($_POST['ly_bio']);

            $userdata = array(
                'user_login'    =>  $username,
                'user_email'    =>  $email,
                'first_name'    =>  $first_name,
                'last_name'     =>  $last_name,
                'role'          =>  self::$custom_roles['student']['role'],
                'user_pass'     =>  $password,
                'description'   =>  $bio,
            );

            $user_id = username_exists($username);

            if (!$user_id && false == email_exists($email)) {
                $user_id = wp_insert_user($userdata);
                if (is_wp_error($user_id)) {
                    echo json_encode(['status' => false, 'message' => esc_html__("An error occurred while creating the student", BaseController::$text_domain)]);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("The user already exists", BaseController::$text_domain)]);
                return;
            }
            echo json_encode(['status' => true, 'message' => esc_html__("Student Added Successfully", BaseController::$text_domain)]);

            self::update_user_metas($user_id, ['ly_status' => 1]);
            return;
        }
    }

    /**
     * EDITING DATA
     *
     * @return json
     */
    public static function edit_student()
    {
        if (self::verify_nonce('edit_student_nonce') == true) {

            echo json_encode(['status' => true, 'message' => esc_html__("Student Updated Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * DELETING DATA
     *
     * @return json
     */
    public static function delete_student()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {

            echo json_encode(['status' => true, 'message' => esc_html__("Student Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * GET DATA
     * @return object
     */
    public static function get_students()
    {
    }


    /**
     * TOGGLING STUDENT STATUS
     *
     * @return json
     */
    public static function toggle_status()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            $student_id = sanitize_text_field($_POST['id']);
            $previous_user_status = get_user_meta($student_id, 'ly_status', true);

            $status_update_to = $previous_user_status == 1 ? 0 : 1;
            self::update_user_metas($student_id, ['ly_status' => $status_update_to]);

            echo json_encode(['status' => true, 'message' => esc_html__("Student Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    function redirect_to_current_page()
    {
        wp_redirect(get_permalink());
    }

    /**
     * UPDATE USER META VALUES
     *
     * @param integer $user_id
     * @param array $metas
     * @return void
     */
    public static function update_user_metas(int $user_id, array $metas)
    {
        foreach ($metas as $key => $value) {
            update_user_meta($user_id, $key, $value);
        }
    }
}
