<?php

/**
 * @package Learny
 */

namespace Learny\table;

use Learny\base\BaseController;
use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');

if (!class_exists("WP_List_Table")) {
    require_once("ABSPATH" . "wp-admin/includes/class-wp-list-table.php");
}

use WP_List_Table;

abstract class Base_Table extends WP_List_Table
{

    private $_items;
    private $_columns = array();
    private $_sortable_columns = array();
    private $_per_page;
    /**
     * DEFAULT CONSTRUCTOR
     *
     * @param array $args
     */
    public function __construct($args = array(), $columns = [], $sortable_columns = [], $per_page = '')
    {
        parent::__construct($args);
        $this->_columns = $columns;
        $this->_per_page = $per_page;
        $this->_sortable_columns = $sortable_columns;
    }

    public function set_data($data)
    {
        $this->_items = $data;
    }

    public function get_columns()
    {
        return $this->_columns;
    }

    public function column_cb($item)
    {
        return "<input type='checkbox' value={$item['id']} />";
    }


    public function get_sortable_columns()
    {
        return $this->_sortable_columns;
    }

    public function prepare_items()
    {
        $paged = isset($_REQUEST['paged']) ? sanitize_text_field($_REQUEST['paged']) : 1;
        $per_page = $this->_per_page;
        $total_items = count($this->_items);
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());

        $data_chunks = array_chunk($this->_items, $per_page);

        $this->items = count($data_chunks) > 0 ? $data_chunks[$paged - 1] : [];

        $this->set_pagination_args(
            [
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_page' => ceil(count($this->_items) / $per_page),

            ]
        );
    }

    public function column_default($item, $column_name)
    {
        return $item[$column_name];
    }
}
