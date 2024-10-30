<?php

/**
 * @package Learny
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class Bootstrap extends BaseController
{
    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        add_action('plugins_loaded', array($this, 'learny_plugin_loaded'));
    }

    /**
     * HOOKS CALLBACK
     *
     * @return void
     */
    public function learny_plugin_loaded()
    {
        load_plugin_textdomain('learny', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}
