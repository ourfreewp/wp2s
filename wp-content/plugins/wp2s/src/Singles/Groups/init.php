<?php
// Path: wp-content/plugins/wp2s/Singles/Groups/init.php
namespace WPS2\Singles\Groups;

class Controller
{

    private $textdomain = 'wp2s';
    private $type       = 'wp2s_group';
    private $archives   = 'groups';
    private $slug       = 'group';
    private $singular   = 'Group';
    private $plural     = 'Groups';
    private $menu       = 'Groups';
    private $icon       = 'dashicons-groups';

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
            if (! in_array('editor', $args['supports'])) {
                $args['supports'][] = 'editor';
            }
            $args['has_archive'] = $this->archives;
            $args['rewrite'] = [
                'slug' => $this->slug,
                'with_front' => false,
            ];
            $args['menu_icon'] = $this->icon;
            $args['labels'] = [
                'name'               => $this->plural,
                'singular_name'      => $this->singular,
                'menu_name'          => $this->menu,
                'name_admin_bar'     => $this->singular,
                'add_new'            => 'Add New',
                'add_new_item'       => 'Add New ' . $this->singular,
                'new_item'           => 'New ' . $this->singular,
                'edit_item'          => 'Edit ' . $this->singular,
                'view_item'          => 'View ' . $this->singular,
                'all_items'          => 'All ' . $this->plural,
                'search_items'       => 'Search ' . $this->plural,
                'parent_item_colon'  => 'Parent ' . $this->plural . ':',
                'not_found'          => 'No ' . strtolower($this->plural) . ' found.',
                'not_found_in_trash' => 'No ' . strtolower($this->plural) . ' found in Trash.',
            ];

        }
        return $args;
    }
}


$controller = new Controller();
$controller->extend_post_type();
