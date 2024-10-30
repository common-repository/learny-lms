<?php

/**
 * @package Learny
 */

namespace Learny\base\shortcodes;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Enqueue;
use Learny\base\Helper;
use Learny\base\modules\gateways\Paypal;

class CoursePlayer extends BaseController
{
    protected $enqueue;

    /**
     * DEFAULT CONSTRUCTOR
     */
    public function __construct()
    {
        parent::__construct();
        $this->enqueue = new Enqueue();
    }


    /**
     * THIS FUNCTION IS FOR REGISTERING SHORTCODES
     *
     * @return void
     */
    public function register()
    {
        add_shortcode('learny-course-player', array($this, 'render'));
    }


    /**
     * RENDER SHORTCODE VIEW
     *
     * @return void
     */
    public function render()
    {
        // CHECK IF THE USER IS LOGGED IN
        Auth::authenticate_login();

        $template_path = null;
        $this->enqueue->public_assets();
        $template_path = Helper::view_path('course-player/index');

        ob_start();
        require_once($template_path);
        return ob_get_clean();
    }
}
