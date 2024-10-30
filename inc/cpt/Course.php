<?php

/**
 * @package Learny
 */

namespace Learny\cpt;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Category;

class Course extends BaseController
{
    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        /**
         * REGISTERING COURSE CUSTOM POST TYPE
         */
        add_action('init', array($this, 'register_course_post_type'));

        /**
         * MANAGING COURSE COLUMNS
         */
        add_filter('manage_learny-courses_posts_columns', array($this, 'manage_course_columns'));

        /**
         * SHOWING THE CUSTOM VALUES IN COURSE CPT
         */
        add_action('manage_learny-courses_posts_custom_column', array($this, 'show_custom_values_to_columns'), 10, 2);

        /**
         * REGISTERING CUSTOM TEMPLATES
         */
        add_filter('archive_template', array($this, 'show_courses_list_template'));
        add_filter('single_template', array($this, 'show_course_template'));

        /**
         * ADDING CUSTOM ROW ACTIONS
         */
        add_filter('post_row_actions', array($this, 'course_custom_row_actions'), 10, 2);

        /**
         * ADDING CUSTOM FILTER IN COURSE LIST PAGE
         */
        add_action('restrict_manage_posts', array($this, 'restrict_manage_posts'));

        /**
         * THIS HOOKS ALLOW US FOR CUSTOM QUERY IN COURSE LIST
         */
        add_filter('parse_query', array($this, 'filter_course_list'));
    }

    /**
     * HOOKS CALLBACK
     *
     * @return void
     */
    public function register_course_post_type()
    {
        /**
         * Post Type: Courses.
         */

        $labels = [
            "name" => __("Courses", BaseController::$text_domain),
            "singular_name" => __("Course", BaseController::$text_domain),
            "menu_name" => __("Learny LMS", BaseController::$text_domain),
            "all_items" => __("Courses", BaseController::$text_domain),
            "add_new" => __("Add new course", BaseController::$text_domain),
            "add_new_item" => __("Add new Course", BaseController::$text_domain),
            "edit_item" => __("Edit Course", BaseController::$text_domain),
            "new_item" => __("New Course", BaseController::$text_domain),
            "view_item" => __("View Course", BaseController::$text_domain),
            "view_items" => __("View Courses", BaseController::$text_domain),
            "search_items" => __("Search Courses", BaseController::$text_domain),
            "not_found" => __("No Courses found", BaseController::$text_domain),
            "not_found_in_trash" => __("No Courses found in trash", BaseController::$text_domain),
            "featured_image" => __("Banner image for this Course", BaseController::$text_domain),
            "set_featured_image" => __("Set banner image for this Course", BaseController::$text_domain),
            "remove_featured_image" => __("Remove banner image for this Course", BaseController::$text_domain),
            "use_featured_image" => __("Use as banner image for this Course", BaseController::$text_domain),
            "archives" => __("Courses", BaseController::$text_domain),
            "name_admin_bar" => __("Course", BaseController::$text_domain),
            "item_updated" => __("Course updated.", BaseController::$text_domain),
        ];

        $args = [
            "label" => __("Courses", BaseController::$text_domain),
            "labels" => $labels,
            "description" => "Build your own courses from here.",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => "courses",
            "show_in_menu" => 'learny',
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            'capabilities' => array(
                'create_posts'          => 'create_learny_courses',
                'edit_post'             => 'edit_learny_course',
                'edit_posts'            => 'edit_learny_courses',
                'edit_others_posts'     => 'edit_others_learny_courses',
                'edit_published_posts'  => 'edit_published_learny_courses',

                'read_post'             => 'read_learny_course',
                'read_posts'            => 'read_learny_courses',

                'delete_post'           => 'delete_learny_course',
                'delete_posts'          => 'delete_learny_courses',
                'delete_published_posts' => 'delete_published_learny_courses',

                'delete_others_posts'   => 'delete_others_learny_courses',

                'publish_posts'         => 'publish_learny_courses',


                'read_private_posts'    => 'read_private_learny_courses',
            ),
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => ["slug" => "course", "with_front" => false],
            "query_var" => true,
            "menu_icon" => "dashicons-smiley",
            "supports" => ["title", "editor", "thumbnail", "excerpt"],
            "show_in_graphql" => false
        ];

        register_post_type("learny-courses", $args);
    }


    /**
     * CALLBACK OF MANAGE POSTS COLUMNS FILTER HOOK
     *
     * @param array $post_columns
     * @return array
     */
    public function manage_course_columns(array $post_columns)
    {
        unset($post_columns['date']);
        $post_columns['title'] = esc_html__('Course Title', BaseController::$text_domain);
        $post_columns['instructor'] = esc_html__('Course Instructor', BaseController::$text_domain);
        $post_columns['category'] = esc_html__('Course Categories', BaseController::$text_domain);
        $post_columns['curriculum'] = esc_html__('Lesson & Section', BaseController::$text_domain);
        $post_columns['enrolments'] = esc_html__('Enrolled Student', BaseController::$text_domain);
        $post_columns['price'] = esc_html__('Course Pricing', BaseController::$text_domain);
        $post_columns['date'] = esc_html__('Created at', BaseController::$text_domain);
        return $post_columns;
    }

    /**
     * CALLBACK OF MANAGE POSTS COLUMNS FILTER HOOK
     *
     * @param string $post_column
     * @param int $post_id
     * @return array
     */
    public function show_custom_values_to_columns(string $post_column, int $post_id)
    {
        switch ($post_column) {
            case 'category':
                $term_obj_list = get_the_terms($post_id, 'learny_category');
                $terms_string = is_countable($term_obj_list) && count($term_obj_list) ? join(', ', wp_list_pluck($term_obj_list, 'name')) : '-';
                echo esc_html($terms_string);
                break;
            case 'instructor':
                $author_id = esc_html(get_post_field('post_author', $post_id));
                $author_fullname = esc_html(get_the_author_meta('first_name', $author_id)) . ' ' . esc_html(get_the_author_meta('last_name', $author_id));
                $author_display_name = esc_html(get_the_author_meta('display_name', $author_id));
                echo !empty($author_fullname) ? esc_html($author_fullname) : esc_html($author_display_name);
                break;
            case 'price':
                $is_free = esc_html(get_post_meta($post_id, 'ly_is_free_course', true));
                echo esc_html($is_free) ? esc_html__('Free', BaseController::$text_domain) : Helper::currency(esc_html(get_post_meta($post_id, 'ly_course_price', true)));
                break;
            default:
                echo "-";
                break;
        }
    }

    /**
     * REGISTERING COURSE LIST PAGE
     *
     * @param string $file_path
     * @return void
     */
    public function show_courses_list_template($file_path)
    {
        if (Helper::is_registered_theme()) {
            return $file_path;
        }

        global $post;
        switch ($post->post_type) {
            case "learny-courses":
                $file_path = $this->plugin_path . 'templates/frontend/courses/index.php';
                break;
        }

        return $file_path;
    }

    /**
     * SINGLE COURSE DETAILS PAGE
     *
     * @param string $file
     * @return string
     */
    public function show_course_template($file_path)
    {
        $themes = array('coursepro');
        $current_theme_details = wp_get_theme();
        $current_theme_textdomain = $current_theme_details->get('TextDomain');
        if (in_array($current_theme_textdomain, $themes)) {
            return $file_path;
        }

        global $post;
        switch ($post->post_type) {
            case "learny-courses":
                $file_path = $this->plugin_path . 'templates/frontend/courses/details.php';
                break;
        }

        return $file_path;
    }
    /**
     * REGISTERING CUSTOM ROW ACTIONS
     *
     * @param array $actions
     * @param object $post
     * @return array
     */
    function course_custom_row_actions($actions, $post)
    {

        // CHECKING POST TYPE
        switch ($post->post_type) {
            case 'learny-courses':
                if (get_current_user_id() == $post->post_author || Helper::get_current_user_role() == "administrator") {
                    unset($actions['inline hide-if-no-js']);
                    $actions['manage_lessons'] = '<a href=' . get_edit_post_link($post->ID) . '>' . esc_html__('Manage Lessons', BaseController::$text_domain) . '</a>';
                }
                break;
        }
        return $actions;
    }

    public static function restrict_manage_posts()
    {
        global $post;
        switch (isset($post) && $post->post_type == "learny-courses") {
            case "learny-courses":
?>
                <select name="instructor_id">
                    <?php $instructors = get_users(['role' => BaseController::$custom_roles['instructor']['role']]); ?>
                    <option value=""><?php esc_html_e('All Instructor', BaseController::$text_domain); ?></option>
                    <?php foreach ($instructors as $key => $instructor) : ?>
                        <option value="<?php echo esc_html($instructor->ID); ?>" <?php if (isset($_GET['instructor_id']) && sanitize_text_field($_GET['instructor_id']) == esc_html($instructor->ID)) echo 'selected'; ?>><?php echo esc_html($instructor->display_name); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="category">
                    <option value=""><?php esc_html_e('All Category', BaseController::$text_domain); ?></option>
                    <?php foreach (Category::get_categories() as $category) : ?>
                        <option value="<?php echo esc_attr($category->slug); ?>" <?php if (isset($_GET['category']) && sanitize_text_field($_GET['category']) == esc_html($category->slug)) echo 'selected'; ?>><?php echo esc_html($category->name); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="price">
                    <option value=""><?php esc_html_e('All', BaseController::$text_domain); ?></option>
                    <option value="paid" <?php if (isset($_GET['price']) && sanitize_text_field($_GET['price']) == "paid") echo "selected"; ?>><?php esc_html_e('Paid', BaseController::$text_domain); ?></option>
                    <option value="free" <?php if (isset($_GET['price']) && sanitize_text_field($_GET['price']) == "free") echo "selected"; ?>><?php esc_html_e('Free', BaseController::$text_domain); ?></option>
                </select>
<?php
                break;
        };
    }

    public static function filter_course_list($query)
    {
        global $pagenow;

        // CHECKING POST TYPE
        if (sanitize_text_field($pagenow) == 'edit.php' && isset($_GET['post_type']) && sanitize_text_field($_GET['post_type']) == "learny-courses") {

            // FILTER BY INSTRUCTOR ID
            if (isset($_GET['instructor_id']) && !empty($_GET['instructor_id'])) {
                $query->query_vars['author'] = sanitize_text_field($_GET['instructor_id']);
            }

            // FILTER BY CATEGORY
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $query->query_vars['learny_category'] = sanitize_text_field($_GET['category']);
            }

            // FILTER BY PRICE
            if (isset($_GET['price']) && !empty($_GET['price'])) {
                $query->query_vars['meta_key'] = 'ly_is_free_course';
                $query->query_vars['meta_value'] = sanitize_text_field($_GET['price']) == "free" ? 1 : 0;
                $query->query_vars['learny_category'] = sanitize_text_field($_GET['category']);
            }
        }

        return $query;
    }
}
