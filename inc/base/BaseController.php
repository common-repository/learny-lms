<?php

/**
 * @package Learny
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class BaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;

    public $version;

    public static $text_domain = 'learny';
    public $slugs;
    public static $tables;
    public static $custom_roles;
    public static $plugin_id = 'learny';
    public static $plugin_pages;


    // Defines the public variables initiated in this class
    public function __construct()
    {
        $this->plugin_path      = plugin_dir_path(dirname(dirname(__FILE__)));
        $this->plugin_url       = plugin_dir_url(dirname(dirname(__FILE__)));
        $this->plugin           = plugin_basename(dirname(dirname(dirname(__FILE__)))) . '/learny.php';
        $this->slugs            = $this->define_slugs();
        self::$tables           = $this->define_tables();
        self::$custom_roles     = $this->define_custom_roles();
        self::$plugin_pages     = $this->define_plugin_pages();

        // FOR DEVELOPMENT PURPOSE MAKE IT TIME FOR CACHE BUSTING
        $this->version = time();
    }

    // Define the menu and submenu slugs
    public function define_slugs()
    {
        $slugs_array = array(
            'report'     => self::$plugin_id . '-' . 'report',
            'payout'     => self::$plugin_id . '-' . 'payout',
            'student'    => self::$plugin_id . '-' . 'student',
            'instructor' => self::$plugin_id . '-' . 'instructor',
            'settings'   => self::$plugin_id . '-' . 'settings',
            'pro_lms'   => self::$plugin_id . '-' . 'pro_lms',
        );
        return $slugs_array;
    }

    // DEFINING PLUGIN PAGES
    private function define_plugin_pages()
    {
        return [
            'ly_auth_page' => 'learny-auth',
            'ly_checkout_page' => 'learny-checkout',
            'ly_dashboard_page' => 'learny-dashboard',
            'ly_course_player_page' => 'learny-course-player'
        ];
    }

    // Define table names created by the plugin
    public function define_tables()
    {
        global $wpdb;
        $tables_array = array(
            'currencies'    => $wpdb->prefix . self::$plugin_id . '_' . 'currencies',
            'enrolment'     => $wpdb->prefix . self::$plugin_id . '_' . 'enrolment',
            'payment'       => $wpdb->prefix . self::$plugin_id . '_' . 'payment',
            'watch_history' => $wpdb->prefix . self::$plugin_id . '_' . 'watch_history',
            'review'        => $wpdb->prefix . self::$plugin_id . '_' . 'review',
            'wishlist'      => $wpdb->prefix . self::$plugin_id . '_' . 'wishlist',
            'payout'        => $wpdb->prefix . self::$plugin_id . '_' . 'payout',
        );
        return $tables_array;
    }

    // Define custom roles created by the plugin
    public function define_custom_roles()
    {
        $roles_array = array(
            'student' => array(
                'role' => self::$plugin_id . '-' . 'student',
                'display_name' => 'Learny Student',
                'caps' => CustomCapabilities::studentCustomCapabilities()
            ),
            'instructor' => array(
                'role' => self::$plugin_id . '-' . 'instructor',
                'display_name' => 'Learny Instructor',
                'caps' => CustomCapabilities::instructorCustomCapabilities()
            )
        );
        return $roles_array;
    }

    // Convenient method for sanitizing an array and return a sanitized array
    public function sanitize_array($array)
    {
        $sanitized_array = array();
        $i = 0;
        foreach ($array as $value) {
            $sanitized_array[$i] = (isset($value)) ? sanitize_text_field($value) : '';
            $i++;
        }
        return $sanitized_array;
    }

    // Convenient method for verifying wp nonce (provided that nonce field name and action is same)
    public static function verify_nonce($nonce_name)
    {
        if (isset($_POST[$nonce_name])) {
            if (wp_verify_nonce(sanitize_text_field($_POST[$nonce_name]), $nonce_name)) {
                return true;
            }
            return false;
        }
        return false;
    }
}
