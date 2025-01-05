<?php

namespace WPS2\Singles\Pages;

class DefaultPageController
{

    private $textdomain = 'wp2s';
    private $type       = 'page';
    private $taxonomies = [
        'wp2s_menu',
        'wp2s_form',
    ];


    public function __construct()
    {
        add_action('init', [$this, 'register_template'], 41);    
    }

    public function extend_post_type()
    {
        add_filter('register_post_type_args', [$this, 'modify_post_type'], 60, 2);
        add_action('init', [$this, 'attach_taxonomy'], 60);
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

        }
        return $args;
    }

    // attach taxonomy to post type
    public function attach_taxonomy()
    {
        add_action('init', [$this, 'register_taxonomy_to_post_type']);
    }

    public function register_taxonomy_to_post_type()
    {
        foreach ($this->taxonomies as $taxonomy) {
            register_taxonomy_for_object_type($taxonomy, $this->type);
        }
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