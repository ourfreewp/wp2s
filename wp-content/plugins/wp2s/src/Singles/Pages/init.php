<?php

namespace WPS2\Singles\Pages;

class DefaultPageController
{

    private $textdomain = 'wp2s';
    private $type       = 'page';


    public function __construct()
    {
        add_action('init', [$this, 'register_template'], 41);
    }

    public function extend_post_type()
    {
        add_filter('register_post_type_args', [$this, 'modify_post_type'], 60, 2);
    }

    public function modify_post_type($args, $post_type)
    {
        if ($this->type === $post_type) {
            $args['publicly_queryable'] = true;
            $args['show_ui'] = true;
            $args['show_in_menu'] = true;
            if (! in_array('editor', $args['supports'])) {
                $args['supports'][] = 'editor';
            };
            $args['taxonomies'] = ['wp2s_menu'];
        }
        return $args;
    }

    public function register_template()
    {
        $post_type_object = get_post_type_object($this->type);
        $post_type_object->template = [
            [
                'wp2s/page-data'
            ],
            [
                'wp2s/page'
            ],
        ];
    }
}

$controller = new DefaultPageController();
$controller->extend_post_type();