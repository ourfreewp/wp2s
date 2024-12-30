<?php
// Path: wp-content/plugins/freewp-activity-feeds/src/types/init-news.php
/**
 * News Post Type
 *
 * @package FreeWP\Core\PostTypes
 */

namespace FreeWP\Core\PostTypes;

use FreeWP\Core\PostType;

/**
 * Class News
 *
 * Registers the "News" custom post type by extending the abstract PostType class.
 */

// Check if the PostType class exists; if not, trigger an error and exit.
if (! class_exists('FreeWP\Core\PostType')) {
    do_action('qm/error', 'Class FreeWP\Core\PostType not found');
    return;
}

class News extends PostType
{

    /**
     * Constructor.
     *
     * Initializes parent constructor and sets up custom permalink if needed.
     */
    public function __construct()
    {
        add_action('init', [$this, 'add_rewrite_rules']);
    }

    /**
     * Get the post type key.
     *
     * @return string
     */
    protected function get_post_type()
    {
        return FREEWP_PREFIX . 'news';
    }

    /**
     * Get the arguments for registering the post type.
     *
     * @return array
     */
    protected function get_args()
    {
        $singular   = 'News';
        $plural     = 'News';
        $textdomain = FREEWP_TEXT_DOMAIN;

        return [
            'label'               => esc_html__($plural, $textdomain),
            'labels'              => $this->get_labels(),
            'description'         => esc_html__('News and updates.', $textdomain),
            'public'              => true,
            'hierarchical'        => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'rest_base'           => 'news',
            'show_in_rest'        => true,
            'has_archive'         => true,
            'menu_position'       => 25,
            'menu_icon'           => 'dashicons-megaphone',
            'supports'            => [
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'author',
                'custom-fields',
                'revisions'
            ],
            'rewrite'             => [
                'slug'       => 'news',
                'pages'      => 'false',
            ],
            'can_export'          => false,
            'delete_with_user'    => false,
        ];
    }

    /**
     * Get the labels for the custom post type.
     *
     * @return array
     */
    protected function get_labels()
    {
        $singular   = 'News';
        $plural     = 'News';
        $textdomain = FREEWP_TEXT_DOMAIN;

        return [
            'name'                     => esc_html__($plural, $textdomain),
            'singular_name'            => esc_html__($singular, $textdomain),
            'add_new'                  => esc_html__('Add New', $textdomain),
            'add_new_item'             => esc_html__('Add New', $textdomain),
            'edit_item'                => esc_html__('Edit ' . $singular, $textdomain),
            'new_item'                 => esc_html__('New ' . $singular, $textdomain),
            'view_item'                => esc_html__('View ' . $singular, $textdomain),
            'view_items'               => esc_html__('View ' . $plural, $textdomain),
            'search_items'             => esc_html__('Search ' . $plural, $textdomain),
            'not_found'                => esc_html__('No ' . $plural . ' found', $textdomain),
            'not_found_in_trash'       => esc_html__('No ' . $plural . ' found in Trash', $textdomain),
            'parent_item_colon'        => esc_html__('Parent ' . $singular . ':', $textdomain),
            'all_items'                => esc_html__('All ' . $plural, $textdomain),
            'archives'                 => esc_html__($singular . ' Archives', $textdomain),
            'attributes'               => esc_html__($singular . ' Attributes', $textdomain),
            'insert_into_item'         => esc_html__('Insert into ' . $singular, $textdomain),
            'uploaded_to_this_item'    => esc_html__('Uploaded to this ' . $singular, $textdomain),
            'featured_image'           => esc_html__('Featured Image', $textdomain),
            'set_featured_image'       => esc_html__('Set featured image', $textdomain),
            'remove_featured_image'    => esc_html__('Remove featured image', $textdomain),
            'use_featured_image'       => esc_html__('Use as featured image', $textdomain),
            'menu_name'                => esc_html__($plural, $textdomain),
            'filter_items_list'        => esc_html__('Filter ' . $plural . ' list', $textdomain),
            'filter_by_date'           => esc_html__('Filter by date', $textdomain),
            'items_list_navigation'    => esc_html__($plural . ' list navigation', $textdomain),
            'items_list'               => esc_html__($plural . ' list', $textdomain),
            'item_published'           => esc_html__($singular . ' published.', $textdomain),
            'item_published_privately' => esc_html__($singular . ' published privately.', $textdomain),
            'item_reverted_to_draft'   => esc_html__($singular . ' reverted to draft.', $textdomain),
            'item_trashed'             => esc_html__($singular . ' trashed.', $textdomain),
            'item_scheduled'           => esc_html__($singular . ' scheduled.', $textdomain),
            'item_updated'             => esc_html__($singular . ' updated.', $textdomain),
            'item_link'                => esc_html__($singular . ' Link', $textdomain),
            'item_link_description'    => esc_html__('A link to a ' . $singular, $textdomain),
        ];
    }

    public function add_rewrite_rules()
    {
        $post_type = $this->get_post_type();
        add_rewrite_rule(
            '^news/?$',
            'index.php?post_type=' . $post_type,
            'top'
        );
    }


}

$news = new News();

$news->register_post_type();
