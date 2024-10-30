<?php

/**
 * @package Learny
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\modules\Enrolment;
use Learny\base\modules\Video;

class AjaxPosts extends BaseController
{
    protected $page_to_load;

    // Arbitrary parameters that might be sent during any ajax call
    public static $param1;
    public static $param2;
    public static $param3;
    public static $param4;
    public static $param5;
    public static $param6;
    public static $param7;
    public static $param8;
    public static $param9;
    public static $param10;
    public static $url;

    // DECLARING THIS CONSTRUCTOR FOR INITIALIZING THE CURRENT USER ROLE
    function __construct()
    {
        parent::__construct();
    }

    // Method for registering ajax submit hook to the plugin
    public function register()
    {
        add_action('wp_ajax_' . self::$plugin_id, array($this, 'post'));

        // USE THIS HOOK FOR LOGGED OUT USERS AJAX CALL
        add_action('wp_ajax_nopriv_' . self::$plugin_id, array($this, 'post'));
    }

    // Method for sanitizing all the received parameters and assign it to the public variables declared in this class
    public function post()
    {
        $task = sanitize_text_field($_POST['task']);

        if (isset($_POST['page']))
            $this->page_to_load = sanitize_text_field($_POST['page']);

        if (isset($_POST['param1']))
            self::$param1 = sanitize_text_field($_POST['param1']);

        if (isset($_POST['param2']))
            self::$param2 = sanitize_text_field($_POST['param2']);

        if (isset($_POST['param3']))
            self::$param3 = sanitize_text_field($_POST['param3']);

        if (isset($_POST['param4']))
            self::$param4 = sanitize_text_field($_POST['param4']);

        if (isset($_POST['param5']))
            self::$param5 = sanitize_text_field($_POST['param5']);

        if (isset($_POST['param6']))
            self::$param6 = sanitize_text_field($_POST['param6']);

        if (isset($_POST['param7']))
            self::$param7 = sanitize_text_field($_POST['param7']);

        if (isset($_POST['param8']))
            self::$param8 = sanitize_text_field($_POST['param8']);

        if (isset($_POST['param9']))
            self::$param9 = sanitize_text_field($_POST['param9']);
        if (isset($_POST['param10']))
            self::$param10 = sanitize_text_field($_POST['param10']);

        if (isset($_POST['url']))
            self::$url = sanitize_text_field($_POST['url']);

        $this->handle_ajax_posts($task);
    }

    // Method for determining the ajax request type and send feedback accordingly
    private function handle_ajax_posts($task)
    {
        // FOR LOADING MODAL PAGES
        if ($task == 'load_modal_page')
            $this->load_modal_page();

        // FOR LOADING SUB PAGE RESPONSES
        if ($task == 'load_response')
            $this->load_response();

        // FOR LOADING CONFIRMATION MODAL PAGES
        if ($task == 'load_confirmation_modal_page')
            $this->load_confirmation_modal_page();

        // FOR GETTING OTHER LIKE STRING/BOOLEAN/INT RESPONSES
        if ($task == 'video_validity_and_duration')
            $this->video_validity_and_duration();

        // FOR SAVING COURSE PROGRESS
        if ($task == 'save_course_progress')
            $this->save_course_progress();

        // FOR SAVING THE LAST PLAYED LESSON
        if ($task == 'save_last_played_lesson')
            $this->save_last_played_lesson();
    }

    // Method for presenting modal with contents sent from ajax post request
    private function load_modal_page()
    {
        require($this->plugin_path . "$this->page_to_load.php");
        die();
    }

    // Method for loading any response to a page after ajax post request
    private function load_response()
    {
        require($this->plugin_path . "$this->page_to_load.php");
        // require("$this->page_to_load.php");
        die();
    }

    // Method for presenting confirm modal with contents sent from ajax post request
    private function load_confirmation_modal_page()
    {
        require($this->plugin_path . "views/modal/confirmation-modal-page.php");
        die();
    }

    // Method for generic ajax responses
    private function load_generic_response()
    {
        require($this->plugin_path . "views/modal/confirmation-modal-page.php");
        die();
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR VIDEO VALIDATING AND FETCHING THE DURATION
     *
     * @return JSON
     */
    private function video_validity_and_duration()
    {
        $video_details = Video::getVideoDetails(self::$param1);
        $status = isset($video_details['status']) ? $video_details['status'] : false;
        $duration = isset($video_details['duration']) ? $video_details['duration'] : "00:00:00";

        echo wp_json_encode(["status" => $status, "duration" => $duration]);

        die();
    }


    /**
     * THIS FUNCTION IS RESPONSIBLE FOR TRACKING COURSE PROGRESS
     *
     * @return JSON
     */
    private function save_course_progress()
    {
        $course_id = sanitize_text_field(self::$param1);
        $lesson_id = sanitize_text_field(self::$param2);
        $progress  = sanitize_text_field(self::$param3);

        $response = Enrolment::save_course_progress($course_id, $lesson_id, $progress);

        echo esc_attr($response);

        die();
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR SAVING THE LAST PLAYED LESSON
     *
     * @return JSON
     */
    private function save_last_played_lesson()
    {
        $course_id = sanitize_text_field(self::$param1);
        $lesson_id = sanitize_text_field(self::$param2);

        $response = Enrolment::save_last_played_lesson($course_id, $lesson_id);

        echo esc_attr($response);

        die();
    }
}
