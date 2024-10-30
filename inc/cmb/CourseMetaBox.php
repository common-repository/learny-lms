<?php

/**
 * @package Learny
 */

namespace Learny\cmb;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

class CourseMetaBox extends BaseController
{

    protected
        $course_requirements_and_outcomes_nonce = "course_requirements_and_outcomes_nonce",
        $course_basic_information_nonce         = "course_basic_information_nonce",
        $course_media_nonce                     = "course_media_nonce",
        $course_pricing_nonce                   = "course_pricing_nonce",
        $course_seo_nonce                       = "course_seo_nonce";

    /**
     * REGISTERING THE HOOK FIRST
     *
     * @return void
     */
    public function register()
    {
        add_action('add_meta_boxes', array($this, 'register_course_metabox'));
        add_action('save_post_learny-courses', array($this, 'save_course'));
    }

    /**
     * REGISTERING THE METABOXES
     *
     * @return void
     */
    public function register_course_metabox()
    {
        /**
         * COURSE CURRICULUM METABOX
         */
        add_meta_box(
            'learny_course_curriculum_metadata',
            esc_html__('Course Curriculum', BaseController::$text_domain),
            array($this, 'render_courese_curriculum_metabox'),
            array('learny-courses'),
            'advanced',
            'default'
        );

        /**
         * COURSE BASIC INFORMATION METABOX
         */
        add_meta_box(
            'learny_course_basic_info',
            esc_html__('Course Basic Information', BaseController::$text_domain),
            array($this, 'render_course_basic_information_metabox'),
            array('learny-courses'),
            'advanced',
            'default'
        );

        /**
         * COURSE REQUIREMENT AND OUTCOMES METABOX
         */
        add_meta_box(
            'learny_course_requirements_and_outcomes',
            esc_html__('Course Requirements and Outcomes', BaseController::$text_domain),
            array($this, 'render_course_requirements_and_outcomes_metabox'),
            array('learny-courses'),
            'advanced',
            'default'
        );

        /**
         * COURSE MEDIA METABOX
         */
        add_meta_box(
            'learny_course_media',
            esc_html__('Course Media', BaseController::$text_domain),
            array($this, 'render_course_media_metabox'),
            array('learny-courses'),
            'advanced',
            'default'
        );

        /**
         * COURSE PRICING METABOX
         */
        add_meta_box(
            'learny_course_pricing',
            esc_html__('Course Pricing', BaseController::$text_domain),
            array($this, 'render_course_pricing_metabox'),
            array('learny-courses'),
            'advanced',
            'default'
        );

        /**
         * COURSE SEO METABOX
         */
        add_meta_box(
            'learny_course_seo_metadata',
            esc_html__('Course SEO Data', BaseController::$text_domain),
            array($this, 'render_seo_metabox'),
            array('learny-courses'),
            'advanced',
            'default'
        );
    }

    /**
     * THIS FUNCTION RENDERS COURSE BASIC INFORMATION METABOX
     *
     * @param post $post
     * @return void
     */
    function render_course_basic_information_metabox($post)
    {
        wp_nonce_field($this->course_basic_information_nonce . '_action', $this->course_basic_information_nonce . '_field');
        ob_start();
        include $this->plugin_path . "views/custom-meta-box/course/section/basic-information.php";
        $metabox_html = ob_get_clean();
        echo html_entity_decode(esc_html($metabox_html));
    }

    /**
     * THIS FUNCTION RENDERS COURSE REQUIREMENT AND OUTCOMES METABOX
     *
     * @param post $post
     * @return void
     */
    function render_course_requirements_and_outcomes_metabox($post)
    {
        wp_nonce_field($this->course_requirements_and_outcomes_nonce . '_action', $this->course_requirements_and_outcomes_nonce . '_field');
        ob_start();
        include $this->plugin_path . "views/custom-meta-box/course/section/requirements-and-outcomes.php";
        $metabox_html = ob_get_clean();
        echo html_entity_decode(esc_html($metabox_html));
    }

