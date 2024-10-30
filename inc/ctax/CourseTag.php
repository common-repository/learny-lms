<?php

/**
 * @package Learny
 */

namespace Learny\ctax;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\cmf\CustomMetaField;

class CourseTag extends BaseController
{
    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        add_action('init', array($this, 'register_course_category'));
    }

    /**
     * HOOKS CALLBACK
     *
     * @return void
     */
    public function register_course_category()
    {
        /**
         * Taxonomy: Course Tags.
         */

        $labels = [
            "name" => __("Course Tags", BaseController::$text_domain),
            "singular_name" => __("Course Tag", BaseController::$text_domain),
        ];


        $args = [
            "label" => __("Course Tags", BaseController::$text_domain),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => ['slug' => 'learny_tag', 'with_front' => true,],
            "show_admin_column" => false,
            "show_in_rest" => true,
            "rest_base" => "learny_tag",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "show_in_quick_edit" => false,
            "show_in_graphql" => false,
            'capabilities' => array(
                'manage_terms'    => 'manage_learny_tags',
                'edit_terms'      => 'edit_learny_tags',
                'delete_terms'    => 'delete_learny_tags',
                'assign_terms'    => 'assign_learny_tags',
            ),
        ];
        register_taxonomy("learny_tag", ["learny-courses"], $args);
    }
}
