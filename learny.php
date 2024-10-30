<?php

/**
 * Plugin Name: Learny LMS
 * Plugin URI:  https://creativeitem.com/docs/learny-lms/
 * Description: Launch your online course business & e-learning platform with the powerful learny lms plugin.
 * Version:     1.3.0
 * Author:      Creativeitem
 * Author URI:  https://www.creativeitem.com/
 * Text Domain: learny
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     Learny LMS
 * @author      Creativeitem
 * @copyright   2021 Creativeitem
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      learny
 */

defined('ABSPATH') || die('No script kiddies please!');

/**
 * LOADING THE AUTOLOAD FILE
 */
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Method runs during plugin activation
function learny_activate()
{
    Learny\base\Activate::activate();
}
register_activation_hook(__FILE__, 'learny_activate');

// Method runs during plugin deactivation
function learny_deactivate()
{
    Learny\base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'learny_deactivate');

// Initialize all the core classes of the plugin with all the necessary hooks that are needed to be registered
if (class_exists('Learny\Init')) {
    Learny\Init::register_services();
}

/**
 * CUSTOM LINKS IN PLUGIN LIST PAGE
 * @param string $links
 * @return array
 */
function learny_add_action_links($links)
{
    $mylinks = array(
        '<a href="' . admin_url('admin.php?page=learny-settings') . '">' . esc_html__('Learny Settings', 'learny') . '</a>',
    );
    return array_merge($links, $mylinks);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'learny_add_action_links');

/**
 * THIS FUNCTION IS FOR UPDATING PLUGIN META
 *
 * @param string $plugin_meta
 * @param string $plugin_file
 * @return string
 */
function learny_plugin_row_meta($plugin_meta, $plugin_file)
{
    if ($plugin_file === plugin_basename(__FILE__)) {
        $row_meta = [
            'docs' => '<a href="https://creativeitem.com/docs/learny-lms" aria-label="' . esc_attr(esc_html__('View Learny Documentation', 'learny')) . '" target="_blank">' . esc_html__('Docs & FAQs', 'learny') . '</a>',
            'video' => '<a href="https://youtube.com/playlist?list=PLR1GrQCi5Zqs5gi5o0DUwuSof1ducgyHw" aria-label="' . esc_attr(esc_html__('View Learny Video Tutorials', 'learny')) . '" target="_blank">' . esc_html__('Video Tutorials', 'learny') . '</a>',
            'pro' => '<a href="https://codecanyon.net/item/learny-lms-wordpress-plugin/35682834" style="color: #FF5722;" aria-label="' . esc_attr(esc_html__('Pro Version', 'learny')) . '" target="_blank"><strong>⭐️' . esc_html__('Pro Version', 'learny') . '⭐️</strong></a>',
        ];
        $plugin_meta = array_merge($plugin_meta, $row_meta);
    }

    return $plugin_meta;
}
add_filter('plugin_row_meta', 'learny_plugin_row_meta', 10, 2);
