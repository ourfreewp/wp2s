<?php
// Path: wp-content/plugins/freewp-bookmarks/src/post-type/init.php
/**
 * Bookmarks Post Type
 *
 * @package FreeWP\Core\PostTypes
 */

namespace FreeWP\Core\PostTypes;

use FreeWP\Core\PostType;

/**
 * Class Bookmarks
 *
 * Registers the "Bookmarks" custom post type by extending the abstract PostType class.
 */

// check if the class exists, if not error out and return

if (! class_exists('FreeWP\Core\PostType')) {
    do_action('qm/error', 'Class FreeWP\Core\PostType not found');
    return;
}

class Bookmarks extends PostType
{

    /**
     * Get the post type key.
     *
     * @return string
     */
    protected function get_post_type()
    {
        return FREEWP_PREFIX . 'bookmark';
    }

    /**
     * Get the arguments for registering the post type.
     *
     * @return array
     */
    protected function get_args()
    {
        $singular = 'Bookmark';
        $plural   = 'Bookmarks';
        $textdomain = FREEWP_TEXT_DOMAIN;
        return [
            'label'               => esc_html__($plural, $textdomain),
            'labels'              => $this->get_labels(),
            'description'         => esc_html__('Bookmarks saved by users.', $textdomain),
            'public'              => false,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'rest_base'           => 'bookmarks',
            'show_in_rest'        => true,
            'has_archive'         => 'bookmarks',
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-book',
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'author', 'custom-fields', 'revisions'],
            'rewrite'             => [
                'slug'       => 'bookmark',
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

        $singular = 'Bookmark';
        $plural   = 'Bookmarks';
        $textdomain = FREEWP_TEXT_DOMAIN;

        return [
            'name'                     => esc_html__($plural, $textdomain),
            'singular_name'            => esc_html__($singular, $textdomain),
            'add_new'                  => esc_html__('Add New ' . $singular, $textdomain),
            'add_new_item'             => esc_html__('Add New ' . $singular, $textdomain),
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
}

$bookmarks = new Bookmarks();

$bookmarks->register_post_type();
