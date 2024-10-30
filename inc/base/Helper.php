<?php

/**
 * @package Learny LMS
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class Helper extends BaseController
{

    protected static $pluginUrl;
    protected static $pluginPath;

    function __construct()
    {
        parent::__construct();
        self::$pluginUrl = $this->plugin_url;
        self::$pluginPath = $this->plugin_path;
    }

    /**
     * RETURN ALL THE DEFAULT THUMBNAILS
     *
     * @param string $thumbnail_type
     * @return void
     */
    public static function get_thumbnail(string $thumbnail_type)
    {
        $thumbnail_url = self::$pluginUrl . '/assets/admin/images/100X100.png';
        switch ($thumbnail_type) {
            case "category":
                $thumbnail_url = self::$pluginUrl . '/assets/admin/images/100X100.png';
                break;
        }

        return $thumbnail_url;
    }

    /**
     * RETURN RIGHT MODAL PATH
     *
     * @return string
     */
    public static function learnyModal()
    {
        return self::$pluginPath . 'views/modal/modal.php';
    }

    /**
     * GETTING CURRENT LEARNY USER ROLE
     *
     * @return void
     */
    public static function get_current_user_role()
    {
        $wp_object = wp_get_current_user();
        $user_role_array_with_plugin_id = $wp_object->roles;

        if (count($user_role_array_with_plugin_id)) {
            if ($user_role_array_with_plugin_id[0] == 'administrator') {
                return $user_role_array_with_plugin_id[0];
            } else {
                $user_role_array = explode('-', $user_role_array_with_plugin_id[0]);
                return isset($user_role_array[1]) ? $user_role_array[1] : $user_role_array[0];
            }
        }
    }

    /**
     * GETTING CURRENT LEARNY USER ROLE
     *
     * @return void
     */
    public static function if_admin_logged_in()
    {
        $wp_object = wp_get_current_user();
        $user_role_array_with_plugin_id = $wp_object->roles;

        if (count($user_role_array_with_plugin_id)) {
            if ($user_role_array_with_plugin_id[0] == 'administrator') {
                return true;
            }

            return false;
        }
    }


    /**
     * GET CURRENCY CODE
     *
     * @param [type] $price
     * @param string $attr
     * @return void
     */
    public static function currency($price)
    {
        $currency = esc_html(get_option('ly_system_currency', '$-USD'));

        $currency_symbol = "$";
        $currency_code = "USD";

        if (isset($currency) && !empty($currency)) {
            $currency_data = explode('-', $currency);
            $currency_symbol = $currency_data[0];
            $currency_code = $currency_data[1];
        }


        $system_currency_position = get_option('ly_currency_position', 'left');


        if ($system_currency_position == 'right') {
            return $price . $currency_symbol;
        } elseif ($system_currency_position == 'right-space') {
            return $price . ' ' . $currency_symbol;
        } elseif ($system_currency_position == 'left') {
            return $currency_symbol . $price;
        } elseif ($system_currency_position == 'left-space') {
            return $currency_symbol . ' ' . $price;
        }
    }

    /**
     * RETURNS URL
     *
     * @param [type] $urlTail
     * @return void
     */
    public static function get_url($urlTail = null)
    {
        $permalink = get_permalink();

        if (empty($urlTail)) {
            return $permalink;
        } else {
            $final_url = strpos($permalink, '?') ? $permalink . "&" . $urlTail : $permalink . "?" . $urlTail;
            if (filter_var($final_url, FILTER_VALIDATE_URL) === FALSE) {
                return $final_url;
            } else {
                return $final_url;
            }
        }
    }

    // RETURN THE FILE EXTENSION
    public static function get_extension($file)
    {
        $filetype = wp_check_filetype($file);
        $filetype = $filetype['ext'];
        return $filetype;
    }


    /**
     * THIS FUNCTION RETURNS THE SHORTCODE FILE PATH
     *
     * @param string $trail
     * @return string
     */
    public static function view_path(string $trail)
    {
        $file_path = self::$pluginPath;
        if (!empty($trail)) {
            $file_path .=  "templates/frontend/shortcode/$trail.php";
        }
        return $file_path;
    }

    // RETURN THE HUMAN READABLE
    public static function readable_time_for_humans($duration)
    {
        if ($duration) {
            $duration_array = explode(':', $duration);
            $hour   = $duration_array[0];
            $minute = $duration_array[1];
            $second = $duration_array[2];
            if ($hour > 0) {
                $duration = $hour . ' ' . 'hr' . ' ' . $minute . ' ' . 'min';
            } elseif ($minute > 0) {
                if ($second > 0) {
                    $duration = ($minute + 1) . ' ' . 'min';
                } else {
                    $duration = $minute . ' ' . 'min';
                }
            } elseif ($second > 0) {
                $duration = $second . ' ' . 'sec';
            } else {
                $duration = '00:00';
            }
        } else {
            $duration = '00:00';
        }
        return $duration;
    }

    /**
     * CHECK IF THE COURSE IS PURCHASED BY THE USER
     *
     * @param int $course_id
     * @return boolean
     */
    public static function has_purchased($course_id)
    {
        $user_id = get_current_user_id();
        global $wpdb;
        $table = self::$tables['enrolment'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `enrolment_user_id` = %d AND `enrolment_course_id` = %d", $user_id, $course_id));
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * THIS FUNCTION VALIDATET IF THE PROVIDED STRING IS AN EMAIL
     *
     * @param string $email
     * @return void
     */
    public static function validate_email(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }


    /**
     * THIS FUNCTION VALIDATES IF THE PROVIDED NUMBER IS POSITIVE AND NUMERICAL
     * @return void
     */
    public static function validate_positive_number($number)
    {
        if (is_numeric($number) && $number > 0) {
            return true;
        }
        return true;
    }


    /**
     * THIS THEME CHECKS IF A THE CURRENT THEME IS REGISTERED
     *
     * @return boolean
     */
    public static function is_registered_theme()
    {
        $themes = array('coursepro');
        $current_theme_details = wp_get_theme();
        $current_theme_textdomain = $current_theme_details->get('TextDomain');

        if (in_array($current_theme_textdomain, $themes)) {
            return true;
        }

        return false;
    }
}

new Helper();
