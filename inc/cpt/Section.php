<?php

/**
 * @package Learny
 */

namespace Learny\cpt;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

class Section extends BaseController
{
    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        /**
         * REGISTERING SECTION CUSTOM POST TYPE
         */
        add_action('init', array($this, 'register_section_post_type'));
    }

    /**
     * HOOKS CALLBACK
     *
     * @return void
     */
    public function register_section_post_type()
    {
        /**
         * Post Type: Sections.
         */

        $labels = [
            "name" => __("Sections", BaseController::$text_domain),
            "singular_name" => __("Section", BaseController::$text_domain),
        ];

        $args = [
            "label" => __("Sections", BaseController::$text_domain),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => false,
            "show_ui" => false,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => true,
            "show_in_menu" => false,
            "show_in_nav_menus" => false,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => ["slug" => "learny-sections", "with_front" => false],
            "query_var" => true,
            "supports" => ["title"],
            "show_in_graphql" => false,
        ];

        register_post_type("learny-sections", $args);
    }
}
