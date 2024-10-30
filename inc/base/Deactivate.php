<?php

/**
 * @package Learny
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class Deactivate extends BaseController
{
    // Method called while the plugin deactivates
    public static function deactivate()
    {
        flush_rewrite_rules();
        self::delete_plugin_pages();
    }

    /**
     * DELETE ALL THE PLUGIN PAGE
     *
     * @return void
     */
    public static function delete_plugin_pages()
    {
        foreach (self::$plugin_pages as $page_id => $page_slug) {
            $page_id = esc_html(get_option($page_id, null));
            if ($page_id) {
                wp_delete_post($page_id);
            }
        }
    }
}
