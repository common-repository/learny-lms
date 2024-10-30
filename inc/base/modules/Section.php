<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use WP_Query;

defined('ABSPATH') or die('You can not access the file directly');

class Section extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_section', array($this, 'post'));
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
            case 'add_section':
                $this->add_section();
                break;
            case 'edit_section':
                $this->edit_section();
                break;
            case 'delete':
                $this->delete_section();
                break;
            case 'sort_section':
                $this->sort_section();
                break;
        }
    }

    /**
     * STORING SECTIONS
     *
     * @return json
     */
    public static function add_section()
    {
        if (self::verify_nonce('add_section_nonce') == true) {
            $section_post_data['post_title']    = sanitize_text_field($_POST['learny_section_title']);
            $section_post_data['post_content']  = "";
            $section_post_data['post_status']   = "publish";
            $section_post_data['post_type']     = "learny-section";

            //save the new post
            $post_id = wp_insert_post($section_post_data);

            self::update_post_meta($post_id, ['order' => 0]);

            update_post_meta($post_id, 'learny_course_id', sanitize_text_field($_POST['learny_course_id']));
            echo json_encode(['status' => true, 'message' => esc_html__("Section Added Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * EDITING A SECTION
     *
     * @return json
     */
    public static function edit_section()
    {
        if (self::verify_nonce('edit_section_nonce') == true) {
            $section_post_data['ID']            = sanitize_text_field($_POST['learny_section_id']);
            $section_post_data['post_title']    = sanitize_text_field($_POST['learny_section_title']);
            $section_post_data['post_content']  = "";
            $section_post_data['post_status']   = "publish";
            $section_post_data['post_type']     = "learny-section";

            //UPDATE THE POST
            wp_update_post($section_post_data);
            echo json_encode(['status' => true, 'message' => esc_html__("Section Updated Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * DELETING A SECTION
     *
     * @return json
     */
    public static function delete_section()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            $ly_section_id = sanitize_text_field($_POST['id']);

            //DELETE THE POST
            wp_delete_post($ly_section_id);

            echo json_encode(['status' => true, 'message' => esc_html__("Section Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * GET SECTIONS BY COURSE ID
     *
     * @param integer $course_id
     * @return object
     */
    public static function get_sections(int $course_id)
    {
        $learny_section_args = array(
            'post_type'       => 'learny-section',
            'posts_per_page'  => -1,
            'meta_key'        => 'order',
            'orderby'         => 'meta_value',
            'order'           => 'ASC',
            'meta_query'      => array(
                array(
                    'key'         => 'learny_course_id',
                    'value'       => $course_id,
                    'compare'     => '='
                ),
            )
        );

        $learny_sections = new WP_Query($learny_section_args);
        // wp_reset_query();
        // wp_reset_postdata();
        return $learny_sections;
    }

    /**
     * SORTING SECTION LIST
     *
     * @return json
     */
    public static function sort_section()
    {
        if (self::verify_nonce('sort_section_nonce') == true) {

            $section_serial = explode(',', sanitize_text_field($_POST['section_serial']));
            for ($i = 0; $i < count($section_serial); $i++) {
                $updater = array(
                    'order' => $i + 1
                );
                self::update_post_meta($section_serial[$i], $updater);
            }
            echo json_encode(['status' => true, 'message' => esc_html__("Section Sorted Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * UPDATE POST META DATA
     *
     * @param int $post_id
     * @param array $post_meta
     * @return void
     */
    private static function update_post_meta($post_id, $post_meta)
    {
        foreach ($post_meta as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
    }

    /**
     * DELETE POST META DATA
     *
     * @param int $post_id
     * @param array $post_meta
     * @return void
     */
    private static function delete_post_meta($post_id, $post_meta)
    {
        foreach ($post_meta as $key => $value) {
            delete_post_meta($post_id, $key);
        }
    }
}
