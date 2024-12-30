<?php
// Path: wp-content/plugins/wp2/Zone/Singles/Module/init.php
namespace WP2\Singles\PostType;

class Module extends Controller
{
    protected function get_post_type()
    {
        return 'wp2_module';
    }

    protected function get_args()
    {
        $singular   = 'Module';
        $plural     = 'Modules';
        $textdomain = 'wp2';

        return [
            'label'               => esc_html__($plural, $textdomain),
            'labels'              => $this->get_labels(),
            'public'              => true,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-admin-generic',
            'supports'            => ['title', 'editor'],
            'rest_base'           => 'wp2/modules',
            'rest_namespace'      => 'wp2/v1',
            'has_archive'         => 'wp2',
            'rewrite'             => ['slug' => 'wp2'],
        ];
    }

    private function get_labels()
    {
        return [
            'name'               => __('Modules', 'wp2'),
            'singular_name'      => __('Module', 'wp2'),
            'add_new'            => __('Add New', 'wp2'),
            'add_new_item'       => __('Add New Module', 'wp2'),
            'edit_item'          => __('Edit Module', 'wp2'),
            'new_item'           => __('New Module', 'wp2'),
            'view_item'          => __('View Module', 'wp2'),
        ];
    }
}

// Register post type
add_action('init', function () {
    $module = new Module();
    $module->register_post_type();
});
