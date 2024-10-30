<?php

/**
 * @package Learny
 */

namespace Learny\base\shortcodes;

ob_start();

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Enqueue;
use Learny\base\Helper;

class Auth extends BaseController
{
    protected $enqueue;
    protected $baseController;
    /**
     * DEFAULT CONSTRUCTOR
     */
    public function __construct()
    {
        parent::__construct();
        $this->enqueue = new Enqueue();
        $this->baseController = new BaseController();
    }


    /**
     * THIS FUNCTION IS FOR REGISTERING SHORTCODES
     *
     * @return void
     */
    public function register()
    {
        add_shortcode('learny-auth', array($this, 'render'));
    }


    /**
     * RENDER SHORTCODE VIEW
     *
     * @return void
     */
    public function render()
    {
        $student_dashboard_page = esc_html(get_option('ly_dashboard_page', 0)) ? esc_url_raw(get_permalink(get_option('ly_dashboard_page', 0))) : esc_url_raw(site_url());
        if (is_user_logged_in()) {
            wp_redirect(esc_url($student_dashboard_page));
            exit;
        }

        $template_path = Helper::view_path('auth/index');
        $this->enqueue->public_assets();

        ob_start();
        require_once($template_path);
        return ob_get_clean();
    }


    /**
     * CHECK IF THE USER IS LOGGEDIN
     *
     * @return boolean
     */
    public static function authenticate_login()
    {
        $ly_auth_page = esc_html(get_option('ly_auth_page', 0)) ? esc_url_raw(get_permalink(get_option('ly_auth_page', 0))) : esc_url_raw(site_url());
        if (!is_user_logged_in()) {
            wp_redirect(esc_url($ly_auth_page));
            exit;
        } else {
            if (current_user_can('manage_options')) {
                wp_redirect(admin_url('/edit.php?post_type=learny-courses'));
                exit;
            }
        }
    }
}
