<?php

/**
 * @package Learny
 */

namespace Learny\ctax;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\cmf\CustomMetaField;

class CourseCategory extends BaseController
{
    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        add_action('init', array($this, 'register_course_category'));

        // REGISTERING META FIELDS
        $this->register_meta_field();
    }

    /**
     * HOOKS CALLBACK
     *
     * @return void
     */
    public function register_course_category()
    {
        /**
         * Taxonomy: Course Categories.
         */

        $labels = [
            "name" => __("Course Categories", BaseController::$text_domain),
            "singular_name" => __("Course Category", BaseController::$text_domain),
            "menu_name" => __("Course Categories", BaseController::$text_domain),
            "all_items" => __("All Course Categories", BaseController::$text_domain),
            "edit_item" => __("Edit Course Category", BaseController::$text_domain),
            "view_item" => __("View Course Category", BaseController::$text_domain),
            "update_item" => __("Update Course Category name", BaseController::$text_domain),
            "add_new_item" => __("Add new Course Category", BaseController::$text_domain),
            "new_item_name" => __("New Course Category name", BaseController::$text_domain),
            "parent_item" => __("Parent Course Category", BaseController::$text_domain),
            "parent_item_colon" => __("Parent Course Category:", BaseController::$text_domain),
            "search_items" => __("Search Course Categories", BaseController::$text_domain),
            "popular_items" => __("Popular Course Categories", BaseController::$text_domain),
            "separate_items_with_commas" => __("Separate Course Categories with commas", BaseController::$text_domain),
            "add_or_remove_items" => __("Add or remove Course Categories", BaseController::$text_domain),
            "choose_from_most_used" => __("Choose from the most used Course Categories", BaseController::$text_domain),
            "not_found" => __("No Course Categories found", BaseController::$text_domain),
            "no_terms" => __("No Course Categories", BaseController::$text_domain),
            "items_list_navigation" => __("Course Categories list navigation", BaseController::$text_domain),
            "items_list" => __("Course Categories list", BaseController::$text_domain),
            "back_to_items" => __("Back to Course Categories", BaseController::$text_domain),
        ];


        $args = [
            "label" => __("Course Categories", BaseController::$text_domain),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => true,
            "show_ui" => true,
            "show_in_menu" => 'learny',
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => ['slug' => 'learny_category', 'with_front' => false,],
            "show_admin_column" => false,
            "show_in_rest" => true,
            "rest_base" => "learny_category",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "show_in_quick_edit" => false,
            "show_in_graphql" => false,
            'capabilities' => array(
                'manage_terms'    => 'manage_learny_categories',
                'edit_terms'      => 'edit_learny_categories',
                'delete_terms'    => 'delete_learny_categories',
                'assign_terms'    => 'assign_learny_categories',
            ),
        ];
        register_taxonomy("learny_category", ["learny-courses"], $args);
    }

    /**
     * GENERATE CUSTOM META FIELDS 
     *
     * @return void
     */
    function register_meta_field()
    {
        $fields = array(
            'learny_course_category_image_thumbnail_url' => array(
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'single'            => true,
                'description'       => esc_html__("A custom meta field for category thumbnail", BaseController::$text_domain),
                'show_in_rest'      => true
            )
        );

        $view_path = $this->plugin_path . "views/custom-field/category/learny-category-fields.php";
        new CustomMetaField('learny_category', $view_path, $fields, false);
    }
}
