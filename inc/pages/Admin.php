<?php

/**
 * @package Learny LMS
 */

namespace Learny\pages;

use Learny\base\BaseController;
use Learny\api\SettingsApi;
use Learny\api\callbacks\AdminCallbacks;

require_once(ABSPATH . '/wp-includes/pluggable.php');

defined('ABSPATH') or die('You can not access the file directly');

class Admin extends BaseController
{
    public $settings;
    public $callbacks;
    public $pages = array();
    public $sub_pages = array();

    private $parent_slug = "learny";

    public function __construct()
    {
        parent::__construct();
    }

    // Method that sets information of all the sub menus present in the plugin
    public function set_pages()
    {

        $this->pages = array(
            array( // 0
                'page_title' => esc_html__('Learny LMS', BaseController::$text_domain),
                'menu_title' => esc_html__('Learny LMS', self::$text_domain),
                'capability' => 'read',
                'menu_slug' => $this->parent_slug,
                'callback' => null,
                'icon_url' => 'dashicons-welcome-learn-more'
            )
        );

        $this->sub_pages = array(
            array(
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Course Category', self::$text_domain),
                'menu_title' => esc_html__('Course Category', self::$text_domain),
                'capability' => 'ly_course_category_permission',
                'menu_slug' => 'edit-tags.php?taxonomy=learny_category&post_type=learny-courses',
                'callback' => ''
            ),

            array(
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Course Tags', self::$text_domain),
                'menu_title' => esc_html__('Course Tags', self::$text_domain),
                'capability' => 'ly_course_tag_permission',
                'menu_slug' => 'edit-tags.php?taxonomy=learny_tag&post_type=learny-courses',
                'callback' => ''
            ),
            array( // 0
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Student', self::$text_domain),
                'menu_title' => esc_html__('Student', self::$text_domain),
                'capability' => 'ly_student_permission',
                'menu_slug' => $this->slugs['student'],
                'callback' => array($this->callbacks, 'student')
            ),
            array( // 0
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Instructor', self::$text_domain),
                'menu_title' => esc_html__('Instructor', self::$text_domain),
                'capability' => 'ly_instructor_permission',
                'menu_slug' => $this->slugs['instructor'],
                'callback' => array($this->callbacks, 'instructor')
            ),
            array( // 0
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Report', self::$text_domain),
                'menu_title' => esc_html__('Report', self::$text_domain),
                'capability' => 'ly_report_permission',
                'menu_slug' => $this->slugs['report'],
                'callback' => array($this->callbacks, 'report')
            ),
            array( // 0
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Payout', self::$text_domain),
                'menu_title' => esc_html__('Payout', self::$text_domain),
                'capability' => 'ly_payout_permission',
                'menu_slug' => $this->slugs['payout'],
                'callback' => array($this->callbacks, 'payout')
            ),
            array( // 0
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Plugin Settings', self::$text_domain),
                'menu_title' => esc_html__('Plugin Settings', self::$text_domain),
                'capability' => 'ly_plugin_settings_permission',
                'menu_slug' => $this->slugs['settings'],
                'callback' => array($this->callbacks, 'settings')
            ),
            array( // 0
                'parent_slug' => $this->parent_slug,
                'page_title' => esc_html__('Learny LMS Pro', self::$text_domain),
                'menu_title' => esc_html__('Learny LMS Pro', self::$text_domain),
                'capability' => 'ly_plugin_pro_permission',
                'menu_slug' => $this->slugs['pro_lms'],
                'callback' => array($this->callbacks, 'pro_lms')
            )
        );
    }

    // Method for adding the pages into this plugin
    public function register()
    {
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->set_pages();
        $this->settings->add_pages($this->pages, $this->sub_pages)->register();
    }
}
