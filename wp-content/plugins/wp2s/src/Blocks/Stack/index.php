<?php

namespace WP2S\Blocks\Stack;

// Validate and sanitize input
$module = isset($a['selected_module']) ? sanitize_text_field($a['selected_module']) : '';

if (empty($module)) {
    return;
}

// Define constants
$module_taxonomy = "wp2s_tax_module";
$stack_post_type = "wp2s_platform";

// Ensure taxonomy and post type exist
if (!taxonomy_exists($module_taxonomy) || !post_type_exists($stack_post_type)) {
    return;
}

// Fetch the module term
$module_term = get_term_by('slug', $module, $module_taxonomy);

if (!$module_term || is_wp_error($module_term) || empty($module_term->name)) {
    return;
}

$module_title = $module_term->name;
$module_description = $module_term->description;
$module_link = get_term_link($module_term);
if (is_wp_error($module_link)) {
    return;
}

// Fetch posts associated with the module
$items = get_posts([
    'post_type' => $stack_post_type,
    'posts_per_page' => -1,
    'tax_query' => [
        [
            'taxonomy' => $module_taxonomy,
            'field' => 'slug',
            'terms' => $module,
        ],
    ],
]);

if (empty($items)) {
    return;
}

// Process items for rendering
$items = array_filter(array_map(function ($item) {
    if (empty($item->post_title) || empty(get_permalink($item))) {
        return null;
    }
    return [
        'title' => $item->post_title,
        'excerpt' => $item->post_excerpt,
        'link' => get_permalink($item),
    ];
}, $items));

$module_item_count = count($items);
if ($module_item_count === 0) {
    return;
}

// Get taxonomy labels
$taxonomy = get_taxonomy($module_taxonomy);
if (!$taxonomy) {
    return;
}

$taxonomy_labels = get_taxonomy_labels($taxonomy);
$module_label_plural = $taxonomy_labels->name ?? '';
$module_label_singular = $taxonomy_labels->singular_name ?? '';
$module_label_all = $taxonomy_labels->all_items ?? '';

$model_label = $module_item_count === 1 ? $module_label_singular : $module_label_plural;

// Define CSS classes
$classes = [
    'wp2s-stack',
    'wp2s-stack--' . $module,
];
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" useBlockProps>
    <div class="wp2s-stack__inner">
        <div class="wp2s-stack__header">
            <div class="wp2s-stack__header-inner">
                <a href="<?php echo esc_url($isEditor ? '#' : $module_link); ?>">
                    <h2 class="wp2s-stack__title">
                        <?php echo esc_html($module_title); ?>
                        <span class="wp2s-stack__count">
                            <?php echo esc_html($module_item_count); ?>
                        </span>
                    </h2>
                </a>
                <p class="wp2s-stack__description">
                    <?php echo esc_html($module_description); ?>
                </p>

            </div>
        </div>
        <div class="wp2s-stack__content">
            <div class="wp2s-stack__content-inner">
                <?php foreach ($items as $item) : ?>
                    <div class="wp2s-stack-item">
                        <a href="<?php echo esc_url($isEditor ? '#' : $item['link']); ?>">
                            <h3 class="wp2s-name">
                                <?php echo esc_html($item['title']); ?>
                            </h3>
                        </a>
                        <div class="wp2s-description">
                            <?php echo esc_html($item['excerpt']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>