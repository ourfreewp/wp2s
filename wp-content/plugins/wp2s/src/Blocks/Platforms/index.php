<?php

namespace WP2S\Blocks\Platforms;

// Constants and variables
$text_domain = 'wp2s';
$post_type = 'wp2s_platform';
$taxonomy = 'wp2s_tax_module';
// Fetch fresh data
$module_groups = [];

$modules = [];

var_dump($attributes['selected_module']);

$modules = get_terms([
    'taxonomy' => $taxonomy,
    'hide_empty' => true,
]);

foreach ($modules as $module) {
    $module_id = $module->term_id;
    $module_name = $module->name;
    $module_slug = $module->slug;
    $module_description = $module->description;
    $module_link = get_term_link($module_id, $taxonomy);

    $module_data = rwmb_meta('wp2s_module_data', [
        'object_type' => 'term',
    ], $module_id) ?: [];

    $module_platforms = get_posts([
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $module_id,
            ],
        ],
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    $module_platform_count = count($module_platforms);

    $module_groups[$module_name] = [
        'name' => $module_name,
        'slug' => $module_slug,
        'description' => $module_description,
        'link' => $module_link,
        'count' => $module_platform_count,
        'platforms' => [],
    ];

    foreach ($module_platforms as $platform) {
        $platform_id = $platform->ID;
        $platform_title = $platform->post_title;
        $platform_slug = $platform->post_name;
        $platform_excerpt = $platform->post_excerpt;
        $platform_permalink = get_permalink($platform_id);

        $module_groups[$module_name]['platforms'][] = [
            'title' => $platform_title,
            'slug' => $platform_slug,
            'excerpt' => $platform_excerpt,
            'permalink' => $platform_permalink,
        ];
    }
}

?>
<?php if (empty($module_groups)) : ?>
    <div class="wp2s-platforms" useBlockProps>
        <div class="wp2s-platforms__inner">
            <?php foreach ($module_groups as $module_name => $module) : ?>
                <div class="wp2s-module">
                    <div class="wp2s-module__header">
                        <h2 class="wp2s-module__title">
                            <span class="wp2s-name"><?php echo esc_html(ucfirst($module_name)); ?></span>
                            <span class="wp2s-module__badges">
                                <span class="wp2s-badge"></span>
                            </span>
                        </h2>
                        <p class="wp2s-module__description">
                            <?php echo esc_html($module['description'] ?? ''); ?>
                        </p>
                        <div class="wp2s-actions">
                            <div class="wp2s-action">
                                <a href="<?php echo esc_url($module['link']); ?>" class="wp2s-button">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="grid">
                        <?php foreach ($module['platforms'] as $platform) : ?>
                            <div class="g-col g-col-12 g-col-sm-6 g-col-md-4 g-col-lg-3">
                                <div class="wp2s-platforms__item">
                                    <div class="wp2s-platform wp2s-platform--<?php echo esc_attr($platform['slug'] ?? ''); ?>">
                                        <div class="wp2s-platform__inner">
                                            <div class="wp2s-platform__header">
                                                <h3 class="wp2s-platform__title">
                                                    <?php echo esc_html($platform['title'] ?? ''); ?>
                                                </h3>
                                            </div>
                                            <div class="wp2s-platform__footer">
                                                <p><?php echo esc_html($platform['excerpt'] ?? ''); ?></p>
                                                <a href="<?php echo esc_url($platform['permalink']); ?>">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>