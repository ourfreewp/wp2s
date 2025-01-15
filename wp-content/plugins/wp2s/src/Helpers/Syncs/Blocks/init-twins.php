<?php
// Path: wp-content/plugins/wp2s/src/Helpers/Syncs/Blocks/init-twins.php
namespace WP2S\Helpers\Syncs\Blocks\PostTwins;

class Controller
{
    /**
     * Ensures WordPress posts exist for a given set of items.
     *
     * @param array $items The items to process, where the keys are the item identifiers and values are the item data.
     * @param string $post_type The WordPress post type to associate with the items.
     * @param callable|null $title_callback A callback to generate post titles.
     * @param callable|null $meta_callback A callback to generate post meta data.
     * @param callable $name_callback A callback to generate the post name (slug).
     * @param string $default_status The default post status. Default is 'publish'.
     * @param callable|null $post_save_callback A callback executed after a post is created or updated.
     *
     * @return void
     */
    public static function check_twin(
        array $items,
        string $post_type,
        ?callable $title_callback = null,
        ?callable $meta_callback = null,
        callable $name_callback,
        string $default_status = 'publish',
        ?callable $post_save_callback = null
    ) {
        foreach ($items as $item_key => $item_data) {
            // Generate the post title
            $title = $title_callback ? $title_callback($item_key, $item_data) : ucwords($item_key);

            // Generate the post name (slug)
            $sanitized_name = sanitize_title($name_callback($item_key, $item_data));

            if (empty($sanitized_name)) {
                error_log("Error: name_callback returned an empty slug for item: {$item_key}");
                continue;
            }

            // Check if the post already exists
            $query = new \WP_Query([
                'post_type'   => $post_type,
                'name'        => $sanitized_name,
                'post_status' => 'any',
                'fields'      => 'ids',
            ]);

            $post_id = $query->have_posts() ? (int) $query->posts[0] : null;

            // Prepare post arguments
            $post_args = [
                'post_type'   => $post_type,
                'post_name'   => $sanitized_name,
                'post_title'  => $title,
                'post_status' => $default_status,
                'meta_input'  => $meta_callback ? $meta_callback($item_key, $item_data) : [],
            ];

            // Insert or update the post
            $result = $post_id
                ? wp_update_post(array_merge(['ID' => $post_id], $post_args), true)
                : wp_insert_post($post_args, true);

            // Handle errors
            if (is_wp_error($result)) {
                error_log("Failed to save post: {$result->get_error_message()}");
                continue;
            }

            $saved_post_id = $post_id ?: (int) $result;

            // Execute the post-save callback if provided
            if (is_callable($post_save_callback)) {
                $post_save_callback($saved_post_id, $item_key, $item_data);
            }

            error_log("Successfully synced post (ID: {$saved_post_id}) for item: {$item_key}");
        }
    }
}
