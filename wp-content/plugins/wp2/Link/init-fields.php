<?php
// Path: wp-content/plugins/freewp-bookmarks/src/fields/init.php
/**
 * Bookmarks Meta Boxes
 *
 * @package FreeWP\Core\MetaBoxes
 */

namespace FreeWP\Core\MetaBoxes;

use FreeWP\Core\MetaBox;

/**
 * Class Bookmarks
 *
 * Registers the "Bookmarks" meta box by extending the abstract MetaBox class.
 */

// check if the class exists, if not error out and return

if (! class_exists('FreeWP\Core\MetaBox')) {
    do_action('qm/error', 'Class FreeWP\Core\MetaBox not found');
    return;
}

class Bookmarks extends MetaBox
{

    /**
     * Get the meta box configuration.
     *
     * @return array
     */
    protected function get_meta_box()
    {
        $prefix = FREEWP_PREFIX;
        $textdomain = FREEWP_TEXT_DOMAIN;

        return [
            'title'       => esc_html__('Bookmark Details', $textdomain),
            'post_types'  => [FREEWP_PREFIX . 'bookmark'],
            'context'     => 'normal',
            'priority'    => 'high',
            'autosave'    => true,
            'fields'      => [
                [
                    'name' => esc_html__('URL', $textdomain),
                    'id'   => $prefix . 'bookmark_url',
                    'type' => 'url',
                ],
                [
                    'name' => esc_html__('Description', $textdomain),
                    'id'   => $prefix . 'bookmark_description',
                    'type' => 'textarea',
                ],
            ],
        ];
    }
}

$bookmarks = new Bookmarks();

$bookmarks->register_meta_box();