    /**
     * THIS FUNCTION RENDERS COURSE MEDIA METABOX
     *
     * @param post $post
     * @return void
     */
    function render_course_media_metabox($post)
    {
        wp_nonce_field($this->course_media_nonce . '_action', $this->course_media_nonce . '_field');
        ob_start();
        include $this->plugin_path . "views/custom-meta-box/course/section/media.php";
        $metabox_html = ob_get_clean();
        echo html_entity_decode(esc_html($metabox_html));
    }

    /**
     * THIS FUNCTION RENDERS COURSE PRICING METABOX
     *
     * @param post $post
     * @return void
     */
    function render_course_pricing_metabox($post)
    {
        wp_nonce_field($this->course_pricing_nonce . '_action', $this->course_pricing_nonce . '_field');
        ob_start();
        include $this->plugin_path . "views/custom-meta-box/course/section/pricing.php";
        $metabox_html = ob_get_clean();
        echo html_entity_decode(esc_html($metabox_html));
    }

    /**
     * THIS FUNCTION RENDERS COURSE SEO METABOX
     *
     * @param post $post
     * @return void
     */
    function render_seo_metabox($post)
    {
        wp_nonce_field($this->course_seo_nonce . '_action', $this->course_seo_nonce . '_field');
        ob_start();
        include $this->plugin_path . "views/custom-meta-box/course/section/seo-meta-data.php";
        $metabox_html = ob_get_clean();
        echo html_entity_decode(esc_html($metabox_html));
    }


    /**
     * THIS FUNCTION RENDERS COURSE CURRICULUM METABOX
     *
     * @param post $post
     * @return void
     */
    function render_courese_curriculum_metabox($post)
    {
        ob_start();
        include $this->plugin_path . "views/custom-meta-box/course/section/curriculum.php";
        $metabox_html = ob_get_clean();
        echo html_entity_decode(htmlspecialchars_decode(esc_html($metabox_html)));
    }

