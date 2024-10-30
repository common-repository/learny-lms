<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use WP_Query;

defined('ABSPATH') or die('You can not access the file directly');
class Lesson extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_lesson', array($this, 'post'));
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
            case 'add_lesson':
                $this->add_lesson();
                break;
            case 'edit_lesson':
                $this->edit_lesson();
                break;
            case 'delete':
                $this->delete_lesson();
                break;
            case 'sort_lesson':
                $this->sort_lesson();
                break;
        }
    }

    public static function add_lesson()
    {
        if (self::verify_nonce('add_lesson_nonce') == true) {

            $lesson_post_type['post_title']    = sanitize_text_field($_POST['learny_lesson_title']);
            $lesson_post_type['post_content']  = "";
            $lesson_post_type['post_status']   = "publish";
            $lesson_post_type['post_type']     = "learny-lessons";

            //save the new post
            $post_id = wp_insert_post($lesson_post_type);

            $lesson_post_meta['ly_lesson_type'] = sanitize_text_field($_POST['ly_lesson_type']);
            $lesson_post_meta['ly_section_id'] = sanitize_text_field($_POST['learny_section_id']);
            $lesson_post_meta['ly_summary'] = sanitize_text_field($_POST['lesson_summary']);
            $lesson_post_meta['ly_attachment'] = esc_url_raw(sanitize_text_field($_POST['ly_lesson_attachment']));
            $lesson_post_meta['order'] = 0;

            if ($lesson_post_meta['ly_lesson_type'] == "youtube" || $lesson_post_meta['ly_lesson_type'] == "vimeo" || $lesson_post_meta['ly_lesson_type'] == "video-url" || $lesson_post_meta['ly_lesson_type'] == "video-file") {
                $lesson_post_meta['ly_video_url'] = sanitize_text_field($_POST['ly_video_url']);
                $lesson_post_meta['ly_video_duration'] = sanitize_text_field($_POST['ly_video_duration']);
            } elseif ($lesson_post_meta['ly_lesson_type'] == "iframe") {
                $lesson_post_meta['ly_iframe_src'] = sanitize_text_field($_POST['ly_iframe_src']);
            } elseif ($lesson_post_meta['ly_lesson_type'] == "note") {
                $lesson_post_type['post_content'] = sanitize_text_field($_POST['note']);
                wp_update_post(
                    array(
                        'ID'           => $post_id,
                        'post_content' => $lesson_post_type['post_content'],
                    )
                );
            }

            self::update_post_meta($post_id, $lesson_post_meta);

            wp_reset_query();
            echo json_encode(['status' => true, 'message' => esc_html__("Lesson Added Successfully", BaseController::$text_domain)]);
        }
    }

    public static function edit_lesson()
    {
        if (self::verify_nonce('edit_lesson_nonce') == true) {
            $lesson_post_data['ID']            = sanitize_text_field($_POST['lesson_id']);
            $lesson_post_data['post_title']    = sanitize_text_field($_POST['learny_lesson_title']);
            $lesson_post_data['post_content']  = "";
            $lesson_post_data['post_status']   = "publish";
            $lesson_post_data['post_type']     = "learny-lessons";

            //UPDATE THE POST
            wp_update_post($lesson_post_data);

            // LESSON METADATA
            $lesson_details = get_post($lesson_post_data['ID']);
            $lesson_post_meta['ly_lesson_type'] = $lesson_details->ly_lesson_type;
            $lesson_post_meta['ly_section_id'] = sanitize_text_field($_POST['learny_section_id']);
            $lesson_post_meta['ly_summary'] = sanitize_text_field($_POST['lesson_summary']);
            $lesson_post_meta['ly_attachment'] = esc_url_raw(sanitize_text_field($_POST['ly_lesson_attachment']));

            if ($lesson_post_meta['ly_lesson_type'] == "youtube" || $lesson_post_meta['ly_lesson_type'] == "vimeo" || $lesson_post_meta['ly_lesson_type'] == "video-url" || $lesson_post_meta['ly_lesson_type'] == "video-file") {
                $lesson_post_meta['ly_video_url'] = sanitize_text_field($_POST['ly_video_url']);
                $lesson_post_meta['ly_video_duration'] = sanitize_text_field($_POST['ly_video_duration']);
            } elseif ($lesson_post_meta['ly_lesson_type'] == "iframe") {
                $lesson_post_meta['ly_iframe_src'] = sanitize_text_field($_POST['ly_iframe_src']);
            } elseif ($lesson_post_meta['ly_lesson_type'] == "note") {
                $lesson_post_type['post_content'] = sanitize_text_field($_POST['note']);
                wp_update_post(
                    array(
                        'ID'           => $lesson_post_data['ID'],
                        'post_content' => $lesson_post_type['post_content'],
                    )
                );
            }

            self::update_post_meta($lesson_post_data['ID'], $lesson_post_meta);

            wp_reset_query();

            echo json_encode(['status' => true, 'message' => esc_html__("Lesson Updated Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * DELETING A LESSON
     *
     * @return json
     */
    public static function delete_lesson()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            $lesson_id = sanitize_text_field($_POST['id']);

            //DELETE THE POST
            wp_delete_post($lesson_id);

            // DELETE POST META
            self::delete_post_meta($lesson_id, ['ly_lesson_type', 'ly_section_id', 'ly_summary', 'ly_attachment', 'ly_video_url', 'ly_video_duration', 'ly_iframe_src']);

            wp_reset_query();

            echo json_encode(['status' => true, 'message' => esc_html__("Lesson Deleted Successfully", BaseController::$text_domain)]);
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

    /**
     * GET LESSONS BY SECTION ID
     *
     * @param integer $ly_section_id
     * @return object
     */
    public static function get_lessons_by_section_id(int $ly_section_id)
    {
        $learny_lesson_args = array(
            'post_type'       => 'learny-lessons',
            'posts_per_page'  => -1,
            'meta_key'        => 'order',
            'orderby'         => 'meta_value',
            'order'           => 'ASC',
            'meta_query'      => array(
                array(
                    'key'         => 'ly_section_id',
                    'value'       => $ly_section_id,
                    'compare'     => '='
                ),
            )
        );

        $learny_lessons = new WP_Query($learny_lesson_args);
        wp_reset_query();
        $learny_lessons->reset_postdata();
        return $learny_lessons;
    }

    /**
     * GET NUMBER OF LESSONS BY COURSE ID
     *
     * @param int $course_id
     * @return int
     */
    public static function get_number_of_lesson_by_course_id($course_id)
    {
        $total_curriculum = 0;
        $learny_sections = Section::get_sections($course_id);
        while ($learny_sections->have_posts()) {
            $learny_sections->the_post();
            $learny_lessons = Lesson::get_lessons_by_section_id(get_the_ID());
            $total_curriculum += $learny_lessons->found_posts;
        }

        // wp_reset_query();
        // wp_reset_postdata();
        return $total_curriculum;
    }

    /**
     * SORT LESSON LIST
     *
     * @return json
     */
    public static function sort_lesson()
    {
        if (self::verify_nonce('sort_lesson_nonce') == true) {

            $lesson_serial = explode(',', sanitize_text_field($_POST['lesson_serial']));
            for ($i = 0; $i < count($lesson_serial); $i++) {
                $updater = array(
                    'order' => $i + 1
                );
                self::update_post_meta($lesson_serial[$i], $updater);
            }
            echo json_encode(['status' => true, 'message' => esc_html__("Lesson Sorted Successfully", BaseController::$text_domain)]);
        }
    }


    /**
     * GET THE LAST PLAYED LESSON OF A COURSE
     *
     * @param integer $course_id
     * @return object
     */
    public static function get_last_played_lesson(int $course_id)
    {
        $current_logged_in_user_id = get_current_user_id();
        global $wpdb;
        $table = self::$tables['watch_history'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `watch_history_user_id` = %d AND `watch_history_course_id` = %d", $current_logged_in_user_id, $course_id));

        if (count($result) > 0 && !empty($result[0]->watch_history_last_played_lesson_id)) {

            $last_played_lesson_id = $result[0]->watch_history_last_played_lesson_id;
            return esc_html(get_post_field('post_name', $last_played_lesson_id));
        } else {

            $learny_section_args = array(
                'post_type'       => 'learny-section',
                'posts_per_page'  => 1,
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
            while ($learny_sections->have_posts()) {
                $learny_sections->the_post();
                $ly_section_id = get_the_ID();
                $learny_lesson_args = array(
                    'post_type'       => 'learny-lessons',
                    'posts_per_page'  => 1,
                    'meta_key'        => 'order',
                    'orderby'         => 'meta_value',
                    'order'           => 'ASC',
                    'meta_query'      => array(
                        array(
                            'key'         => 'ly_section_id',
                            'value'       => $ly_section_id,
                            'compare'     => '='
                        ),
                    )
                );

                $learny_lessons = new WP_Query($learny_lesson_args);

                while ($learny_lessons->have_posts()) {
                    $learny_lessons->the_post();
                    return esc_html(get_post_field('post_name', get_the_ID()));
                }

                wp_reset_query();
            }

            wp_reset_query();
        }
    }
}
