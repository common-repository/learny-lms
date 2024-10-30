<?php

/**
 * @package Learny LMS
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class PostStates extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_filter('display_post_states', array($this, 'display'));
    }

    /**
     * FUNCTION FOR SHOWING POST STATES, FILTER CALLBACK
     *
     * @param array $states
     * @return array
     */
    public function display($states)
    {
        global $post;

        if (!$post) {
            return $states;
        }

        $ly_auth_page = esc_attr(get_option('ly_auth_page'), '');
        $ly_checkout_page = esc_attr(get_option('ly_checkout_page'), '');
        $ly_dashboard_page = esc_attr(get_option('ly_dashboard_page'), '');
        $ly_course_player_page = esc_attr(get_option('ly_course_player_page'), '');

        if ('page' == get_post_type($post->ID) && $ly_checkout_page == $post->ID) {
            $states[] = esc_html__('Learny Checkout Page', BaseController::$text_domain);
        } elseif ('page' == get_post_type($post->ID) && $ly_dashboard_page == $post->ID) {
            $states[] = esc_html__('Learny Student Dashboard Page', BaseController::$text_domain);
        } elseif ('page' == get_post_type($post->ID) && $ly_course_player_page == $post->ID) {
            $states[] = esc_html__('Learny Course Player Page', BaseController::$text_domain);
        } elseif ('page' == get_post_type($post->ID) && $ly_auth_page == $post->ID) {
            $states[] = esc_html__('Learny Login and Registration Page', BaseController::$text_domain);
        }

        return $states;
    }
}