    /**
     * SAVING COURSE DATA CALLBACK
     *
     * @param integer $post_id
     * @return void
     */
    public function save_course(int $post_id)
    {
        $this->save_course_basic_information($post_id);
        $this->save_course_requirements_and_outcomes($post_id);
        $this->save_course_media($post_id);
        $this->save_course_pricing($post_id);
        $this->save_course_seo_metadata($post_id);
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR SAVING THE COURSE BASIC INFORMATION
     *
     * @param int $post_id
     * @return void
     */
    public function save_course_basic_information(int $post_id)
    {
        // FIRST CHECK IF THE POST IS VALID FOR SAVING
        if (!$this->is_secured($this->course_basic_information_nonce . '_action', $this->course_basic_information_nonce . '_field', $post_id)) {
            return $post_id;
        }

        $ly_course_difficulty_level = isset($_POST['ly_course_difficulty_level']) ? sanitize_text_field($_POST['ly_course_difficulty_level']) : '';
        $ly_course_language_made_in = isset($_POST['ly_course_language_made_in']) ? sanitize_text_field($_POST['ly_course_language_made_in']) : '';
        $ly_is_trendy_course = isset($_POST['ly_is_trendy_course']) ? true : false;

        update_post_meta($post_id, 'ly_course_difficulty_level', $ly_course_difficulty_level);
        update_post_meta($post_id, 'ly_course_language_made_in', $ly_course_language_made_in);
        update_post_meta($post_id, 'ly_is_trendy_course', $ly_is_trendy_course);
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR SAVING THE COURSE OUTCOMES AND COURSE REQUIREMENTS  
     *
     * @param int $post_id
     * @return void
     */
    public function save_course_requirements_and_outcomes(int $post_id)
    {
        // FIRST CHECK IF THE POST IS VALID FOR SAVING
        if (!$this->is_secured($this->course_requirements_and_outcomes_nonce . '_action', $this->course_requirements_and_outcomes_nonce . '_field', $post_id)) {
            return $post_id;
        }

        $course_requirements = isset($_POST['ly_course_requirements']) ? sanitize_text_field($_POST['ly_course_requirements']) : '';
        $course_outcomes = isset($_POST['ly_course_outcomes']) ? sanitize_text_field($_POST['ly_course_outcomes']) : '';

        update_post_meta($post_id, 'ly_course_requirements', $course_requirements);
        update_post_meta($post_id, 'ly_course_outcomes', $course_outcomes);
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR SAVING THE COURSE MEDIA
     *
     * @param int $post_id
     * @return void
     */
    public function save_course_media(int $post_id)
    {
        // FIRST CHECK IF THE POST IS VALID FOR SAVING
        if (!$this->is_secured($this->course_media_nonce . '_action', $this->course_media_nonce . '_field', $post_id)) {
            return $post_id;
        }

        $course_preview_provider = isset($_POST['ly_course_preview_provider']) ? sanitize_text_field($_POST['ly_course_preview_provider']) : '';
        $course_preview_url = isset($_POST['ly_course_preview_url']) ? sanitize_text_field($_POST['ly_course_preview_url']) : '';

        if ($course_preview_provider == "youtube") {
            $video_id = $this->get_youtube_video_id($course_preview_url);
            $course_preview_url = "https://www.youtube.com/watch?v=" . $video_id;
        }

        update_post_meta($post_id, 'ly_course_preview_provider', $course_preview_provider);
        update_post_meta($post_id, 'ly_course_preview_url', $course_preview_url);
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR SAVING THE COURSE PRICING
     *
     * @param int $post_id
     * @return void
     */
    public function save_course_pricing(int $post_id)
    {
        // FIRST CHECK IF THE POST IS VALID FOR SAVING
        if (!$this->is_secured($this->course_pricing_nonce . '_action', $this->course_pricing_nonce . '_field', $post_id)) {
            return $post_id;
        }

        $is_free_course = isset($_POST['ly_is_free_course']) ? 1 : 0;
        $course_price = isset($_POST['ly_course_price']) ? sanitize_text_field($_POST['ly_course_price']) : '';

        update_post_meta($post_id, 'ly_is_free_course', $is_free_course);
        update_post_meta($post_id, 'ly_course_price', $course_price);
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR SAVING THE COURSE SEO META DATA
     *
     * @param int $post_id
     * @return void
     */
    public function save_course_seo_metadata(int $post_id)
    {
        // FIRST CHECK IF THE POST IS VALID FOR SAVING
        if (!$this->is_secured($this->course_seo_nonce . '_action', $this->course_seo_nonce . '_field', $post_id)) {
            return $post_id;
        }

        $course_meta_description = isset($_POST['ly_course_meta_description']) ? sanitize_text_field($_POST['ly_course_meta_description']) : '';

        update_post_meta($post_id, 'ly_course_meta_description', $course_meta_description);
    }

    /**
     * VERIFYING THE POST DATA IS SECURED
     *
     * @param string $nonce_action
     * @param string $nonce_field
     * @param integer $post_id
     * @return boolean
     */
    private function is_secured(string $nonce_action, string $nonce_field, int $post_id)
    {
        $nonce = isset($_POST[$nonce_field]) ? sanitize_text_field($_POST[$nonce_field]) : '';

        if ($nonce == '') {
            return false;
        }
        if (!wp_verify_nonce($nonce, $nonce_action)) {
            return false;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }

        if (wp_is_post_autosave($post_id)) {
            return false;
        }

        if (wp_is_post_revision($post_id)) {
            return false;
        }
        return true;
    }

    /**
     * GET YOUTUBE VIDEO ID
     */

    private function get_youtube_video_id(string $embed_url = '')
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $embed_url, $match);
        $video_id = $match[1];
        return $video_id;
    }
}
