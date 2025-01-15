<?php

namespace WP2S\Blocks\FullStack;

$module_taxonomy = 'wp2s_tax_module';
$platform_post_type = 'wp2s_platform';

$modules = get_terms([
    'taxonomy' => $module_taxonomy,
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
]);

$modules = array_filter($modules, function ($module) use ($platform_post_type) {
    $platforms = get_posts([
        'post_type' => $platform_post_type,
        'posts_per_page' => 1,
        'tax_query' => [
            [
                'taxonomy' => 'wp2s_tax_module',
                'field' => 'slug',
                'terms' => $module->slug,
            ],
        ],
    ]);

    return !empty($platforms);
});

if (is_wp_error($modules) || empty($modules)) {
    return;
}

$module_count = count($modules);
?>

<div class="wp2s-fullstack" useBlockProps>
    <div class="wp2s-fullstack__inner">
        <div class="grid">
            <?php foreach ($modules as $module) :

                $module_name_lower = strtolower($module->name);

                $col_class = 'g-col g-col-12';

            ?>
                <div class="<?php echo esc_attr($col_class); ?>">
                    <div class="wp2s-fullstack__section">
                        <?php
                        $module_slug = esc_attr($module->slug);
                        $module_name = esc_html($module->name);
                        echo bs_block([
                            'id' => 'wp2s/stack',
                            'data' => [
                                'selected_module' => $module_slug,
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>