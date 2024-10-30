<?php

/**
 * @package Learny
 */

namespace Learny\cpt;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

class Lesson extends BaseController
{
    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        /**
         * REGISTERING LESSON CUSTOM POST TYPE
         */
        add_action('init', array($this, 'register_lesson_post_type'));
    }

    /**
     * HOOKS CALLBACK
     *
     * @return void
     */
    public function register_lesson_post_type()
    {
        /**
         * Post Type: Lessons.
         */

        $labels = [
            "name" => __("Lessons", BaseController::$text_domain),
            "singular_name" => __("Lesson", BaseController::$text_domain),
        ];

        $args = [
            "label" => __("Lessons", BaseController::$text_domain),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => false,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => false,
            "show_in_nav_menus" => false,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => true,
            "rewrite" => ["slug" => "learny-lessons", "with_front" => false],
            "query_var" => true,
            "supports" => ["title"],
            "show_in_graphql" => false,
        ];

        register_post_type("learny-lessons", $args);
    }
}
