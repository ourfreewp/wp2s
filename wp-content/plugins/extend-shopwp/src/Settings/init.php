<?php

namespace WP2\Extend\ShopWP\Settings;

class Controller {

    public function register_hooks() {
        add_filter('shopwp_use_products_all_template', [$this, 'disable_all_products_template']);
        add_filter('shopwp_use_products_single_template', [$this, 'disable_single_product_template']);
        add_filter('shopwp_use_collections_all_template', [$this, 'disable_all_collections_template']);
        add_filter('shopwp_use_collections_single_template', [$this, 'disable_single_collection_template']);
        add_filter('shopwp_register_products_args', [$this, 'modify_product_args']);
        add_filter('shopwp_show_breadcrumbs', [$this, 'disable_breadcrumbs']);
    }

    public function disable_all_products_template($use_plugin_template) {
        return false;
    }

    public function disable_single_product_template($use_plugin_template) {
        return false;
    }

    public function disable_all_collections_template($use_plugin_template) {
        return false;
    }

    public function disable_single_collection_template($use_plugin_template) {
        return false;
    }

    public function modify_product_args($args) {
        $args['exclude_from_search'] = true;
        return $args;
    }

    public function disable_breadcrumbs($show_breadcrumbs) {
        return false;
    }
}

// Initialize and register hooks
$controller = new Controller();
$controller->register_hooks();