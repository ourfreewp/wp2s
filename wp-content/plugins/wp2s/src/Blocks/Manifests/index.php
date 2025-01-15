<?php
namespace WPS2\Blocks\Manifests;

// Constants and variables
$text_domain = 'wp2s';
$post_type = 'wp2s_manifest';
$manifest_groups = [];
$taxonomy = 'wp2s_manifest_category';
$default_category = 'uncategorized';
$category_positions = [];
$category_position_meta = 'wp2s_manifest_category_position';

// Retrieve all manifests
$manifests = get_posts([
    'post_type' => $post_type,
    'numberposts' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

foreach ($manifests as $manifest) {
    $post_id = $manifest->ID;
    $slug = str_replace('-manifest', '', $manifest->post_name);
    $post_type = 'wp2s_' . $slug;
    $excerpt = $manifest->post_excerpt;

    // Get post type object
    $post_type_object = get_post_type_object($post_type);

    // Safely count posts
    $count = 0;
    if ($post_type_object) {
        $post_counts = wp_count_posts($post_type_object->name);
        $count = isset($post_counts->publish) ? $post_counts->publish : 0;
    }

    // Safely handle labels
    $singular = $post_type_object && isset($post_type_object->labels->singular_name)
        ? $post_type_object->labels->singular_name : '';

    $plural = $post_type_object && isset($post_type_object->labels->name)
        ? $post_type_object->labels->name : '';

    $count_label = ($count === 1) ? $singular : $plural;

    // Use taxonomy to determine category
    $categories = wp_get_post_terms($post_id, $taxonomy, ['fields' => 'all']);
    $category = $categories ? $categories[0] : (object)['name' => $default_category, 'term_id' => 0];

    $data_category = $category->name;

    // Get category position
    $category_id = $category->term_id;
    if (!isset($category_positions[$data_category])) {
        $category_positions[$data_category] = rwmb_meta($category_position_meta, [
            'object_type' => 'term',
        ], $category_id) ?: PHP_INT_MAX; // Default to a large number if no position is set
    }

    // Group manifests by category
    if (!isset($manifest_groups[$data_category])) {
        $manifest_groups[$data_category] = [];
    }

    $manifest_groups[$data_category][] = [
        'slug' => $slug,
        'excerpt' => $excerpt,
        'count' => $count,
        'count_label' => $count_label,
    ];
}

// Sort categories by position
uksort($manifest_groups, function ($a, $b) use ($category_positions) {
    return $category_positions[$a] <=> $category_positions[$b];
});
?>

<div class="wp2s-manifests" useBlockProps>
    <div class="wp2s-manifests__inner grid">
        <?php foreach ($manifest_groups as $category => $manifests) : ?>
            <div class="wp2s-manifest-category g-col g-col-12 g-col-sm-6 g-col-md-4 g-col-lg-3">
                <div class="wp2s-manifest-category__title">
                    <h2><?php echo esc_html(ucfirst($category)); ?></h2>
                </div>
                <?php foreach ($manifests as $manifest) : ?>
                    <div class="wp2s-manifests__item">
                        <div class="wp2s-manifest wp2s-manifest--<?php echo esc_attr($manifest['slug']); ?>">
                            <div class="wp2s-manifest__inner">
                                <div class="wp2s-manifest__footer">
                                    <div class="wp2s-badge">
                                        <?php echo esc_html($manifest['count_label']); ?> (<?php echo esc_html($manifest['count']); ?>)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>