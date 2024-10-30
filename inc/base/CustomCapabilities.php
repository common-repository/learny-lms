<?php

/**
 * @package Learny
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class CustomCapabilities extends BaseController
{

    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        /**
         * REGISTERING COURSE CUSTOM POST TYPE
         */
        add_action('admin_init', array($this, 'addCustomCapabilitiesToAdmin'));
    }


    /**
     * ADDING CUSTOM CAPABILITIES TO ADMIN
     *
     * @return void
     */
    public function addCustomCapabilitiesToAdmin()
    {
        $custom_capabilities = array(
            'manage_options',
            // LEARNY COURSE
            'create_learny_courses',
            'edit_learny_course',
            'read_learny_course',
            'delete_learny_course',
            'delete_learny_courses',
            'delete_published_learny_courses',
            'delete_others_learny_courses',
            'edit_learny_courses',
            'edit_others_learny_courses',
            'publish_learny_courses',
            'edit_published_learny_courses',
            'read_private_learny_courses',
            'edit_learny_courses',

            // LEARNY CATEGORY
            'manage_learny_categories',
            'edit_learny_categories',
            'delete_learny_categories',
            'assign_learny_categories',

            // LEARNY TAG
            'manage_learny_tags',
            'edit_learny_tags',
            'delete_learny_tags',
            'assign_learny_tags',

            // MENU PAGE
            'ly_course_category_permission',
            'ly_course_tag_permission',
            'ly_student_permission',
            'ly_instructor_permission',
            'ly_report_permission',
            'ly_payout_permission',
            'ly_plugin_settings_permission',
            'ly_plugin_pro_permission'

        );
        $role = get_role('administrator');
        foreach ($custom_capabilities as $custom_capability) {
            $role->add_cap($custom_capability);
        }
    }


    /**
     * ADDING CUSTOM CAPABILITIES TO INSTRUCTOR
     *
     * @return void
     */
    public static function instructorCustomCapabilities()
    {
        $custom_capabilities = array(
            // GENERIC
            'read' => true,
            'upload_files' => true,
            'edit_posts' => true,
            'delete_posts' => true,

            // LEARNY COURSE
            'create_learny_courses' => true,
            'edit_learny_course' => true,
            'edit_learny_courses' => true,
            'edit_published_learny_courses' => true,
            'read_learny_courses' => true,
            'read_learny_course' => true,
            'delete_learny_course' => true,
            'delete_learny_courses' => true,
            'delete_published_learny_courses' => true,
            'read_private_learny_courses' => true,

            // LEARNY CATEGORY
            'manage_learny_categories' => true,
            'assign_learny_categories' => true,

            // LEARNY TAGS
            'assign_learny_tags' => true,

            // MENU PAGE
            'ly_course_category_permission' => true,
            'ly_course_tag_permission' => true,
            'ly_student_permission' => true,
            'ly_instructor_permission' => true,
            'ly_report_permission' => true,
            'ly_payout_permission' => true

        );

        return $custom_capabilities;
    }

    /**
     * ADDING CUSTOM CAPABILITIES TO STUDENT
     *
     * @return void
     */
    public static function studentCustomCapabilities()
    {
        $custom_capabilities = array(
            'read' => 1,
            'upload_files' => 1,
            'edit_posts' => 1
        );

        return $custom_capabilities;
    }
}
