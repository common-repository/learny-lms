<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class Category extends BaseController
{

    /**
     * GET CATEGORY TAXONOMY
     *
     * @return object
     */
    public static function get_categories()
    {
        $terms = get_terms([
            'taxonomy' => 'learny_category',
            'hide_empty' => true,
        ]);

        return $terms;
    }

    /**
     * GET PARENT CATEGORIES
     *
     * @return object
     */
    public static function get_parent_categories()
    {
        $terms = get_terms([
            'taxonomy' => 'learny_category',
            'hide_empty' => false,
            'parent' => 0
        ]);

        return $terms;
    }

    /**
     * THIS FUNCTION RETURNS SUB CATEGORIES
     *
     * @return object
     */
    public static function get_subcategories($parent_category_id)
    {
        $terms = get_terms([
            'taxonomy' => 'learny_category',
            'hide_empty' => false,
            'parent' => $parent_category_id
        ]);

        return $terms;
    }
}
