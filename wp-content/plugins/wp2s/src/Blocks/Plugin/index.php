<?php

namespace WPS2\Blocks\Plugin;

$prefix = 'wp2s_plugin_';
$index = $block['index'] ?? null;
$inherit = $attributes['inherit'] ?? false;
$plugin = $attributes['plugin'] ?? null;
$plugin_id = $plugin ? $plugin->ID : null;
$post_id = $block['context']['postId'] ?? null;
$item = null;

if ($inherit && $post_id) {
    $item = $post_id;
} elseif ($plugin_id) {
    $item = $plugin_id;
}

if ($item) {
    global $wp_embed;
    $plugin = get_post($item);
    if (!$plugin) return;

    $permalink = !$isEditor ? get_the_permalink($plugin) : '#';

    $title   = $plugin->post_title ?? null;
    $excerpt = $plugin->post_excerpt ?? null;
    $content = $plugin->post_content ?? null;
    $author_id = $plugin->post_author ?? null;
    $author = null;

    $post_status = $plugin->post_status;
    $status_obj  = get_post_status_object($post_status);
    $status      = $status_obj->label ?? null;
    
    if ($author_id && $author_id != 1) {
        $author = get_the_author_meta('display_name', $author_id);
    }

    $name = rwmb_meta($prefix . 'name');
    $name = $name !== '' ? $name : ($plugin ? $plugin->post_title : null);

    $desc = rwmb_meta($prefix . 'description');
    $desc = $desc !== '' ? $desc : ($plugin ? $plugin->post_excerpt : null);

    $transient_key = 'wp2s_plugin_addons_' . $plugin->ID;

    $addons = get_children([
        'post_parent' => $plugin->ID,
        'post_type' => 'wp2s_plugin',
        'post_status' => ['publish', 'plugin_awaiting_docs'],
        'numberposts' => -1,
    ]);

    $addons = count($addons);

    $show_meta = $author || $addons || $status;
}
?>

<div useBlockProps class="wp2s-plugin wp2s-plugin--">

    <div class="wp2s-plugin__inner">

        <div class="wp2s-plugin__content">

            <div class="wp2s-plugin__header">

                <p class="wp2s-name"><?php echo esc_html($name); ?></p>

                <p class="wp2s-description"><?php echo esc_html($desc); ?></p>

                <?php if ($show_meta): ?>

                    <div class="wp2s-meta">
                        <?php if ($status): ?>
                            <div class="wp2s-meta-item wp2s-meta-item--status">
                                <span class="wp2s-meta-label d-none">Status&nbsp;</span>
                                <span class="wp2s-meta-value"><?php echo esc_html($status); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($author): ?>
                            <div class="wp2s-meta-item wp2s-meta-item--author">
                                <span class="wp2s-meta-label d-none">Maker&nbsp;</span>
                                <span class="wp2s-meta-value"><?php echo esc_html($author); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($addons && $addons > 0): ?>
                            <div class="wp2s-meta-item wp2s-meta-item--children">
                                <span class="wp2s-meta-label">Addons&nbsp;</span>
                                <span class="wp2s-meta-value"><?php echo esc_html($addons); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

            <div class="wp2s-plugin__footer">

                <div class="wp2s-actions">

                    <a class="wp2s-action wp2s-action--permalink" href="<?php echo esc_url($permalink); ?>">
                        Learn More
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>