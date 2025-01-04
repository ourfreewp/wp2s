<?php
// Path: wp-content/plugins/wp2s/Blocks/ArchiveCollection/index.php
namespace WPS2\Blocks\ArchiveCollection;

$plugin_dir = WP2S_PLUGIN_DIR ?? plugin_dir_path(__FILE__);

$template_slug = $attributes['template'] ?? '';

$plugins = [];

$collections = [];

switch ($template_slug) {
    case 'all':
        $collections = [
            'archives'
        ];
        break;
    case 'directory':
        $collections = [
            'directories'
        ];
        break;
    default:
        $collections = [];
        break;
}

$post_type = 'page';

$taxonomy  = 'wp2s_collection';

$archives = get_posts([
    'post_type' => $post_type,
    'posts_per_page' => -1,
    'post_status' => ['publish', 'private'],
    'tax_query' => [
        [
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $collections,
        ],
    ],
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

?>

<div useBlockProps class="wp2s-collection wp2s-collection--<?php echo esc_attr($template_slug); ?>">

    <ul class="wp2s-archives">

        <?php foreach ($archives as $archive) : ?>

            <?php
            $status = get_post_status($archive->ID);

            switch ($status) {
                case 'publish':
                    $archive_name = get_the_title($archive->ID);
                    $archive_description = get_the_excerpt($archive->ID);
                    break;
                case 'private':
                    $archive_name = 'Coming Soon';
                    $archive_description = 'This archive will be revealed soon.';
                    break;
                default:
                    $archive_name = 'Unknown';
                    $archive_description = 'This archive is in an unknown state.';
                    break;
            }
            ?>

            <li class="wp2s-archive">
                <div class="wp2s-archive__inner">
                    <div class="wp2s-archive__content">
                        <div class="wp2s-archive__name"><?php echo esc_html($archive_name); ?></div>
                        <div class="wp2s-archive__description"><?php echo esc_html($archive_description); ?></div>
                    </div>
                </div>
            </li>

        <?php endforeach; ?>

    </ul>

</div>