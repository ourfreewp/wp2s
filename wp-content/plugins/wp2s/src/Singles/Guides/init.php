<?php
// Path: wp-content/plugins/wp2s/Singles/Guides/init.php
namespace WPS2\Singles\Guides;

class Controller {

    private $textdomain = 'wp2s';
    private $type       = 'wp2s_guide';
    private $archives   = 'learn';
    private $slug       = 'guide';
    private $singular   = 'Guide';
    private $plural     = 'Guides';
    private $icon       = 'dashicons-welcome-learn-more';

    public function extend_post_type() {
        add_filter( 'register_post_type_args', [ $this, 'modify_post_type' ], 10, 2 );
    }

    public function modify_post_type( $args, $post_type ) {
        if ( $this->type === $post_type ) {
            $args['public'] = true;
            $args['publicly_queryable'] = true;
            $args['show_ui'] = true;
            $args['show_in_menu'] = true;
            if ( ! in_array( 'editor', $args['supports'] ) ) {
                $args['supports'][] = 'editor';
            }
            $args['has_archive'] = $this->archives;
            $args['rewrite'] = [
                'slug' => $this->slug,
                'with_front' => false,
            ];
            $args['menu_icon'] = $this->icon;
            $args['labels'] = [
                'name' => __( $this->plural, $this->textdomain ),
                'singular_name' => __( $this->singular, $this->textdomain ),
                'add_new' => __( 'Add New', $this->textdomain ),
                'add_new_item' => __( 'Add New ' . $this->singular, $this->textdomain ),
                'edit_item' => __( 'Edit ' . $this->singular, $this->textdomain ),
                'new_item' => __( 'New ' . $this->singular, $this->textdomain ),
                'view_item' => __( 'View ' . $this->singular, $this->textdomain ),
                'view_items' => __( 'View ' . $this->plural, $this->textdomain ),
                'search_items' => __( 'Search ' . $this->plural, $this->textdomain ),
                'not_found' => __( 'No ' . $this->plural . ' found', $this->textdomain ),
                'not_found_in_trash' => __( 'No ' . $this->plural . ' found in Trash', $this->textdomain ),
                'all_items' => __( 'All ' . $this->plural, $this->textdomain ),
            ];
        }
        return $args;
    }
}

$controller = new Controller();
$controller->extend_post_type();