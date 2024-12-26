<?php
add_filter('slim_seo_meta_description', function ($description, $object_id) {

    if (get_post_type($object_id) === 'page') {

        $slim_meta = get_post_meta($object_id, 'slim_seo', true);

        if (! empty($slim_meta['description'])) {
            return $slim_meta['description'];
        }

        $subtitle = get_post_meta($object_id, 'subtitle', true);

        if (! empty($subtitle)) {
            return $subtitle;
        }
    }

    return $description;
}, 10, 2);

add_action('slim_seo_init', function ($plugin) {
    $plugin->disable('code');
    $plugin->disable('redirection');
});

add_filter('slim_seo_sitemap_taxonomies', function ($taxonomies) {
    $taxonomies = array_diff($taxonomies, ['attribute']);
    return $taxonomies;
});

add_filter('slim_seo_sitemap_post_types', function ($post_types) {
    $post_types = array_diff($post_types, ['profile']);
    return $post_types;
});

add_filter('slim_seo_sitemap_post_type_query_args', function ($query_args) {

    $excluded_post_ids = [];

    $excluded_post_ids[] = get_page_by_path('success')->ID;

    $excluded_post_ids[] = get_page_by_path('error')->ID;

    $query_args['post__not_in'] = $excluded_post_ids;

    return $query_args;
});
