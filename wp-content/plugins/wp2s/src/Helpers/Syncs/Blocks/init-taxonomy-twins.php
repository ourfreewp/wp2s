<?php
// Path: wp-content/plugins/wp2s/src/Helpers/Syncs/Blocks/init-taxonomy-twins.php
namespace WP2S\Helpers\Syncs\Blocks\TaxonomyTwins;

class Controller
{
    /**
     * Ensures WordPress taxonomy terms exist for a given set of items.
     *
     * @param array $items The items to process, where the keys are the item identifiers and values are the item data.
     * @param string $taxonomy The taxonomy to associate with the items.
     * @param callable|null $title_callback A callback to generate term names.
     * @param callable|null $meta_callback A callback to generate term meta data.
     * @param callable $slug_callback A callback to generate the term slug.
     *
     * @return void
     */
    public static function check_twin(
        array $items,
        string $taxonomy,
        ?callable $title_callback = null,
        ?callable $meta_callback = null,
        callable $slug_callback
    ) {
        foreach ($items as $item_key => $item_data) {
            // Generate the term name
            $term_name = $title_callback ? $title_callback($item_key, $item_data) : ucwords($item_key);
    
            // Generate the term slug
            $sanitized_slug = sanitize_title($slug_callback($item_key, $item_data));
    
            if (empty($sanitized_slug)) {
                error_log("Error: slug_callback returned an empty slug for item: {$item_key}");
                continue;
            }
    
            // Check if the term already exists
            $existing_term = get_term_by('slug', $sanitized_slug, $taxonomy);
    
            if ($existing_term) {
                // Update term name if it has changed
                if ($existing_term->name !== $term_name) {
                    wp_update_term($existing_term->term_id, $taxonomy, ['name' => $term_name]);
                    error_log("Updated taxonomy term name to: {$term_name} (ID: {$existing_term->term_id})");
                }
    
                // Optionally, update the term meta if needed
                if (is_callable($meta_callback)) {
                    foreach ($meta_callback($item_key, $item_data) as $meta_key => $meta_value) {
                        update_term_meta($existing_term->term_id, $meta_key, $meta_value);
                    }
                }
    
                continue;
            }
    
            // Insert the term
            $inserted_term = wp_insert_term($term_name, $taxonomy, ['slug' => $sanitized_slug]);
    
            // Handle errors
            if (is_wp_error($inserted_term)) {
                error_log("Failed to insert taxonomy term: {$inserted_term->get_error_message()}");
                continue;
            }
    
            $term_id = $inserted_term['term_id'];
    
            // Optionally, add term meta if provided
            if (is_callable($meta_callback)) {
                foreach ($meta_callback($item_key, $item_data) as $meta_key => $meta_value) {
                    update_term_meta($term_id, $meta_key, $meta_value);
                }
            }
    
            error_log("Successfully synced taxonomy term (ID: {$term_id}) for item: {$item_key}");
        }
    }
}