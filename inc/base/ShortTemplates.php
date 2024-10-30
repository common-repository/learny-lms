<?php

/**
 * @package Learny LMS
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class ShortTemplates extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_filter('template_include', array($this, 'integrated_shortcode'), -10, 2);
    }


    /**
     * INTEGRATE SOME DEFAULT SHORTCODE FOR FRONTEND SHORTCODE VIEWS
     *
     * @param $template
     *
     * @return mixed
     */
    public static function integrated_shortcode($template)
    {
        global $post;

        if (!$post) {
            return $template;
        }

        $ly_auth_page = esc_attr(get_option('ly_auth_page'), '');
        $ly_checkout_page = esc_attr(get_option('ly_checkout_page'), '');
        $ly_dashboard_page = esc_attr(get_option('ly_dashboard_page'), '');
        $ly_course_player_page = esc_attr(get_option('ly_course_player_page'), '');

        if ('page' == get_post_type($post->ID) && $ly_checkout_page == $post->ID) {
            if (!preg_match('/\[learny-checkout\s?(.*)\]/', $post->post_content)) {
                $post->post_content .= '[learny-checkout]';
            }
        } elseif ('page' == get_post_type($post->ID) && $ly_dashboard_page == $post->ID) {
            if (!preg_match('/\[learny-dashboard\s?(.*)\]/', $post->post_content)) {
                $post->post_content .= '[learny-dashboard]';
            }
        } elseif ('page' == get_post_type($post->ID) && $ly_course_player_page == $post->ID) {
            if (!preg_match('/\[learny-course-player\s?(.*)\]/', $post->post_content)) {
                $post->post_content .= '[learny-course-player]';
            }
        } elseif ('page' == get_post_type($post->ID) && $ly_auth_page == $post->ID) {
            if (!preg_match('/\[learny-auth\s?(.*)\]/', $post->post_content)) {
                $post->post_content .= '[learny-auth]';
            }
        }

        return $template;
    }
}
