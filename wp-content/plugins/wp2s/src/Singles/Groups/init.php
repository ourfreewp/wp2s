<?php
// Path: wp-content/plugins/wp2s/Singles/Groups/init.php
namespace WPS2\Singles\Groups;

class Controller
{

    private $textdomain = 'wp2s';
    private $type       = 'wp2s_group';
    private $archives   = 'groups';
    private $slug       = 'group';

    public function extend_post_type()
    {
        add_filter('register_post_type_args', [$this, 'modify_post_type'], 10, 2);
    }

    public function modify_post_type($args, $post_type)
    {
        if ($this->type === $post_type) {
            $args['public'] = true;
            $args['publicly_queryable'] = true;
            $args['show_ui'] = true;
            $args['show_in_menu'] = true;
            $args['has_archive'] = false;
            if (! in_array('editor', $args['supports'])) {
                $args['supports'][] = 'editor';
            }
            $args['has_archive'] = $this->archives;
            $args['rewrite'] = [
                'slug' => $this->slug,
                'with_front' => false,
            ];
        }
        return $args;
    }
}


$controller = new Controller();
$controller->extend_post_type();
