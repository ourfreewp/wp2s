<?php

add_filter('shopwp_use_products_all_template', function ($use_plugin_template) {
    return false;
});

add_filter('shopwp_use_products_single_template', function ($use_plugin_template) {
    return false;
});

add_filter('shopwp_use_collections_all_template', function ($use_plugin_template) {
    return false;
});

add_filter('shopwp_use_collections_single_template', function ($use_plugin_template) {
    return false;
});

add_filter('shopwp_register_products_args', function ($args) {
    $args['exclude_from_search'] = true;
    return $args;
});

add_filter('shopwp_show_breadcrumbs', function ($show_breadcrumbs) {
    return false;
});
