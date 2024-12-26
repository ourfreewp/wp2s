<?php
// Path: wp-content/plugins/freewp-activity-feeds/src/feeds/init.php
/**
 * Feeds
 *
 * @package FreeWP\Core\Feeds
 */

namespace FreeWP\Core\Feeds;

use WP_Query;

class News
{
    /**
     * Constructor.
     *
     * Initializes the class by setting filters and administration functions.
     */
    public function __construct()
    {
        add_action('pre_get_posts', [$this, 'query']);
        do_action( 'qm/debug', 'News feed initialized' );
    }

    /**
     * Modify queries related to the News post type.
     *
     * @param \WP_Query $query
     */
    public function query($query)
    {
        // Avoid modifying queries in the admin or non-main queries.
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        // Only modify queries on the 'news' post type archive.
        if (!is_post_type_archive(FREEWP_PREFIX . 'news')) {
            return;
        }

        // Set the number of posts per page to 10.
        $query->set('posts_per_page', 10);

        // Set the post types to 'news' and 'activity'.
        $post_types = [
            FREEWP_PREFIX . 'news',
            FREEWP_PREFIX . 'activity',
        ];

        $query->set('post_type', $post_types);

        return $query;
    }
}

new News();