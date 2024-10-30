<?php

/**
 * @package Learny
 */

namespace Learny\api;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\api\callbacks\AdminCallbacks;
use Learny\base\BaseController;

class SettingsApi
{
    public $admin_page = array();
    public $admin_sub_page = array();

    /**
     * Method for registering admin menu hook to the plugin
     *
     * @return void
     */
    public function register()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('parent_file', array($this, 'menu_highlight'));
    }


    /**
     * THIS FUNCTION IS REQUIRED FOR HIGHLIGHTING THE TAXONOMY SUBMENUS
     *
     * @param [type] $parent_file
     * @return void
     */
    function menu_highlight($parent_file)
    {
        global $plugin_page, $submenu_file, $post_type, $taxonomy;
        if ('learny-courses' == $post_type) {
            if ($taxonomy == 'learny_category') {
                $plugin_page = 'edit-tags.php?taxonomy=learny_category&post_type=learny-courses'; // the submenu slug 
                $submenu_file = 'edit-tags.php?taxonomy=learny_category&post_type=learny-courses';    // the submenu slug
            }
            if ($taxonomy == 'learny_tag') {
                $plugin_page = 'edit-tags.php?taxonomy=learny_tag&post_type=learny-courses'; // the submenu slug 
                $submenu_file = 'edit-tags.php?taxonomy=learny_tag&post_type=learny-courses';    // the submenu slug
            }
        }
        return $parent_file;
    }

    /**
     * Method for adding other sub menus to the plugin
     *
     * @param array $pages
     * @return object
     */
    public function add_pages(array $pages, array $sub_pages)
    {
        $this->admin_page = array_merge($this->admin_page, $pages);
        $this->admin_sub_page = array_merge($this->admin_sub_page, $sub_pages);
        return $this;
    }

    /**
     * Method for adding menu items to the plugin after all necessary configurations
     *
     * @return void
     */
    public function add_admin_menu()
    {
        // ADDING PAGES TO THE PLUGIN
        foreach ($this->admin_page as $page) {
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], 64);
        }

        // ADDING SUBPAGES TO THE PLUGIN
        foreach ($this->admin_sub_page as $page) {
            add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback']);
        }

        // REMOVE ALL THE UNNECESSARY SUBMENU PAGES
        $this->remove_default_submenu_pages();
    }

    /**
     * JUST REMOVE SOME DEFAULT SUBMENUSS
     *
     * @return void
     */
    private function remove_default_submenu_pages()
    {
        // REMOVE COURSE CUSTOM POST SUBMENU
        remove_submenu_page("edit.php?post_type=learny-courses", "post-new.php?post_type=learny-courses");
    }
}
