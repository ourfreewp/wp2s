<?php

namespace WP2\REST\Singles\TaxonomyTerms;

class Controller
{
    public function set_terms(\WP_REST_Request $request)
    {
        $params = $request->get_params();
        $post_id = $params['post_id'] ?? '';
        $terms = $params['terms'] ?? '';
        $append = $params['append'] ?? false;
        $taxonomy_rest_route = $params['taxonomy'] ?? '';

        $taxonomies = get_taxonomies();
        $taxonomy = null;

        foreach ($taxonomies as $tax) {
            $taxonomy_object = get_taxonomy($tax);
            if ($taxonomy_object->rest_base === $taxonomy_rest_route || $taxonomy_object->name === $taxonomy_rest_route) {
                $taxonomy = $taxonomy_object->name;
                break;
            }
        }

        if (!$taxonomy) {
            return new \WP_Error('taxonomy_not_found', 'Taxonomy not found', ['status' => 404]);
        }

        $assigned_terms = wp_set_object_terms($post_id, $terms, $taxonomy, $append);

        if (is_wp_error($assigned_terms)) {
            return new \WP_REST_Response([
                'error' => 'failed_to_set_terms',
                'message' => $assigned_terms->get_error_message()
            ], 400);
        }

        return new \WP_REST_Response([
            'message' => 'Terms updated successfully',
            'post_id' => $post_id,
            'taxonomy' => $taxonomy,
            'terms' => $terms
        ], 200);
    }
}

new Controller();