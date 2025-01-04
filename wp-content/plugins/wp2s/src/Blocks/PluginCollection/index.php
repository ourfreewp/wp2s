<?php
// Path: wp-content/plugins/wp2s/Blocks/PluginCollection/PluginCollection.php
namespace WPS2\Blocks\PluginCollection;

$plugin_dir = WP2S_PLUGIN_DIR ?? plugin_dir_path(__FILE__);

$template_slug = $attributes['template'] ?? '';

$plugins = [];

$plugin_collections = [];

switch ($template_slug) {
    case 'all':
        $plugin_collections = [
            'supported-plugins'
        ];
        break;
    case 'supported':
        $plugin_collections = [
            'supported-plugins'
        ];
        break;
    case 'supported-free':
        $plugin_collections = [
            'supported-free-plugins'
        ];
        break;
    case 'supported-pro':
        $plugin_collections = [
            'supported-pro-plugins'
        ];
        break;
    case 'maker-free':
        $plugin_collections = [
            'free-maker-plugins'
        ];
        break;
    case 'maker-pro':
        $plugin_collections = [
            'pro-maker-plugins'
        ];
        break;
    default:
        $plugin_collections = [];
        break;
}

$post_type = 'wp2s_plugin';

$taxonomy  = 'wp2s_collection';

$plugins = get_posts([
    'post_type' => $post_type,
    'posts_per_page' => -1,
    'post_status' => ['publish', 'private'],
    'tax_query' => [
        [
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $plugin_collections,
        ],
    ],
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

?>

<div useBlockProps class="wp2s-collection wp2s-collection--<?php echo esc_attr($template_slug); ?>">

    <ul class="wp2s-plugins">

        <?php foreach ($plugins as $plugin) : ?>

            <?php
            $status = get_post_status($plugin->ID);

            switch ($status) {
                case 'publish':
                    $plugin_name = get_the_title($plugin->ID);
                    $plugin_description = get_the_excerpt($plugin->ID);
                    break;
                case 'private':
                    $plugin_name = 'Coming Soon';
                    $plugin_description = 'This plugin will be revealed soon.';
                    break;
                default:
                    $plugin_name = 'Unknown';
                    $plugin_description = 'This plugin is in an unknown state.';
                    break;
            }
            ?>

            <li class="wp2s-plugin">
                <div class="wp2s-plugin__inner">
                    <div class="wp2s-plugin__content">
                        <div class="wp2s-plugin__name"><?php echo esc_html($plugin_name); ?></div>
                        <div class="wp2s-plugin__description"><?php echo esc_html($plugin_description); ?></div>
                    </div>
                </div>
            </li>

        <?php endforeach; ?>

    </ul>

</div>