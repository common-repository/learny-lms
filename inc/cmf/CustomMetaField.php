<?php

/**
 * @package Learny
 */

namespace Learny\cmf;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

class CustomMetaField extends BaseController
{
    protected $is_post;
    protected $slug;
    protected $view_path;
    protected $fields;

    /**
     * DEFAULT CONSTRUCTOR FOR INITIALIZING MANDATORY FIELDS
     *
     * @param string $slug
     * @param string $view_path
     * @param array $fields
     * @param boolean $is_post
     */
    function __construct(string $slug, string $view_path, array $fields, bool $is_post)
    {
        $this->slug = $slug;
        $this->view_path = $view_path;
        $this->is_post = $is_post;
        $this->fields = $fields;
        $this->register();
    }

    /**
     * REGISTERING HOOKS
     *
     * @return void
     */
    public function register()
    {
        add_action('init', array($this, 'register_custom_meta_fields'));
        add_action($this->slug . "_add_form_fields", array($this, 'taxm_category_form_field'));
        add_action($this->slug . "_edit_form_fields", array($this, 'taxm_category_form_field'));
        add_action("created_" . $this->slug, array($this, 'taxm_save_category_meta'));
        add_action("edited_" . $this->slug, array($this, 'taxm_update_category_meta'));
    }

    /**
     * HOOKS CALLBACK
     *
     * @return void
     */
    public function register_custom_meta_fields()
    {
        if (count($this->fields)) {
            foreach ($this->fields as $key => $arguments) {
                register_meta('term', $key, $arguments);
            }
        }
    }

    /**
     * GENERATE CUSTOM META FIELDS VIEW
     *
     * @return void
     */
    function taxm_category_form_field($term)
    {
        ob_start();
        include  $this->view_path;
        $content = ob_get_clean();
        echo html_entity_decode(esc_html($content));
    }


    /**
     * STORING CUSTOM META FIELDS VALUES
     *
     * @param int $term_id
     * @return void
     */
    function taxm_save_category_meta($term_id)
    {
        if (wp_verify_nonce(sanitize_text_field($_POST['_wpnonce_add-tag']), 'add-tag')) {
            foreach ($this->fields as $key => $arguments) {
                $extra_field = sanitize_text_field($_POST[$key]);
                update_term_meta($term_id, $key, $extra_field);
            }
        }
    }

    /**
     * UPDATING CUSTOM META FIELDS VALUES
     *
     * @param int $term_id
     * @return void
     */
    function taxm_update_category_meta($term_id)
    {
        if (wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), "update-tag_{$term_id}")) {
            foreach ($this->fields as $key => $arguments) {
                $extra_field = sanitize_text_field($_POST[$key]);
                update_term_meta($term_id, $key, $extra_field);
            }
        }
    }
}
