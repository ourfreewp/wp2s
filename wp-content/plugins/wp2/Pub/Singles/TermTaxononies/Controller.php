<?php
// Path: wp-content/plugins/freewp-activity-feeds/src/types/init-feeds.php
/**
 * ActivityFeeds Taxonomy
 *
 * @package FreeWP\Core\Taxonomies
 */

namespace FreeWP\Core\Taxonomies;

use FreeWP\Core\Taxonomy;

/**
 * Class ActivityFeeds
 *
 * Registers the "ActivityFeeds" custom post type by extending the abstract Taxonomy class.
 */

// Check if the Taxonomy class exists; if not, trigger an error and exit.
if (! class_exists('FreeWP\Core\Taxonomy')) {
    do_action('qm/error', 'Class FreeWP\Core\Taxonomy not found');
    return;
}

class ActivityFeeds extends Taxonomy
{
    /**
     * Get the taxonomy key.
     *
     * @return string
     */
    protected function get_taxonomy()
    {
        return FREEWP_PREFIX . 'activity_feed';
    }

    /**
     * Get the object types the taxonomy applies to.
     *
     * @return array
     */
    protected function get_object_type()
    {
        return [FREEWP_PREFIX . 'activity'];
    }

    /**
     * Get the arguments for registering the taxonomy.
     *
     * @return array
     */
    protected function get_args()
    {
        $singular   = 'Activity Feed';
        $plural     = 'Activity Feeds';
        $textdomain = FREEWP_TEXT_DOMAIN;

        return [
            'label'                 => esc_html__($plural, $textdomain),
            'labels'                => $this->get_labels(),
            'description'           => esc_html__('Activity feeds for users.', $textdomain),
            'public'                => false,
            'show_in_rest'          => true,
            'rest_base'             => 'feeds',
            'rewrite'               => [
                'with_front' => false,
                'slug'       => false,
                'hierarchical' => false,
            ],
        ];
    }


    /**
     * Get the labels for the taxonomy.
     *
     * @return array
     */
    private function get_labels()
    {
        $singular   = 'Activity Feed';
        $plural     = 'Activity Feeds';
        $textdomain = FREEWP_TEXT_DOMAIN;

        return [
            'name'                       => esc_html__($plural, $textdomain),
            'singular_name'              => esc_html__($singular, $textdomain),
            'search_items'               => esc_html__('Search Activity Feeds', $textdomain),
            'popular_items'              => esc_html__('Popular Activity Feeds', $textdomain),
            'all_items'                  => esc_html__('All Activity Feeds', $textdomain),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => esc_html__('Edit Activity Feed', $textdomain),
            'update_item'                => esc_html__('Update Activity Feed', $textdomain),
            'add_new_item'               => esc_html__('Add New Activity Feed', $textdomain),
            'new_item_name'              => esc_html__('New Activity Feed Name', $textdomain),
            'separate_items_with_commas' => esc_html__('Separate Activity Feeds with commas', $textdomain),
            'add_or_remove_items'        => esc_html__('Add or remove Activity Feeds', $textdomain),
            'choose_from_most_used'      => esc_html__('Choose from the most used Activity Feeds', $textdomain),
            'not_found'                  => esc_html__('No Activity Feeds found.', $textdomain),
            'menu_name'                  => esc_html__($plural, $textdomain),
        ];
    }


}

$feeds = new ActivityFeeds();

$feeds->register_taxonomy();
